<?php

namespace App\Services;

use App\Models\Outbound;
use App\Models\Inventory;

class PickingService
{

public function picking($id)
{
    $outbound = Outbound::with('details.orders')->findOrFail($id);

    if ($outbound->status === 'PICKED') {
        return response()->json([
            'success' => false,
            'message' => 'Outbound sudah selesai picking'
        ], 400);
    }

    foreach ($outbound->details as $detail) {

        foreach ($detail->orders as $order) {

            if ($order->qty_picked >= $order->qty_allocated) {
                continue;
            }

            $remainingQty = $order->qty_allocated - $order->qty_picked;

            if ($remainingQty <= 0) {
                continue;
            }

            $pickQty = $remainingQty;

            $order->qty_picked += $pickQty;

            if ($order->qty_picked >= $order->qty_allocated) {
                $order->status = 'PICKED';
            } else {
                $order->status = 'PARTIAL';
            }

            $order->save();

            $binStock = $order->inventory;

            if ($binStock) {

                $binStock->qty_allocated -= $pickQty;
                $binStock->qty_stock -= $pickQty;

                if ($binStock->qty_allocated < 0) {
                    $binStock->qty_allocated = 0;
                }

                if ($binStock->qty_stock < 0) {
                    $binStock->qty_stock = 0;
                }

                $binStock->save();
            }

            $outStation = Inventory::where('sku_id', $detail->sku)
                ->where('location_id', 'OUT-STATION')
                ->where('batch_number', $order->batch_number)
                ->first();

            if ($outStation) {

                $outStation->qty_stock += $pickQty;
                $outStation->qty_allocated = $outStation->qty_stock;
                $outStation->save();

            } else {

                Inventory::create([
                    'sku_id'        => $detail->sku,
                    'location_id'   => 'OUT-STATION',
                    'batch_number'  => $order->batch_number,
                    'expired_date'  => $order->expired_date,
                    'inbound_detail_id' => $source->inbound_detail_id ?? null,
                    'qty_stock'     => $pickQty,
                    'qty_allocated' => $pickQty
                ]);
            }

            $detail->qty_picked += $pickQty;
        }

        if ($detail->qty_picked >= $detail->qty_allocated) {
            $detail->status = 'PICKED';
        } else {
            $detail->status = 'PARTIAL';
        }

        $detail->save();
    }

    $allPicked = $outbound->details->every(function ($detail) {
        return $detail->status === 'PICKED';
    });

    $outbound->status = $allPicked ? 'PICKED' : 'PARTIAL';
    $outbound->save();

    return response()->json([
        'success' => true,
        'message' => 'Picking berhasil diproses'
    ]);
}

public function packing($id)
{

    $outbound = Outbound::with('details')->findOrFail($id);

    foreach ($outbound->details as $detail)
    {

        $detail->qty_packed = $detail->qty_picked;
        $detail->status = 'PACKED';
        $detail->save();

    }

    $outbound->status = 'PACKED';
    $outbound->save();

    return response()->json([
        'success' => true,
        'message' => 'Packing selesai'
    ]);

}

}