<?php

namespace App\Http\Controllers\Achat;

use App\Models\Product;
use App\Models\Achat\Achat;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use App\Models\Achat\AchatStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AchatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create(){

        $Fournisseurs = Fournisseur::all();
        $products = Product::all();
        
        return view('achat.create', compact('Fournisseurs','products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_fournisseur' => 'required',
            'products' => 'required|array',
            'products.*.produit' => 'required|string',
            'products.*.prix_unitaire' => 'required|numeric',
            'products.*.mesures' => 'required|array',
            'products.*.mesures.*.longueur' => 'nullable|numeric',
            'products.*.mesures.*.largeur' => 'nullable|numeric',
            'products.*.mesures.*.quantite' => 'required|numeric',
            'products.*.mesures.*.mode' => 'required|string',
            'services' => 'nullable|array',
            'services.*.type' => 'required|string',
            'services.*.quantite' => 'required|numeric',
            'services.*.montant' => 'required|numeric',
        ]);

        $no_bl = Achat::max('no_bl') + 1; // Auto-generate no_bl

        // dd($request);

        foreach ($validatedData['products'] as $product) {
            foreach ($product['mesures'] as $mesure) {

                $productModel = Product::where('name', $product['produit'])->first(); // Assuming 'name' matches 'produit'
                if ($productModel) {
                    // Check if there's enough quantity in stock
                    // if ($productModel->quantity >= $mesure['quantite']) {
                        // Reduce the product's quantity
                        $productModel->quantity += $mesure['quantite'];
                        $productModel->save();
                    // } else {
                        // Handle case when there's not enough stock
                    //     return redirect()->back()->withErrors([
                    //         'products' => "Insufficient stock for product: {$product['produit']}"
                    //     ]);
                    // }
                } else {
                    return redirect()->back()->withErrors([
                        'products' => "Product not found: {$product['produit']}"
                    ]);
                }

                $sale = new Achat();
                $sale->no_bl = $no_bl;
                $sale->annee = now()->year;
                $sale->date = now();
                $sale->id_fournisseur = $validatedData['id_fournisseur'];
                $sale->produit = $product['produit'];
                $sale->longueur = $mesure['longueur'] ?? 0;
                $sale->largeur = $mesure['largeur'] ?? 0;
                $sale->nbr = $mesure['quantite'];
                $sale->qte = ($sale->longueur * $sale->largeur * $sale->nbr);
                $sale->mode = $mesure['mode'];
                $sale->prix_unitaire = $product['prix_unitaire'];

                // generate the ref product 
                $words = explode(' ', $product['produit']);
                $ref = '';
                foreach ($words as $word) {
                    $ref .= strtoupper($word[0]); // Take the first character and make it uppercase
                }
            
                $sale->ref_produit = $ref;


                // calculate the montant by mode 
                $mode = $mesure['mode'];
                $montant = 0;
                if ($mode === "M2") {
                    $montant = $sale->longueur * $sale->largeur * $sale->nbr * $sale->prix_unitaire;
                } elseif ($mode === "ML") {
                    $montant = ($sale->longueur + $sale->largeur) * $sale->nbr * $sale->prix_unitaire;
                } elseif ($mode === "Unite") {
                    $montant = $sale->nbr * $sale->prix_unitaire;
                    $sale->longueur = null;
                    $sale->largeur = null;
                    $sale->qte = null;
                }
                $sale->montant = $montant;
                
                $sale->save();
            }
        }

        // save the services 
        if (isset($validatedData['services'])) {
            foreach ($validatedData['services'] as $service) {
                $sale = new Achat();
                $sale->no_bl = $no_bl;
                $sale->annee = now()->year;
                $sale->date = now();
                $sale->id_fournisseur = $validatedData['id_fournisseur'];
                $sale->produit = $service['type'];
                $sale->longueur = null; // Not applicable for services
                $sale->largeur = null; // Not applicable for services
                $sale->qte = null;     // Not applicable for services
                $sale->nbr = $service['quantite'];     // Not applicable for services
                $sale->mode = 'service';     // Not applicable for services
                $sale->prix_unitaire = $service['montant']; // Optional
                $sale->montant = $service['montant'] * $service['quantite']; // Direct montant
                $sale->ref_produit = strtoupper(substr($service['type'], 0, 3)); // Example: FIN for "Finition"
                $sale->save();
            }
        }

        
        
        // Calculate total amount for the newly created BL
        $total_amount = Achat::where('no_bl', $no_bl)->sum('montant');

        // Create a new PaymentStatus record
        AchatStatus::create([
            'no_bl' => $no_bl,
            'id_fournisseur' => $validatedData['id_fournisseur'],
            'name_fournisseur' => Fournisseur::where('id', $validatedData['id_fournisseur'])->value('raison'), 
            'date_bl' => now(), // Use current date
            'montant_total' => $total_amount,
            'montant_payed' => 0, 
            'montant_restant' => $total_amount,
            'user-name' => Auth::user()->name,
        ]);

        return redirect()->back()->with('success', 'Achat ajoutée avec succès!');
        // return redirect()->route('avanceAchat.create', [
        //     'no_bl' => $no_bl, 
        //     'id_fournisseur' => $validatedData['id_fournisseur'],
        //     'total_amount' => $total_amount,
        // ]);
    }


    public function getByBl(Request $request)
    {
        // $sales = Sale::where('no_bl', $request->no_bl)->get();
        $achats = Achat::select([
            'achats.id',
            'achats.no_bl',
            'achats.annee',
            'achats.date',
            'achats.id_fournisseur',
            'achats.ref_produit',
            'achats.produit',
            'achats.longueur',
            'achats.largeur',
            'achats.nbr',
            'achats.qte',
            'achats.prix_unitaire',
            'achats.montant',
            'fournisseurs.raison as name_fournisseur'
        ])
        ->join('fournisseurs', 'achats.id_fournisseur', '=', 'fournisseurs.id') // Use strict join
        ->where('Achats.no_bl', $request->no_bl)
        ->get();
        return response()->json(['achats' => $achats]);
    }

}
