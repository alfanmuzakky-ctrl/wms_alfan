<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbound;
use App\Models\OutboundDetail;

class PackingCheckController extends Controller
{

/*
HALAMAN PACKING CHECK
*/

public function index()
{

$outboundId = request('outbound');

$outbound = null;

if($outboundId)
{
$outbound = Outbound::with('details')->find($outboundId);
}

return view('packing_check.index',compact('outbound'));

}


/*
LOAD ORDER SAAT SCAN OUTBOUND
*/

public function loadOrder(Request $request)
{

$outbound = Outbound::with('details')
->where('id',$request->outbound_id)
->first();

if(!$outbound)
{
return response()->json([
'error'=>'Outbound tidak ditemukan'
]);
}

return response()->json($outbound);

}


/*
SCAN SKU
*/

public function scanSku(Request $request)
{

$detail = OutboundDetail::where('outbound_id',$request->outbound_id)
->where('sku',$request->sku)
->first();

if(!$detail)
{
return response()->json([
'error'=>'SKU tidak ada di order'
]);
}

/*
TAMBAH QTY PACKED
*/

if($detail->qty_packed < $detail->order_qty)
{

$detail->qty_packed += 1;

$detail->save();

}

return response()->json([
'sku'=>$detail->sku,
'qty_packed'=>$detail->qty_packed,
'order_qty'=>$detail->order_qty
]);

}


/*
CONFIRM PACKING
*/

public function confirmPack(Request $request)
{

$outbound = Outbound::with('details')->find($request->outbound_id);

if(!$outbound)
{
return response()->json(['error'=>'Outbound tidak ditemukan']);
}

foreach($outbound->details as $detail)
{

if($detail->qty_packed != $detail->order_qty)
{
return response()->json([
'error'=>'Masih ada SKU yang belum lengkap'
]);
}

$detail->status = 'PACKED';

$detail->save();

}

$outbound->status = 'PACKED';

$outbound->save();

return response()->json([
'success'=>'Packing selesai'
]);

}

}