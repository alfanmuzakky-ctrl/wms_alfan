<?php

namespace App\Services;

use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Outbound;
use App\Models\OrderDetail;

class PrintService
{

public function printInbound($id)
{
    $inbound = Inbound::with('details')->findOrFail($id);

    return view('print.inbound', compact('inbound'));
}

public function printSku($detail_id)
{
    $detail = InboundDetail::with(['inbound', 'sku'])->findOrFail($detail_id);

    return view('print.sku', compact('detail'));
}

public function printPicking($id)
{
    $outbound = Outbound::findOrFail($id);

    $orderDetails = OrderDetail::with('outboundDetail.skuData')
        ->whereHas('outboundDetail', function($q) use ($id){
            $q->where('outbound_id',$id);
        })
        ->orderBy('location')
        ->get();

    return view('print.picking_list', compact(
        'outbound',
        'orderDetails'
    ));
}

}