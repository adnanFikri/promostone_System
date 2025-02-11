<?php

namespace App\Http\Controllers\Achat;

use App\Models\Product;
use App\Models\Reglement;
use App\Models\AchatCheck;
use App\Models\Achat\Achat;
use App\Models\BonCommande;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\Achat\AchatStatus;
use App\Http\Controllers\Controller;
use App\Models\Achat\Achatreglement;
use Illuminate\Support\Facades\Auth;

class BonCommandeController extends Controller
{

public function index()
    {
        if (request()->ajax()) {
            $bons = BonCommande::with(['achats', 'achatStatuses'])
                ->get()
                ->groupBy('no_bl');
            
                
            $bons = $bons->map(function ($group) {

                $fournisseur = Fournisseur::where('id',$group->first()->achatStatuses->first()->id_fournisseur)->first();

                $bon = $group->first();  // Get the first bon for this group
                
                // Loop through all sales and get the 'ref_produit' for each sale
                $products = $group->flatMap(function($bon) {
                    return $bon->achats->map(function($sale) {
                        return $sale->produit;  // Extract 'ref_produit' from each sale
                    });
                });
            
                // Convert the products to a unique, comma-separated string
                $productsString = $products->unique()->implode(', ');
            
                return [
                    'id' => $bon->id,
                    'no_bl' => $bon->no_bl,
                    'reception' => $bon->reception,
                    'userName' => $bon->userName,
                    'client' => $fournisseur->raison,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->achats->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    // 'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
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

        return view('Achat.bons.ListCommande');
    }
    

public function showBonCommande($no_bl)
    {
        // Retrieve the payment status details
        $AchatStatus = AchatStatus::where('no_bl', $no_bl)->first();

        if (!$AchatStatus) {
            return redirect()->back()->with('error', 'Bon de Livraison not found.');
        }

        // Retrieve the sales details for the specific Bon de Livraison
        $achats = Achat::where('no_bl', $no_bl)->get();

        // Retrieve payment details (e.g., advance payments)
        $reglements  =  Achatreglement::where('no_bl', $no_bl)->get();
        $fournissuers  =  Fournisseur::where('id', $AchatStatus->id_fournisseur)->first();

        // Group sales data by product name
        $groupedAchats = $achats->groupBy('produit');

        // BonLivraison::firstOrCreate(
        //     ['no_bl' => $no_bl]
        // );

        // Prepare data for the view
        $data = [
            'paymentStatus' => $AchatStatus,
            'groupedSales' => $groupedAchats,
            'reglements' => $reglements,
            'client' => $fournissuers,
        ];

        // dd($data);
        return view('Achat.bons.bonCommande', $data);
    }

public function showBonReception($no_bl)
    {
        // Retrieve the payment status details
        $AchatStatus = AchatStatus::where('no_bl', $no_bl)->first();

        if (!$AchatStatus) {
            return redirect()->back()->with('error', 'Bon de Livraison not found.');
        }

        // Retrieve the sales details for the specific Bon de Livraison
        $achats = Achat::where('no_bl', $no_bl)->get();

        // Retrieve payment details (e.g., advance payments)
        $reglements  =  Achatreglement::where('no_bl', $no_bl)->get();
        $fournissuers  =  Fournisseur::where('id', $AchatStatus->id_fournisseur)->first();

        // Group sales data by product name
        $groupedAchats = $achats->groupBy('produit');

        // BonLivraison::firstOrCreate(
        //     ['no_bl' => $no_bl]
        // );

        // Prepare data for the view
        $data = [
            'paymentStatus' => $AchatStatus,
            'groupedSales' => $groupedAchats,
            'reglements' => $reglements,
            'client' => $fournissuers,
        ];

        // dd($data);
        return view('Achat.bons.bonCommande', $data);
    }


public function confirmReception($no_bl)
    {
        $saleLines = Achat::where('no_bl', $no_bl)->get();
        $products = Product::all(); // Fetch available products
        $clients = Fournisseur::all(); // Fetch available products
        $client = Fournisseur::where('id', $saleLines[0]->id_fournisseur)->first(); // Fetch available products

        return view('achat.bons.confirmReception', compact('saleLines', 'products', 'clients', 'client'));
    }


public function saveConfirmReception(Request $request, $achatId)
    {
        // Validate input data
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.produit' => 'required|string',
            'products.*.prix_unitaire' => 'required|numeric',
            'products.*.longueur' => 'nullable|numeric',
            'products.*.largeur' => 'nullable|numeric',
            'products.*.quantite' => 'required|numeric',
            'products.*.mode' => 'required|string',
            'services' => 'nullable|array',
            'services.*.type' => 'required|string',
            'services.*.quantite' => 'required|numeric',
            'services.*.montant' => 'required|numeric',
        ]);

        // Retrieve the existing achat
        $achat = Achat::findOrFail($achatId);

        // Keep the same reference for Bon de Commande
        $no_bl = $achat->no_bl;
        $id_fournisseur = $achat->id_fournisseur;
        // dd($id_fournisseur);

        // Save current Achat, AchatStatus, and Reglements data
        $achatsOLD = Achat::where('no_bl', $no_bl)->get()->toArray();
        $reglementsOLD = Reglement::where('no_bl', $no_bl)->get()->toArray();
        $achatStatusOLD = AchatStatus::where('no_bl', $no_bl)->first();

        // Store the previous data in an AchatCheck table
        AchatCheck::create([
            'no_bl' => $no_bl, 
            'id_fournisseur' => $id_fournisseur,
            'user_name' => auth()->user()->name,
            'changeDate' => now(),
            'achats_data' => $achatsOLD,
            'reglements_data' => $reglementsOLD,
            'achat_status_data' => $achatStatusOLD->toArray(),
        ]);

        // Delete old reception data (not Bon de Commande)
        Achat::where('no_bl', $no_bl)->delete();
        // dd($validatedData['products']);

        // Insert new Achat (Bon de Réception) data
        foreach ($validatedData['products'] as $product) {
            $longueur = $product['longueur'] ?? 0;
            $largeur = $product['largeur'] ?? 0;
            $quantite = $product['quantite'];
            $mode = $product['mode'];
            $prix_unitaire = $product['prix_unitaire'];

            $achat = new Achat();
            $achat->no_bl = $no_bl;
            $achat->annee = 2025;
            $achat->date = $achatStatusOLD->date_bl;
            $achat->id_fournisseur = $id_fournisseur;
            $achat->produit = $product['produit'];
            $achat->longueur = $longueur;
            $achat->largeur = $largeur;
            $achat->nbr = $quantite;
            $achat->qte = ($longueur * $largeur * $quantite);
            $achat->mode = $mode;
            $achat->prix_unitaire = $prix_unitaire;

            // Generate ref_produit
            $achat->ref_produit = strtoupper(collect(explode(' ', $product['produit']))->map(fn($word) => $word[0])->join(''));

            // Calculate total price based on mode
            $achat->montant = match ($mode) {
                'M2' => $longueur * $largeur * $quantite * $prix_unitaire,
                'ML' => ($longueur + $largeur) * $quantite * $prix_unitaire,
                'Unité' => $quantite * $prix_unitaire,
                default => 0,
            };

            if ($mode === 'Unité') {
                $achat->longueur = null;
                $achat->largeur = null;
                $achat->qte = $achat->nbr;
            }

            $achat->save();
        }

        // Insert new services data
        if (isset($validatedData['services'])) {
            foreach ($validatedData['services'] as $service) {
                $achat = new Achat();
                $achat->no_bl = $no_bl;
                $achat->annee = 2025;
                $achat->date = $achatStatusOLD->date_bl;
                $achat->id_fournisseur = $id_fournisseur;
                $achat->produit = $service['type'];
                $achat->longueur = null;
                $achat->largeur = null;
                $achat->qte = $service['quantite'];
                $achat->nbr = $service['quantite'];
                $achat->mode = 'service';
                $achat->prix_unitaire = $service['montant'];
                $achat->montant = $service['montant'] * $service['quantite'];
                $achat->ref_produit = strtoupper(substr($service['type'], 0, 3));
                $achat->save();
            }
        }

        // Update AchatStatus (if needed)
        $total_amount = Achat::where('no_bl', $no_bl)->sum('montant');
        AchatStatus::where('no_bl', $no_bl)->update([
            'montant_total' => $total_amount,
            'montant_restant' => $total_amount - $achatStatusOLD->montant_payed,
        ]);

        BonCommande::where('no_bl', $no_bl)->update([
            'reception' => "Oui",
        ]);

        return redirect()->route('achatStatus.index', ['no_bl' => $no_bl])
            ->with('success', 'Bon de Réception mis à jour avec succès!');
    }


}
