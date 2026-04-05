<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\InboundDetail;
use App\Models\Inventory;
use App\Models\Inbound;

class InboundProcessService
{
    public function received(Request $request)
    {
        DB::beginTransaction();
        try {
        
            $detail = InboundDetail::findOrFail($request->detail_id);
            $inbound = $detail->inbound;

            if ($inbound->status == 'CLOSE') {
                return response()->json(['success' => false, 'message' => 'Inbound sudah ditutup']);
            }

            $receiveQty = (int) $request->receive_qty;
            $remaining = $detail->qty - $detail->received_qty;

            if ($receiveQty <= 0 || $receiveQty > $remaining) {
                return response()->json(['success' => false, 'message' => 'Qty tidak valid atau melebihi sisa']);
            }

            
            $inventory = Inventory::firstOrCreate(
                [
                    'sku_id' => $detail->sku_id,
                    'location_id' => 'INB-STATION',
                    'batch_number' => $detail->batch_number,
                    'expired_date' => $detail->expired_date,
                    'inbound_detail_id' => $detail->id,
                ],
                ['qty_stock' => 0, 'qty_allocated' => 0]
            );

            $inventory->qty_stock += $receiveQty;
            $inventory->save();

            
            $detail->received_qty += $receiveQty;
            $detail->status = ($detail->received_qty >= $detail->qty) ? 'RECEIVED' : 'PARTIAL';
            $detail->save();

            
            $this->updateInboundStatus($inbound);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Receive berhasil']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function updateInboundStatus(Inbound $inbound)
    {
        $details = $inbound->details;
        $total = $details->count();
        $receivedFull = $details->where('status', 'RECEIVED')->count();
        $isPartial = $details->where('received_qty', '>', 0)->count();

        if ($total > 0 && $receivedFull === $total) {
            $inbound->status = 'RECEIVED';
        } elseif ($isPartial > 0) {
            $inbound->status = 'PARTIAL';
        } else {
            $inbound->status = 'CREATE';
        }
        $inbound->save();
    }
}