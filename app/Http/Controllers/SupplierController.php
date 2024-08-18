<?php

namespace App\Http\Controllers;

use App\DataTables\SupplierDataTable;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SupplierDataTable $dataTable)
    {
        $title = 'Master Supplier';

        return $dataTable->render('master.supplier.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Supplier';

        return view('master/supplier/create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
        ];

        Supplier::create($data);

        return Redirect::route('master.supplier.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Add Supplier Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Supplier';
        $supplier = Supplier::find($id);

        return view('master/supplier/edit', compact('title', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        $data = [
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
        ];

        $supplier->update($data);

        return Redirect::route('master.supplier.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Supplier Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Supplier::find($id)->delete();

        return Redirect::route('master.supplier.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Supplier Success!");
    }
}
