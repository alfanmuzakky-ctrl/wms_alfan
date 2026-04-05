<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
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