<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Outbound;
use App\Models\Inventory;
use App\Models\OrderDetail;

class AllocationService
{

public function allocate($id)
{
    try {

        $outbound = Outbound::with('details')->findOrFail($id);

        foreach ($outbound->details as $detail)
        {

            $need = $detail->order_qty - $detail->qty_allocated;

            if ($need <= 0) continue;

            $hasExpired = Inventory::where('sku_id', $detail->sku)
                ->whereNotNull('expired_date')
                ->exists();

            $query = Inventory::where('sku_id', $detail->sku)
                ->whereRaw('qty_stock - qty_allocated > 0');

            if ($hasExpired) {
                $query->whereNotNull('expired_date')
                      ->orderBy('expired_date','asc')
                      ->orderBy('created_at','asc'); 
            } else {
                $query->orderBy('created_at','asc');
            }

            $stocks = $query->get();

            foreach ($stocks as $stock)
            {

                if ($need <= 0) break;

                $available = $stock->qty_stock - $stock->qty_allocated;

                if ($available <= 0) continue;

                $allocate = min($available, $need);

                OrderDetail::create([
                    'outbound_detail_id' => $detail->id,
                    'inventory_id' => $stock->id,
                    'location' => $stock->location_id,
                    'batch_number' => $stock->batch_number,
                    'expired_date' => $stock->expired_date,
                    'qty_allocated' => $allocate,
                    'qty_picked' => 0,
                    'status' => 'ALLOCATED'
                ]);

                $stock->qty_allocated += $allocate;
                $stock->save();

                $detail->qty_allocated += $allocate;

                $need -= $allocate;
            }

            if ($detail->qty_allocated >= $detail->order_qty) {
                $detail->status = 'ALLOCATED';
            } else {
                $detail->status = 'PARTIAL';
            }

            $detail->save();
        }

        $allAllocated = $outbound->details->every(function($d){
            return $d->status === 'ALLOCATED';
        });

        $outbound->status = $allAllocated ? 'ALLOCATED' : 'PARTIAL';
        $outbound->save();

        return response()->json([
            'success' => true,
            'message' => 'Allocation Complete',
            'reload_drawer' => true
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'reload_drawer' => true
        ]);
    }
}

public function reallocate(Request $request)
{
    DB::beginTransaction();

    try {

        $order = OrderDetail::findOrFail($request->order_id);

        $source = $order->inventory;

        if (!$source) {
            throw new \Exception("Inventory source tidak ditemukan");
        }

        $skuId = $source->sku_id;

        $qty = $request->qty;

        if ($qty > $order->qty_allocated) {
            throw new \Exception("Qty melebihi allocated");
        }

        $source->qty_allocated -= $qty;
        $source->save();

        $order->qty_allocated -= $qty;
        $order->save();

        if ($order->qty_allocated <= 0) {
            $order->delete();
        }

        $stocks = Inventory::where('sku_id', $skuId)
            ->where('location_id', $request->dest_location_id)
            ->whereRaw('qty_stock - qty_allocated > 0')
            ->orderByRaw('expired_date IS NULL')
            ->orderBy('expired_date','asc')
            ->orderBy('created_at','asc')
            ->get();

        $need = $qty;

        foreach ($stocks as $stock) {

            if ($need <= 0) break;

            $available = $stock->qty_stock - $stock->qty_allocated;

            if ($available <= 0) continue;

            $take = min($available, $need);

            OrderDetail::create([
                'outbound_detail_id' => $order->outbound_detail_id,
                'inventory_id' => $stock->id,
                'location' => $stock->location_id,
                'batch_number' => $stock->batch_number,
                'expired_date' => $stock->expired_date,
                'qty_allocated' => $take,
                'qty_picked' => 0,
                'status' => 'ALLOCATED'
            ]);

            $stock->qty_allocated += $take;
            $stock->save();

            $need -= $take;
        }

        if ($need > 0) {
            throw new \Exception("Stock tujuan tidak cukup");
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Reallocate berhasil'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'reload_drawer' => true
        ]);
    }
}

}