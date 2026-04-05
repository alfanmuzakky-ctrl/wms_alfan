<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Location;

class PutawayService
{
    public function putawayIndex()
    {
        $stagingInventories = Inventory::where('location_id', 'INB-STATION')
            ->where('qty_stock', '>', 0)
            ->get();

        $locations = Location::all();

        return view('putaway.index', compact('stagingInventories', 'locations'));
    }

    public function putawayProcess(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required',
            'destination' => 'required',
            'qty' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();
        try {
            $source = Inventory::findOrFail($request->inventory_id);

            if ($request->qty > $source->qty_stock) {
                return response()->json(['success' => false, 'message' => 'Qty melebihi stok tersedia']);
            }

            $source->qty_stock -= $request->qty;
            $source->save();

            $dest = Inventory::firstOrCreate(
                [
                    'sku_id' => $source->sku_id,
                    'location_id' => $request->destination,
                    'batch_number' => $source->batch_number,
                    'expired_date' => $source->expired_date,
                    'inbound_detail_id' => $source->inbound_detail_id
                ],
                ['qty_stock' => 0, 'qty_allocated' => 0]
            );

            $dest->qty_stock += $request->qty;
            $dest->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Putaway berhasil']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}