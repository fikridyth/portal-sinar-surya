<?php

namespace App\Http\Controllers;

use App\Models\Ppn;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
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

        return view('preorder.add-po.index', compact('title', 'suppliers'));
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
        $penjualan = Supplier::where('id', $supplier1->id)->first();
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

        $allProducts = $products1->concat($products2)->concat($products3)->sortBy(['nama', 'unit_jual']);

        return view('preorder.add-po.get-barang', compact('title', 'supplier1', 'supplier2', 'supplier3', 'allProducts', 'penjualan'));
    }

    public function processBarang(Request $request)
    {
        $title = 'List Barang';
        $products = $request->input('products');
        $supplier1 = Supplier::where('nama', $request->supplierName)->first();
        $getProducts = Product::whereIn('id', $products)->orderBy('nama', 'asc')->get();

        return view('preorder.add-po.list-barang', compact('title', 'getProducts', 'supplier1'));
    }

    public function orderBarang(Request $request)
    {
        $idSupplier = $request->input('id_supplier');
        $getSupplier = Supplier::whereIn('id', $idSupplier)->get()->keyBy('id');

        $ppnValue = Ppn::pluck('ppn')->first();
        $isPpn = $request->input('is_ppn');

        $nama = $request->input('nama');
        $stok = $request->input('stok');
        $unitJual = $request->input('unit_jual');
        $kode = $request->input('kode');
        $order = $request->input('order');
        $price = $request->input('price');
        $fieldTotal = $request->input('fieldtotal');
        $kodeSumber = $request->input('kode_sumber');
        $diskon1 = $request->input('diskon1');
        $diskon2 = $request->input('diskon2');
        $diskon3 = $request->input('diskon3');
        $dataDetail = [];
        $count = count($order);
        if (count($price) !== $count || count($fieldTotal) !== $count) {
            return response()->json(['error' => 'Array lengths do not match.'], 400);
        }

        for ($i = 0; $i < $count; $i++) {
            $supplierId = $idSupplier[$i];
            $ppnId = $isPpn[$i];
            $supplier = $getSupplier->get($supplierId);

            $dataDetail[] = [
                'kode' => $kode[$i],
                'nama' => $nama[$i],
                'unit_jual' => $unitJual[$i],
                'stok' => $stok[$i],
                'order' => (int) $order[$i],
                'price' => (int) $price[$i],
                'field_total' => (int) $fieldTotal[$i],
                'kode_sumber' => $kodeSumber[$i],
                'diskon1' => (int) $diskon1[$i],
                'diskon2' => (int) $diskon2[$i],
                'diskon3' => (int) $diskon3[$i],
                'penjualan_rata' => $supplier ? $supplier->penjualan_rata : null,
                'waktu_kunjungan' => $supplier ? $supplier->waktu_kunjungan : null,
                'stok_minimum' => $supplier ? $supplier->stok_minimum : null,
                'stok_maksimum' => $supplier ? $supplier->stok_maksimum : null,
                'is_ppn' => (int) $ppnId == 1 ? $ppnValue : 0
            ];
        }
        $detail = response()->json($dataDetail);

        $supplier1 = Supplier::where('nama', $request->supplierName)->first();
        $data = [
            'nomor_po' => 'PO-000001-01',
            'id_supplier' => $supplier1->id,
            'date_first' => Carbon::now()->format('Y-m-d'),
            'date_last' => Carbon::now()->addDays(16)->format('Y-m-d'),
            'detail' => json_encode($detail->original),
        ];
        // dd($supplier1->id, $dataDetail);

        Preorder::create($data);
        
        return Redirect::route('daftar-po')
            ->with('alert.status', '00')
            ->with('alert.message', "Add PreOrder Success!");
    }

    public function daftarPo()
    {
        $title = 'Daftar PreOrder';
        $preorders = Preorder::all();

        return view('preorder.detail-po.daftar-po', compact('title', 'preorders'));
    }

    public function showDaftarPo($id)
    {
        $title = 'Show PreOrder';
        $preorder = Preorder::find($id);

        return view('preorder.detail-po.show-daftar-po', compact('title', 'preorder'));
    }

    public function editDaftarPo($id)
    {
        $title = 'Edit PreOrder';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::all();

        return view('preorder.detail-po.edit-daftar-po', compact('title', 'preorder', 'ppn', 'products'));
    }

    public function updateEditedData(Request $request)
    {
        // dd($request->all());

        $preorder = Preorder::find($request->id);
        $getDetail = json_decode($preorder->detail, true);
        $getArray = $getDetail[$request->array];
        $getArray['order'] = $request->order;
        $getArray['price'] = $request->netto;
        $getArray['field_total'] = $request->total;
        $getDetail[$request->array] = $getArray;
        $preorder->detail = json_encode($getDetail);
        $preorder->save();

        return response()->json(['success' => true]);
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
