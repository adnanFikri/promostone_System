<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\PaymentStatusController;

Route::get('/', function () {
    return redirect()->route("paymentStatus.index");
})->middleware(['auth', 'verified'])->name('paymentStatus.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/upload', [SalesController::class, 'showUploadForm'])->name('sales.upload');
Route::post('/import', [SalesController::class, 'import'])->name('sales.import');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/get-by-bl', [SalesController::class, 'getByBl']);


// -=-=-= 00 CLIENT ROUTES 00 -=-=-=-
Route::resource('clients', ClientController::class); // This will handle the CRUD operations
Route::get('sales/search', [ClientController::class, 'search'])->name('sales.search');
Route::get('/clients/upload', [ClientController::class, 'uploadForm'])->name('clients.upload');
Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');

// -=-=-= 00 PaymentController ROUTES 00 -=-=-=-
Route::get('/payment-status', [PaymentStatusController::class, 'index'])->name('paymentStatus.index');
Route::get('/populate-payment-status', [PaymentStatusController::class, 'populatePaymentStatus']);
Route::get('/populate-check', [PaymentStatusController::class, 'getSalesWithNoPaymentStatus']);



// -=-=-= 00 Regelements ROUTES 00 -=-=-=-
Route::get('/reglements', [ReglementController::class, 'index'])->name('reglements.index');
Route::get('/reglements/create', [ReglementController::class, 'create'])->name('reglements.create');
Route::post('/reglements/store', [ReglementController::class, 'store'])->name('reglements.store');
Route::get('/reglements/search', [ReglementController::class, 'search'])->name('reglements.search');
Route::get('/payment-status/{code_client}', [ReglementController::class, 'getPaymentStatus']);
Route::delete('/reglements/{id}', [ReglementController::class, 'destroy'])->name('reglements.destroy');
Route::get('/reglements/get-by-bl', [ReglementController::class, 'getReglementsByBl']);

// Route::get('/client-bls/{codeClient}', [ReglementController::class, 'getBLs']);

Route::get('/client-bls/{clientCode}', [ReglementController::class, 'getClientBls'])->name('client.bls');
Route::get('/get-all-bls', [ReglementController::class, 'getAllBLs']);
