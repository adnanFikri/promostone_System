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
                
                // Loop through all sales and get the 'ref_produit' for each sale
                $products = $group->flatMap(function($bon) {
                    return $bon->sales->map(function($sale) {
                        return $sale->ref_produit;  // Extract 'ref_produit' from each sale
                    });
                });
            
                // Convert the products to a unique, comma-separated string
                $productsString = $products->unique()->implode(', ');
            
                return [
                    'id' => $bon->id,
                    'no_bl' => $bon->no_bl,
                    'coupe' => $bon->coupe,
                    'print_nbr' => $bon->print_nbr,
                    'print_date' => $bon->print_date,
                    'date_coupe' => $bon->date_coupe,
                    'userName' => $bon->userName,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->sales->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
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
    
        return response()->json(['message' => 'Livrée status updated successfully.']);
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
