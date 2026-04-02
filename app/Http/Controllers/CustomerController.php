<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /* Page Header: Index */
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    /* Page Header: Create View */
    public function create()
    {
        return view('customers.create');
    }

    /* Page Header: Store Data */
    public function store(Request $request)
    {
        Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan',
            'module'  => 'customers'
        ]);
    }

    /* Page Header: Show Detail */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.detail', compact('customer'));
    }

    /* Page Header: Update Data */
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