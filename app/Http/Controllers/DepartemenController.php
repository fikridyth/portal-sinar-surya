<?php

namespace App\Http\Controllers;

use App\DataTables\DepartemenDataTable;
use App\Models\Departemen;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DepartemenDataTable $dataTable)
    {
        $title = 'Master Departemen';

        return $dataTable->render('master.departemen.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Departemen';
        $units = Unit::all();

        return view('master/departemen/create', compact('title', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'id_unit' => $request->id_unit,
            'nama' => $request->nama,
        ];

        Departemen::create($data);

        return Redirect::route('master.departemen.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Add Departemen Success!");
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
        $title = 'Edit Unit';
        $departemen = Departemen::find($id);
        $units = Unit::all();

        return view('master/departemen/edit', compact('title', 'departemen', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $departemen = Departemen::find($id);

        $data = [
            'id_unit' => $request->id_unit,
            'nama' => $request->nama,
        ];

        $departemen->update($data);

        return Redirect::route('master.departemen.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Departemen Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Departemen::find($id)->delete();

        return Redirect::route('master.departemen.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Departemen Success!");
    }
}
