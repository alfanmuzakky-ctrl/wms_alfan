<?php

/* Imports */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SkuController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\InboundController;


/* Dashboard */
Route::get('/', [DashboardController::class,'index']);
Route::get('/dashboard', [DashboardController::class,'index']);


/* SKU */
Route::resource('skus', SkuController::class);


/* Supplier */
Route::resource('suppliers', SupplierController::class);


/* Location */
Route::resource('locations', LocationController::class);


/* Customer */
Route::resource('customers', CustomerController::class);


/* Inventory */
Route::get('/inventories', [InventoryController::class,'index']);


/* Inbound */
Route::resource('inbounds', InboundController::class);

Route::post('/inbounds/{id}/add-sku', [InboundController::class,'addSku']);
Route::post('/inbounds/{id}/receive', [InboundController::class,'received']);
Route::post('/inbounds/{id}/close', [InboundController::class,'close']);

Route::get('/putaway', [InboundController::class,'putawayIndex']);
Route::post('/putaway/process', [InboundController::class,'putawayProcess']);




/* Print */
Route::get('/print/sku/{detail}', [PrintController::class,'printSku'])->name('print.sku');
Route::get('/print/inbound/{id}', [PrintController::class,'printInbound'])->name('print.inbound');