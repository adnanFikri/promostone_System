<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\BonCoupe;
use App\Models\BonSortie;
use App\Models\Reglement;
use App\Models\BonLivraison;
use Illuminate\Http\Request;

use App\Models\PaymentStatus;
use function Illuminate\Log\log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Routing\Controller;

class ReglementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('permission:view reglements')->only(['index']);
        $this->middleware('permission:create reglements')->only(['create']);
        $this->middleware('permission:store reglements')->only(['store']);
        $this->middleware('permission:delete reglements')->only(['destroy']);
        $this->middleware('permission:search reglements')->only(['search']);
        $this->middleware('permission:view payment status by client')->only(['getPaymentStatus']);
        $this->middleware('permission:view reglements by bl')->only(['getReglementsByBl']);
        $this->middleware('permission:view client bls')->only(['getClientBls']);
        $this->middleware('permission:view all bls')->only(['getAllBLs']);
        $this->middleware('permission:create avance')->only(['avance']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reglement::select(
                'reglements.id',
                'reglements.no_bl',
                'reglements.code_client',
                'clients.name as name_client',
                'reglements.montant',
                'reglements.mode',
                DB::raw("DATE_FORMAT(reglements.date, '%d-%m-%Y %H:%i:%s') as date"),
                'reglements.type_pay',
                'reglements.reference_chq', 
                DB::raw("DATE_FORMAT(reglements.date_chq, '%d-%m-%Y') as date_chq"),
                'reglements.user-name',
                'reglements.bls_count',
                'reglements.montant_total',
                'reglements.bls_list',
                DB::raw("DATE_FORMAT(reglements.date_encaissement, '%d-%m-%Y') as date_encaissement"),
                'reglements.type_bank', 
            )
            ->leftJoin('clients', 'clients.code_client', '=', 'reglements.code_client')->distinct() // LEFT JOIN with clients table
            ->where('reglements.montant', '>', 0)
            ->get();

            return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                // $btn = ''; // Initialize the variable to prevent "Undefined variable" error

                $btn = '<div class="flex space-x-2">';

                $colorEncaissement = $row->date_encaissement ? 'text-green-600 hover:text-green-300' : 'text-gray-600 hover:text-gray-400';
                
                if ($row->type_pay === 'Chèque' ) {

                    $btn .= '
                            <button title="Voir les détails du chèque" style="float:left;" class="btn btn-primary btn-sm view-cheque float-left"
                                data-id="' . $row->id . '" 
                                data-ref="' . $row->reference_chq . '" 
                                data-date="' . $row->date_chq . '" 
                                data-date_encaissement="' . $row->date_encaissement . '" 
                                data-type_bank="' . $row->type_bank . '"
                            >
                                <svg class="w-6 h-6 text-blue-800 hover:text-blue-400 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                </svg>
                            </button>';

                            if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin')) {
                                $btn .= '
                                    <button title="Encaisser le chèque" style="float:left;" class="btn btn-primary btn-sm encaisse-cheque" 
                                        data-id="' . $row->id . '" 
                                        >
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white '. $colorEncaissement.'" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z"/>
                                            </svg>
                                    </button>';
                            }
                            
                }else if($row->type_pay === 'Virement'){

                    $btn .= '
                            <button title="Voir les détails du virement" style="float:left;" class="btn btn-primary btn-sm view-virment float-left"
                                data-id="' . $row->id . '" 
                                data-date_encaissement="' . $row->date_encaissement . '" 
                                data-type_bank="' . $row->type_bank . '"
                            >
                                <svg class="w-6 h-6 text-blue-800 hover:text-blue-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                </svg>
                            </button>';
                    
                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin')) {
                        $btn .= '
                                <button title="Encaisser le chèque" style="float:left;" class="btn btn-primary btn-sm encaisse-cheque" 
                                    data-id="' . $row->id . '" 
                                    >
                                        <svg class="w-6 h-6 dark:text-white '. $colorEncaissement.'" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z"/>
                                        </svg>
                                </button>';
                    }
                }
                $deleteUrl = route('reglements.destroy', $row->id);
                if (auth()->user()->can('delete reglements')) {
                    $btn .= '
                            <form title="Supprimer le règlement" action="' . $deleteUrl . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Etes-vous sûr de vouloir supprimer ce règlement?\');">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="text-red-500 hover:text-red-300 hover:underline">
                                    <svg class="w-6 h-6 text-red-400 hover:text-red-300 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>';
                }

                if ($row->bls_count > 0) {
                    $btn .= '
                            <button title="les détails du Règlement Multi " style="float:left;" class="btn btn-info btn-sm view-multi-reglement" 
                                data-bls-count="' . $row->bls_count . '" 
                                data-montant-total="' . $row->montant_total . '" 
                                data-bls-list=\'' . json_encode($row->bls_list) . '\'>
                                <svg class="w-6 h-6 text-green-600 hover:text-green-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-1 1H6a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2a1 1 0 0 0-1-1H9Zm1 2h4v2h1a1 1 0 1 1 0 2H9a1 1 0 0 1 0-2h1V4Zm5.707 8.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                </svg>

                            </button>';
                }
                
    $btn .= '</div>'; // Fermeture du conteneur flex    
            
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
        }

        return view('reglements.index');
    }

    public function create()
    {
        $clients = client::all(); // Fetch all clients
        return view('reglements.create', compact('clients'));
    }

    public function store(Request $request)
    {
        try {
        // {{{{{check existing reglement
            $override = $request->input('override', false); // Get override flag

            $existingReglement = Reglement::where([
                ['no_bl', $request->no_bl],
                ['montant', $request->montant],
                ['mode', $request->mode],
            ])->first();

            if ($existingReglement && !$override) { // If override is false, ask for confirmation
                if ($request->mode == 'reglement') {
                    return response()->json([
                        'success' => 'confirm', 
                        'message' => 'Un règlement avec le même montant existe déjà pour cette BL. Voulez-vous continuer et enregistrer quand même ?',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement a déjà été enregistré pour cette BL avec ce montant.',
                ]);
            }
        // check existing reglement }}}}

            $client = Client::where('code_client', $request->code_client)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found.',
                ], 404);
            }
            $totalAmount = $request->montant;
            $blsList = [];
            $blsCount = 0;
            $reglements = [];

            if ($request->no_bl === 'multi') {
                // Fetch all unpaid BLs
                $paymentStatuses = PaymentStatus::where('code_client', $request->code_client)
                    ->where('montant_restant', '>', 0)
                    ->orderBy('date_bl', 'asc')
                    ->get();

                foreach ($paymentStatuses as $paymentStatus) {
                    if ($totalAmount <= 0) break;

                    $amountToPay = min($totalAmount, $paymentStatus->montant_restant);
                    $rest = $paymentStatus->montant_restant - $amountToPay;
                    $blsList[] = "no_bl: {$paymentStatus->no_bl} => Montant Paye: {$amountToPay}dh => Rest: $rest ";

                    // Update payment status
                    $paymentStatus->update([
                        'montant_payed' => $paymentStatus->montant_payed + $amountToPay,
                        'montant_restant' => $paymentStatus->montant_restant - $amountToPay,
                    ]);

                    // Create a separate Reglement for each BL
                    $reglements[] = Reglement::create([
                        'no_bl' => $paymentStatus->no_bl,
                        'code_client' => $request->code_client,
                        'nom_client' => $client->name,
                        'montant' => $amountToPay,
                        'mode' => $request->mode,
                        'date' => now(),
                        'type_pay' => $request->type_pay,
                        'reference_chq' => $request->reference_chq,
                        'date_chq' => $request->date_chq,
                        'user-name' => Auth::user()->name,
                        'bls_count' => 0, // Will be updated later
                        'montant_total' => $request->montant,
                        'bls_list' => '', // Will be updated later
                    ]);

                    $totalAmount -= $amountToPay;
                    $blsCount++;
                }

                // Now update all created Reglements with the correct bls_count and bls_list
                $blsListStr = implode(', ', $blsList);
                foreach ($reglements as $reglement) {
                    $reglement->update([
                        'bls_count' => $blsCount,
                        'bls_list' => $blsListStr,
                    ]);
                }

                // If there's remaining money, store it in client's solde_restant
                // if ($totalAmount > 0) {
                //     $client->update(['solde_restant' => $client->solde_restant + $totalAmount]);
                // }

                if ($totalAmount > 0) {
                    // Ensure solde_restant is a valid JSON array or initialize an empty array
                    $soldeRestant = is_string($client->solde_restant) && json_decode($client->solde_restant, true)
                        ? json_decode($client->solde_restant, true)
                        : [];
                
                    // Ensure it's an array
                    if (!is_array($soldeRestant)) {
                        $soldeRestant = [];
                    }
                
                    // Add the new remaining amount with the corresponding BL
                    $soldeRestant[] = [
                        'montant_rest' => $totalAmount,
                        'dateReglement'=> now(),
                        'montantTotale'=> $request->montant,
                        'no_bl' => $blsListStr, // Or use a specific BL if only one
                    ];
                
                    // Update the client with the new JSON-encoded solde_restant
                    $client->update(['solde_restant' => json_encode($soldeRestant)]);
                }
                

                return response()->json([
                    'success' => true,
                    'message' => 'Règlement enregistré avec succès.',
                    'reglement' => $reglements, // Returns all created reglements
                    'updatedPaymentStatus' => $paymentStatus,
                    'client' => $client,
                    'bls_list' => $blsListStr,
                    'bls_count' => $blsCount,
                ]);
            } else {
                $paymentStatus = PaymentStatus::where('no_bl', $request->no_bl)->first();

                if (!$paymentStatus) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun BL trouvé pour le client et le numéro BL donnés.',
                    ], 404);
                }
                // Fetch client name from `clients` table if `name_client` is missing
                if (is_null($paymentStatus->name_client)) {
                    $client = Client::where('code_client', $request->code_client)->first();
                    if ($client) {
                        $paymentStatus->update(['name_client' => $client->name]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Client not found for the given client code.',
                        ], 404);
                    }
                }
                // Fetch date_bl from `sales` table if missing
                if (is_null($paymentStatus->date_bl)) {
                    $sale = Sale::where('no_bl', $request->no_bl)->first();
                    if ($sale) {
                        $paymentStatus->update(['date_bl' => $sale->date]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Sale not found for the given BL number.',
                        ], 404);
                    }
                }
                // Create the Reglement entry
                $reglement = Reglement::create([
                    'no_bl' => $request->no_bl,
                    'code_client' => $request->code_client,
                    'nom_client' => $paymentStatus->name_client ,
                    'montant' => $request->montant,
                    'mode' => $request->mode,
                    'date' =>  $request->has('destination') ? now() : $request->date, 
                    'type_pay' => $request->type_pay,
                    'reference_chq' => $request->reference_chq, 
                    'date_chq' => $request->date_chq,          
                    'user-name' => Auth::user()->name
                ]);


                $rest = $paymentStatus->montant_total - ($paymentStatus->montant_payed + $request->montant);
                // Prepare data to update PaymentStatus
                $paymentStatusData = [
                    'montant_payed' => $paymentStatus->montant_payed + $request->montant,
                    'montant_restant' => $rest ,
                ];
            
                if ($request->has('chefAtelier') && $request->chefAtelier !== null) {
                    $paymentStatusData['chef-atelier'] = $request->chefAtelier;
                    BonLivraison::firstOrCreate(
                        ['no_bl' => $request->no_bl, 'userName' => Auth::user()->name]
                    );
        
                    BonCoupe::firstOrCreate(
                        ['no_bl' => $request->no_bl]
                    );
                    
                    BonSortie::firstOrCreate(
                        ['no_bl' => $request->no_bl]
                    );
                    
                }
                
                if ($request->has('destination') && $request->destination !== null) {
                    $paymentStatusData['destination'] = $request->destination;
                    $paymentStatusData['commerçant'] = Auth::user()->name;
                }
                
                if ($request->has('date_echeance') && $request->date_echeance !== null) {
                    $paymentStatusData['date-echeance'] = $request->date_echeance;
                }

                $paymentStatusData['user-name'] = Auth::user()->name;

                // Update the PaymentStatus
                $paymentStatus->update($paymentStatusData);

                // Success response
                return response()->json([
                    'success' => true,
                    'message' => 'Règlement enregistré avec succès.',
                    'reglement' => $reglement,
                    'updatedPaymentStatus' => $paymentStatus,
                ]);
            }
            
       

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 400);
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('q');

        $clients = Client::query()
            ->select(['code_client', 'name'])
            ->where('name', 'like', "%{$search}%")
            ->orWhere('code_client', 'like', "%{$search}%")
            ->distinct() // Ensure unique combinations of code_client and name_client
            ->orderByDesc('id') 
            ->get()
            ->map(function ($client) {
                return [
                    'code_client' => $client->code_client,
                    'name' => $client->name,
                ];
            });
        
        return response()->json($clients);
    }

    public function getAllBLs(Request $request)
    {
        // try {
            // Fetch BL data (replace with your actual query)
            $search = $request->input('q');
            $bls = PaymentStatus::orderBy('id', 'asc')
            ->orWhere('no_bl', 'like', "%{$search}%")
            ->get(['no_bl', 'name_client', 'code_client', 'montant_restant']);
            

            return response()->json($bls, 200);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }

    public function getBLs(Request $request)
    {
        $search = $request->input('q');
        $bls = PaymentStatus::select('no_bl', 'name_client', 'code_client')
            ->where('no_bl', 'like', "%{$search}%")
            ->orWhere('name_client', 'like', "%{$search}%")
            ->orWhere('code_client', 'like', "%{$search}%")
            ->get()
            ->map(function ($paymentStatus) {
                return [
                    'id' => $paymentStatus->no_bl,  // Use BL number as ID
                    'text' => $paymentStatus->no_bl . ' - ' . $paymentStatus->name_client . ' (' . $paymentStatus->code_client . ')',  // Display text
                ];
            });

        return response()->json($bls);
    }

    public function getReglementsByBl(Request $request)
    {
        // $reglements = Reglement::where('no_bl', $request->no_bl)->get();
        $reglements = Reglement::where('no_bl', $request->no_bl)
                       ->where('montant', '>', 0)
                       ->get();
        return response()->json(['reglements' => $reglements]);
    }

    
    
    public function getClientBls($clientCode)
{
    $bls = PaymentStatus::where('code_client', $clientCode)
    ->orderBy('id', 'desc') // Sort by 'no_bl' in ascending order
    ->get(['id', 'no_bl', 'montant_restant','code_client', 'name_client']);
    return response()->json($bls);
}  


    public function getPaymentStatus($codeClient)
    {
        $paymentStatus = PaymentStatus::where('code_client', $codeClient)->first();

        if ($paymentStatus) {
            return response()->json($paymentStatus);
        }

        return response()->json(null, 404);
    }


    public function destroy($id)
    {
        // Find the reglement
        $reglement = Reglement::findOrFail($id);

        // Find the related payment status
        $paymentStatus = PaymentStatus::where('no_bl', $reglement->no_bl)->first();

        // Reverse the payment status updates
        if ($paymentStatus) {
            $paymentStatus->update([
                'montant_payed' => $paymentStatus->montant_payed - $reglement->montant,
                'montant_restant' => $paymentStatus->montant_restant + $reglement->montant,
            ]);
        }

        // Delete the reglement
        $reglement->delete();

        // Redirect back with a success message
        return redirect()->route('reglements.index')->with('success', 'Règlement deleted successfully');
    }


    public function avance($no_bl, $code_client, $total_amount)
{
    $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();
    $clientName = Client::where('code_client', $code_client)->value('name');

    return view('sales.avance', [
        'no_bl' => $no_bl,
        'code_client' => $code_client,
        'client_name' => $clientName,
        'total_amount' => $total_amount,
    ]);
}

    public function editAvance($no_bl, $code_client, $total_amount, $oldPayedAmount)
{
    $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();
    $clientName = Client::where('code_client', $code_client)->value('name');
    $regelement = Reglement::where('no_bl', $no_bl)->first();

    return view('sales.editAvance', [
        'no_bl' => $no_bl,
        'code_client' => $code_client,
        'client_name' => $clientName,
        'total_amount' => $total_amount,
        'oldPayedAmount' => $oldPayedAmount,
        'paymentStatus' => $paymentStatus,
        'regelement' => $regelement
    ]);
}

    public function updateAvance(Request $request)
{
    try {
        $reglement = Reglement::where('no_bl', $request->no_bl)->first();
    
        if (!$reglement) {
            return response()->json([
                'success' => false,
                'message' => 'Reglement not found.',
            ], 404);
        }

        // Fetch the corresponding PaymentStatus entry
        $paymentStatus = PaymentStatus::where('no_bl', $reglement->no_bl)->first();

        if (!$paymentStatus) {
            return response()->json([
                'success' => false,
                'message' => 'No BL found for the given Reglement.',
            ], 404);
        }

        // Update PaymentStatus name_client if null
        if (is_null($paymentStatus->name_client)) {
            $client = Client::where('code_client', $request->code_client)->first();
            if ($client) {
                $paymentStatus->update(['name_client' => $client->name]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found for the given client code.',
                ], 404);
            }
        }

        // Update date_bl in PaymentStatus if null
        if (is_null($paymentStatus->date_bl)) {
            $sale = Sale::where('no_bl', $paymentStatus->no_bl)->first();
            if ($sale) {
                $paymentStatus->update(['date_bl' => $sale->date]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale not found for the given BL number.',
                ], 404);
            }
        }
        $difference = $request->montant - $reglement->montant;

        // Create the Reglement entry
        $reglement->update([
            'montant' => $request->montant,
            // 'date' => $request->has('destination') ? now() : $request->date,
            'type_pay' => $request->type_pay,
            'reference_chq' => $request->reference_chq,
            'date_chq' => $request->date_chq,
            'user-name' => Auth::user()->name,
        ]);


        // Check if the difference is negative and disallow it if not allowed
        // if ($difference < 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Le montant mis à jour ne peut pas être inférieur au montant initial.',
        //     ], 400);
        // }

        // $rest = $paymentStatus->montant_total - ($paymentStatus->montant_payed + $request->montant);
        // Prepare data to update PaymentStatus
        // $paymentStatusData = [
        //     'montant_payed' => $paymentStatus->montant_payed + $difference,
        //     'montant_restant' => $paymentStatus->montant_restant - $difference,
        // ];
        
        

        if ($request->has('chefAtelier') && $request->chefAtelier !== null) {
            $paymentStatusData['chef-atelier'] = $request->chefAtelier;
        }
        
        if ($request->has('destination') && $request->destination !== null) {
            $paymentStatusData['destination'] = $request->destination;
            $paymentStatusData['commerçant'] = Auth::user()->name;
            // $paymentStatusData['chef-atelier'] = $request->chefAtelier;
        }
        
        if ($request->has('date_echeance') && $request->date_echeance !== null) {
            $paymentStatusData['date-echeance'] = $request->date_echeance;
        }

        $paymentStatusData['user-name'] = Auth::user()->name;

        // Update the PaymentStatus
        $paymentStatus->update($paymentStatusData);

        // Success response
        return response()->json([
            'success' => true,
            'message' => 'Règlement enregistré avec succès.',
            'reglement' => $reglement,
            'updatedPaymentStatus' => $paymentStatus,
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 400);
    }
}


public function encaisserCheque(Request $request, $id)
{
    $request->validate([
        'date_encaissement' => 'required',
        'type_bank' => 'required|string|max:255',
    ]);

    $reglement = Reglement::findOrFail($id);
    $reglement->date_encaissement = $request->date_encaissement;
    $reglement->type_bank = $request->type_bank;
    // $reglement->status = 'encaissé'; // Optionally update status
    $reglement->save();

    return response()->json(['message' => 'Chèque encaissé avec succès!']);
}


}
