<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function index()
    {
        return view('dashboard.index', [

            'totalSku' => DB::table('skus')->count(),
            'totalSupplier' => DB::table('suppliers')->count(),
            'totalCustomer' => DB::table('customers')->count(),
            'totalLocation' => DB::table('locations')->count(),

            'totalStock' => DB::table('inventories')->sum('qty_stock') ?? 0,
            'allocatedStock' => DB::table('inventories')->sum('qty_allocated') ?? 0,

            'stockInbound' => DB::table('inventories')
                ->where('location_id', 'INB-STATION')
                ->sum('qty_stock') ?? 0,

            'stockOutbound' => DB::table('inventories')
                ->where('location_id', 'OUT-STATION')
                ->sum('qty_stock') ?? 0,

            'totalInbound' => DB::table('inbounds')->count(),
            'inboundCreate' => DB::table('inbounds')->where('status', 'CREATE')->count(),
            'inboundReceived' => DB::table('inbounds')->where('status', 'RECEIVED')->count(),

            'totalOutbound' => DB::table('outbounds')->count(),
            'outboundPacked' => DB::table('outbounds')->where('status', 'PACKED')->count(),
            'outboundShipped' => DB::table('outbounds')->where('status', 'SHIPPED')->count(),

            'latestInbound' => DB::table('inbounds')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),

            'latestOutbound' => DB::table('outbounds')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ]);
    }
}