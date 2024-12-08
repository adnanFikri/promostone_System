<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Yajra\DataTables\Facades\DataTables;

class PaymentStatusController extends Controller
{

//     public function index(Request $request)
// {
//     if ($request->ajax()) {
//         $data = PaymentStatus::select(
//             'payment_statuses.id',
//             'payment_statuses.code_client',
//             'clients.name as name_client',
//             'payment_statuses.number_sales',
//             'payment_statuses.montant_total',
//             'payment_statuses.number_paid',
//             'payment_statuses.payed_total',
//             'payment_statuses.remaining_balance'
//         )
//         ->leftJoin('clients', 'clients.code_client', '=', 'payment_statuses.code_client') // Use LEFT JOIN
//         ->get();

//         return DataTables::of($data)
//             ->addColumn('actions', function ($row) {
//                 $btn = '<a href="' . route('paymentStatus.index', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
//                 $btn .= ' <a href="' . route('paymentStatus.index', $row->id) . '" class="delete btn btn-danger btn-sm">Delete</a>';
//                 return $btn;
//             })
//             ->rawColumns(['actions'])
//             ->make(true);
//     }

//     return view('paymentStatus.index');
// }


public function index(Request $request)
{
    // if ($request->ajax()) {
    //     $data = PaymentStatus::select(
    //         'payment_statuses.id',
    //         'payment_statuses.no_bl',       
    //         'payment_statuses.code_client',
    //         'payment_statuses.name_client',
    //         'payment_statuses.date_bl', 
    //         'payment_statuses.montant_total',
    //         'payment_statuses.montant_payed',
    //         'payment_statuses.montant_restant'
    //     )->get();

    //     return DataTables::of($data)
    //         ->addColumn('actions', function ($row) {
    //             $btn = '<a href="' . route('paymentStatus.index', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
    //             $btn .= ' <a href="' . route('paymentStatus.index', $row->id) . '" class="delete btn btn-danger btn-sm">Delete</a>';
    //             return $btn;
    //         })
    //         ->rawColumns(['actions'])
    //         ->make(true);
    // }

    if ($request->ajax()) {
        $query = PaymentStatus::select(
            'payment_statuses.id',
            'payment_statuses.no_bl',       
            'payment_statuses.code_client',
            'payment_statuses.name_client',
            'payment_statuses.date_bl', 
            'payment_statuses.montant_total',
            'payment_statuses.montant_payed',
            'payment_statuses.montant_restant'
        );

        // Apply date range filter if both dates are provided
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_statuses.date_bl', [$request->date_from, $request->date_to]);
        } 
        // Apply filter if only date_from is provided
        elseif ($request->filled('date_from')) {
            $query->whereDate('payment_statuses.date_bl', '>=', $request->date_from);
        } 
        // Apply filter if only date_to is provided
        elseif ($request->filled('date_to')) {
            $query->whereDate('payment_statuses.date_bl', '<=', $request->date_to);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                $btn = '<a href="' . route('paymentStatus.index', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn .= ' <a href="' . route('paymentStatus.index', $row->id) . '" class="delete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    return view('paymentStatus.index');
}

    
    // This function should be executed only once to initialize the PaymentStatus table
    // public function populatePaymentStatus()
    // {
    //     // Get all unique clients from the Sales table (those who have made at least one sale)
    //     $clients = Sale::select('code_client')->distinct()->whereNotNull('code_client')->get();

    //     foreach ($clients as $client) {
    //         // Get all sales for this client, filtering out null code_client
    //         $sales = Sale::where('code_client', $client->code_client)->get();

    //         // Calculate the total amount (montant_total) and number of sales (number_sales)
    //         $montantTotal = $sales->sum('montant');
    //         $numberSales = $sales->count();
    //         $remainingBalance = $montantTotal; // Initially, the remaining balance equals the total amount

    //         // Create the PaymentStatus record for this client with the initial values
    //         PaymentStatus::create([
    //             'code_client' => $client->code_client,
    //             'number_sales' => $numberSales,
    //             'montant_total' => $montantTotal,
    //             'number_paid' => 0, // No payments made yet
    //             'payed_total' => 0, // No payments made yet
    //             'remaining_balance' => $remainingBalance // Initial remaining balance is the full amount
    //         ]);
    //     }

    //     return response()->json(['message' => 'PaymentStatus table populated successfully!']);
    // }

    // 0000- new populate -00000

    public function populatePaymentStatus()
    {
        set_time_limit(120); // Sets execution time to 120 seconds


        PaymentStatus::truncate();
        
        // Get all unique BLs from the Sales table (grouped by no_bl)
        $bls = Sale::select('no_bl', 'code_client', 'date')
            ->distinct()
            ->whereNotNull('no_bl')
            ->get();
    
        foreach ($bls as $bl) {
            // Skip if no_bl or code_client is null
            if (empty($bl->no_bl) || empty($bl->code_client)) {
                continue; // Skip this record
            }
    
            // Fetch all sales for this specific BL
            $sales = Sale::where('no_bl', $bl->no_bl)->get();
    
            // Calculate the total amount for this BL
            $montantTotal = $sales->sum('montant');
            $montantPayed = 0; // No payments made yet for this BL
            $montantRestant = $montantTotal; // Initially, the remaining balance equals the total amount
    
            PaymentStatus::create([
                'no_bl' => $bl->no_bl,
                'code_client' => $bl->code_client,
                'name_client' => Client::where('code_client', $bl->code_client)->value('name'), // Assume a `name` field exists in the `clients` table
                'date_bl' => $bl->date,
                'montant_total' =>   $montantTotal,
                'montant_payed' =>   $montantPayed,
                'montant_restant' => $montantRestant,
            ]);
        }
    
        return response()->json(['message' => 'PaymentStatus table populated successfully!']);
    }
    


    public function getSalesWithNoPaymentStatus()
    {
        // Get all sales where code_client does not exist in PaymentStatus
        $sales = Sale::whereNotIn('code_client', PaymentStatus::pluck('code_client')->toArray())
                      ->get();

        // Return the retrieved sales
        return response()->json($sales);
    }
}
