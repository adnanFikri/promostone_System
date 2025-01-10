<?php

use App\Http\Controllers\Achat\AchatStatusController;
use App\Models\Sale;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BonCoupeController;
use App\Http\Controllers\BonSortieController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\Achat\AchatController;
use App\Http\Controllers\Achat\AchatreglementController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/welcome', function () {
    return view('welcome'); // Create the `welcome.blade.php` view in the `resources/views` directory
})->middleware(['auth', 'verified'])->name('welcome.page');


// Route::get('/payment-status', [PaymentStatusController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('paymentStatus.index');

// Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';


Route::resource('roles', RoleController::class);
Route::post('/modifier-permissions/{id}', [RoleController::class, 'updatePermissions'])->name('addPermissions');
Route::resource('permissions', PermissionController::class);

// -=-=-= 00 SALES ROUTES 00 -=-=-=-
Route::get('/upload', [SalesController::class, 'showUploadForm'])->name('sales.upload');
Route::post('/import', [SalesController::class, 'import'])->name('sales.import');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/get-by-bl', [SalesController::class, 'getByBl'])->name("sales.get-by-bl");
Route::get('/sales/create', [SalesController::class, 'create'])->name("sales.create");
Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
Route::get('/bon-livraison/{no_bl}', [SalesController::class, 'showBonLivraison'])->name('bon_livraison');
Route::get('/bon-coup/{no_bl}', [SalesController::class, 'showBonCoup'])->name('bon_coup');
Route::get('/bon-sortie/{no_bl}', [SalesController::class, 'showBonSortie'])->name('bon_sortie');


// -=-=-= 00 CLIENT ROUTES 00 -=-=-=-
Route::get('sales/search', [ClientController::class, 'search'])->name('sales.search');
Route::get('/clients/next-code', [ClientController::class, 'getNextCode']);
Route::get('/clients/upload', [ClientController::class, 'uploadForm'])->name('clients.upload');
Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
Route::get('/filter-bls', [ClientController::class, 'filterBLs'])->name('filter.bls');
Route::get('/clientsByCode/{code_client}', [ClientController::class, 'getClientData'])->name('clients.ByCode');
Route::put('/clients/{client}/change-type/{type}', [ClientController::class, 'changeType'])->name('clients.changeType');

Route::resource('clients', ClientController::class); // This will handle the CRUD operations


// -=-=-= 00 PaymentController ROUTES 00 -=-=-=-
Route::get('/payment-status', [PaymentStatusController::class, 'index'])->name('paymentStatus.index');
Route::get('/populate-payment-status', [PaymentStatusController::class, 'populatePaymentStatus']);
Route::get('/populate-check', [PaymentStatusController::class, 'getSalesWithNoPaymentStatus']);
Route::get('/getByClientType', [PaymentStatusController::class, 'getByClientType'])->name('getByClientType');



// -=-=-= 00 Regelements ROUTES 00 -=-=-=-
Route::get('/reglements', [ReglementController::class, 'index'])->name('reglements.index');
Route::get('/reglements/create', [ReglementController::class, 'create'])->name('reglements.create');
Route::post('/reglements/store', [ReglementController::class, 'store'])->name('reglements.store');
Route::delete('/reglements/{id}', [ReglementController::class, 'destroy'])->name('reglements.destroy');
Route::get('/reglements/search', [ReglementController::class, 'search'])->name('reglements.search');
Route::get('/payment-status/{code_client}', [ReglementController::class, 'getPaymentStatus']);
Route::get('/reglements/get-by-bl', [ReglementController::class, 'getReglementsByBl'])->name("reglements.get-by-bl");
Route::get('/client-bls/{clientCode}', [ReglementController::class, 'getClientBls'])->name('client.bls');
Route::get('/get-all-bls', [ReglementController::class, 'getAllBLs'])->name('reglements.get-all-bls');

Route::get('/avance/create/{no_bl}/{code_client}/{total_amount}', [ReglementController::class, 'avance'])->name('avance.create');



// -=-=-= 00 Products ROUTES 00 -=-=-=-
Route::resource('products', ProductController::class);

// -=-=-= 00 Users ROUTES 00 -=-=-=-
Route::resource('users', UserController::class);



// -=-=-= 00 BON DE LIVRAISON ROUTES 00 -=-=-=-
Route::get('/listBonLivraison', [BonLivraisonController::class, 'index'])->name('listBonLivraison.index'); // DataTable
Route::post('/bonLivraison/{id}/update-livree', [BonLivraisonController::class, 'updateLivree']);


// -=-=-= 00 BON DE COUPE ROUTES 00 -=-=-=-
Route::get('/listBonCoupe', [BonCoupeController::class, 'index'])->name('listBonCoupe.index'); // DataTable
Route::post('/bonCoupe/{id}/update-coupe', [BonCoupeController::class, 'updateCoupe']);
Route::post('/bonCoupe/increment-print/{id}', [BonCoupeController::class, 'incrementPrintNbr'])->name('bon_coupe.increment_print');


// -=-=-= 00 BON DE SORTIE ROUTES 00 -=-=-=-
Route::get('/listBonSortie', [BonSortieController::class, 'index'])->name('listBonSortie.index'); // DataTable
Route::post('/bonSortie/{id}/update-sortie', [BonSortieController::class, 'updateSortie']);
Route::post('/bonSortie/increment-print/{id}', [BonSortieController::class, 'incrementPrintNbr'])->name('bon_coupe.increment_print');




// =============================================================================================================================
// =============================================================================================================================
// =============================================================================================================================




// 00000000000000000000 FOURNISSEUR 000000000000000000000
Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
Route::post('/fournisseurs', [FournisseurController::class, 'store'])->name('fournisseurs.store');
Route::get('/fournisseurs/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
Route::put('/fournisseurs/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
Route::delete('/fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
Route::get('/fournisseurs/search', [FournisseurController::class, 'search'])->name('fournisseurs.search');


// 00000000000000000000 ACHAT 000000000000000000000
Route::get('/achat/create', [AchatController::class, 'create'])->name('achat.create');
Route::get('/achat/get-by-bl', [AchatController::class, 'getByBl'])->name("achat.get-by-bl");
Route::get('/achat/create', [AchatController::class, 'create'])->name("achat.create");
Route::post('/achat/store', [AchatController::class, 'store'])->name('achat.store');
Route::get('/achat/get-by-bl', [AchatController::class, 'getByBl'])->name("achat.get-by-bl");



// 0000000000000 00 ACHAT STATUS Controller ROUTES 00 0000000000000
Route::get('/achat-status', [AchatStatusController::class, 'index'])->name('achatStatus.index');
Route::get('/getByClientType', [AchatStatusController::class, 'getByClientType'])->name('getByClientType');



// -=-=-= 00 ACHAT Regelements ROUTES 00 -=-=-=-
Route::get('achat/reglements', [AchatreglementController::class, 'index'])->name('achat.reglements.index');
Route::get('achat/reglements/create', [AchatreglementController::class, 'create'])->name('achat.reglements.create');
Route::post('achat/reglements/store', [AchatreglementController::class, 'store'])->name('achat.reglements.store');
Route::delete('achat/reglements/{id}', [AchatreglementController::class, 'destroy'])->name('achat.reglements.destroy');
Route::get('achat/reglements/search', [AchatreglementController::class, 'search'])->name('achat.reglements.search');
Route::get('achat/payment-status/{code_client}', [AchatreglementController::class, 'getPaymentStatus']);
Route::get('achat/reglements/get-by-bl', [AchatreglementController::class, 'getReglementsByBl'])->name("achat.reglements.get-by-bl");
Route::get('achat/client-bls/{clientCode}', [AchatreglementController::class, 'getClientBls'])->name('achat.client.bls');
Route::get('achat/get-all-bls', [AchatreglementController::class, 'getAllBLs'])->name('achat.reglements.get-all-bls');

Route::get('achat/avance/create/{no_bl}/{code_client}/{total_amount}', [AchatreglementController::class, 'avance'])->name('achat.avance.create');





Route::view('/logn','auth.loginn');

















































































// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000

                                        // AFTER ADD PERMISSIONS 

// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
// 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000

// -=-=-= 00 SALES ROUTES  00 -=-=-=-
// Route::middleware('permission:view sales')->get('/sales', [SalesController::class, 'index'])->name('sales.index');
// Route::middleware('permission:create sales')->get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
// Route::middleware('permission:store sales')->post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
// Route::middleware('permission:view bon livraison')->get('/bon-livraison/{no_bl}', [SalesController::class, 'showBonLivraison'])->name('bon_livraison');
// Route::middleware('permission:view bon coup')->get('/bon-coup/{no_bl}', [SalesController::class, 'showBonCoup'])->name('bon_coup');
// Route::middleware('permission:view sales upload')->get('/upload', [SalesController::class, 'showUploadForm'])->name('sales.upload');
// Route::middleware('permission:import sales')->post('/import', [SalesController::class, 'import'])->name('sales.import');
// Route::middleware('permission:view sales by bl')->get('/sales/get-by-bl', [SalesController::class, 'getByBl'])->name('sales.get-by-bl');
// // Route::middleware('permission:get sales with clients')->get('/sales/get-sales-with-clients', [SalesController::class, 'getSalesWithClients']);


// // -=-=-= 00 Client ROUTES  00 -=-=-=-
// Route::middleware('permission:view clients')->get('/clients', [ClientController::class, 'index'])->name('clients.index');
// Route::middleware('permission:create clients')->get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
// Route::middleware('permission:store clients')->post('/clients', [ClientController::class, 'store'])->name('clients.store');
// Route::middleware('permission:view client details')->get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
// Route::middleware('permission:edit clients')->get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
// Route::middleware('permission:update clients')->put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
// Route::middleware('permission:delete clients')->delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
// Route::middleware('permission:search sales')->get('sales/search', [ClientController::class, 'search'])->name('sales.search');
// Route::middleware('permission:generate client code')->get('/clients/next-code', [ClientController::class, 'getNextCode']);
// Route::middleware('permission:view client upload')->get('/clients/upload', [ClientController::class, 'uploadForm'])->name('clients.upload');
// Route::middleware('permission:import clients')->post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
// // Route::middleware('permission:filter payment statuses')->get('/clients/filter-payment-statuses', [ClientController::class, 'filterPaymentStatuses']);
// Route::middleware('permission:view client data by code')->get('/clientsByCode/{code_client}', [ClientController::class, 'getClientData'])->name('clients.ByCode');
// Route::middleware('permission:change client type')->put('/clients/{client}/change-type/{type}', [ClientController::class, 'changeType'])->name('clients.changeType');


// // -=-=-= 00 PaymentStatus ROUTES  00 -=-=-=-
// Route::middleware('permission:view payment statuses')->get('/payment-status', [PaymentStatusController::class, 'index'])->name('paymentStatus.index');
// Route::middleware('permission:populate payment statuses')->get('/populate-payment-status', [PaymentStatusController::class, 'populatePaymentStatus']);
// Route::middleware('permission:view sales with no payment status')->get('/populate-check', [PaymentStatusController::class, 'getSalesWithNoPaymentStatus']);
// Route::middleware('permission:filter payment statuses by client type')->get('/getByClientType', [PaymentStatusController::class, 'getByClientType'])->name('getByClientType');


// // -=-=-= 00 Reglements ROUTES  00 -=-=-=-
// Route::middleware('permission:view reglements')->get('/reglements', [ReglementController::class, 'index'])->name('reglements.index');
// Route::middleware('permission:create reglements')->get('/reglements/create', [ReglementController::class, 'create'])->name('reglements.create');
// Route::middleware('permission:store reglements')->post('/reglements/store', [ReglementController::class, 'store'])->name('reglements.store');
// Route::middleware('permission:delete reglements')->delete('/reglements/{id}', [ReglementController::class, 'destroy'])->name('reglements.destroy');
// Route::middleware('permission:search reglements')->get('/reglements/search', [ReglementController::class, 'search'])->name('reglements.search');
// Route::middleware('permission:view payment status by client')->get('/payment-status/{code_client}', [ReglementController::class, 'getPaymentStatus']);
// Route::middleware('permission:view reglements by bl')->get('/reglements/get-by-bl', [ReglementController::class, 'getReglementsByBl'])->name('reglements.get-by-bl');
// Route::middleware('permission:view client bls')->get('/client-bls/{clientCode}', [ReglementController::class, 'getClientBls'])->name('client.bls');
// Route::middleware('permission:view all bls')->get('/get-all-bls', [ReglementController::class, 'getAllBLs'])->name('reglements.get-all-bls');
// Route::middleware('permission:create avance')->get('/avance/create/{no_bl}/{code_client}/{total_amount}', [ReglementController::class, 'avance'])->name('avance.create');



// // -=-=-= 00 PRODUCTS ROUTES  00 -=-=-=-
// Route::middleware('permission:view products')->get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::middleware('permission:create products')->get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::middleware('permission:store products')->post('/products', [ProductController::class, 'store'])->name('products.store');
// Route::middleware('permission:view product details')->get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// Route::middleware('permission:edit products')->get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::middleware('permission:update products')->put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
// Route::middleware('permission:delete products')->delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
// Route::middleware('permission:generate product code')->post('/products/generate-code', [ProductController::class, 'generateProductCode']);



// // -=-=-= 00 PROFILE ROUTES  00 -=-=-=-
// Route::middleware('permission:edit profile')->get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// Route::middleware('permission:update profile')->patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Route::middleware('permission:delete profile')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
