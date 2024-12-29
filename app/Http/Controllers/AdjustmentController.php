<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

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

        $products = $query->get();
        // dd(count($products));

        return view('master.adjustment.show', compact('title', 'titleHeader', 'products'));
    }
    
    public function update(Request $request) {
        dd($request->all());
    }
}
