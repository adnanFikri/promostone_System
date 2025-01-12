<?php

namespace App\Http\Controllers\Achat;

use App\Models\Achat\Achat;
use Illuminate\Http\Request;
use App\Models\Achat\AchatStatus;
use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Yajra\DataTables\Facades\DataTables;

class AchatStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view achat payment statuses')->only(['index']);


    }
public function index(Request $request)
{
    if ($request->ajax()) {
        $query = AchatStatus::select(
            'achat_statuses.id',
            'achat_statuses.no_bl',       
            'achat_statuses.id_fournisseur',
            'achat_statuses.name_fournisseur',
            'achat_statuses.date_bl', 
            'achat_statuses.montant_total',
            'achat_statuses.montant_payed',
            'achat_statuses.montant_restant',
            // 'clients.type as client_type' // Include client type in the selection
        );  
        // Apply date range filter if both dates are provided
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('achat_statuses.date_bl', [$request->date_from, $request->date_to]);
        } 
        // Apply filter if only date_from is provided
        elseif ($request->filled('date_from')) {
            $query->whereDate('achat_statuses.date_bl', '>=', $request->date_from);
        } 
        // Apply filter if only date_to is provided
        elseif ($request->filled('date_to')) {
            $query->whereDate('achat_statuses.date_bl', '<=', $request->date_to);
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

    return view('achat.achatStatus.index');
}



    // public function getSalesWithNoPaymentStatus()
    // {
    //     // Get all sales where code_client does not exist in PaymentStatus
    //     $sales = Achat::whereNotIn('id_fournissuer', AchatStatus::pluck('id_fournissuer')->toArray())
    //                   ->get();

    //     // Return the retrieved sales
    //     return response()->json($sales);
    // }


//     public function getByClientType(Request $request)
// {
//     $clientType = $request->input('client_type');

//     $query = AchatStatus::query();

//     if ($clientType && $clientType !== 'all') {
//         // Join the 'clients' table to filter by client type
//         $query->join('clients', 'payment_statuses.code_client', '=', 'clients.code_client')
//               ->where('clients.type', $clientType)
//               ->select('payment_statuses.*'); // Ensure to only select the columns needed from 'payment_statuses'
//     }

//     $data = $query->get(); // Fetch the filtered data

//     // Return the data in JSON format
//     return response()->json([
//         'data' => $data
//     ]);
// }


}
