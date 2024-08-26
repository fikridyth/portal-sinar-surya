<?php

namespace App\Http\Controllers;

use App\Models\Ppn;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
        $products1 = $supplier1 ? Product::where('id_supplier', $supplier1->id)->where('kode_sumber', '=', null)->get() : collect();

        $supplier2 = null;
        $products2 = collect();
        $supplier3 = null;
        $products3 = collect();

        if ($request->dataSupplier2) {
            $supplier2 = Supplier::where('nama', $request->dataSupplier2)->first();
            $products2 = $supplier2 ? Product::where('id_supplier', $supplier2->id)->where('kode_sumber', '=', null)->get() : collect();
        }

        if ($request->dataSupplier3) {
            $supplier3 = Supplier::where('nama', $request->dataSupplier3)->first();
            $products3 = $supplier3 ? Product::where('id_supplier', $supplier3->id)->where('kode_sumber', '=', null)->get() : collect();
        }

        $allProducts = $products1->concat($products2)->concat($products3)->sortBy(['nama', 'unit_jual'])
            ->map(function ($product) {
                // ambil semua stok child untuk digabungkan
                $getChild = Product::where('kode_sumber', $product->kode)->get();
                $totalStok = 0;
                foreach ($getChild as $child) {
                    $convertChild = $child->stok / $child->konversi;
                    $totalStok += $convertChild;
                }
                $product->stok = $product->stok + $totalStok;
                return $product;
            });
        // dd($allProducts);

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
        $suppliers = Supplier::all();
        $orderSupplier = $preorders->pluck('id_supplier')->toArray();
        // dd($orderSupplier);

        return view('preorder.detail-po.daftar-po', compact('title', 'preorders', 'suppliers', 'orderSupplier'));
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
        $products = Product::orderBy('nama', 'asc')->get();
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::all();

        return view('preorder.detail-po.edit-daftar-po', compact('title', 'preorder', 'ppn', 'products', 'products'));
    }

    public function storeNewData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.kode' => 'required|string',
            'data.*.order' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422);
        }

        // $request->validate([
        //     'data.*.kode' => 'required|string',
        //     'data.*.order' => 'required|numeric',
        // ]);
        
        $preorder = Preorder::find($request->id);
        $ppnValue = Ppn::pluck('ppn')->first();
        $detail = json_decode($preorder->detail, true);
        
        foreach ($request->input('data') as $item) {
            $product = Product::where('kode', $item['kode'])->first();
            $supplier = Supplier::where('id', $product->id_supplier)->first();
            $newEntry = [
                'kode' => $product->kode,
                'nama' => $product->nama,
                'unit_jual' => $product->unit_jual,
                'stok' => $product->stok,
                'order' => $item['order'],
                'price' => $product->harga_jual,
                'field_total' => $item['order'] * $product->harga_jual,
                'kode_sumber' => $product->kode_sumber,
                'diskon1' => $product->diskon1,
                'diskon2' => $product->diskon2,
                'diskon3' => $product->diskon3,
                'penjualan_rata' => $supplier->penjualan_rata,
                'waktu_kunjungan' => $supplier->waktu_kunjungan,
                'stok_minimum' => $supplier->stok_minimum,
                'stok_maksimum' => $supplier->stok_maksimum,
                'is_ppn' => $product->is_ppn == 1 ? $ppnValue : 0,
            ];
            
            // Add the new entry to the detail array
            $detail[] = $newEntry;
        }

        $preorder->detail = json_encode($detail);
        $preorder->save();

        return response()->json(['success' => true]);
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

    public function destroyCurrentData(Request $request)
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
