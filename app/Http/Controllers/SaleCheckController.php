<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Reglement;
use App\Models\SaleCheck;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCheckController extends Controller
{
    public function showSaleCheck($id)
{
    // Retrieve the SaleCheck record
    $saleCheck = SaleCheck::where('id', $id)->first();

    if (!$saleCheck) {
        return redirect()->back()->with('error', 'Sale Check not found.');
    }

    // Extract data from the SaleCheck record
    $salesData = $saleCheck->sales_data; // Serialized sales data
    $reglementsData = $saleCheck->reglements_data; // Serialized reglements data
    $paymentStatusData = $saleCheck->payment_status_data; // Serialized payment status data

    // Recreate Eloquent models and collections
    $sales = collect($salesData)->map(function ($sale) {
        return new Sale($sale); // Map each sale to a Sale model instance
    });

    $reglements = collect($reglementsData)->map(function ($reglement) {
        return new Reglement($reglement); // Map each reglement to a Reglement model instance
    });

    $paymentStatus = new PaymentStatus($paymentStatusData); // Create a PaymentStatus model instance

    // Retrieve the client data
    $client = Client::where('code_client', $saleCheck->code_client)->first();

    // Group sales data by product name
    $groupedSales = $sales->groupBy('produit');

    // Prepare data for the view
    $data = [
        'paymentStatus' => $paymentStatus,
        'groupedSales' => $groupedSales,
        'reglements' => $reglements,
        'client' => $client,
    ];

    // dd($data); // Debugging: Ensure the structure matches your expectations

    return view('sales.bonL', $data);
}

public function filterSaleChecks(Request $request) {
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

    $filteredSaleChecks = SaleCheck::whereBetween('created_at', [$startDate, $endDate])->get();

    return view('your-modal-view', compact('filteredSaleChecks'));
}


}
