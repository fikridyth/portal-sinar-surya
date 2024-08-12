<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PreOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'PreOrder';
        $suppliers = Supplier::all();

        return view('preorder.index', compact('title', 'suppliers'));
    }

    public function getSupplierData(Request $request)
    {
        $nama_supplier = $request->query('nama');
        $supplier = Supplier::where('nama', $nama_supplier)->first();

        if ($supplier) {
            return response()->json($supplier);
        } else {
            return response()->json(['error' => 'Supplier not found'], 404);
        }
    }

    public function getListBarang(Request $request)
    {
        $title = 'Get Barang';
        $supplier1 = Supplier::where('nama', $request->dataSupplier1)->first();
        $products1 = $supplier1 ? Product::where('id_supplier', $supplier1->id)->get() : collect();

        $supplier2 = null;
        $products2 = collect();
        $supplier3 = null;
        $products3 = collect();

        if ($request->dataSupplier2) {
            $supplier2 = Supplier::where('nama', $request->dataSupplier2)->first();
            $products2 = $supplier2 ? Product::where('id_supplier', $supplier2->id)->get() : collect();
        }

        if ($request->dataSupplier3) {
            $supplier3 = Supplier::where('nama', $request->dataSupplier3)->first();
            $products3 = $supplier3 ? Product::where('id_supplier', $supplier3->id)->get() : collect();
        }

        $allProducts = $products1->concat($products2)->concat($products3);

        return view('preorder.get-barang', compact('title', 'supplier1', 'supplier2', 'supplier3', 'allProducts'));
    }

    public function processBarang(Request $request)
    {
        $title = 'List Barang';
        $products = $request->input('products');
        $supplier1 = Supplier::where('nama', $request->supplierName)->first();
        $getProducts = Product::whereIn('id', $products)->get();

        return view('preorder.list-barang', compact('title', 'getProducts', 'supplier1'));
    }

    public function orderBarang(Request $request)
    {
        // dd($request->all());
        $orders = $request->input('order');
        $prices = $request->input('price');
        $fieldTotals = $request->input('fieldtotal');
        $dataDetail = [];
        $count = count($orders);
        if (count($prices) !== $count || count($fieldTotals) !== $count) {
            return response()->json(['error' => 'Array lengths do not match.'], 400);
        }

        for ($i = 0; $i < $count; $i++) {
            $dataDetail[] = [
                'order' => (int) $orders[$i],
                'price' => (int) $prices[$i],
                'fieldtotal' => (int) $fieldTotals[$i]
            ];
        }
        $detail = response()->json($dataDetail);

        $supplier1 = Supplier::where('nama', $request->supplierName)->first();
        $data = [
            'nomor_po' => 'PO-000001-01',
            'id_supplier' => $supplier1->id,
            'ref' => 'A01',
            'detail' => json_encode($detail->original),
        ];
        // dd($supplier1->id, $dataDetail);

        Preorder::create($data);
        
        return Redirect::route('preorder.index-po')
            ->with('alert.status', '00')
            ->with('alert.message', "Add PreOrder Success!");
    }

    public function daftarPo()
    {
        $title = 'Daftar PreOrder';
        $preorders = Preorder::all();

        return view('preorder.daftar-po', compact('title', 'preorders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
