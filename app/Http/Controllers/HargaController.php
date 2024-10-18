<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HargaController extends Controller
{
    public function index()
    {
        $title = "Master Harga";
        $suppliers = Supplier::where('status', 1)->get();

        return view('master.harga.index', compact('title', 'suppliers'));
    }

    public function show($id)
    {
        $id = dekrip($id);
        $title = "Show Master Harga";
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)->where('status', 1)->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        // dd($id, count($products));

        return view('master.harga.show', compact('title', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all(), $id);
        Product::find($id)->update([
            'harga_lama' => $request->harga_lama,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'profit' => number_format((($request->harga_jual - $request->harga_pokok) / $request->harga_pokok) * 100, 2),
            'harga_sementara' => $request->harga_sementara,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir
        ]);

        return Redirect::route('master.harga.show', enkrip($request->id_supplier))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Harga Success!");
    }
    
    public function indexHargaSementara()
    {
        $title = "Master Harga Sementara";
        $products = Product::whereNotNull('harga_sementara')
            ->where('tanggal_awal', '<=', now()->format('Y-m-d'))
            ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))
            ->get()
            ->groupBy(function($product) {
                return $product->supplier->nama; // Menggunakan nama supplier sebagai key
            });
        // dd($products);

        return view('master.harga.index-sementara', compact('title', 'products'));
    }

    public function showHargaSementara($id)
    {
        $id = dekrip($id);
        $title = "Show Master Harga";
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)
            ->whereNotNull('harga_sementara')
            ->where('tanggal_awal', '<=', now()->format('Y-m-d'))
            ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))
            ->get();
        // dd($id, count($products));

        return view('master.harga.show-sementara', compact('title', 'suppliers', 'products'));
    }
}
