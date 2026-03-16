
<?php

/* Imports */
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
/* Global / Dashboard */
Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

/* SKU Management */
Route::resource('skus', SkuController::class);
Route::get('/skus', [SkuController::class, 'index']);
Route::get('/skus/create', [SkuController::class, 'create']);
Route::post('/skus/store', [SkuController::class, 'store']);
Route::get('/skus/{id}', [SkuController::class, 'show'])->where('id', '.*');
Route::put('/skus/{id}', [SkuController::class, 'update'])->where('id', '.*');

/* Supplier Management */
Route::resource('suppliers', SupplierController::class);
Route::get('/supplier-data', [SupplierController::class, 'index']);

/* Location Management */
Route::resource('locations', LocationController::class);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/create', [LocationController::class, 'create']);
Route::post('/locations/store', [LocationController::class, 'store']);
Route::get('/locations/{id}', [LocationController::class, 'show'])->where('id', '.*');
Route::put('/locations/{id}', [LocationController::class, 'update'])->where('id', '.*');

/* Inbound Management */

Route::resource('inbounds', InboundController::class);
Route::get('/inbounds', [InboundController::class, 'index']);
Route::get('/inbounds/create', [InboundController::class, 'create']);
Route::post('/inbounds/store', [InboundController::class, 'store']);
Route::get('/inbounds/{id}', [InboundController::class, 'show']);
Route::post('/inbounds/{id}/add-sku', [InboundController::class, 'addSku']);
Route::get('/putaway', [InboundController::class, 'putawayIndex']);
Route::post('/putaway/process', [InboundController::class, 'putawayProcess']);
Route::post('/inbounds/{id}/receive', [InboundController::class, 'received']);
Route::post('/inbounds/{id}/close', [InboundController::class, 'close']);
Route::post('/inbounds/receive', [InboundController::class, 'received']);

/* Inventory Management */
Route::get('/inventories', [InventoryController::class, 'index']);

/* Outbound Management */
Route::get('/outbounds',[OutboundController::class,'index']);

Route::get('/outbounds/create',[OutboundController::class,'create']);

Route::post('/outbounds',[OutboundController::class,'store']);

Route::get('/outbounds/{id}',[OutboundController::class,'show']);

Route::post('/outbounds/{id}/add-sku',[OutboundController::class,'addSku']);

Route::post('/outbounds/{id}/allocate',[OutboundController::class,'allocate']);

Route::post('/outbounds/{id}/picking',[OutboundController::class,'picking']);

Route::post('/outbounds/{id}/packing',[OutboundController::class,'packing']);

Route::post('/outbounds/{id}/ship',[OutboundController::class,'ship']);
Route::get('/outbounds',[OutboundController::class,'index']);

Route::get('/outbounds/{id}',[OutboundController::class,'show']);
Route::post('/outbounds/{id}/allocate',[OutboundController::class,'allocate']);

Route::post('/outbounds/{id}/picking',[OutboundController::class,'picking']);

Route::post('/outbounds/{id}/packing',[OutboundController::class,'packing']);

Route::post('/outbounds/{id}/ship',[OutboundController::class,'ship']);

/* Customer Management */
Route::resource('customers', CustomerController::class);
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show'])->where('id', '.*');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->where('id', '.*');

/* Print & Reporting */
Route::get('/print/sku/{detail}', [PrintController::class, 'printSku'])->name('print.sku');
Route::get('/print/inbound/{id}', [PrintController::class, 'printInbound'])->name('print.inbound');
Route::get('/print/picking/{id}', [PrintController::class,'printPicking']);

Route::prefix('outbounds')->group(function(){

    Route::get('/{id}',[OutboundController::class,'show']);

    Route::post('/add-sku',[OutboundController::class,'addSku']);

    Route::post('/{id}/allocate',[OutboundController::class,'allocate']);

    Route::post('/{id}/picking',[OutboundController::class,'picking']);

    Route::post('/{id}/packing',[OutboundController::class,'packing']);

    Route::post('/{id}/ship',[OutboundController::class,'ship']);

});
Route::get('/outbounds', [OutboundController::class, 'index']);

Route::get('/outbounds/create',[OutboundController::class,'create']);
Route::post('/outbounds',[OutboundController::class,'store']);

use App\Http\Controllers\PackingCheckController;
/* Packing & Check */
Route::get('/packing-check',[PackingCheckController::class,'index']);
Route::resource('packing', CustomerController::class);

Route::post('/packing-check/load-order',[PackingCheckController::class,'loadOrder']);

Route::post('/packing-check/scan-sku',[PackingCheckController::class,'scanSku']);

Route::post('/packing-check/confirm-pack',[PackingCheckController::class,'confirmPack']);