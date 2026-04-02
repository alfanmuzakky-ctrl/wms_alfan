
<?php

/* Imports */
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkuController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\PackingCheckController;


/* AUTH (PUBLIC) */

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return app(AuthController::class)->showLogin();
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


/* PROTECTED (WAJIB LOGIN) */

Route::middleware(['auth'])->group(function () {

    /* DASHBOARD */
    Route::get('/', function () {
    return view('layouts');
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);


    /* MASTER DATA */
    Route::resource('skus', SkuController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('customers', CustomerController::class);


    /* INVENTORY */
    Route::get('/inventories', [InventoryController::class, 'index']);


    /* INBOUND */
    Route::prefix('inbounds')->group(function () {

        Route::resource('inbounds', InboundController::class);
Route::get('/', [InboundController::class, 'index']);
Route::get('/create', [InboundController::class, 'create']);
Route::post('/', [InboundController::class, 'store']);
Route::get('/{id}', [InboundController::class, 'show']);
Route::post('/{id}/add-sku', [InboundController::class, 'addSku']);
Route::get('/putaway', [InboundController::class, 'putawayIndex']);
Route::post('/putaway/process', [InboundController::class, 'putawayProcess']);
Route::post('/{id}/receive', [InboundController::class, 'received']);
Route::post('/{id}/close', [InboundController::class, 'close']);
Route::post('/receive', [InboundController::class, 'received']);
    });

    Route::get('/putaway', [InboundController::class, 'putawayIndex']);
    Route::post('/putaway/process', [InboundController::class, 'putawayProcess']);


    /* OUTBOUND */
    Route::prefix('outbounds')->group(function () {

        Route::get('/', [OutboundController::class, 'index']);
        Route::get('/create', [OutboundController::class, 'create']);
        Route::post('/', [OutboundController::class, 'store']);
        Route::get('/{id}', [OutboundController::class, 'show']);

        Route::post('/{id}/add-sku', [OutboundController::class, 'addSku']);
        Route::post('/{id}/allocate', [OutboundController::class, 'allocate']);
        Route::post('/{id}/picking', [OutboundController::class, 'picking']);
        Route::post('/{id}/packing', [OutboundController::class, 'packing']);
        Route::post('/{id}/ship', [OutboundController::class, 'ship']);
    });


    /* PACKING CHECK */
    Route::get('/packing-check', [PackingCheckController::class, 'index']);
    Route::post('/packing-check/load-order', [PackingCheckController::class, 'loadOrder']);
    Route::post('/packing-check/scan-sku', [PackingCheckController::class, 'scanSku']);
    Route::post('/packing-check/confirm-pack', [PackingCheckController::class, 'confirmPack']);


    /* PRINT */
    Route::get('/print/sku/{detail}', [PrintController::class, 'printSku'])->name('print.sku');
    Route::get('/print/inbound/{id}', [PrintController::class, 'printInbound'])->name('print.inbound');
    Route::get('/print/picking/{id}', [PrintController::class, 'printPicking']);


    /* MOBILE */
    Route::get('/wms', function () {
        return view('mobile.layouts');
    });

});