<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryLocation;

class InventoryService
{
    public function addStock($skuId, $locationId, $qty, $batch = null, $expired = null)
    {
        // Update summary inventory
        $inventory = Inventory::firstOrCreate(
            ['sku_id' => $skuId],
            ['qty_stock' => 0, 'qty_allocated' => 0]
        );

        $inventory->qty_stock += $qty;
        $inventory->save();

        // Update location stock
        $locationStock = InventoryLocation::firstOrCreate(
            [
                'sku_id' => $skuId,
                'location_id' => $locationId,
                'batch_number' => $batch,
                'expired_date' => $expired
            ],
            ['qty' => 0]
        );

        $locationStock->qty += $qty;
        $locationStock->save();
    }
}