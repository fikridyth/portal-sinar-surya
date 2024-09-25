<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
        $title = "Show Master Harga";
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)->where('status', 1)->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        // dd($id, count($products));

        return view('master.harga.show', compact('title', 'suppliers', 'products'));
    }
}
