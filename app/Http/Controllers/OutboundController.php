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

    foreach ($outbound->details as $detail)
    {

    foreach ($detail->orders as $order)
    {

    $pickQty = $order->qty_allocated;

    if($pickQty <= 0) continue;

    /*
    1️⃣ UPDATE ORDER DETAIL
    */

    $order->qty_picked = $pickQty;
    $order->status = 'PICKED';
    $order->save();

    /*
    2️⃣ UPDATE INVENTORY BIN
    */

    $binStock = Inventory::where('sku_id',$detail->sku)
    ->where('location_id',$order->location)
    ->where('batch_number',$order->batch_number)
    ->first();

    if($binStock)
    {

    $binStock->qty_allocated -= $pickQty;
    $binStock->qty_stock -= $pickQty;

    $binStock->save();

    }

    /*
    3️⃣ PINDAHKAN KE OUT-STATION
    */

    $outStation = Inventory::where('sku_id',$detail->sku)
    ->where('location_id','OUT-STATION')
    ->where('batch_number',$order->batch_number)
    ->first();

    if($outStation)
    {

    /*
    Tambahkan stock
    */

    $outStation->qty_stock += $pickQty;

    /*
    Karena barang di station sudah reserved
    allocated = qty_stock
    */

    $outStation->qty_allocated = $outStation->qty_stock;

    $outStation->save();

    }
    else
    {

    Inventory::create([
    'sku_id'=>$detail->sku,
    'location_id'=>'OUT-STATION',
    'batch_number'=>$order->batch_number,
    'expired_date'=>$order->expired_date,

    'qty_stock'=>$pickQty,

    /*
    langsung allocated semua
    */

    'qty_allocated'=>$pickQty
    ]);

    }

    /*
    4️⃣ UPDATE OUTBOUND DETAIL
    */

    $detail->qty_picked += $pickQty;

    }

    /*
    UPDATE STATUS DETAIL
    */

    $detail->status = 'PICKED';
    $detail->save();

    }

    /*
    UPDATE STATUS OUTBOUND
    */

    $outbound->status = 'PICKED';
    $outbound->save();

    return response()->json([
        'success' => true,
        'message' => 'Picking selesai'
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

    foreach ($outbound->details as $detail)
    {

    $detail->status = 'SHIPPED';
    $detail->save();

    }

    $outbound->status = 'SHIPPED';
    $outbound->save();

    return response()->json([
        'success' => true,
        'message' => 'Outbound shipped'
    ]);

    }
}