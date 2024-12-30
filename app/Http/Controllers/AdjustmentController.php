<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdjustmentController extends Controller
{
    public function index() {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        $grups = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        // dd(count($products));

        return view('master.adjustment.index', compact('title', 'titleHeader', 'grups', 'departemens', 'suppliers', 'products'));
    }

    public function show(Request $request) {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        // dd($request->all());

        $query = Product::query();

        if (!empty($request->selected_products)) {
            $query->whereIn('id', $request->selected_products);
        }

        if (!empty($request->selected_suppliers)) {
            $query->whereIn('id_supplier', $request->selected_suppliers);
        }

        if (!empty($request->selected_grups)) {
            $query->whereIn('id_unit', $request->selected_grups);
        }

        if (!empty($request->selected_departemens)) {
            $query->whereIn('id_departemen', $request->selected_departemens);
        }

        $products = $query->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        session(['products' => $products]);
        // dd(count($products));

        return view('master.adjustment.show', compact('title', 'titleHeader', 'products'));
    }

    public function indexEdit() {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        $grups = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        // dd(count($products));

        return view('master.adjustment.index-edit', compact('title', 'titleHeader', 'grups', 'departemens', 'suppliers', 'products'));
    }

    public function password(Request $request) {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        // dd($request->all());

        $query = Product::query();

        if (!empty($request->selected_products)) {
            $query->whereIn('id', $request->selected_products);
        }

        if (!empty($request->selected_suppliers)) {
            $query->whereIn('id_supplier', $request->selected_suppliers);
        }

        if (!empty($request->selected_grups)) {
            $query->whereIn('id_unit', $request->selected_grups);
        }

        if (!empty($request->selected_departemens)) {
            $query->whereIn('id_departemen', $request->selected_departemens);
        }

        $products = $query->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        session(['products' => $products]);

        $getUser = User::where('name', 'LO HARYANTO')->first();
        $passUser = $getUser->show_password;

        return view('master.adjustment.password', compact('title', 'titleHeader', 'products', 'passUser'));
    }

    public function edit(Request $request) {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';

        $products = session('products');
        // dd(count($products));

        return view('master.adjustment.edit', compact('title', 'titleHeader', 'products'));
    }
    
    public function update(Request $request) {
        $fisik = $request->input('fisik'); // Ini akan berupa array dengan ID produk dan nilai fisik yang baru

        foreach ($fisik as $productId => $newFisik) {
            // Lakukan pembaruan data sesuai kebutuhan, misalnya:
            $product = Product::find($productId);
            if ($product) {
                $product->stok = $newFisik;
                $product->save();
            }
        }

        return Redirect::route('master.adjustment.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Adjustment Success!");
    }
    
    public function updateEdit(Request $request) {
        $fisik = $request->input('fisik'); // Ini akan berupa array dengan ID produk dan nilai fisik yang baru

        foreach ($fisik as $productId => $newFisik) {
            // Lakukan pembaruan data sesuai kebutuhan, misalnya:
            $product = Product::find($productId);
            if ($product) {
                $product->stok = $newFisik;
                $product->save();
            }
        }

        return Redirect::route('master.adjustment.index-edit')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Adjustment Success!");
    }

    public function cetak() {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';

        $products = session('products');

        return view('master.adjustment.cetak', compact('title', 'titleHeader', 'products'));
    }
}
