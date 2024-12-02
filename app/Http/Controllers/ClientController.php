<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Sale;  // Import the Sale model

class ClientController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::select(['id', 'code_client', 'name', 'phone', 'type']);
            return DataTables::of($clients)
                ->addColumn('actions', function ($client) {
                    $editUrl = route('clients.edit', $client->id);
                    $deleteUrl = route('clients.destroy', $client->id);
                    return '
                        <div id="div-actions1" class="bg-gray-100" style="background-color:transparent;display:flex;">
                            <a  href="'.$editUrl.'" class="text-blue-500 hover:underline ">
                                <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </a>
                            <form action="'.$deleteUrl.'" method="POST" style="display: inline-block; float:left;" onsubmit="return confirm(\'Are you sure?\');">
                                '.csrf_field().method_field('DELETE').'
                                <button type="submit" class="text-red-500 hover:underline"><svg class="w-6 h-6 text-red-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>
                         </div>
                    ';
                })
                ->rawColumns(['actions']) // Render HTML for actions column
                ->make(true);
        }

        return view('clients.index');
    }

   
    
    
    // Show the form to create a new client
    public function create()
    {
        // Fetch all the sales to populate the code_client dropdown
        $sales = Sale::all(); // Or use a more efficient query with pagination or search functionality
        return view('clients.create', compact('sales'));
    }

    // Store a new client in the database
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'code_client' => 'required|exists:sales,code_client',  // Ensure the code_client exists in sales
            'name' => 'required|string',
            'phone' => 'required|string',
            'type' => 'required|string|in:Particulier,Fiche client,Anomalie',
        ]);

        // Create the new client
        $client = new Client();
        $client->code_client = $request->input('code_client');
        $client->name = $request->input('name');
        $client->phone = $request->input('phone');
        $client->type = $request->input('type');
        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function search(Request $request)
{
    $search = $request->get('q'); // Get search query
    // dd($request);
    $sales = Sale::where('code_client', 'like', '%' . $search . '%')
                 ->groupBy('code_client') // Group by code_client to return unique clients
                 ->get(['code_client']); 
    return response()->json($sales);
}

public function edit(Client $client)
{
    $sales = Sale::all(); // Fetch sales data for the dropdown
    return view('clients.edit', compact('client', 'sales'));
}

// Update the client in the database
    public function update(Request $request, Client $client)
    {
        // Validate the data
        $request->validate([
            'code_client' => 'required|exists:sales,code_client',
            'name' => 'required|string',
            'phone' => 'required|string',
            'type' => 'required|string|in:Particulier,Fiche client,Anomalie',
        ]);

        // Update the client
        $client->update([
            'code_client' => $request->input('code_client'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'type' => $request->input('type'),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Request $request, Client $client){
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function uploadForm()
    {
        return view('clients.upload');
    }

    public function show(){
        return view('clients.upload');
        
    }

    // Import the Excel file
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'action' => 'required|in:add,replace',
        ]);

        $action = $request->input('action');

        // If replace action is selected, truncate the clients table first
        if ($action === 'replace') {
            Client::truncate();
        }

        // Import the Excel file for clients
        Excel::import(new ClientsImport, $request->file('file'));

        $message = $action === 'replace' 
            ? 'Existing client data replaced and new data imported successfully!' 
            : 'New client data added successfully!';

        return redirect()->back()->with('success', $message);
    }

}
