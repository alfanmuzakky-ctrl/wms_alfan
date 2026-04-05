<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Customer;
use App\Models\Sku;
use App\Models\Inventory;

class OutboundService
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

    $details = OutboundDetail::where('outbound_id', $id)
        ->with([
            'skuData',
            'orders'
        ])
        ->get();

    $skus = Sku::orderBy('id')->get();

    $locationsBySku = Inventory::whereRaw('qty_stock - qty_allocated > 0')
        ->get()
        ->groupBy('sku_id');

    return view('outbounds.show', compact(
    'outbound',
    'details',
    'skus',
    'locationsBySku'
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

}