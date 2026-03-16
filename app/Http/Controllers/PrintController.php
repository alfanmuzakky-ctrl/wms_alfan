<?php

namespace App\Http\Controllers;

/* Imports */
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Outbound;
use App\Models\Allocation;
use App\Models\OrderDetail;

class PrintController extends Controller
{

    /* Print Inbound Document */
    public function printInbound($id)
    {
        $inbound = Inbound::with('details')->findOrFail($id);

        return view('print.inbound', compact('inbound'));
    }

    /* Print SKU Label */
    public function printSku($detail_id)
    {
        $detail = InboundDetail::with(['inbound', 'sku'])->findOrFail($detail_id);

        return view('print.sku', compact('detail'));
    }

    /* Print Picking List (Outbound) */
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