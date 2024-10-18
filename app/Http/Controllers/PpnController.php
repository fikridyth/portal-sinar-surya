<?php

namespace App\Http\Controllers;

use App\Models\Ppn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PpnController extends Controller
{
    public function edit($id)
    {
        $id = dekrip($id);
        $title = 'Ubah PPN';
        $ppn = Ppn::find($id);
        return view('master.ppn.index', compact('title', 'ppn'));
    }

    public function update(Request $request, $id)
    {
        $id = dekrip($id);
        $ppn = Ppn::find($id);
        $ppn->update([
            'ppn' => $request->ppn,
        ]);

        return Redirect::route('master.ppn.edit', enkrip($ppn->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update PPN Success!");
    }
}
