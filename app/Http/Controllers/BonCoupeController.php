<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\BonCoupe;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BonCoupeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:index bon coup')->only(['index']);
        $this->middleware('permission:view bon coup')->only(['showBonCoup']);
        $this->middleware('permission:update coupe bon coup')->only(['updateCoupe']);
    }

    public function index()
    {
        if (request()->ajax()) {
            $bons = BonCoupe::with(['sales', 'paymentStatuses'])
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
                    'coupe' => $bon->coupe,
                    'print_nbr' => $bon->print_nbr,
                    'finition' => $bon->finition,
                    'print_date' => $bon->print_date,
                    'date_coupe' => $bon->date_coupe,
                    'userName' => $bon->userName,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->sales->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
                    'created_at' => $bon->created_at, // Add created_at for sorting
                    'isAdmin' => $isAdmin,
                ];
            });
    

            // $bons = $bons->sortBy(function ($item) {
            //     $coupeOrder = ['Non' => 0, 'En cours' => 1, 'Oui' => 2];
            //     $finitionOrder = ['Non' => 0, 'En cours' => 1, 'Oui' => 2, 'Sans' => 3];
            
            //     return [
            //         $coupeOrder[$item['coupe']] ?? 3, // Default to last if not found
            //         $finitionOrder[$item['finition']] ?? 4, // Default to last if not found
            //         $item['created_at'] // Sort by date, oldest first
            //     ];
            // });

            $bons = $bons->sort(function ($a, $b) {
                $coupeOrder = ['Non' => 0, 'En cours' => 1, 'Oui' => 2];
                $finitionOrder = ['Non' => 0, 'En cours' => 1, 'Oui' => 2, 'Sans' => 3];
            
                // Compare coupe order
                if ($coupeOrder[$a['coupe']] !== $coupeOrder[$b['coupe']]) {
                    return $coupeOrder[$a['coupe']] <=> $coupeOrder[$b['coupe']];
                }
            
                // Compare finition order
                if ($finitionOrder[$a['finition']] !== $finitionOrder[$b['finition']]) {
                    return $finitionOrder[$a['finition']] <=> $finitionOrder[$b['finition']];
                }
            
                // If coupe is "Oui", sort by created_at descending
                if ($a['coupe'] === 'Oui' && $b['coupe'] === 'Oui') {
                    return $b['created_at'] <=> $a['created_at']; // Newest first
                }
            
                // Default sorting by created_at ascending (oldest first)
                return $a['created_at'] <=> $b['created_at'];
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
    
        return view('bons.coupe_view');
    }
    
    
    public function showBonCoup($no_bl)
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
        $print_nbr  =  BonCoupe::where('no_bl', $paymentStatus->no_bl)->first();

        // Group sales data by product name
        $groupedSales = $sales->groupBy('produit');

        BonCoupe::firstOrCreate(
            ['no_bl' => $no_bl]
        );
        
        // Prepare data for the view
        $data = [
            'paymentStatus' => $paymentStatus,
            'groupedSales' => $groupedSales,
            'reglements' => $reglements,
            'client' => $client,
            'print_nbr' => $print_nbr,
        ];

        return view('sales.bonC', $data);
    }
    
    public function updateCoupe(Request $request, $id)
    {
        if($request->input('coupe') === 'Oui'){
            $bon = BonCoupe::findOrFail($id);
            $bon->coupe = $request->input('coupe');
            $bon->date_coupe= now();
            $bon->userName = Auth::user()->name;
            $bon->save();
        }else{
            $bon = BonCoupe::findOrFail($id);
            $bon->coupe = $request->input('coupe');
            $bon->date_coupe= null;
            $bon->userName = null;
            $bon->save();
        }
    
        return response()->json(['message' => 'Coupe status updated successfully.']);
    }
    public function updateFinition(Request $request, $id)
    {
        $bon = BonCoupe::findOrFail($id);
        $bon->finition = $request->input('finition');
        $bon->save();
        return response()->json(['message' => 'Finition status updated successfully.']);
    }
    
    
    public function incrementPrintNbr($no_bl)
    {
        // Retrieve the BonCoupe record based on the no_bl value
        $bonCoupe = BonCoupe::where('no_bl', $no_bl)->first();
    

        if ($bonCoupe ) {
            // if (!auth()->user()->can('create users')) {
                // Increment the print_nbr field
                $bonCoupe->print_nbr = $bonCoupe->print_nbr + 1;
                // add other date for imprement and let this for select of coupr
                $bonCoupe->print_date = now();
                $bonCoupe->save();
            // }
    
            // Return success response
            return response()->json(['success' => true, 'message' => 'Print number incremented successfully.']);
        }
    
        // Return error response if BonCoupe is not found
        return response()->json(['success' => false, 'message' => 'Bon Coupe not found.'], 404);
    }
        
}
