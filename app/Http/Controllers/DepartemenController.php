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
        $titleHeader = "MASTER DEPARTEMEN";

        return $dataTable->render('master.departemen.index', compact('title', 'titleHeader'));
    }

    public function getDepartemenByUnit(Request $request)
    {
        $unitId = $request->input('unit_id');
        dd($unitId);

        // Fetch the departemen data based on the unit ID
        $departemen = Departemen::where('id_unit', $unitId)->get(['id', 'nama']);

        // Return the data as JSON
        return response()->json(['departemen' => $departemen]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Departemen';
        $units = Unit::all();
        $departemen = new Departemen();

        return view('master/departemen/create', compact('title', 'units', 'departemen'));
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

        $departemen = Departemen::create($data);

        return Redirect::route('master.departemen.show', enkrip($departemen->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Add Departemen Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = dekrip($id);
        $title = 'Show Departemen';
        $departemen = Departemen::find($id);

        return view('master/departemen/show', compact('title', 'departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Unit';
        $id = dekrip($id);
        $departemen = Departemen::find($id);
        $units = Unit::all();

        return view('master/departemen/edit', compact('title', 'departemen', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = dekrip($id);
        $departemen = Departemen::find($id);

        $data = [
            'id_unit' => $request->id_unit,
            'nama' => $request->nama,
        ];

        $departemen->update($data);

        return Redirect::route('master.departemen.show', enkrip($departemen->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Departemen Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = dekrip($id);
        Departemen::find($id)->delete();

        return Redirect::route('master.departemen.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Departemen Success!");
    }
}
