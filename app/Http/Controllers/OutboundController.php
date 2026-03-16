<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Customer;
use App\Models\Sku;
use App\Models\Inventory;

class OutboundController extends Controller
{
public function index()
{
    $outbounds = Outbound::orderBy('created_at','desc')->get();

    return view('outbounds.index', compact('outbounds'));
}

public function show($id)
{
    $outbound = Outbound::with('customer')->findOrFail($id);

    $details = OutboundDetail::where('outbound_id', $id)
                ->with('sku')
                ->get();

    return view('outbounds.show', compact('outbound','details'));
}

public function create()
{
    $customers = Customer::orderBy('name')->get();

    return view('outbounds.create', compact('customers'));
}

public function store(Request $request)
{
    $request->validate([
        'id' => 'required|unique:outbounds,id',
        'customer_id' => 'required|exists:customers,id'
    ]);

    Outbound::create([
        'id' => $request->id,
        'customer_id' => $request->customer_id,
        'status' => 'CREATE'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Outbound berhasil dibuat'
    ]);
}
}