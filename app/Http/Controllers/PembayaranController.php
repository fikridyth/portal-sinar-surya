<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Pembayaran;
use App\Models\Preorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PembayaranController extends Controller
{
    public function index()
    {
        $title = 'List Pembayaran';
        $pembayarans = Pembayaran::whereNull('is_bayar')->get();
        $banks = Bank::orderBy('nama', 'desc')->get();
        
        return view('pembayaran.index', compact('title', 'pembayarans', 'banks'));
    }

    public function show($id)
    {
        $title = 'Detail Pembayaran';
        $pembayaran = Pembayaran::find($id);
        $banks = Bank::orderBy('nama', 'desc')->get();
        
        return view('pembayaran.show', compact('title', 'pembayaran', 'banks'));
    }

    public function update(Request $request, $id)
    {
        // dd($id, $request->all());
        $pembayaran = Pembayaran::find($id);
        $getPreorder = Preorder::where('nomor_po', $pembayaran->nomor_po)->first();

        $pembayaran->update([
            'is_bayar' => 1,
        ]);

        Pembayaran::create([
            'id_supplier' => $pembayaran->id_supplier,
            'date' => now()->format('Y-m-d'),
            'nomor_po' => $pembayaran->nomor_po,
            'grand_total' => $request->payment ?? 0,
            'nomor_giro' => 'TUNAI',
            'id_parent' => $id
        ]);

        Pembayaran::create([
            'id_supplier' => $pembayaran->id_supplier,
            'date' => now()->format('Y-m-d'),
            'nomor_po' => $pembayaran->nomor_po,
            'grand_total' => $request->other_income ?? 0,
            'nomor_giro' => 'OTHER INCOME',
            'id_parent' => $id
        ]);

        $getPreorder->update([
            'is_pay' => 1,
        ]);

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Success!");
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::find($id);
        $parent = Pembayaran::find($pembayaran->id_parent);
        $getPreorder = Preorder::where('nomor_po', $parent->nomor_po)->first();
        
        Pembayaran::where('id_parent', $parent->id)->delete();
        $parent->update(['is_bayar' => null]);
        $getPreorder->update(['is_pay' => null]);
        
        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Pembayaran Success!");
    }
}
