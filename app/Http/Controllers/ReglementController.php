<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ReglementController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reglement::select(
                'reglements.id',
                'reglements.code_client',
                'clients.name as name_client',
                'reglements.montant',
                'reglements.date',
                'reglements.type_pay'
            )
            ->leftJoin('clients', 'clients.code_client', '=', 'reglements.code_client') // LEFT JOIN with clients table
            ->get();

            return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                $btn = '<a href="' . route('reglements.destroy', $row->id) . '" class="delete btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();"><svg class="w-6 h-6 text-gray-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
</svg>
</a>';
                $btn .= ' <form id="delete-form-' . $row->id . '" action="' . route('reglements.destroy', $row->id) . '" method="POST" style="display: none;" >' . csrf_field() . method_field('DELETE') . '</form>';
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

        // Validate
        $request->validate([
            'code_client' => 'required|exists:clients,code_client',
            'montant' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type_pay' => 'nullable|string|max:255',
        ]);

        // Create new reglement
        $reglement = Reglement::create($request->all());

        Log::info('Reglement created', ['reglement' => $reglement]);

        // Update payment status
        $paymentStatus = PaymentStatus::where('code_client', $request->code_client)->first();

        if ($paymentStatus) {
            $numberPaid = Reglement::where('code_client', $request->code_client)->count();
            $payedTotal = Reglement::where('code_client', $request->code_client)->sum('montant');
            $remainingBalance = $paymentStatus->montant_total - $payedTotal;

            $paymentStatus->update([
                'number_paid' => $numberPaid,
                'payed_total' => $payedTotal,
                'remaining_balance' => $remainingBalance,
            ]);

        }

        $updatedPaymentStatus = PaymentStatus::where('code_client', $request->code_client)->first();

        // Set success message in session
        session()->flash('success', 'Règlement enregistré avec succès.');
        
        return response()->json([
            'success' => true,
            'message' => 'Règlement enregistré avec succès.',
            'updatedPaymentStatus' => $updatedPaymentStatus,
        ]);
    } catch (\Exception $e) {

        // In case of error, set error message in session
        session()->flash('error', 'An error occurred while saving the règlement.');
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while saving the règlement.',
        ], 500);
    }
}


    


    public function search(Request $request)
    {
        $search = $request->input('q');
        $clients = Client::where('name', 'like', "%{$search}%")
            ->orWhere('code_client', 'like', "%{$search}%")
            ->get();

        return response()->json($clients);
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
        $reglement = Reglement::findOrFail($id);

        // Delete the reglement
        $reglement->delete();

        // Redirect back with a success message
        return redirect()->route('reglements.index')->with('success', 'Règlement deleted successfully');
    }

}
