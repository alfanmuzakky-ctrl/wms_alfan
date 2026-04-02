<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Sku;

class SkuController extends Controller
{
    /* Page Header: Index */
    public function index()
    {
        $skus = Sku::all();
        return view('skus.index', compact('skus'));
    }

    /* Page Header: Create View */
    public function create()
    {
        return view('skus.create');
    }

    /* Page Header: Store Data */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:skus,id',
            'name' => 'required'
        ]);

        Sku::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'SKU berhasil ditambahkan',
            'module'  => 'skus'
        ]);
    }

    /* Page Header: Show Detail */
    public function show($id)
    {
        $sku = Sku::findOrFail($id);
        return view('skus.detail', compact('sku'));
    }

    /* Page Header: Update Data */
    public function update(Request $request, $id)
    {
        $sku = Sku::findOrFail($id);

        $sku->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'SKU berhasil diupdate',
            'module'  => 'skus'
        ]);
    }
}