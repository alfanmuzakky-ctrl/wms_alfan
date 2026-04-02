<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbound;
use App\Models\OutboundDetail;

class PackingCheckController extends Controller
{

    
    public function index()
    {
        $outboundId = request('outbound');
        $outbound = null;

        if ($outboundId) {
            $outbound = Outbound::with('details')->find($outboundId);
        }

        return view('packing_check.index', compact('outbound'));
    }


   
    public function loadOrder(Request $request)
    {
        $outbound = Outbound::with('details')
            ->where('id', $request->outbound_id)
            ->first();

        if (!$outbound) {
            return response()->json([
                'error' => 'Outbound tidak ditemukan'
            ]);
        }

        return response()->json($outbound);
    }


    
    public function scanSku(Request $request)
{
    $detail = OutboundDetail::where('outbound_id', $request->outbound_id)
        ->where('sku', $request->sku)
        ->first();

    if (!$detail) {
        return response()->json([
            'error' => 'SKU tidak ada di order'
        ]);
    }


    $qty = $request->qty ?? 1;

  
    $maxQty = $detail->qty_picked ?? $detail->qty_allocated ?? 0;

 
    if ($maxQty <= 0) {
        return response()->json([
            'error' => 'Barang belum dipicking'
        ]);
    }


    if ($detail->qty_packed + $qty > $maxQty) {
        return response()->json([
            'error' => 'Qty melebihi qty picking'
        ]);
    }

   
    if ($detail->qty_packed + $qty > $detail->order_qty) {
        return response()->json([
            'error' => 'Qty melebihi order'
        ]);
    }

  
    $detail->qty_packed += $qty;

  
    if ($detail->qty_packed == $maxQty) {
        $detail->status = 'PACKED';
    } else {
        $detail->status = 'PARTIAL';
    }

    $detail->save();

    return response()->json([
        'sku'         => $detail->sku,
        'qty_packed'  => $detail->qty_packed,
        'order_qty'   => $detail->order_qty,
        'max_qty'     => $maxQty,
        'status'      => $detail->status
    ]);
}


    public function confirmPack(Request $request)
    {
        $outbound = Outbound::with('details')->find($request->outbound_id);

        if (!$outbound) {
            return response()->json([
                'error' => 'Outbound tidak ditemukan'
            ]);
        }

        foreach ($outbound->details as $detail) {

          
            if ($detail->qty_packed != $detail->order_qty) {
                return response()->json([
                    'error' => 'Masih ada SKU yang belum lengkap'
                ]);
            }

            
            if ($detail->qty_packed > ($detail->qty_picked ?? 0)) {
                return response()->json([
                    'error' => 'Qty packing melebihi qty picking'
                ]);
            }

            $detail->status = 'PACKED';
            $detail->save();
        }

    
        $outbound->status = 'PACKED';
        $outbound->save();

        return response()->json([
            'success' => 'Packing selesai'
        ]);
    }

}