<?php

namespace App\Http\Controllers;

/* Imports */
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Allocation;

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
    public function printOutbound($id)
    {
        $allocations = Allocation::with(['sku','location'])
            ->where('outbound_id', $id)
            ->orderBy('location_id')
            ->get();

        return view('print.picking_list', [
    'outbound_id' => $id,
    'allocations' => $allocations
]);
    }

}