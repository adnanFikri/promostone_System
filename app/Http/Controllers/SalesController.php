<?php

namespace App\Http\Controllers;

use App\Models\BonCoupe;
use App\Models\BonLivraison;
use App\Models\BonSortie;
use App\Models\Reglement;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\DB;
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
        $this->middleware('permission:view bon livraison')->only(['showBonLivraison']);
        $this->middleware('permission:view bon coup')->only(['showBonCoup']);
        $this->middleware('permission:view bon sortie')->only(['showBonSortie']);
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
                ->leftJoin('clients', 'sales.code_client', '=', 'clients.code_client') // Join the clients table
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

        $no_bl = Sale::max('no_bl') + 1; // Auto-generate no_bl

        // dd($request);

        foreach ($validatedData['products'] as $product) {
            foreach ($product['mesures'] as $mesure) {

                $productModel = Product::where('name', $product['produit'])->first(); // Assuming 'name' matches 'produit'
                if ($productModel) {
                    // Check if there's enough quantity in stock
                    if ($productModel->quantity >= $mesure['quantite']) {
                        // Reduce the product's quantity
                        $productModel->quantity -= $mesure['quantite'];
                        $productModel->save();
                    } else {
                        // Handle case when there's not enough stock
                        return redirect()->back()->withErrors([
                            'products' => "Insufficient stock for product: {$product['produit']}"
                        ]);
                    }
                } else {
                    return redirect()->back()->withErrors([
                        'products' => "Product not found: {$product['produit']}"
                    ]);
                }

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
                $sale = new Sale();
                $sale->no_bl = $no_bl;
                $sale->annee = now()->year;
                $sale->date = now();
                $sale->code_client = $validatedData['code_client'];
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


    // // fetching all sales with his clients.
    // public function getSalesWithClients()
    // {
    //     $sales = DB::table('sales')
    //         ->join('clients', 'sales.code_client', '=', 'clients.code_client') // Join the tables
    //         ->select(
    //             'sales.no_bl',
    //             'sales.annee',
    //             'sales.date as date_bl',
    //             'sales.code_client',
    //             'clients.name as client', 
    //             'sales.ref_produit',
    //             'sales.produit',
    //             'sales.longueur as long',
    //             'sales.largeur as larg',
    //             'sales.nbr',
    //             'sales.qte',
    //             'sales.prix_unitaire as prix_u',
    //             'sales.montant as bl_mont'
    //         )
    //         ->get();

    //     return response()->json($sales);
    // }





}
