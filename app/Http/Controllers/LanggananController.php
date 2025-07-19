<?php

namespace App\Http\Controllers;

use App\DataTables\LanggananDataTable;
use App\Models\Langganan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LanggananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LanggananDataTable $dataTable)
    {
        $title = 'Master langganan';
        $titleHeader = 'MASTER LANGGANAN';

        return $dataTable->render('master.langganan.index', compact('title', 'titleHeader'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create langganan';
        $langganan = new Langganan();

        return view('master/langganan/create', compact('title', 'langganan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // get nomor langganan
        $sequence = '00000001';
        $getNomor = Langganan::max("nomor");
        $getNomorLangganan = $getNomor ? str_pad($getNomor + 1, 8, '0', STR_PAD_LEFT) : $sequence;

        $data = [
            'nomor' => $getNomorLangganan,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'kota' => $request->kota,
            'kontak' => $request->kontak,
            'telepon' => $request->telepon,
            'fax' => $request->fax,
            'zona' => $request->zona,
        ];
        // dd($data);

        $langganan = Langganan::create($data);

        return Redirect::route('master.langganan.show', enkrip($langganan->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Add Langganan Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Show Langganan';
        $id = dekrip($id);
        $langganan = Langganan::find($id);

        return view('master/langganan/show', compact('title', 'langganan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Langganan';
        $id = dekrip($id);
        $langganan = Langganan::find($id);

        return view('master/langganan/edit', compact('title', 'langganan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = dekrip($id);
        $langganan = Langganan::find($id);

        $data = [
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'kota' => $request->kota,
            'kontak' => $request->kontak,
            'telepon' => $request->telepon,
            'fax' => $request->fax,
            'zona' => $request->zona,
        ];

        $langganan->update($data);

        return Redirect::route('master.langganan.show', enkrip($langganan->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Langganan Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = dekrip($id);
        Langganan::find($id)->delete();

        return Redirect::route('master.langganan.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Langganan Success!");
    }
}
