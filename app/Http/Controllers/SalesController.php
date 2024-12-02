<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    /**
     * Show the form to upload an Excel file.
     */

    //  public function index(Request $request)
    //  {
    //      if ($request->ajax()) {
    //          $sales = Sale::with('client')->select([
    //             'sales.no_bl',
    //             'sales.annee',
    //             'sales.date', // Use the original column name
    //             'sales.code_client',
    //             'clients.name as client', // Keep this alias for client name
    //             'sales.ref_produit',
    //             'sales.produit',
    //             'sales.longueur', // Original name
    //             'sales.largeur',  // Original name
    //             'sales.nbr',
    //             'sales.qte',
    //             'sales.prix_unitaire', // Original name
    //             'sales.montant'
    //          ]);
     
    //          return DataTables::of($sales)
    //              ->addColumn('client', function ($sale) {
    //                  return $sale->client ? $sale->client->name : 'Unknown'; // Get client name
    //              })
    //              ->addColumn('actions', function ($sale) {
    //                  $editUrl = route('sales.index', $sale->id);
    //                  $deleteUrl = route('sales.index', $sale->id);
    //                  return '
    //                      <a href="' . $editUrl . '" class="text-blue-500 hover:underline mr-2">Edit</a>
    //                      <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
    //                          ' . csrf_field() . method_field('DELETE') . '
    //                          <button type="submit" class="text-red-500 hover:underline">Delete</button>
    //                      </form>
    //                  ';
    //              })
    //              ->rawColumns(['actions']) // Render HTML for the Actions column
    //              ->make(true);
    //      }
     
    //      return view('sales.index');
    //  }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Join clients table for sorting and filtering
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
                    'clients.name as client_name' // Include client name in query
                ])
                ->leftJoin('clients', 'sales.code_client', '=', 'clients.code_client'); // Join the clients table
    
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
    
     
     
    public function showUploadForm()
    {
        return view('sales.upload');
    }

    /**
     * Handle the file upload and import process.
     */
    // public function import(Request $request)
    // {
    //     // Validate inputs
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv',
    //         'action' => 'required|in:add,replace',
    //     ]);

    //     $action = $request->input('action');

    //     if ($action === 'replace') {
    //         Sale::truncate();
    //     }

    //     // Import the Excel file
    //     Excel::import(new SalesImport, $request->file('file'));

    //     $message = $action === 'replace' 
    //         ? 'Existing data replaced and new data imported successfully!' 
    //         : 'New data added successfully!';
            
    //     return redirect()->back()->with('success', $message);
    // }

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


// fetching all sales with his clients.

public function getSalesWithClients()
{
    $sales = DB::table('sales')
        ->join('clients', 'sales.code_client', '=', 'clients.code_client') // Join the tables
        ->select(
            'sales.no_bl',
            'sales.annee',
            'sales.date as date_bl',
            'sales.code_client',
            'clients.name as client', 
            'sales.ref_produit',
            'sales.produit',
            'sales.longueur as long',
            'sales.largeur as larg',
            'sales.nbr',
            'sales.qte',
            'sales.prix_unitaire as prix_u',
            'sales.montant as bl_mont'
        )
        ->get();

    return response()->json($sales);
}


}
