<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Routing\Controller;

class PaymentStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('permission:view payment statuses')->only(['index']);
        $this->middleware('permission:populate payment statuses')->only(['populatePaymentStatus']);
        $this->middleware('permission:view sales with no payment status')->only(['getSalesWithNoPaymentStatus']);
        $this->middleware('permission:filter payment statuses by client type')->only(['getByClientType']);
    }
public function index(Request $request)
{
    if ($request->ajax()) {
        $query = PaymentStatus::select(
            'payment_statuses.id',
            'payment_statuses.no_bl',       
            'payment_statuses.code_client',
            'payment_statuses.name_client',
            'payment_statuses.date_bl', 
            'payment_statuses.montant_total',
            'payment_statuses.montant_payed',
            'payment_statuses.montant_restant',
            'clients.type as client_type' // Include client type in the selection
        )
        ->join('clients', 'payment_statuses.code_client', '=', 'clients.code_client'); // Join with clients table

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

        // Apply client type filter if provided
        if ($request->filled('client_type') && $request->client_type !== 'all') {
            $query->where('clients.type', $request->client_type);
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


    public function getByClientType(Request $request)
{
    $clientType = $request->input('client_type');

    $query = PaymentStatus::query();

    if ($clientType && $clientType !== 'all') {
        // Join the 'clients' table to filter by client type
        $query->join('clients', 'payment_statuses.code_client', '=', 'clients.code_client')
              ->where('clients.type', $clientType)
              ->select('payment_statuses.*'); // Ensure to only select the columns needed from 'payment_statuses'
    }

    $data = $query->get(); // Fetch the filtered data

    // Return the data in JSON format
    return response()->json([
        'data' => $data
    ]);
}




}
