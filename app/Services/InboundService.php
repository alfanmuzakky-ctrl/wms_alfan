<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inbound;
use App\Models\Supplier;
use App\Models\InboundDetail;
use App\Models\Sku;

class InboundService
{
    public function index()
    {
        $inbounds = Inbound::with('details')->get();
        return view('inbounds.index', compact('inbounds'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('inbounds.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:inbounds,id',
            'supplier_id' => 'required'
        ]);

        Inbound::create([
            'id' => $request->id,
            'supplier_id' => $request->supplier_id,
            'status' => 'CREATE'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inbound berhasil dibuat',
            'module' => 'inbounds'
        ]);
    }

    public function show($id)
    {
        $inbound = Inbound::with('details')->findOrFail($id);
        $skus = Sku::all();
        return view('inbounds.show', compact('inbound', 'skus'));
    }

    public function addSku(Request $request, $id)
    {
        $request->validate([
            'sku_id' => 'required',
            'qty' => 'required|numeric|min:1'
        ]);

        InboundDetail::create([
            'inbound_id' => $id,
            'sku_id' => $request->sku_id,
            'qty' => $request->qty,
            'batch_number' => $request->batch_number,
            'expired_date' => $request->expired_date,
            'status' => 'CREATE'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SKU berhasil ditambahkan'
        ]);
    }

    public function close($id)
    {
        DB::beginTransaction();
        try {
            $inbound = Inbound::with('details')->findOrFail($id);

            if ($inbound->status === 'CLOSE') {
                return response()->json(['success' => false, 'message' => 'Inbound sudah ditutup']);
            }

            foreach ($inbound->details as $detail) {
                if ($detail->received_qty < $detail->qty) {
                    return response()->json(['success' => false, 'message' => 'Masih ada item yang belum selesai']);
                }
            }

            $inbound->status = 'CLOSE';
            $inbound->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Inbound berhasil ditutup']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}