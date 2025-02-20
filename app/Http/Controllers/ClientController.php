<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use App\Imports\ClientsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Sale;  // Import the Sale model
use Illuminate\Validation\ValidationException;
// use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:view clients')->only(['index']);
        $this->middleware('permission:create clients')->only(['create']);
        $this->middleware('permission:store clients')->only(['store']);
        $this->middleware('permission:view client details')->only(['show']);
        $this->middleware('permission:edit clients')->only(['edit']);
        $this->middleware('permission:update clients')->only(['update']);
        $this->middleware('permission:delete clients')->only(['destroy']);
        $this->middleware('permission:search sales')->only(['search']);
        $this->middleware('permission:generate client code')->only(['getNextCode']);
        $this->middleware('permission:view client upload')->only(['uploadForm']);
        $this->middleware('permission:import clients')->only(['import']);
        $this->middleware('permission:view client data by code')->only(['getClientData']);
        $this->middleware('permission:change client type')->only(['changeType']);
    }

    public function index(Request $request)
    {
            if ($request->ajax()) {
                // $clients = Client::select(['id', 'code_client', 'name', 'category', 'phone', 'type', 'user-name']);
                $clients = Client::select([
                    'clients.id', 
                    'clients.code_client', 
                    'clients.name', 
                    'clients.category', 
                    'clients.phone', 
                    'clients.type', 
                    'clients.user-name',
                    DB::raw('COALESCE(SUM(payment_statuses.montant_total), 0) as total_sales'),
                    DB::raw('COALESCE(SUM(payment_statuses.montant_payed), 0) as total_paid'),
                    DB::raw('COALESCE(SUM(payment_statuses.montant_restant), 0) as total_restant'),
                ])
                ->leftJoin('payment_statuses', 'clients.code_client', '=', 'payment_statuses.code_client')
                ->groupBy('clients.id', 'clients.code_client', 'clients.name', 'clients.category', 'clients.phone', 'clients.type', 'clients.user-name');
    
                
                return DataTables::of($clients)
                    ->addColumn('actions', function ($client) {
                        $actions = '<div id="div-actions1" class="bg-gray-100" style="background-color:transparent;display:flex;">';
        
                        if (auth()->user()->can('edit clients')) {
                            $actions .= '
                                <a href="#" onclick="openUpdateModal(' . $client->id . ')" class="text-blue-500 hover:underline">
                                    <svg class="w-6 h-6 text-blue-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                </a>';
                        }
        
                        if (auth()->user()->can('delete clients')) {
                            $deleteUrl = route('clients.destroy', $client->id);
                            $actions .= '
                                <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Etes-vous sûr de vouloir supprimer ce client?\');">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="text-red-500 hover:underline">
                                        <svg class="w-6 h-6 text-red-400 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>';
                        }
        
                        if (auth()->user()->can('change client type')) {
                            $actions .= '
                                <div class="ml-4 form-check form-check-inline">
                                    <input ' . ($client->type == 'PARTICULIER' ? 'checked' : '') . ' class="form-check-input type-radio ml-4" type="radio" name="typeClient-' . $client->id . '" onclick="hello(' . $client->id . ',1)" value="P">
                                    <label for="particulier-' . $client->id . '" class="form-check-label font-bold">P</label>
                                </div>
                                <div class="ml-4 form-check form-check-inline font-bold">
                                    <input ' . ($client->type === 'FICHE CLIENT' ? 'checked' : '') . ' class="form-check-input type-radio" type="radio" name="typeClient-' . $client->id . '" onclick="hello(' . $client->id . ',2)" value="F">
                                    <label for="ficheClient-' . $client->id . '" class="form-check-label">F</label>
                                </div>
                                <div class="ml-4 form-check form-check-inline font-bold">
                                    <input ' . ($client->type === 'ANOMALIE' ? 'checked' : '') . ' class="form-check-input type-radio" type="radio" name="typeClient-' . $client->id . '" onclick="hello(' . $client->id . ',3)" value="A">
                                    <label for="ficheClient-' . $client->id . '" class="form-check-label">A</label>
                                </div>';
                        }
        
                        $actions .= '</div>';
                        return $actions;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
        
        

        return view('clients.index');
    }

    public function create()
    {
        // Fetch all the sales to populate the code_client dropdown
        $sales = Sale::all(); // Or use a more efficient query with pagination or search functionality
        return view('clients.create', compact('sales'));
    }

    // Store a new client in the database
    // public function store(Request $request)
    // {
    //     // Validate the incoming data
    //     $request->validate([
    //         'code_client' => 'required|exists:sales,code_client',  // Ensure the code_client exists in sales
    //         'name' => 'required|string',
    //         'phone' => 'required|string',
    //         'type' => 'required|string|in:Particulier,Fiche client,Anomalie',
    //     ]);

    //     // Create the new client
    //     $client = new Client();
    //     $client->code_client = $request->input('code_client');
    //     $client->name = $request->input('name');
    //     $client->phone = $request->input('phone');
    //     $client->type = $request->input('type');
    //     $client->save();

    //     return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    // }

    public function store(Request $request)
{
    // $request->validate([
    //     'code_client' => 'required|unique:clients,code_client',
    //     'name' => 'required',
    //     'phone' => 'required',
    //     'type' => 'required',
    // ], [
    //     'code_client.required' => 'Client code is required.',
    //     'code_client.unique' => 'The client code already exists. Please use a unique code.',
    //     'name.required' => 'Client name is required.',
    //     'phone.required' => 'Phone number is required.',
    //     'type.required' => 'Client type is required.',
    // ]);

    // Transform the 'name' attribute to uppercase
    $data = $request->all();
    $data['name'] = strtoupper($request->input('name'));

    // Create the client
    Client::create($data);
    
    if ($request->ajax()) {
        return response()->json(['message' => 'Client créé avec succès!']);
    }

    return redirect()->route('clients.index')->with('succès', 'Client créé avec succès!');
}

    // public function getNextCode()
    // {
    //     // Get the last client with code_client starting with '25s'
    //     $lastClientCode = Client::where('code_client', 'like', '25s%')
    //         ->orderBy('code_client', 'desc')
    //         ->value('code_client'); // Retrieve only the 'code_client' value

    //     // If the last client exists, increment its number; otherwise, start at 1
    //     $nextClientNumber = $lastClientCode ? intval(substr($lastClientCode, 3)) + 1 : 1;
        
    //     // Generate the new client code
    //     $newClientCode = '25s' . $nextClientNumber;

    //     return response()->json(['code_client' => $newClientCode]);
        
    // }
    public function getNextCode()
    {
        // Get the last client code, ordering alphabetically by prefix and numerically by the number part
        $lastClientCode = Client::selectRaw("
            LEFT(code_client, 1) as prefix, 
            CAST(SUBSTRING(code_client, 2) AS UNSIGNED) as code_number, 
            code_client
        ")
        ->orderBy('prefix', 'desc') // Order by the letter prefix
        ->orderBy('code_number', 'desc') // Then order numerically by the number part
        ->value('code_client'); // Retrieve only the 'code_client' value

        if ($lastClientCode) {
            // Extract the letter prefix and number from the last code
            $lastPrefix = substr($lastClientCode, 0, 1);
            $lastNumber = intval(substr($lastClientCode, 1));

            if ($lastNumber < 999) {
                // Increment the number if it's less than 999
                $nextNumber = $lastNumber + 1;
                $newClientCode = $lastPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                // Move to the next letter and reset the number to 1 if 999 is reached
                $nextPrefix = chr(ord($lastPrefix) + 1);
                $newClientCode = $nextPrefix . '001';
            }
        } else {
            // If no clients exist, start with A001
            $newClientCode = 'A001';
        }

        return response()->json(['code_client' => $newClientCode]);
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

    // In ClientController
    public function show(Client $client)
    {
        return response()->json($client);
    }


    public function edit(Client $client)
    {
        $sales = Sale::all(); // Fetch sales data for the dropdown
        return view('clients.edit', compact('client', 'sales'));
    }

    // Update the client in the database

    public function update(Request $request, Client $client)
    {
        try {
            // $validated = $request->validate([
            //     'name' => 'required|string',
            //     'phone' => 'nullable|string',
            //     'type' => 'required|string',
            // ]);

            $request['name'] = strtoupper($request['name']);

            // Update the client
            $client->update($request->all());

            return response()->json(['message' => 'Client updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating client: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update client. Check logs for details.'], 500);
        }
    }


    public function destroy(Request $request, Client $client){
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function uploadForm()
    {
        return view('clients.upload');
    }


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

    // FOR SELECT EXIST ON TABLE PAYMENT STATUS FOR GET BL'S WHO HIS CLIENT HAVE SOME TYPE
    public function filterPaymentStatuses(Request $request)
    {
        $type = $request->input('clientType'); // Retrieve the selected type
    
        // Base query: Join PaymentStatus with Client
        $query = PaymentStatus::query()
            ->join('clients', 'payment_statuses.client_id', '=', 'clients.id');
    
        // Apply filter based on client type if not 'all'
        if ($type && $type !== 'all') {
            $query->where('clients.type', $type);
        }
    
        // Fetch the filtered data
        $results = $query->select('payment_statuses.*', 'clients.name as client_name', 'clients.type')
                         ->get();
    
        return response()->json($results); // Return data as JSON for the frontend
    }
    
// show type and phone for clients in table PyemntStatus wehn click on name client
    public function getClientData($code_client)
    {
        try {
            $client = Client::where('code_client', $code_client)->firstOrFail();
            return response()->json([
                'category' => $client->category,
                'phone' => $client->phone,
                'type' => $client->type,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    }

    public function changeType(Client $client, $type) // Use route model binding
    {
        if ($type == 1) {
            $tp = "PARTICULIER";
        } elseif ($type == 2) { // Use elseif for clarity
            $tp = "FICHE CLIENT"; // Corrected typo
        }elseif ($type == 3){
            $tp = "ANOMALIE"; // Corrected typo
        }
        else {
            return response()->json(['error' => 'Invalid type provided.'], 400); // Return error response
        }

        $client->update(['type' => $tp]);

        return response()->json(['message' => 'Type updated successfully!']); // Return success message as JSON
    }
}
