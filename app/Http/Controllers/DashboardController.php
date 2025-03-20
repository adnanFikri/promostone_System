<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Client;
use App\Models\BonCoupe;
use App\Models\BonSortie;
use App\Models\Reglement;
use App\Models\SaleCheck;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Achat\Achatreglement;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
    }
    public function index()
    {
    
        // Get today's date
        $dateFrom = Carbon::now()->startOfDay()->toDateString();
        $dateTo = Carbon::now()->endOfDay()->toDateString();

    // 000000000000000000000000000000000000000000000000
    // 0000000000 {START JOURANL CAISSE PART 0000000000
    // 000000000000000000000000000000000000000000000000

        // sold de bon reglements not avance
        $filteredReglements = Reglement::whereBetween('created_at', [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ])
        ->where('mode', 'reglement') // Ensure it's related to the sales
        ->get()
        ->groupBy('no_bl')
        ->flatten(); // Flatten the grouped results

        $filteredReglementsSum = $filteredReglements->sum('montant');



        // Retrieve sales within the date range
        $sales = Sale::whereBetween('date', [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ])->get();

        // Get the list of unique 'no_bl' from sales
        $salesNoBL = $sales->pluck('no_bl')->unique();

        $reglements = Reglement::whereBetween('created_at', [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ])->get();
        
        $totalByType = [
            'Espèce' => $reglements->where('type_pay', 'Espèce')->sum('montant'),
            'Chèque' => $reglements->where('type_pay', 'Chèque')->sum('montant'),
            'Virement' => $reglements->where('type_pay', 'Virement')->sum('montant'),
        ];

        $filteredPayments = PaymentStatus::whereIn('no_bl', $salesNoBL)->get();
        $totalChiffreAffaire = $filteredPayments->sum('montant_total');
        $totalReglement = array_sum($totalByType);
        // $totalSalesMontantRestant = $totalChiffreAffaire - $totalReglement;

    // ~00000000000000000000000000000000000000000000000
    // ~0000000000 }END JOURANL CAISSE PART 00000000000
    // ~00000000000000000000000000000000000000000000000
// ====================================================================
// ====================================================================
    
    // 000000000000000000000000000000000000000000000000
    // 00000000000 {START DATE CHEQUE PART 00000000000
    // 000000000000000000000000000000000000000000000000

        // Fetch reglements with date_chq within 24h before today or today
        // $date24hAgo = Carbon::now()->subDay()->startOfDay();
        // $reglementsWithin24h = Reglement::whereBetween('date_chq', [
        //     $date24hAgo,
        //     Carbon::parse($dateTo)->endOfDay()
        // ])->get();
        $date24hAgo = Carbon::now()->subDay()->startOfDay();
        $reglementsWithin24h = Reglement::select(
                'reglements.*', 
                'clients.name as name_client'
            )
            ->leftJoin('clients', 'clients.code_client', '=', 'reglements.code_client') // Join clients table
            ->whereBetween('reglements.date_chq', [
                $date24hAgo,
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->get();

        // Fetch AchatReglements with date_chq within 48h before today or today
        $date48hAgo = Carbon::now()->subDays(2)->startOfDay();
        $achatReglementsWithin48h = Achatreglement::select(
                'achatreglements.*', 
                'fournisseurs.raison as name_fournisseur'
            )
            ->leftJoin('fournisseurs', 'fournisseurs.id', '=', 'achatreglements.id_fournisseur') // Join fournisseurs table
            ->whereBetween('achatreglements.date_chq', [
                $date48hAgo,
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->get();

    // ~00000000000000000000000000000000000000000000000
    // ~0000000000 {END DATE CHEQUE PART 00000000000
    // ~00000000000000000000000000000000000000000000000
// ====================================================================
// ====================================================================

    // 000000000000000000000000000000000000000000000000
    // 00000000 {START PAYMENT STATUS CHECK PART 000000
    // 000000000000000000000000000000000000000000000000

        $lastSaleCheck = SaleCheck::select('no_bl', 'user_name', DB::raw("DATE_FORMAT(CONVERT_TZ(created_at, '+00:00', '+00:00'), '%Y-%m-%d %H:%i:%s') as created_at"))
        ->orderBy('created_at', 'desc')
        ->first();

    // ~00000000000000000000000000000000000000000000000
    // ~0000000 {END PAYMENT STATUS CHECK PART 00000000
    // ~00000000000000000000000000000000000000000000000
// ====================================================================
// ====================================================================

    // 000000000000000000000000000000000000000000000000
    // 00000 {START BON COUPE EN COURE CHECK PART 00000
    // 000000000000000000000000000000000000000000000000
        // Get Bons with 'En cours' in coupe
        $bonsCoupe = BonCoupe::where('coupe', 'En cours')
            ->whereHas('sales', function ($query) {
                $query->join('products', 'sales.produit', '=', 'products.name')
                    ->whereNotIn('products.type', ['TRANCHE', 'CARREAUX', 'ESCALIER']);
            })
            ->with(['sales', 'paymentStatuses'])
            ->get()
            ->groupBy('no_bl');

        // Get Bons with 'En cours' in finition
        $bonsFinition = BonCoupe::where('finition', 'En cours')
            ->whereHas('sales', function ($query) {
                $query->join('products', 'sales.produit', '=', 'products.name')
                    ->whereNotIn('products.type', ['TRANCHE', 'CARREAUX', 'ESCALIER']);
            })
            ->with(['sales', 'paymentStatuses'])
            ->get()
            ->groupBy('no_bl');

        // Transform data
        $bonsCoupe = $bonsCoupe->map(function ($group) {
            $bon = $group->first();
            $code_client = $bon->paymentStatuses->first()->code_client ?? null;
            $client_raison = 'N/A';

            if ($code_client) {
                $client = Client::where('code_client', $code_client)->first();
                $client_raison = $client ? ($client->category . ' ' . $client->name) : 'N/A';
            }

            return [
                'no_bl' => $bon->no_bl,
                'dateCommence' => $bon->date_encours,
                'produit' => $bon->sales->first()->produit ?? 'N/A',
                'nom_client' => $client_raison,
                'coupe' => $bon->coupe,
            ];
        });

        $bonsFinition = $bonsFinition->map(function ($group) {
            $bon = $group->first();
            $code_client = $bon->paymentStatuses->first()->code_client ?? null;
            $client_raison = 'N/A';

            if ($code_client) {
                $client = Client::where('code_client', $code_client)->first();
                $client_raison = $client ? ($client->category . ' ' . $client->name) : 'N/A';
            }

            return [
                'no_bl' => $bon->no_bl,
                'produit' => $bon->sales->first()->produit ?? 'N/A',
                'nom_client' => $client_raison,
                'finition' => $bon->finition,
            ];
        });

    // ~00000000000000000000000000000000000000000000000
    // ~00000 {END BON COUPE EN COURE CHECK PART 000000
    // ~00000000000000000000000000000000000000000000000
// ====================================================================
// ====================================================================

    // 000000000000000000000000000000000000000000000000
    // 000000000000 {START BON SORITE PART 000000000000
    // 000000000000000000000000000000000000000000000000

        // Get Bons from BonSortie with 'Oui' in sortie and today's date
        $bonsSortie = BonSortie::where('sortie', 'Oui')
            ->whereDate('date_sortie', now()->toDateString())
            ->with(['sales', 'paymentStatuses'])
            ->get()
            ->groupBy('no_bl');

            // Transform data
            $bonsSortie = $bonsSortie->map(function ($group) {
                $bon = $group->first();
                $code_client = $bon->paymentStatuses->first()->code_client ?? null;
                $client_raison = 'N/A';

                if ($code_client) {
                    $client = Client::where('code_client', $code_client)->first();
                    $client_raison = $client ? ($client->category . ' ' . $client->name) : 'N/A';
                }

                return [
                    'no_bl' => $bon->no_bl,
                    'dateSortie' => $bon->date_sortie,
                    'produit' => $bon->sales->first()->produit ?? 'N/A',
                    'nom_client' => $client_raison,
                    'sortie' => $bon->sortie,
                ];
            });


    // ~00000000000000000000000000000000000000000000000
    // ~000000000000 {END BON SORITE PART 0000000000000
    // ~00000000000000000000000000000000000000000000000
// ====================================================================
// ===================================================================

// ========================================================
// =>                                                    <=
// ==>                   RETURN DATA                    <==
// ===>                                                <===
// ====>                                              <====
        return view('dashboard', compact(
            // JOURANL CAISSE PART
            'dateFrom',
            'dateTo',
            'filteredReglements',
            'filteredReglementsSum',
            'totalByType',
            'totalChiffreAffaire',

            // DATE CHEQUE PART
            'reglementsWithin24h',
            'achatReglementsWithin48h',

            // PAYMENT STATUS PART
            'lastSaleCheck',

            //BON COUPE EN COURE PART
            'bonsCoupe', 'bonsFinition',

            //BON BON SORITE PART
            'bonsSortie',
        ));
    }

    public function paymentStatusData(Request $request)
{
    $today = Carbon::today();
    if ($request->ajax()) {
        $query = PaymentStatus::select(
            'payment_statuses.no_bl',       
            'payment_statuses.code_client',
            'payment_statuses.name_client',
            'payment_statuses.date_bl', 
            'payment_statuses.changeCount',  
            'payment_statuses.montant_total',
            'payment_statuses.montant_payed',
            'payment_statuses.montant_restant',
            'clients.type as client_type' 
        )
        ->join('clients', 'payment_statuses.code_client', '=', 'clients.code_client')
        ->distinct()
        ->whereDate('payment_statuses.date_bl', '=', $today);

        $lastSaleCheck = SaleCheck::select('no_bl', 'user_name',DB::raw("DATE_FORMAT(CONVERT_TZ(created_at, '+00:00', '+00:00'), '%Y-%m-%d %H:%i:%s') as created_at"))
        ->orderBy('created_at', 'desc')
        ->first();
        
        $data = $query->get();

        $data->each(function ($item) {
            // Fetch `coupe` from BonCoupe
            $item->coupe = BonCoupe::where('no_bl', $item->no_bl)->value('coupe');

            // Fetch `sortie` from BonSortie
            $item->sortie = BonSortie::where('no_bl', $item->no_bl)->value('sortie');

            $coupef = BonCoupe::where('no_bl', $item->no_bl)->first();
            $item->time_difference = 'pas encore';

            if ($coupef && $coupef->print_date && $coupef->date_coupe) {
                $printDate = Carbon::parse($coupef->print_date);
                $dateCoupe = Carbon::parse($coupef->date_coupe);
                $diffHours = floor($printDate->diffInHours($dateCoupe));
                $diffMinutes = $printDate->diffInMinutes($dateCoupe) % 60;
                $item->time_difference = "{$diffHours}h {$diffMinutes}min";
            }
        });

        return DataTables::of($data)
        ->make(true);
    }
    
}

public function getPaymentsOutImpayed(Request $request)
{
    if ($request->ajax()) {
        $paymentsOutImpayed = PaymentStatus::where('montant_restant', '>', 30)
            ->whereHas('bonSortie', function ($query) {
                $query->where('sortie', 'Oui');
            })
            ->get()
            ->groupBy('name_client')
            ->map(function ($group) {
                return [
                    'name_client' => $group->first()->name_client,
                    'montant_restant' => $group->sum('montant_restant')
                ];
            })->values(); // Ensure correct JSON format

        return DataTables::of($paymentsOutImpayed)->make(true);
    }
}

}
