<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inbound;
use App\Models\Supplier;
use App\Models\InboundDetail;
use App\Models\Sku;
use App\Models\Inventory;
use App\Models\Location;

class InboundController extends Controller
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
                    'expired_date' => $detail->expired_date
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