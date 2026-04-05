<?php

namespace App\Services;

use App\Models\Outbound;
use App\Models\Inventory;

class ShippingService
{

public function ship($id)
{
    try {

        $outbound = Outbound::with('details')->findOrFail($id);

        if ($outbound->status !== 'PACKED') {
            return response()->json([
                'success' => false,
                'message' => 'Outbound belum selesai packing'
            ], 400);
        }

        foreach ($outbound->details as $detail) {

            if ($detail->qty_packed <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => "SKU {$detail->sku} belum dipacking"
                ], 400);
            }

            if ($detail->qty_packed < $detail->qty_allocated) {
                return response()->json([
                    'success' => false,
                    'message' => "SKU {$detail->sku} belum fully packed"
                ], 400);
            }

            $shipQty = $detail->qty_packed;

            $outStations = Inventory::where('sku_id', $detail->sku)
                ->where('location_id', 'OUT-STATION')
                ->where('qty_stock', '>', 0)
                ->orderByRaw('expired_date IS NULL') 
                ->orderBy('expired_date','asc')
                ->orderBy('created_at','asc')
                ->get();

            if ($outStations->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock OUT-STATION tidak ditemukan untuk SKU {$detail->sku}"
                ], 400);
            }

            $need = $shipQty;

            foreach ($outStations as $stock) {

                if ($need <= 0) break;

                $available = $stock->qty_stock;

                if ($available <= 0) continue;

                $take = min($available, $need);

                $stock->qty_stock -= $take;
                $stock->qty_allocated -= $take;

                if ($stock->qty_allocated < 0) {
                    $stock->qty_allocated = 0;
                }

                $stock->save();

                $need -= $take;
            }

            if ($need > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock OUT-STATION tidak cukup untuk SKU {$detail->sku}"
                ], 400);
            }

            $detail->status = 'SHIPPED';
            $detail->save();
        }

        $outbound->status = 'SHIPPED';
        $outbound->save();

        return response()->json([
            'success' => true,
            'message' => 'Outbound berhasil di shipping'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}

}