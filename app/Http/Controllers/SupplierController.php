<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /* Page Header: Index */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    /* Page Header: Create View */
    public function create()
    {
        return view('suppliers.create');
    }

    /* Page Header: Store Data */
    public function store(Request $request)
    {
        $request->validate([
            'id'   => 'required|unique:suppliers,id',
            'name' => 'required'
        ]);

        Supplier::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil disimpan',
            'module'  => 'suppliers'
        ]);
    }

    /* Page Header: Show Detail */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.detail', compact('supplier'));
    }

    /* Page Header: Update Data */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diupdate',
            'module'  => 'suppliers'
        ]);
    }
}