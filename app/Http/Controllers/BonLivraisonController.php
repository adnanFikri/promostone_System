<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonLivraisonController extends Controller
{
 
public function index()
{
    if (request()->ajax()) {
        $bons = BonLivraison::with(['sales', 'paymentStatuses'])
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
                'livree' => $bon->livree,
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

    return view('bons.livraison_view');
}


    public function updateLivree(Request $request, $id)
    {
        $bon = BonLivraison::findOrFail($id);
        $bon->livree = $request->input('livree');
        $bon->save();
    
        return response()->json(['message' => 'Livrée status updated successfully.']);
    }
    


}
