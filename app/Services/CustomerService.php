<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerService
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan',
            'module'  => 'customers'
        ]);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.detail', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil diupdate',
            'module'  => 'customers'
        ]);
    }
}