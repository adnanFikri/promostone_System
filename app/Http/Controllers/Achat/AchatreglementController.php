<?php

namespace App\Http\Controllers\Achat;

use App\Models\Achat\Achat;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\Achat\AchatStatus;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Achat\Achatreglement;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AchatreglementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('permission:view achat reglements')->only(['index']);
        $this->middleware('permission:create achat reglements')->only(['create']);
        $this->middleware('permission:store achat reglements')->only(['store']);
        $this->middleware('permission:delete achat reglements')->only(['destroy']);
        // $this->middleware('permission:search reglements')->only(['search']);
        // $this->middleware('permission:view payment status by client')->only(['getPaymentStatus']);
        // $this->middleware('permission:view reglements by bl')->only(['getReglementsByBl']);
        // $this->middleware('permission:view client bls')->only(['getClientBls']);
        // $this->middleware('permission:view all bls')->only(['getAllBLs']);
        $this->middleware('permission:create achat avance')->only(['avance']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Achatreglement::select(
                'achatreglements.id',
                'achatreglements.no_bl',
                'achatreglements.id_fournisseur',
                'fournisseurs.raison as name_fournisseur',
                'achatreglements.montant',
                'achatreglements.date',
                'achatreglements.type_pay',
                'achatreglements.reference_chq', // Add reference_chq
                'achatreglements.date_chq' 
            )
            ->leftJoin('fournisseurs', 'fournisseurs.id', '=', 'achatreglements.id_fournisseur') // LEFT JOIN with clients table
            ->get();

            return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                if (auth()->user()->can('delete achat reglements')) {
                    $btn = '
                            <a style="float:right;" href="' . route('achat.reglements.destroy', $row->id) . '" class="hover:color-red-400 delete btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();"><svg class="w-6 h-6 text-red-400  hover:text-red-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        ';
                }
                if ($row->type_pay === 'Chèque') {
                    $btn .= '<button style="float:left;" class="btn btn-primary btn-sm view-cheque float-left" data-id="' . $row->id . '" data-ref="' . $row->reference_chq . '" data-date="' . $row->date_chq . '">
                        <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>

                    </button>';
                }
            
                if (auth()->user()->can('delete achat reglements')) {
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('reglements.destroy', $row->id) . '" method="POST" style="display: none;" >' . csrf_field() . method_field('DELETE') . '</form>';
                }

                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
        }

        return view('achat.reglements.index');
    }

    // public function create()
    // {
    //     $clients = client::all(); // Fetch all clients
    //     return view('reglements.create', compact('clients'));
    // }

    public function store(Request $request)
    {
        try {
            $AchatStatus = AchatStatus::where('no_bl', $request->no_bl)->first();

            if (!$AchatStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'No BL found for the given client and BL number.',
                ], 404);
            }
            // Create the Reglement entry
            Log::info("ahcat status : " . $AchatStatus);
            $reglement = Achatreglement::create([
                'no_bl' => $request->no_bl,
                'id_fournisseur' => $request->id_fournisseur,
                'nom_fournisseur' => $AchatStatus->name_fournisseur,
                'montant' => $request->montant,
                'date' =>  $request->has('commerçant') ? now() : $request->date, 
                'type_pay' => $request->type_pay,
                'reference_chq' => $request->reference_chq, 
                'date_chq' => $request->date_chq,           
            ]);

            Log::info("reglements : " .$reglement);


            $rest = $AchatStatus->montant_total - ($AchatStatus->montant_payed + $request->montant);
            // Prepare data to update PaymentStatus
            $AchatStatusData = [
                'montant_payed' => $AchatStatus->montant_payed + $request->montant,
                'montant_restant' => $rest > 0 ? $rest : 0,
                // 'commerçant' => 'ahmed',
            ];

            $AchatStatusData['user-name'] = Auth::user()->name;

            // Update the PaymentStatus
            $AchatStatus->update($AchatStatusData);

            // Success response
            return response()->json([
                'success' => true,
                'message' => 'Règlement enregistré avec succès.',
                'reglement' => $reglement,
                'updatedPaymentStatus' => $AchatStatus,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 400);
        }
    }


    public function getAllBLs(Request $request)
    {
        // try {
            // Fetch BL data (replace with your actual query)
            $search = $request->input('q');
            $bls = AchatStatus::orderBy('id', 'asc')
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
        $bls = AchatStatus::select('no_bl', 'name_client', 'code_client')
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
        $reglements = AchatReglement::where('no_bl', $request->no_bl)->get();
        return response()->json(['reglements' => $reglements]);
    }

    
    
    public function getClientBls($clientCode)
{
    $bls = AchatStatus::where('code_client', $clientCode)
    ->orderBy('id', 'desc') // Sort by 'no_bl' in ascending order
    ->get(['id', 'no_bl', 'montant_restant','code_client', 'name_client']);
    return response()->json($bls);
}  


    public function getPaymentStatus($codeClient)
    {
        $paymentStatus = AchatStatus::where('code_client', $codeClient)->first();

        if ($paymentStatus) {
            return response()->json($paymentStatus);
        }

        return response()->json(null, 404);
    }


    public function destroy($id)
    {
        // Find the reglement
        $reglement = AchatReglement::findOrFail($id);

        // Find the related payment status
        $paymentStatus = AchatStatus::where('no_bl', $reglement->no_bl)->first();

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
    $paymentStatus = AchatStatus::where('no_bl', $no_bl)->first();
    $clientName = Fournisseur::where('code_client', $code_client)->value('name');

    return view('sales.avance', [
        'no_bl' => $no_bl,
        'code_client' => $code_client,
        'client_name' => $clientName,
        'total_amount' => $total_amount,
    ]);
}
}
