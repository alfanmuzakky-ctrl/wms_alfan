<?php

namespace App\Http\Controllers;

/* Imports */
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /* Page Header: Index */
    public function index()
    {
        $locations = Location::all();
        return view('locations.index', compact('locations'));
    }

    /* Page Header: Create View */
    public function create()
    {
        return view('locations.create');
    }

    /* Page Header: Store Data */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:locations,id',
            'zone_group' => 'required',
            'location_category' => 'required',
            'location_attribute' => 'required'
        ]);

        Location::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Location berhasil ditambahkan',
            'module'  => 'locations'
        ]);
    }

    /* Page Header: Show Detail */
    public function show($id)
    {
        $location = Location::findOrFail($id);
        return view('locations.detail', compact('location'));
    }

    /* Page Header: Update Data */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $location->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Location berhasil diupdate',
            'module'  => 'locations'
        ]);
    }
}