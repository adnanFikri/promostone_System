<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Yajra\DataTables\Facades\DataTables;

class PaymentStatusController extends Controller
{

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = PaymentStatus::all();
    //         return DataTables::of($data)
    //             ->addColumn('actions', function($row) {
    //                 $btn = '<a href="'.route('paymentStatus.index', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
    //                 $btn .= ' <a href="'.route('paymentStatus.index', $row->id).'" class="delete btn btn-danger btn-sm">Delete</a>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['actions'])
    //             ->make(true);
    //     }
        
    //     return view('paymentStatus.index');
    // }

    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = PaymentStatus::select(
            'payment_statuses.id',
            'payment_statuses.code_client',
            'clients.name as name_client',
            'payment_statuses.number_sales',
            'payment_statuses.montant_total',
            'payment_statuses.number_paid',
            'payment_statuses.payed_total',
            'payment_statuses.remaining_balance'
        )
        ->leftJoin('clients', 'clients.code_client', '=', 'payment_statuses.code_client') // Use LEFT JOIN
        ->get();

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
    public function populatePaymentStatus()
    {
        // Get all unique clients from the Sales table (those who have made at least one sale)
        $clients = Sale::select('code_client')->distinct()->whereNotNull('code_client')->get();

        foreach ($clients as $client) {
            // Get all sales for this client, filtering out null code_client
            $sales = Sale::where('code_client', $client->code_client)->get();

            // Calculate the total amount (montant_total) and number of sales (number_sales)
            $montantTotal = $sales->sum('montant');
            $numberSales = $sales->count();
            $remainingBalance = $montantTotal; // Initially, the remaining balance equals the total amount

            // Create the PaymentStatus record for this client with the initial values
            PaymentStatus::create([
                'code_client' => $client->code_client,
                'number_sales' => $numberSales,
                'montant_total' => $montantTotal,
                'number_paid' => 0, // No payments made yet
                'payed_total' => 0, // No payments made yet
                'remaining_balance' => $remainingBalance // Initial remaining balance is the full amount
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
