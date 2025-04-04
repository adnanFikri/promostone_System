<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;

class JournaleCaisseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:view journaleCaisse')->only(['journalCaisse']);
    }

    public function journalCaisse()
{
    return view('journaleCaisse.show', [
        'dateFrom' => null, // Initialize as null
        'dateTo' => null,   // Initialize as null
        'salesDetails' => [],
        'totalAllSales' => 0,
        'totalSalesReglementEspece' => 0,
        'totalSalesReglementCheque' => 0,
        'totalSalesReglementVirement' => 0,
        'totalSalesMontantRestant' => 0,
        'filteredReglements' => collect(), // Empty collection
        'filteredReglementsSum' => 0, // Initialize as 0
        'reglements' => collect(), // Empty collection
        'totalChiffreAffaire' => 0,
        'totalByType' => collect() // Empty collection
    ]);
}

    public function filterJournalCaisse(Request $request)
{
    $request->validate([
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
    ]);

    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');

    // Retrieve sales within the date range
    // $sales = Sale::whereBetween('date', [$dateFrom, $dateTo])->get();
    $sales = Sale::whereBetween('date', [
        Carbon::parse($dateFrom)->startOfDay(),
        Carbon::parse($dateTo)->endOfDay()
    ])->get();
    

    $totalAllSales = $sales->sum('montant');
    // Get the list of unique 'no_bl' from sales
    $salesNoBL = $sales->pluck('no_bl')->unique();

    // Retrieve reglement totals but only for sales that exist in $sales
    // $filteredReglements = Reglement::whereIn('no_bl', $salesNoBL)->get();
    $filteredReglements = Reglement::whereIn('no_bl', $salesNoBL)
    ->orderBy('created_at') // Ensure first record per BL
    ->get()
    ->groupBy('no_bl') // Group by no_bl
    ->map(fn($reglements) => $reglements->first());


    $totalReglementEspece = $filteredReglements->where('type_pay', 'Espèce')->sum('montant');
    $totalReglementCheque = $filteredReglements->where('type_pay', 'Chèque')->sum('montant');
    $totalReglementVirement = $filteredReglements->where('type_pay', 'Virement')->sum('montant');

    $filteredPayments = PaymentStatus::whereIn('no_bl', $salesNoBL)->get();
    // $totalSalesMontantRestant = $filteredPayments->where('montant_restant', '>=', 0)->sum('montant_restant');
    $totalreglement = $totalReglementEspece + $totalReglementCheque + $totalReglementVirement;
    $totalChiffreAffaire = $filteredPayments->sum('montant_total');
    $totalSalesMontantRestant = $totalChiffreAffaire - $totalreglement;


    // Group sales by product name
    // Group sales by BL and then by product
    $groupedSales = $sales->groupBy('no_bl')->map(function ($salesByBL) {
        return $salesByBL->groupBy('produit')->map(function ($salesByProduct) {
            // Initialize totalQte as 0
            $totalQte = 0;
            
            // Loop through each sale and determine whether to sum 'qte' or 'nbr'
            foreach ($salesByProduct as $sale) {
                if ($sale->mode == 'Unité' || $sale->mode == 'service') {
                    // Use 'nbr' for Unité or service modes
                    $totalQte += $sale->nbr;
                } else {
                    // Use 'qte' for M2 or M3 modes
                    $totalQte += $sale->qte;
                }
            }
    
            // Prix unitaire remains the same
            $prixUnitaire = $salesByProduct->first()->prix_unitaire;
    
            return [
                'produit' => $salesByProduct->first()->produit, // Nom du produit
                'qte' => $totalQte, // Somme des quantités ou nbr
                'prix_unitaire' => $prixUnitaire,
                'montant' => $prixUnitaire * $totalQte, // Recalcul du montant total
            ];
        });
    });
    
    // Retrieve related data
    $salesDetails = $groupedSales->map(function ($salesByProduit, $no_bl) {
        $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();
        $reglements = Reglement::where('no_bl', $no_bl)->first();

        return [
            'no_bl' => $no_bl,
            'salesByProduit' => $salesByProduit,
            'paymentStatus' => $paymentStatus,
            'reglements' => $reglements,
        ];
    })->sortBy('no_bl');

    // dd($salesDetails);

    // 0000000000000000000000000000000000000000000000000000000000000000000000000000
    // 000000000 for showed reglemetns details include just reglements mode 0000000
    // 0000000000000000000000000000000000000000000000000000000000000000000000000000
    $filteredReglements = Reglement::whereBetween('created_at', [
        Carbon::parse($dateFrom)->startOfDay(),
        Carbon::parse($dateTo)->endOfDay()
    ])
    // ->whereIn('no_bl', $salesNoBL) // Ensure it's related to the sales
    ->where('mode', 'reglement') // Ensure it's related to the sales
    ->get()
    ->groupBy('no_bl')
    ->flatten(); // Flatten the grouped results

    // 0000000000000000000000000000000000000000000000000000000000000000000000000000
    // 000000000 for showed reglemetns details include just reglements mode 0000000
    // 0000000000000000000000000000000000000000000000000000000000000000000000000000
    $filteredReglementsSum = $filteredReglements->sum('montant');
    // $filteredReglementsSum = Reglement::whereBetween('created_at', [
    //     Carbon::parse($dateFrom)->startOfDay(),
    //     Carbon::parse($dateTo)->endOfDay()
    // ])
    // // ->whereIn('no_bl', $salesNoBL) // Ensure it's related to the sales
    // // ->where('mode', 'reglement') // Ensure it's related to the sales
    // ->get()
    // ->groupBy('no_bl')
    // ->flatten()->sum('montant'); // Flatten the grouped results


    $reglements = Reglement::whereBetween('created_at', [
        Carbon::parse($dateFrom)->startOfDay(),
        Carbon::parse($dateTo)->endOfDay()
    ])->get();
    // $totalChiffreAffaire = $reglements->sum('montant');
    $totalByType = $reglements->groupBy('type_pay')->map->sum('montant');

    // 00000000000000000000000000000000000000000000000000000000000000000000000000000000000000
    // 000000000 for showed reglemetns details diviese for distristone and promostone 0000000
    // 00000000000000000000000000000000000000000000000000000000000000000000000000000000000000
    $reglementsDistristone = Reglement::whereBetween('created_at', [
        Carbon::parse($dateFrom)->startOfDay(),
        Carbon::parse($dateTo)->endOfDay()
    ])
    ->where('type_pay', 'Espèce') // Filter only "Espèce" payments
    ->whereHas('paymentStatus', function ($query) {
        $query->where('commerçant', 'like', 'Distristone%'); // Ensure column name is correct
    })
    ->get();

    $reglementsDistristoneMontant = $reglementsDistristone->sum('montant');
    
    // dd($filteredReglements);
    return view('journaleCaisse.show', [
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'salesDetails' => $salesDetails,
        'totalAllSales' => $totalAllSales,
        'totalSalesReglementEspece' => $totalReglementEspece,
        'totalSalesReglementCheque' => $totalReglementCheque,
        'totalSalesReglementVirement' => $totalReglementVirement,
        'totalSalesMontantRestant' => $totalSalesMontantRestant,

        "filteredReglements" => $filteredReglements,
        "filteredReglementsSum" => $filteredReglementsSum,

        'reglements' => $reglements, 'totalChiffreAffaire' => $totalChiffreAffaire, 'totalByType' => $totalByType,
        'reglementsDistristoneMontant' => $reglementsDistristoneMontant
    ]);

}

}
