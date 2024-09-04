<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\Ppn;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $now = Carbon::now();
        $tanggalRange = [];
        for ($i = 0; $i <= $penjualan->penjualan_rata; $i++) {
            $tanggalRange[] = $now->copy()->subDays($i)->format('Y-m-d');
        }

        $stokMinimum = $penjualan->stok_minimum;
        $stokMaksimum = $penjualan->stok_maksimum;
        $rekapJualanByRange = Penjualan::whereIn('tanggal', $tanggalRange)->get()->groupBy('nama')
            ->map(function ($items) use ($stokMinimum, $stokMaksimum){
                $totalPerName = $items->sum('total'); // Hitung total
                $averagePerName = (float)number_format($totalPerName / count($items), 2); // Hitung total
                return [
                    'total' => $totalPerName,
                    'minimum' => $averagePerName * $stokMinimum,
                    'average' => $averagePerName,
                    'maximum' => $averagePerName * $stokMaksimum
                ];
            });

        // Convert to array if needed
        $getProductJual = $rekapJualanByRange->toArray();


        $getAllProducts = $products1->concat($products2)->concat($products3)->sortBy(['nama', 'unit_jual'])
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

        // Convert $allProducts to a collection indexed by 'nama'
        $allProductsByName = $getAllProducts->keyBy('nama');

        // Merge sales data with product data
        $allProducts = $getProductJual;

        foreach ($allProducts as $name => $salesData) {
            if ($allProductsByName->has($name)) {
                $product = $allProductsByName->get($name);
                $product->total = number_format($salesData['total'], 2);
                $product->minimum = number_format($salesData['minimum'], 2);
                $product->average = $salesData['average'];
                $product->maximum = number_format($salesData['maximum'], 2);
            }
        }

        // Convert to array if needed
        $allProducts = $allProductsByName->toArray();
        $previousUrl = url()->previous();
        // DD($allProducts);

        return view('preorder.add-po.get-barang', compact('title', 'supplier1', 'supplier2', 'supplier3', 'penjualan', 'allProducts', 'previousUrl'));
    }

    public function processBarang(Request $request)
    {
        $title = 'List Barang';

        $parameters = $request->request->all();

        $names = isset($parameters["name"]) ? (array) $parameters["name"] : [];
        $stocks = isset($parameters["stock"]) ? (array) $parameters["stock"] : [];
        $orders = isset($parameters["orderPo"]) ? (array) $parameters["orderPo"] : [];
        $harga = isset($parameters["harga"]) ? (array) $parameters["harga"] : [];

        // Prepare the formatted data
        $data = $result = [];
        $maxItems = max(count($names), count($stocks), count($orders), count($harga));

        // Prepare the formatted data and fetch products
        for ($i = 0; $i < count($names); $i++) {
            if ($orders[$i] !== null && $orders[$i] !== 0) { // Skip items with null orders
                $item = [
                    'nama' => $names[$i] ?? null,
                    'stok' => $stocks[$i] ?? null,
                    'order' => $orders[$i] ?? null,
                    'harga' => $harga[$i] ?? null
                ];

                // Fetch products based on the 'nama' field
                $getProducts = Product::where('nama', $item['nama'])->where('kode_sumber', '=', null)->orderBy('nama', 'asc')->first();

                // Combine product details with item data
                $results[] = [
                    'product' => $getProducts,
                    'details' => $item
                ];
            }
        }
        // dd($results);

        $supplier1 = Supplier::where('nama', $request->supplierName)->first();

        $explodeUrl = explode('/', $request->previous_url);
        $prevUrl = end($explodeUrl);

        if (empty($results)) {
            if ($prevUrl == 'preorder') {
                return redirect()->route('preorder.index')->with('alert.status', '99')->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
            } else {
                return redirect()->route('daftar-po')->with('alert.status', '99')->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
            }
        }

        return view('preorder.add-po.list-barang', compact('title', 'getProducts', 'supplier1', 'results'));
    }

    public function orderBarang(Request $request)
    {
        // dd($request->all());
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

        $totalHarga = 0;
        for ($i = 0; $i < $count; $i++) {
            $supplierId = $idSupplier[$i];
            $ppnId = $isPpn[$i];
            $supplier = $getSupplier->get($supplierId);

            $dataDetail[] = [
                'kode' => $kode[$i],
                'nama' => $nama[$i],
                'unit_jual' => $unitJual[$i],
                'stok' => $stok[$i],
                'order' => $order[$i],
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

            $totalHarga += (int) $fieldTotal[$i];
        }
        $jumlahHarga = (int) $totalHarga;
        $detail = response()->json($dataDetail);

        // get nomor po
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Preorder::max("nomor_po");
        if ($getLastPo) {
            $explodeLastPo = explode('-', $getLastPo);
            if ($explodeLastPo[1] == $dateNow) {
                $sequence = (int) $explodeLastPo[2] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        } 
        $getNomorPo = 'PO-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        // dd($getNomorPo);

        $supplier1 = Supplier::where('nama', $request->supplierName)->first();
        $data = [
            'nomor_po' => $getNomorPo,
            'id_supplier' => $supplier1->id,
            'date_first' => Carbon::now()->format('Y-m-d'),
            'date_last' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'detail' => json_encode($detail->original),
            'total_harga' => $jumlahHarga,
            'grand_total' => $jumlahHarga,
            'receive_type' => 'A',
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
        $supplierIds = Supplier::pluck('id')->toArray();

        // Fetch all preorders
        // $preorders = Preorder::all();
        $preorders = Preorder::where('receive_type', '!=', 'B')->get();

        // Initialize an array with supplier IDs as keys and empty arrays as default values
        $supplierPreorders = array_fill_keys($supplierIds, []);

        // Populate the array with preorders where applicable
        foreach ($preorders as $preorder) {
            if (array_key_exists($preorder->id_supplier, $supplierPreorders)) {
                // If a preorder exists for this supplier, append it to the array
                $supplierPreorders[$preorder->id_supplier][] = $preorder;
            }
        }

        // Create the final list of suppliers with their associated preorders
        $listPreorders = array_map(function($preorders, $id) {
            return [
                'supplier' => Supplier::where('id', $id)->first(),
                'preorders' => $preorders,
            ];
        }, $supplierPreorders, array_keys($supplierPreorders));
        // dd($listPreorders);

        // foreach ($listPreorders as $po) {
        // dd($po['preorder']['nomor_po']); }

        return view('preorder.detail-po.daftar-po', compact('title', 'listPreorders'));
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
        $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();

        return view('preorder.detail-po.edit-daftar-po', compact('title', 'preorder', 'ppn', 'products'));
    }

    public function storeNewData(Request $request)
    {
        // dd($request->all());
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
        $supplier = Supplier::find($preorder->id_supplier);
        $ppnValue = Ppn::pluck('ppn')->first();
        $detail = json_decode($preorder->detail, true);

        foreach ($request->input('data') as $item) {
            $product = Product::where('kode', $item['kode'])->first();
            // $supplier = Supplier::where('id', $product->id_supplier)->first();
            $getChild = Product::where('kode_sumber', $product->kode)->get();
            $totalStok = 0;
            foreach ($getChild as $child) {
                $convertChild = $child->stok / $child->konversi;
                $totalStok += $convertChild;
            }
            $newEntry = [
                'kode' => $product->kode,
                'nama' => $product->nama,
                'unit_jual' => $product->unit_jual,
                'stok' => number_format($product->stok + $totalStok, 2),
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

        $totalHarga = 0;
        foreach ($detail as $dtl) {
            $totalHarga += $dtl['field_total'];
        }
        $jumlahHarga = (int) $totalHarga;
        $preorder->update([
            'total_harga' => $jumlahHarga,
            'ppn_global' => $preorder->ppn_global,
            'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
        ]);

        return response()->json([
            'success' => true,
            'newTotalHarga' => $jumlahHarga
        ]);
    }

    public function updateEditedData(Request $request)
    {
        // dd($request->all());

        $getNetto = str_replace(',', '', $request->netto);
        $getTotal = str_replace(',', '', $request->total);

        $preorder = Preorder::find($request->id);
        $getDetail = json_decode($preorder->detail, true);
        $getArray = $getDetail[$request->array];
        $getArray['order'] = $request->order;
        $getArray['price'] = (int)$getNetto;
        $getArray['field_total'] = (int)$getTotal;
        $getDetail[$request->array] = $getArray;
        $preorder->detail = json_encode($getDetail);
        $preorder->save();

        $totalHarga = 0;
        foreach ($getDetail as $detail) {
            $totalHarga += $detail['field_total'];
        }
        $jumlahHarga = (int) $totalHarga;
        $preorder->update([
            'total_harga' => $jumlahHarga,
            'ppn_global' => $preorder->ppn_global,
            'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
        ]);

        return response()->json([
            'success' => true,
            'newTotalHarga' => number_format($jumlahHarga),
            'newGrandTotal' => number_format($jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100))
        ]);
    }

    public function destroyCurrentData(Request $request)
    {
        // dd($request->all());

        $preorder = Preorder::find($request->id);
        $getDetail = json_decode($preorder->detail, true);
        unset($getDetail[$request->array]);

        // Re-index the array to ensure sequential keys if needed
        $getDetail = array_values($getDetail);

        // Encode the array back to JSON and save it to the preorder record
        $preorder->detail = json_encode($getDetail);
        $preorder->save();

        // Calculate the new total harga
        $totalHarga = 0;
        foreach ($getDetail as $detail) {
            $totalHarga += $detail['field_total'] ?? 0; // Ensure field_total exists
        }

        $jumlahHarga = (int) $totalHarga;
        $preorder->update([
            'total_harga' => $jumlahHarga,
            'ppn_global' => $preorder->ppn_global,
            'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
        ]);

        return response()->json([
            'success' => true,
            'newTotalHarga' => number_format($jumlahHarga)
        ]);
    }

    public function setPpn(Request $request, $id)
    {
        $preorder = Preorder::find($id);
        $preorder->update([
            'total_harga' => $request->total_harga,
            'ppn_global' => $request->ppn_global,
            'grand_total' => $request->total_harga + ($request->total_harga * $request->ppn_global / 100),
        ]);
        return redirect()->back();
    }

    public function setDiskon(Request $request, $id)
    {
        $preorder = Preorder::find($id);
        // $getDetail = json_decode($preorder->detail, true);
        // foreach ($getDetail as &$item) {
        //     if (isset($item['diskon1'])) {
        //         $item['diskon1'] = $request->diskon_global;
        //     }
        // }
        // $preorder->detail = json_encode($getDetail);
        // $preorder->save();

        $preorder->update([
            'diskon_global' => $request->diskon_global,
        ]);
        return redirect()->back();
    }

    public function setBonus(Request $request, $id)
    {
        $preorder = Preorder::find($id);
        $getDetail = json_decode($preorder->detail, true);
        $getDetail[$request->no]['field_total'] = 0;
        $preorder->detail = json_encode($getDetail);
        $preorder->save();

        // Calculate the new total harga
        $totalHarga = 0;
        foreach ($getDetail as $detail) {
            $totalHarga += $detail['field_total'] ?? 0; // Ensure field_total exists
        }

        $jumlahHarga = (int) $totalHarga;
        $preorder->update([
            'total_harga' => $jumlahHarga,
            'ppn_global' => $preorder->ppn_global,
            'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
        ]);

        return redirect()->back();
    }

    public function receivePo($id)
    {
        $title = 'Receive PO';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();

        return view('preorder.receive-po.index', compact('title', 'preorder', 'ppn', 'products'));
    }

    public function createReceivePo()
    {
        $title = 'Create Receive PO';
        $preorder = new Preorder;
        $ppn = Ppn::pluck('ppn')->first();
        // $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();
        $products = Product::orderBy('nama', 'asc')->get();
        $suppliers = Supplier::all();

        // get nomor po
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Preorder::max("nomor_po");
        if ($getLastPo) $explodeLastPo = explode('-', $getLastPo);
        if ($explodeLastPo[1] == $dateNow) {
            $sequence = (int) $explodeLastPo[2] + 1;
        } else {
            (int) $sequence;
        }
        $getNomorPo = 'PO-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        return view('preorder.receive-po.create', compact('title', 'preorder', 'ppn', 'products', 'getNomorPo', 'suppliers'));
    }

    public function getPreorderData(Request $request)
    {
        $kode_supplier = $request->query('kode');
        $supplier = Supplier::where('nomor', $kode_supplier)->first();
        $preorder = Preorder::where('id_supplier', $supplier->id)->where('is_receive', '=', null)->get();
        // dd($kode_supplier, $supplier, $preorder);

        return response()->json($preorder);
    }

    public function storeReceiveData(Request $request)
    {
        // dd($request->all());
        $supplier = Supplier::where('nomor', $request->supplier)->first();

        $preorder = Preorder::create([
            'nomor_po' => $request->nomor_po,
            'id_supplier' => $supplier->id,
            'date_last' => $request->tanggal_po,
            'date_first' => $request->tanggal_po,
            'is_cetak' => 1,
            'receive_type' => 'B',
            'is_receive' => 1,
        ]);

        return redirect()->route('receive-po.create-detail', $preorder->id);
        // return redirect()->route('receive-po.create-detail', $id);
    }

    public function createDetailReceivePo($id)
    {
        $title = 'Detail Receive PreOrder';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();

        return view('preorder.receive-po.create-detail', compact('title', 'preorder', 'ppn', 'products'));
    }

    public function daftarReceivePo()
    {
        $title = 'Daftar Receive PreOrder';
        $preorders = Preorder::where('receive_type', 'B')->get();

        return view('preorder.receive-po.daftar-receive-po', compact('title', 'preorders'));
    }

    public function storePembayaran(Request $request)
    {
        $preorder = Preorder::find($request->id_po);
        $data = [
            'id_supplier' => $preorder->id_supplier,
            'date' => now()->format('Y-m-d'),
            'total' => $preorder->grand_total,
        ];
        
        Pembayaran::create($data);
        $preorder->update(['is_pay' => 1]);

        return Redirect::route('daftar-receive-po')
            ->with('alert.status', '00')
            ->with('alert.message', "Add Pembayaran Success!");
    }

    public function daftarHargaJualKecil()
    {
        $title = 'Daftar Harga Jual Kecil';
        $preorders = Preorder::where('receive_type', 'B')->get();

        return view('preorder.receive-po.daftar-receive-po', compact('title', 'preorders'));
    }
}
