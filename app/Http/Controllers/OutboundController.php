<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Customer;
use App\Models\Sku;
use App\Models\Inventory;
use App\Models\OrderDetail;

class OutboundController extends Controller
{

public function index()
{

$outbounds = Outbound::with('customer')
    ->orderBy('created_at','desc')
    ->get();

return view('outbounds.index',compact('outbounds'));

}


public function create()
{
    $customers = Customer::orderBy('name')->get();

    return view('outbounds.create',compact('customers'));
}


public function store(Request $request)
{

    $request->validate([
        'id'=>'required|unique:outbounds,id',
        'customer_id'=>'required'
    ]);

    Outbound::create([
        'id'=>$request->id,
        'customer_id'=>$request->customer_id,
        'status'=>'CREATE'
    ]);

    return response()->json([
            'success' => true,
            'message' => 'Outbound berhasil dibuat',
            'module' => 'outbounds'
        ]);
}


public function show($id)
{

    $outbound = Outbound::with('customer')->findOrFail($id);

    $details = OutboundDetail::where('outbound_id',$id)
        ->with('skuData')
        ->get();

    $skus = Sku::orderBy('id')->get();

    return view('outbounds.show',compact(
        'outbound',
        'details',
        'skus'
    ));
}


public function addSku(Request $request,$id)
{

    $request->validate([
        'sku'=>'required',
        'qty'=>'required|numeric|min:1'
    ]);

    OutboundDetail::create([
        'outbound_id'=>$id,
        'sku'=>$request->sku,
        'order_qty'=>$request->qty,
        'status'=>'CREATE'
    ]);

    return response()->json([
            'success' => true,
            'message' => 'SKU berhasil ditambahkan'
        ]);
}

    public function allocate($id)
{

try {

$outbound = Outbound::with('details')->findOrFail($id);

foreach ($outbound->details as $detail)
{

$need = $detail->order_qty - $detail->qty_allocated;

if ($need <= 0) continue;

$stocks = Inventory::where('sku_id', $detail->sku)
->whereRaw('qty_stock - qty_allocated > 0')
->orderByRaw('expired_date IS NULL')
->orderBy('expired_date','asc')   // FEFO
->orderBy('created_at','asc')     // FIFO
->get();

foreach ($stocks as $stock)
{

if ($need <= 0) break;

$available = $stock->qty_stock - $stock->qty_allocated;

if ($available <= 0) continue;

$allocate = min($available, $need);

OrderDetail::create([
'outbound_detail_id' => $detail->id,
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

$detail->status = 'ALLOCATED';
$detail->save();

}

$outbound->status = 'ALLOCATED';
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

    public function picking($id)
{
    $outbound = Outbound::with('details.orders')->findOrFail($id);

    /*
    🔒 CEK: jika outbound sudah selesai
    */
    if ($outbound->status === 'PICKED') {
        return response()->json([
            'success' => false,
            'message' => 'Outbound sudah selesai picking'
        ], 400);
    }

    foreach ($outbound->details as $detail) {

        foreach ($detail->orders as $order) {

            /*
            🔒 CEK: skip jika sudah fully picked
            */
            if ($order->qty_picked >= $order->qty_allocated) {
                continue;
            }

            /*
            Hitung sisa yang bisa dipick
            */
            $remainingQty = $order->qty_allocated - $order->qty_picked;

            if ($remainingQty <= 0) {
                continue;
            }

            $pickQty = $remainingQty;

            /*
            1️⃣ UPDATE ORDER
            */
            $order->qty_picked += $pickQty;

            if ($order->qty_picked >= $order->qty_allocated) {
                $order->status = 'PICKED';
            } else {
                $order->status = 'PARTIAL';
            }

            $order->save();

            /*
            2️⃣ UPDATE INVENTORY BIN (SOURCE)
            */
            $binStock = Inventory::where('sku_id', $detail->sku)
                ->where('location_id', $order->location)
                ->where('batch_number', $order->batch_number)
                ->first();

            if ($binStock) {

                $binStock->qty_allocated -= $pickQty;
                $binStock->qty_stock -= $pickQty;

                // prevent minus
                if ($binStock->qty_allocated < 0) {
                    $binStock->qty_allocated = 0;
                }

                if ($binStock->qty_stock < 0) {
                    $binStock->qty_stock = 0;
                }

                $binStock->save();
            }

            /*
            3️⃣ PINDAHKAN KE OUT-STATION
            */
            $outStation = Inventory::where('sku_id', $detail->sku)
                ->where('location_id', 'OUT-STATION')
                ->where('batch_number', $order->batch_number)
                ->first();

            if ($outStation) {

                $outStation->qty_stock += $pickQty;

                // semua barang di station dianggap allocated
                $outStation->qty_allocated = $outStation->qty_stock;

                $outStation->save();

            } else {

                Inventory::create([
                    'sku_id'        => $detail->sku,
                    'location_id'   => 'OUT-STATION',
                    'batch_number'  => $order->batch_number,
                    'expired_date'  => $order->expired_date,
                    'qty_stock'     => $pickQty,
                    'qty_allocated' => $pickQty
                ]);
            }

            /*
            4️⃣ UPDATE DETAIL
            */
            $detail->qty_picked += $pickQty;
        }

        /*
        🔄 UPDATE STATUS DETAIL
        */
        if ($detail->qty_picked >= $detail->qty_allocated) {
            $detail->status = 'PICKED';
        } else {
            $detail->status = 'PARTIAL';
        }

        $detail->save();
    }

    /*
    🔄 UPDATE STATUS OUTBOUND
    */
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
    public function ship($id)
{
    $outbound = Outbound::with('details')->findOrFail($id);

    /*
    🔒 CEK: hanya bisa ship jika sudah PACKED
    */
    if ($outbound->status !== 'PACKED') {
        return response()->json([
            'success' => false,
            'message' => 'Outbound belum selesai packing'
        ], 400);
    }

    foreach ($outbound->details as $detail) {

        /*
        🔒 VALIDASI: pastikan qty packed ada
        */
        if ($detail->qty_packed <= 0) {
            return response()->json([
                'success' => false,
                'message' => "SKU {$detail->sku} belum dipacking"
            ], 400);
        }

        $shipQty = $detail->qty_packed;

        /*
        1️⃣ KURANGI STOCK DI OUT-STATION
        */
        $outStation = Inventory::where('sku_id', $detail->sku)
            ->where('location_id', 'OUT-STATION')
            ->first();

        if (!$outStation) {
            return response()->json([
                'success' => false,
                'message' => "Stock OUT-STATION tidak ditemukan untuk SKU {$detail->sku}"
            ], 400);
        }

        /*
        🔒 VALIDASI: tidak boleh minus
        */
        if ($outStation->qty_stock < $shipQty) {
            return response()->json([
                'success' => false,
                'message' => "Stock tidak cukup untuk SKU {$detail->sku}"
            ], 400);
        }

        $outStation->qty_stock -= $shipQty;
        $outStation->qty_allocated -= $shipQty;

        // prevent minus
        if ($outStation->qty_allocated < 0) {
            $outStation->qty_allocated = 0;
        }

        $outStation->save();

        /*
        2️⃣ UPDATE STATUS DETAIL
        */
        $detail->status = 'SHIPPED';
        $detail->save();
    }

    /*
    3️⃣ UPDATE STATUS OUTBOUND
    */
    $outbound->status = 'SHIPPED';
    $outbound->save();

    return response()->json([
        'success' => true,
        'message' => 'Outbound berhasil di shipping'
    ]);
}
}