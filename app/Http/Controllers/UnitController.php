<?php

namespace App\Http\Controllers;

use App\DataTables\UnitDataTable;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UnitDataTable $dataTable)
    {
        $title = 'Master Group';
        $titleHeader = "MASTER GROUP";

        return $dataTable->render('master.unit.index', compact('title', 'titleHeader'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Group';
        $unit = new Unit();

        return view('master/unit/create', compact('title', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'nama' => $request->nama,
        ];

        $unit = Unit::create($data);

        return Redirect::route('master.unit.show', enkrip($unit->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Add Unit Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Show Group';
        $id = dekrip($id);
        $unit = Unit::find($id);

        return view('master/unit/show', compact('title', 'unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Group';
        $id = dekrip($id);
        $unit = Unit::find($id);

        return view('master/unit/edit', compact('title', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = dekrip($id);
        $unit = Unit::find($id);

        $data = [
            'nama' => $request->nama,
        ];

        $unit->update($data);

        return Redirect::route('master.unit.show', enkrip($unit->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Unit Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = dekrip($id);
        Unit::find($id)->delete();

        return Redirect::route('master.unit.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Unit Success!");
    }
}
