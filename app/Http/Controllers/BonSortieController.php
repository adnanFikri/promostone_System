<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\BonSortie;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Auth;

class BonSortieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:index bon sortie')->only(['index']);
        $this->middleware('permission:view bon sortie')->only(['showBonSortie']);
        $this->middleware('permission:update sortie bon sortie')->only(['updateSortie']);
    }
    public function index()
    {
        if (request()->ajax()) {
            $bons = BonSortie::with(['sales', 'paymentStatuses'])
                ->get()
                ->groupBy('no_bl');
            
            $bons = $bons->map(function ($group) {
                $bon = $group->first();  // Get the first bon for this group
                $isAdmin = auth()->user()->can('create users');

                 // Retrieve client information
                 $code_client = $group->first()->paymentStatuses->first()->code_client ?? null;
                 $client_raison = 'N/A';
                 
                 if ($code_client) {
                     $client = Client::where('code_client', $code_client)->first();
                     $client_raison = $client ? ($client->category . ' ' . $client->name) : 'N/A';
                 }
                
                // Loop through all sales and get the 'ref_produit' for each sale
                $products = $group->flatMap(function($bon) {
                    return $bon->sales->map(function($sale) {
                        return $sale->produit;  // Extract 'ref_produit' from each sale
                    });
                });
            
                // Convert the products to a unique, comma-separated string
                $productsString = $products->unique()->implode(', ');
            
                return [
                    'id' => $bon->id,
                    'client' => $client->name, // Add client name
                    'no_bl' => $bon->no_bl,
                    'sortie' => $bon->sortie,
                    'print_nbr' => $bon->print_nbr,
                    'date_sortie' => $bon->date_sortie,
                    'userName' => $bon->userName,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->sales->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
                    'isAdmin' => $isAdmin,
                    'created_at' => $bon->created_at
                ];
            });
    
            $bons = $bons->sort(function ($a, $b) {
                // Sort by 'sortie' ('Non' first)
                if ($a['sortie'] === 'Non' && $b['sortie'] === 'Oui') return -1;
                if ($a['sortie'] === 'Oui' && $b['sortie'] === 'Non') return 1;
            
                // If both are 'Non', sort by date ascending (oldest first)
                if ($a['sortie'] === 'Non') {
                    return strtotime($a['created_at']) <=> strtotime($b['created_at']);
                }
            
                // If both are 'Oui', sort by date descending (latest first)
                return strtotime($b['created_at']) <=> strtotime($a['created_at']);
            });
            
            
            return datatables()->of($bons)
                ->addColumn('actions', function ($bon) {
                    return '
                        <button class="bg-blue-500 text-white px-2 py-1 rounded-md" onclick="editBon('.$bon['id'].')">Edit</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded-md" onclick="deleteBon('.$bon['id'].')">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        return view('bons.sortie_view');
    }
    
    public function showBonSortie($no_bl)
    {
        // Retrieve the payment status details
        $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();

        if (!$paymentStatus) {
            return redirect()->back()->with('error', 'Bon de Livraison not found.');
        }

        // Retrieve the sales details for the specific Bon de Livraison
        $sales = Sale::where('no_bl', $no_bl)->get();

        // Retrieve payment details (e.g., advance payments)
        $reglements  =  Reglement::where('no_bl', $no_bl)->get();
        $client  =  Client::where('code_client', $paymentStatus->code_client)->first();
        $print_nbr  =  BonSortie::where('no_bl', $paymentStatus->no_bl)->first();


        // Group sales data by product name
        $groupedSales = $sales->groupBy('produit');

        // Prepare data for the view
        $data = [
            'paymentStatus' => $paymentStatus,
            'groupedSales' => $groupedSales,
            'reglements' => $reglements,
            'client' => $client,
            'print_nbr' => $print_nbr,
        ];

        return view('sales.bonS', $data);
    }
    
    public function updateSortie(Request $request, $id)
    {
        $bon = BonSortie::findOrFail($id);
        $bon->sortie = $request->input('sortie');
        if($request->input('sortie') == "Oui"){
            $bon->date_sortie= now();
            $bon->userName = Auth::user()->name;
        }else{
            $bon->date_sortie= null;
            $bon->userName = null;
        }
        $bon->save();
    
        return response()->json(['message' => 'L\'état de sortie a été mis à jour avec succès.']);
    }

    public function incrementPrintNbr($no_bl)
    {
        // Retrieve the BonCoupe record based on the no_bl value
        $bonSortie = BonSortie::where('no_bl', $no_bl)->first();
    
        if ($bonSortie) {
            // if (!auth()->user()->can('create users')) {
                // Increment the print_nbr field
                $bonSortie->print_nbr = $bonSortie->print_nbr + 1;
                $bonSortie->save();
            // }
    
            // Return success response
            return response()->json(['success' => true, 'message' => 'Print number incremented successfully.']);
        }
    
        // Return error response if BonCoupe is not found
        return response()->json(['success' => false, 'message' => 'Bon Coupe not found.'], 404);
    }
}
