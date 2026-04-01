<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbound;
use App\Models\OutboundDetail;

class PackingCheckController extends Controller
{

    /*
    📦 HALAMAN PACKING CHECK
    */
    public function index()
    {
        $outboundId = request('outbound');
        $outbound = null;

        if ($outboundId) {
            $outbound = Outbound::with('details')->find($outboundId);
        }

        return view('packing_check.index', compact('outbound'));
    }


    /*
    🔍 LOAD ORDER SAAT SCAN OUTBOUND
    */
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


    /*
    📦 SCAN SKU (PACKING)
    */
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

    // 🔥 ambil qty dari frontend
    $qty = $request->qty ?? 1;

    /*
    🔒 Tentukan batas maksimal packing
    */
    $maxQty = $detail->qty_picked ?? $detail->qty_allocated ?? 0;

    /*
    🔒 VALIDASI: belum picking
    */
    if ($maxQty <= 0) {
        return response()->json([
            'error' => 'Barang belum dipicking'
        ]);
    }

    /*
    🔒 VALIDASI: tidak boleh lebih dari picking
    */
    if ($detail->qty_packed + $qty > $maxQty) {
        return response()->json([
            'error' => 'Qty melebihi qty picking'
        ]);
    }

    /*
    🔒 VALIDASI: tidak boleh lebih dari order
    */
    if ($detail->qty_packed + $qty > $detail->order_qty) {
        return response()->json([
            'error' => 'Qty melebihi order'
        ]);
    }

    /*
    ✅ TAMBAH QTY PACKED (SESUSAI INPUT)
    */
    $detail->qty_packed += $qty;

    /*
    🔄 UPDATE STATUS
    */
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


    /*
    ✅ CONFIRM PACKING
    */
    public function confirmPack(Request $request)
    {
        $outbound = Outbound::with('details')->find($request->outbound_id);

        if (!$outbound) {
            return response()->json([
                'error' => 'Outbound tidak ditemukan'
            ]);
        }

        foreach ($outbound->details as $detail) {

            /*
            🔒 VALIDASI: tidak boleh kurang dari order
            */
            if ($detail->qty_packed != $detail->order_qty) {
                return response()->json([
                    'error' => 'Masih ada SKU yang belum lengkap'
                ]);
            }

            /*
            🔒 VALIDASI: tidak boleh lebih dari picking
            */
            if ($detail->qty_packed > ($detail->qty_picked ?? 0)) {
                return response()->json([
                    'error' => 'Qty packing melebihi qty picking'
                ]);
            }

            $detail->status = 'PACKED';
            $detail->save();
        }

        /*
        🔄 UPDATE STATUS OUTBOUND
        */
        $outbound->status = 'PACKED';
        $outbound->save();

        return response()->json([
            'success' => 'Packing selesai'
        ]);
    }

}