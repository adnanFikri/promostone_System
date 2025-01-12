<?php

namespace App\Http\Controllers;

use App\Models\BonSortie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonSortieController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $bons = BonSortie::with(['sales', 'paymentStatuses'])
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
                    'sortie' => $bon->sortie,
                    'print_nbr' => $bon->print_nbr,
                    'date_sortie' => $bon->date_sortie,
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
    
        return view('bons.sortie_view');
    }
    
    
        public function updateSortie(Request $request, $id)
        {
            $bon = BonSortie::findOrFail($id);
            $bon->sortie = $request->input('sortie');
            $bon->save();
        
            return response()->json(['message' => 'L\'état de sortie a été mis à jour avec succès.']);
        }

        public function incrementPrintNbr($no_bl)
        {
            // Retrieve the BonCoupe record based on the no_bl value
            $bonSortie = BonSortie::where('no_bl', $no_bl)->first();
        
            if ($bonSortie) {
                if (!auth()->user()->can('create users')) {
                    // Increment the print_nbr field
                    $bonSortie->print_nbr = $bonSortie->print_nbr + 1;
                    $bonSortie->date_sortie= now();
                    $bonSortie->userName = Auth::user()->name;
                    $bonSortie->save();
                }
        
                // Return success response
                return response()->json(['success' => true, 'message' => 'Print number incremented successfully.']);
            }
        
            // Return error response if BonCoupe is not found
            return response()->json(['success' => false, 'message' => 'Bon Coupe not found.'], 404);
        }
}
