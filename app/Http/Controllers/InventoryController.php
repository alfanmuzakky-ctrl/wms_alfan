<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
{
    $inventories = Inventory::where('qty_stock', '>', 0)
        ->orderBy('sku_id')
        ->orderBy('location_id')
        ->get();

    return view('inventories.index', compact('inventories'));
}
}