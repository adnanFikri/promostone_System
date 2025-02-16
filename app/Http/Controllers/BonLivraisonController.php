<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Reglement;
use App\Models\BonLivraison;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\DB;

class BonLivraisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:index bon livraison')->only(['index']);
        $this->middleware('permission:view bon livraison')->only(['showBonLivraison']);
        $this->middleware('permission:update livree bon livraison')->only(['updateLivree']);
    }
 
    public function index()
    {
        if (request()->ajax()) {
            $bons = BonLivraison::with(['sales', 'paymentStatuses'])
                ->get()
                ->groupBy('no_bl');
            
                
            $bons = $bons->map(function ($group) {

                $client = Client::where('code_client',$group->first()->paymentStatuses->first()->code_client)->first();
                $client_raison = $client->category . ' ' .$client->name;

                $bon = $group->first();  // Get the first bon for this group
                
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
                    'no_bl' => $bon->no_bl,
                    'livree' => $bon->livree,
                    'userName' => $bon->userName,
                    'client' => $client_raison,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->sales->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
                    // 'name_client' => $group->first()->paymentStatuses->first()->name_client ?? 'N/A',  // Access the first item in the paymentStatuses collection
                ];
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

        return view('bons.livraison_view');
    }

    public function showBonLivraison($no_bl)
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

        // Group sales data by product name
        $groupedSales = $sales->groupBy('produit');

        BonLivraison::firstOrCreate(
            ['no_bl' => $no_bl]
        );

        // Prepare data for the view
        $data = [
            'paymentStatus' => $paymentStatus,
            'groupedSales' => $groupedSales,
            'reglements' => $reglements,
            'client' => $client,
        ];

        return view('sales.bonL', $data);
    }

    public function updateLivree(Request $request, $id)
    {
        $bon = BonLivraison::findOrFail($id);
        $bon->livree = $request->input('livree');
        $bon->save();
    
        return response()->json(['message' => 'Livrée status updated successfully.']);
    }
    


}
