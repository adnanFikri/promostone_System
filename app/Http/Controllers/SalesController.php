<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\Reglement;
use App\Models\SaleCheck;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Routing\Controller;


class SalesController extends Controller
{
    public function __construct()
    {
        // Apply the auth middleware to every action
        $this->middleware('auth');
        
        // Define permission-based middleware for each method
        $this->middleware('permission:view sales')->only(['index']);
        $this->middleware('permission:create sales')->only(['create']);
        $this->middleware('permission:store sales')->only(['store']);
        $this->middleware('permission:view sales upload')->only(['showUploadForm']);
        $this->middleware('permission:import sales')->only(['import']);
        $this->middleware('permission:view sales by bl')->only(['getByBl']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Join clients table for sorting and filtering
            $sales = Sale::select([
                    'sales.id',
                    'sales.no_bl',
                    // 'sales.annee',
                    DB::raw('DATE_FORMAT(CONVERT_TZ(sales.created_at, "+00:00", "+01:00"), "%H:%i:%s") as morocco_time'),
                    'sales.date',
                    'sales.code_client',
                    'sales.ref_produit',
                    'sales.produit',
                    'sales.longueur',
                    'sales.largeur',
                    'sales.nbr',
                    'sales.qte',
                    'sales.prix_unitaire',
                    'sales.montant',
                    'clients.name as client_name' // Include client name in query
                ])
                ->leftJoin('clients', 'sales.code_client', '=', 'clients.code_client')->distinct() // Join the clients table
                ->orderBy('sales.created_at', 'desc');
    
            return DataTables::of($sales)
                ->addColumn('client_name', function ($sale) {
                    return $sale->client_name ?? 'N/A';
                })
                ->addColumn('actions', function ($sale) {
                    $editUrl = route('sales.index', $sale->id);
                    $deleteUrl = route('sales.index', $sale->id);
                    return '
                        <a href="' . $editUrl . '" class="text-blue-500 hover:underline mr-2">Edit</a>
                        <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        return view('sales.index');
    }

    public function create(){

        $clients = Client::all();
        $products = Product::all();
        
        return view('sales.create', compact('clients','products'));
    }
 
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_client' => 'required|string',
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

        $no_bl = Sale::max('no_bl') + 1; // Start with the next no_bl

        // Keep incrementing until we find a unique no_bl
        while (
            DB::table('payment_statuses')->where('no_bl', $no_bl)->exists() ||
            DB::table('reglements')->where('no_bl', $no_bl)->exists()
        ) {
            $no_bl++; // Increment and check again
        }

        

        // dd($request);

        foreach ($validatedData['products'] as $product) {
            foreach ($product['mesures'] as $mesure) {

                $sale = new Sale();
                $sale->no_bl = $no_bl;
                $sale->annee = now()->year;
                $sale->date = now();
                $sale->code_client = $validatedData['code_client'];
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
                } elseif ($mode === "Unité") {
                    $montant = $sale->nbr * $sale->prix_unitaire;
                    $sale->longueur = null;
                    $sale->largeur = null;
                    $sale->qte = $sale->nbr;
                }
                $sale->montant = $montant;
                

                $productModel = Product::where('name', $product['produit'])->first(); // Assuming 'name' matches 'produit'
                if ($productModel) {
                    // Check if there's enough quantity in stock
                    // if ($productModel->quantity >= $sale->qte) {
                        // Reduce the product's quantity
                        $productModel->quantity -= $sale->qte;
                        $productModel->save();
                    // } else {
                    //     // Handle case when there's not enough stock
                    //     return redirect()->back()->withErrors([
                    //         'products' => "Stock insuffisant pour le produit : {$product['produit']}"
                    //     ]);
                    // }
                } else {
                    return redirect()->back()->withErrors([
                        'products' => "Product not found: {$product['produit']}"
                    ]);
                }
                $sale->save();

            }
        }

        // save the services 
        if (isset($validatedData['services'])) {
            foreach ($validatedData['services'] as $service) {
                $sale = new Sale();
                $sale->no_bl = $no_bl;
                $sale->annee = now()->year;
                $sale->date = now();
                $sale->code_client = $validatedData['code_client'];
                $sale->produit = $service['type'];
                $sale->longueur = null; // Not applicable for services
                $sale->largeur = null; // Not applicable for services
                $sale->qte = $service['quantite'];     // Not applicable for services
                $sale->nbr = $service['quantite'];     // Not applicable for services
                $sale->mode = 'service';     // Not applicable for services
                $sale->prix_unitaire = $service['montant']; // Optional
                $sale->montant = $service['montant'] * $service['quantite']; // Direct montant
                $sale->ref_produit = strtoupper(substr($service['type'], 0, 3)); // Example: FIN for "Finition"
                $sale->save();
            }
        }

        
        
        // Calculate total amount for the newly created BL
        $total_amount = Sale::where('no_bl', $no_bl)->sum('montant');

        // Create a new PaymentStatus record
        PaymentStatus::create([
            'no_bl' => $no_bl,
            'code_client' => $validatedData['code_client'],
            'name_client' => Client::where('code_client', $validatedData['code_client'])->value('name'), 
            'date_bl' => now(), // Use current date
            'montant_total' => $total_amount,
            'montant_payed' => 0, 
            'montant_restant' => $total_amount,
        ]);

        // return redirect()->route('sales.index')->with('success', 'Vente ajoutée avec succès!');
        return redirect()->route('avance.create', [
            'no_bl' => $no_bl, 
            'code_client' => $validatedData['code_client'],
            'total_amount' => $total_amount,
        ]);
    }


    public function edit($no_bl)
    {
        $saleLines = Sale::where('no_bl', $no_bl)->get();
        $products = Product::all(); // Fetch available products
        $clients = Client::all(); // Fetch available products
        $client = Client::where('code_client', $saleLines[0]->code_client)->first(); // Fetch available products
    
        return view('sales.edit', compact('saleLines', 'products', 'clients', 'client'));
    }

    // Update the sale
    public function update(Request $request, $saleId)
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

    // Retrieve the existing sale
    $sale = Sale::findOrFail($saleId);

    // Keep the same no_bl and code_client
    $no_bl = $sale->no_bl;
    $code_client = $sale->code_client;

    // save the current data 
    // Fetch sales data
    $salesOLD = Sale::where('no_bl', $no_bl)->get()->toArray();

    // Fetch reglement data
    $reglementsOLD = Reglement::where('no_bl', $no_bl)->get()->toArray();

    // Fetch payment status data
    $paymentStatusOLD = PaymentStatus::where('no_bl', $no_bl)->first();

    $salesCheck = SaleCheck::create(
['no_bl' => $no_bl, 'code_client'=>$code_client, 'user_name' => Auth::user()->name, 'changeDate' => now(),
            'sales_data' => $salesOLD,
            'reglements_data' => $reglementsOLD,
            'payment_status_data' => $paymentStatusOLD->toArray(),
        ]
    );

    // Delete old sales details
    Sale::where('no_bl', $no_bl)->delete();

    // Insert new sales data
    foreach ($validatedData['products'] as $product) {
        $longueur = $product['longueur'] ?? 0;
        $largeur = $product['largeur'] ?? 0;
        $quantite = $product['quantite'];
        $mode = $product['mode'];
        $prix_unitaire = $product['prix_unitaire'];

        $sale = new Sale();
        $sale->no_bl = $no_bl;
        $sale->annee = 2025;
        $sale->date = $paymentStatusOLD->date_bl;
        $sale->code_client = $code_client;
        $sale->produit = $product['produit'];
        $sale->longueur = $longueur;
        $sale->largeur = $largeur;
        $sale->nbr = $quantite;
        $sale->qte = ($longueur * $largeur * $quantite);
        $sale->mode = $mode;
        $sale->prix_unitaire = $prix_unitaire;

        // Generate ref_produit
        $sale->ref_produit = strtoupper(collect(explode(' ', $product['produit']))->map(fn($word) => $word[0])->join(''));

        // Calculate montant based on mode
        $sale->montant = match ($mode) {
            'M2' => $longueur * $largeur * $quantite * $prix_unitaire,
            'ML' => ($longueur + $largeur) * $quantite * $prix_unitaire,
            'Unité' => $quantite * $prix_unitaire,
            default => 0,
        };

        if ($mode === 'Unité') {
            $sale->longueur = null;
            $sale->largeur = null;
            $sale->qte = $sale->nbr;
        }

        $sale->save();
    }

    // Insert new services data
    if (isset($validatedData['services'])) {
        foreach ($validatedData['services'] as $service) {
            $sale = new Sale();
            $sale->no_bl = $no_bl;
            $sale->annee = 2025;
            $sale->date = $paymentStatusOLD->date_bl;
            $sale->code_client = $code_client;
            $sale->produit = $service['type'];
            $sale->longueur = null;
            $sale->largeur = null;
            $sale->qte = $service['quantite'];
            $sale->nbr = $service['quantite'];
            $sale->mode = 'service';
            $sale->prix_unitaire = $service['montant'];
            $sale->montant = $service['montant'] * $service['quantite'];
            $sale->ref_produit = strtoupper(substr($service['type'], 0, 3));
            $sale->save();
        }
    }

    // Update PaymentStatus
    $total_amount = Sale::where('no_bl', $no_bl)->sum('montant');
    $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();

    PaymentStatus::where('no_bl', $no_bl)->update([
        'changeCount' => $paymentStatus->changeCount + 1,
        'montant_total' => $total_amount,
        // 'montant_payed' => 0,
        'montant_restant' => $total_amount - $paymentStatus->montant_payed, // Adjust if payments are tracked
    ]);

    return redirect()->route('avance.edit', [
        'no_bl' => $no_bl, 
        'code_client' => $code_client,
        'total_amount' => $total_amount,
        'oldPayedAmount' => $paymentStatus->montant_payed
    ]);

    // return redirect()->route('sales.index')->with('success', 'Vente mise à jour avec succès!');
    }
    

    
     
    public function showUploadForm()
    {
        return view('sales.upload');
    }

    public function import(Request $request)
    {

        // dd($request->file('file'));
        // Validate inputs
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'action' => 'required|in:add,replace',
        ]);

        $action = $request->input('action');

        if ($action === 'replace') {
            Sale::truncate();
        }

        // Import the Excel file
        Excel::import(new SalesImport, $request->file('file'));

        $message = $action === 'replace' 
            ? 'Existing data replaced and new data imported successfully!' 
            : 'New data added successfully!';
            
        return redirect()->back()->with('success', $message);
    }

    public function getByBl(Request $request)
    {
        // $sales = Sale::where('no_bl', $request->no_bl)->get();
        $sales = Sale::select([
            'sales.id',
            'sales.no_bl',
            'sales.annee',
            'sales.date',
            'sales.code_client',
            'sales.ref_produit',
            'sales.produit',
            'sales.longueur',
            'sales.largeur',
            'sales.nbr',
            'sales.qte',
            'sales.prix_unitaire',
            'sales.montant',
            'clients.name as client_name'
        ])
        ->join('clients', 'sales.code_client', '=', 'clients.code_client') // Use strict join
        ->where('sales.no_bl', $request->no_bl)
        ->get();
        return response()->json(['sales' => $sales]);
    }


}
