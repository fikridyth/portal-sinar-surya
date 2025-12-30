<?php

namespace App\Http\Controllers;

use App\DataTables\ReceiveDataTable;
use App\DataTables\ReceiveDoneDataTable;
use App\DataTables\SearchProductDataTable;
use App\Models\HargaSementara;
use App\Models\HargaSementaraPos;
use App\Models\Hutang;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Penjualan;
use App\Models\Ppn;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\ProductPos;
use App\Models\ProductStock;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        // $suppliers = Supplier::whereHas('products', function ($query) {
        //     $query->where('stok', '>', 0);
        // })->get();
        // dd(count($suppliers));
        $suppliers = Supplier::where('status', 1)->get();
        // dd(count($suppliers), count($suppliers2));

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

    public function getDataPenjualan(Request $request)
    {
        // dd($request->all());
        $title = 'Get Barang';
        $titleHeader = 'DAFTAR BARANG YANG HARUS DIPESAN';
        $supplier1 = Supplier::where('nama', $request->dataSupplier1)->first();
        $supplier2 = Supplier::where('nama', $request->dataSupplier2)->first();
        $supplier3 = Supplier::where('nama', $request->dataSupplier3)->first();
        // Ambil semua supplier dalam satu query
        $supplierNames = array_filter([
            $request->dataSupplier1,
            $request->dataSupplier2,
            $request->dataSupplier3
        ]);

        $suppliers = Supplier::whereIn('nama', $supplierNames)->get()->keyBy('nama');

        // Ambil semua ID supplier
        $supplierIds = $suppliers->pluck('id');

        // Ambil produk berdasarkan ID supplier secara batch
        $products = Product::whereIn('id_supplier', $supplierIds)
            ->whereNull('kode_sumber')
            ->where('stok', '>', 0)
            ->whereNotNull('harga_pokok')
            ->get()
            ->groupBy('id_supplier');

        // Proses stok anak dalam satu query
        $productCodes = $products->flatMap(function ($items) {
            return $items->pluck('kode');
        });
        $childProducts = Product::whereIn('kode_sumber', $productCodes)->get()->groupBy('kode_sumber');

        // Tambahkan stok anak ke produk
        foreach ($products as $supplierId => $supplierProducts) {
            foreach ($supplierProducts as $product) {
                if ($childProducts->has($product->kode)) {
                    $childStok = $childProducts->get($product->kode)->sum(function ($child) {
                        return $child->stok / $child->konversi;
                    });
                    $product->stok += $childStok;
                }
            }
        }

        $penjualan = Supplier::where('id', $supplier1->id)->first();
        if ($penjualan) {
            $now = Carbon::now();
            $tanggalRange = [];
            for ($i = 0; $i <= $penjualan->penjualan_rata; $i++) {
                $tanggalRange[] = $now->copy()->subDays($i)->format('Y-m-d');
            }

            $penjualanRata = $penjualan->penjualan_rata;
            $stokMinimum = $penjualan->stok_minimum;
            $stokMaksimum = $penjualan->stok_maksimum;

            $rekapJualanByRange = ProductStock::where('tipe', 'POS')->whereIn('tanggal', $tanggalRange)->orderBy('tanggal', 'desc')->get()->groupBy('kode')
                ->map(function ($items) use ($stokMinimum, $stokMaksimum, $penjualanRata) {
                    $totalPerName = $items->sum('total'); // Hitung total
                    $averagePerName = (float)number_format($totalPerName / $penjualanRata, 2); // Hitung total
                    return [
                        'total' => $totalPerName,
                        'minimum' => abs($averagePerName * $stokMinimum),
                        'average' => abs($averagePerName),
                        'maximum' => abs($averagePerName * $stokMaksimum)
                    ];
                });
            // dd($rekapJualanByRange, $tanggalRange, $penjualan);
        }

        // filter cari average lebih dari 0
        // $rekapJualanByRange = $rekapJualanByRange->filter(function ($item) {
        //     return $item['average'] > 0;
        // });

        // Gabungkan produk dari semua supplier
        $getAllProducts = $products->collapse()->sortBy(['nama', 'unit_jual']);
        $allProductsByName = $getAllProducts->keyBy('kode');
        // dd($products, $getAllProducts, $allProductsByName, $rekapJualanByRange);

        foreach ($allProductsByName as $name => $product) {
            // dd($allProductsByName, $rekapJualanByRange);
            // Jika nama tidak ada dalam rekap penjualan, beri nilai default 0
            $salesData = $rekapJualanByRange[$name] ?? [
                'total' => 0,
                'minimum' => 0,
                'average' => 0,
                'maximum' => 0
            ];

            if ($salesData['average'] == 0 || $product->stok > $salesData['maximum']) {
                unset($allProductsByName[$name]);
                continue; // Skip to the next iteration
            }

            $product->total = number_format($salesData['total'], 2);
            $product->minimum = number_format($salesData['minimum'], 2);
            $product->average = $salesData['average'];
            $product->maximum = number_format($salesData['maximum'], 2);
        }

        // Convert $allProductsByName to an array if needed
        // dd($allProductsByName);
        $allProducts = $allProductsByName->toArray();
        $previousUrl = url()->previous();
        if (count($allProducts) <= 0) {
            $allProducts = Product::where('id_supplier', $supplier1->id)->limit(100)->get();
        }
        // dd(count($allProducts));
        // dd($supplier1->nama);

        return view('preorder.add-po.get-data-penjualan', compact('title', 'titleHeader', 'supplier1', 'supplier2', 'supplier3', 'penjualan', 'allProducts', 'previousUrl'));
    }

    public function getListBarang(Request $request)
    {

        // dd($request->all());
        $title = 'Get Barang';
        $titleHeader = 'DAFTAR BARANG YANG HARUS DIPESAN';
        $supplier1 = Supplier::where('nama', $request->dataSupplier1)->first();
        $supplier2 = Supplier::where('nama', $request->dataSupplier2)->first();
        $supplier3 = Supplier::where('nama', $request->dataSupplier3)->first();
        // Ambil semua supplier dalam satu query
        $supplierNames = array_filter([
            $request->dataSupplier1,
            $request->dataSupplier2,
            $request->dataSupplier3
        ]);

        $suppliers = Supplier::whereIn('nama', $supplierNames)->get()->keyBy('nama');

        // Ambil semua ID supplier
        $supplierIds = $suppliers->pluck('id');

        // Ambil produk berdasarkan ID supplier secara batch
        // $products = Product::whereIn('id_supplier', $supplierIds)->whereNull('kode_sumber')->get();
        // dd(count($products));
        $products = Product::whereIn('id_supplier', $supplierIds)
            ->whereNull('kode_sumber')
            // ->where('stok', '>', 0)
            ->whereNotNull('harga_pokok')
            ->get()
            ->groupBy('id_supplier');

        // dd($products);

        // Proses stok anak dalam satu query
        $productCodes = $products->flatMap(function ($items) {
            return $items->pluck('kode');
        });
        $childProducts = Product::whereIn('kode_sumber', $productCodes)->get()->groupBy('kode_sumber');

        // Tambahkan stok anak ke produk
        foreach ($products as $supplierId => $supplierProducts) {
            foreach ($supplierProducts as $product) {
                if ($childProducts->has($product->kode)) {
                    $childStok = $childProducts->get($product->kode)->sum(function ($child) {
                        return $child->stok / $child->konversi;
                    });
                    $product->stok += $childStok;
                }
            }
        }

        $penjualan = Supplier::where('id', $supplier1->id)->first();
        if ($penjualan) {
            $now = Carbon::now();
            $tanggalRange = [];
            for ($i = 0; $i <= $penjualan->penjualan_rata; $i++) {
                $tanggalRange[] = $now->copy()->subDays($i)->format('Y-m-d');
            }

            $penjualanRata = $request->penjualan_rata ?? $penjualan->penjualan_rata;
            $stokMinimum = $request->stok_minimum ?? $penjualan->stok_minimum;
            $stokMaksimum = $request->stok_maksimum ?? $penjualan->stok_maksimum;
            $waktuKunjungan = $request->waktu_kunjungan ?? $penjualan->waktu_kunjungan;

            $rekapJualanByRange = ProductStock::where('tipe', 'POS')->whereIn('tanggal', $tanggalRange)->orderBy('tanggal', 'desc')->get()->groupBy('kode')
                ->map(function ($items) use ($stokMinimum, $stokMaksimum, $penjualanRata) {
                    $totalPerName = $items->sum('total'); // Hitung total
                    $averagePerName = (float)number_format($totalPerName / $penjualanRata, 2); // Hitung total
                    return [
                        'total' => $totalPerName,
                        'minimum' => abs($averagePerName * $stokMinimum),
                        'average' => abs($averagePerName),
                        'maximum' => abs($averagePerName * $stokMaksimum)
                    ];
                });
            // dd($rekapJualanByRange, $tanggalRange, $penjualan);
        }

        // filter cari average lebih dari 0
        // $rekapJualanByRange = $rekapJualanByRange->filter(function ($item) {
        //     return $item['average'] > 0;
        // });

        // Gabungkan produk dari semua supplier
        $getAllProducts = $products->collapse()->sortBy(['nama', 'unit_jual']);
        $allProductsByName = $getAllProducts->keyBy('kode');
        // dd($products, $getAllProducts, $allProductsByName, $rekapJualanByRange);

        foreach ($allProductsByName as $name => $product) {
            // dd($allProductsByName, $rekapJualanByRange);
            // Jika nama tidak ada dalam rekap penjualan, beri nilai default 0
            $salesData = $rekapJualanByRange[$name] ?? [
                'total' => 0,
                'minimum' => 0,
                'average' => 0,
                'maximum' => 0
            ];

            // if ($salesData['average'] == 0 || $product->stok > $salesData['maximum']) {
            //     unset($allProductsByName[$name]);
            //     continue; // Skip to the next iteration
            // }

            $product->total = number_format($salesData['total'], 2);
            $product->minimum = number_format($salesData['minimum'], 2);
            $product->average = $salesData['average'];
            $product->maximum = number_format($salesData['maximum'], 2);
        }

        // Convert $allProductsByName to an array if needed
        // dd($allProductsByName);
        $allProducts = $allProductsByName->toArray();
        $previousUrl = url()->previous();
        // dd(count($allProducts));

        $getChildProducts = Product::whereIn('id_supplier', $supplierIds)
            ->whereNotNull('kode_sumber')
            ->whereNotNull('harga_pokok')
            ->get();
        // dd(count($getChildProducts));

        return view('preorder.add-po.get-barang', compact('title', 'titleHeader', 'supplier1', 'supplier2', 'supplier3', 'penjualan', 'allProducts', 'previousUrl', 'penjualanRata', 'stokMinimum', 'stokMaksimum', 'waktuKunjungan', 'getChildProducts'));
    }

    public function getProductsByKodePo($kode)
    {
        $allProducts = Product::where('kode_sumber', $kode)->get();

        // Ekstrak angka setelah 'P' dan urutkan berdasarkan angka tersebut secara menurun
        $sortedProducts = $allProducts->sort(function ($a, $b) {
            // Ekstrak angka setelah 'P' dari kode_sumber
            $aNumber = intval(substr(strrchr($a->unit_jual, 'P'), 1));
            $bNumber = intval(substr(strrchr($b->unit_jual, 'P'), 1));

            // Urutkan secara menurun
            return $aNumber - $bNumber;
        });

        // Jika Anda ingin mengubah koleksi menjadi array
        $products = $sortedProducts->values()->all();
        // dd($products);
        return response()->json(['products' => $products]);
    }

    public function processBarang(Request $request)
    {
        $title = 'List Barang';
        $titleHeader = 'DAFTAR BARANG YANG HARUS DIPESAN';

        // Ambil previous URL
        $explodeUrl = explode('/', $request->previous_url);
        $prevUrl = end($explodeUrl);

        // dd($request->all());

        // Ambil semua input
        $names = (array) $request->name;
        $stocks = (array) $request->stock;
        $prices = (array) $request->harga;
        $orders = (array) $request->orderPo;

        if (empty($names)) {
            return redirect()->back()
                ->with('alert.status', '99')
                ->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
        }

        $results = [];

        foreach ($orders as $key => $orderQty) {
            // Skip jika order 0/null
            if ($orderQty === null || $orderQty === '0' || $orderQty === 0) continue;

            // Ambil data dari masing-masing array
            $itemNama  = $names[$key] ?? null;
            $itemStock = $stocks[$key] ?? null;
            $itemHarga = $prices[$key] ?? null;

            if (!$itemNama) continue;

            // Pisahkan nama/unit dari string
            $splitNama = explode('/', $itemNama);
            $baseNama = $splitNama[0] ?? null;
            $unit     = $splitNama[1] ?? null;

            // Ambil product dari DB
            $product = Product::where('nama', $baseNama)
                ->where('unit_jual', $unit)
                ->orderBy('nama', 'asc')
                ->first();

            // Simpan ke results
            $results[] = [
                'product' => $product,
                'details' => [
                    'nama'  => $itemNama,
                    'stok'  => $itemStock,
                    'order' => $orderQty,
                    'harga' => $itemHarga,
                    'type'  => is_numeric($key) ? 'parent' : 'child',
                    'key'   => $key
                ]
            ];
        }

        if (empty($results)) {
            return redirect()->route('preorder.index')
                ->with('alert.status', '99')
                ->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
        }

        // Update data supplier
        $supplier1 = Supplier::find($request->supplierId);
        $supplier1->update([
            'penjualan_rata' => $request->penjualan_rata,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'stok_minimum' => $request->stok_minimum,
            'stok_maksimum' => $request->stok_maksimum,
        ]);

        return view('preorder.add-po.list-barang', compact(
            'title',
            'titleHeader',
            'supplier1',
            'results'
        ));
    }

    public function cetakTambahPo(Request $request)
    {
        // dd($request->all());
        $title = 'Cetak PreOrder';
        $idSupplier = $request->input('id_supplier');
        if (!$idSupplier) {
            return redirect()->route('daftar-po')
                ->with('alert.status', '99')
                ->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
        }
        $getSupplier = Supplier::whereIn('id', $idSupplier)->get()->keyBy('id');
        $nama = $request->input('nama');
        $stok = $request->input('stok');
        $unitJual = $request->input('unit_jual');
        $kode = $request->input('kode');
        $order = $request->input('order');
        $price = $request->input('price');
        $oldPrice = $request->input('old_price');
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
            $supplier = $getSupplier->get($supplierId);

            $dataDetail[] = [
                'kode' => $kode[$i],
                'nama' => $nama[$i],
                'unit_jual' => $unitJual[$i],
                'stok' => $stok[$i],
                'order' => $order[$i],
                'price' => (int) $price[$i],
                'old_price' => (int) $oldPrice[$i],
                'field_total' => (int) $fieldTotal[$i],
                'kode_sumber' => $kodeSumber[$i],
                'diskon1' => (int) $diskon1[$i],
                'diskon2' => (int) $diskon2[$i],
                'diskon3' => (int) $diskon3[$i],
                'penjualan_rata' => $supplier ? $supplier->penjualan_rata : null,
                'waktu_kunjungan' => $supplier ? $supplier->waktu_kunjungan : null,
                'stok_minimum' => $supplier ? $supplier->stok_minimum : null,
                'stok_maksimum' => $supplier ? $supplier->stok_maksimum : null,
                'is_ppn' => (int) 0
            ];

            $totalHarga += (int) $fieldTotal[$i];
        }
        $jumlahHarga = (int) $totalHarga;
        $results = $dataDetail;
        // dd($results);

        return view('preorder.add-po.cetak-add-po', compact('title', 'results'));
    }

    public function orderBarang(Request $request)
    {
        // dd($request->all());
        $idSupplier = $request->input('id_supplier');
        if (!$idSupplier) {
            return redirect()->route('daftar-po')
                ->with('alert.status', '99')
                ->with('alert.message', 'PILIH BARANG YANG AKAN DI ORDER');
        }
        $getSupplier = Supplier::whereIn('id', $idSupplier)->get()->keyBy('id');
        $nama = $request->input('nama');
        $stok = $request->input('stok');
        $unitJual = $request->input('unit_jual');
        $kode = $request->input('kode');
        $order = $request->input('order');
        $price = $request->input('price');
        $oldPrice = $request->input('old_price');
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
            $supplier = $getSupplier->get($supplierId);

            $dataDetail[] = [
                'kode' => $kode[$i],
                'nama' => $nama[$i],
                'unit_jual' => $unitJual[$i],
                'stok' => $stok[$i],
                'order' => $order[$i],
                'price' => (int) $price[$i],
                'old_price' => (int) $oldPrice[$i],
                'field_total' => (int) $fieldTotal[$i],
                'kode_sumber' => $kodeSumber[$i],
                'diskon1' => (int) $diskon1[$i],
                'diskon2' => (int) $diskon2[$i],
                'diskon3' => (int) $diskon3[$i],
                'penjualan_rata' => $supplier ? $supplier->penjualan_rata : null,
                'waktu_kunjungan' => $supplier ? $supplier->waktu_kunjungan : null,
                'stok_minimum' => $supplier ? $supplier->stok_minimum : null,
                'stok_maksimum' => $supplier ? $supplier->stok_maksimum : null,
                'is_ppn' => (int) 0
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

        $data = [
            'nomor_po' => $getNomorPo,
            'id_supplier' => $request->supplierId,
            'date_first' => Carbon::now()->format('Y-m-d'),
            'date_last' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'detail' => json_encode($detail->original),
            'total_harga' => $jumlahHarga,
            'grand_total' => $jumlahHarga,
            'receive_type' => 'A',
            'is_cancel' => 1,
        ];
        // dd($supplier1->id, $dataDetail);

        Preorder::create($data);
        
        $supplier1 = Supplier::find($request->supplierId);
        $supplier1->update([
            'penjualan_rata' => $request->penjualan_rata,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'stok_minimum' => $request->stok_minimum,
            'stok_maksimum' => $request->stok_maksimum,
        ]);

        return Redirect::route('daftar-po')
            ->with('alert.status', '00')
            ->with('alert.message', "Add PreOrder Success!");
    }

    public function daftarPo()
    {
        $title = 'Daftar PreOrder';
        $titleHeader = 'DAFTAR BARANG YANG HARUS DIPESAN';
        // $supplierIds = Supplier::whereHas('products', function ($query) {
        //     $query->where('stok', '>', 0);
        // })->pluck('id')->toArray();

        $suppliers = Supplier::where('status', 1)->where('waktu_kunjungan', '!=', 0)->where('hari', '!=', 'MINGGU')->get();
        $supplierIds = Supplier::where('status', 1)->where('waktu_kunjungan', '!=', 0)->where('hari', '!=', 'MINGGU')->pluck('id')->toArray();
        // dd(count($suppliers));

        // Fetch all preorders
        // $preorders = Preorder::all();
        $preorders = Preorder::where('receive_type', '!=', 'B')->whereNull('is_receive')->get();

        // Initialize an array with supplier IDs as keys and empty arrays as default values
        $supplierPreorders = array_fill_keys($supplierIds, []);

        // Populate the array with preorders where applicable
        $currentDate = now()->toDateString();
        // dd($currentDate);
        foreach ($preorders as $preorder) {
            if (array_key_exists($preorder->id_supplier, $supplierPreorders) && $preorder->date_last > $currentDate && $preorder->is_receive == null) {
                // If a preorder exists for this supplier, append it to the array
                $supplierPreorders[$preorder->id_supplier][] = $preorder;
            }
        }

        // Create the final list of suppliers with their associated preorders
        $listPreorders = array_map(function ($preorders, $id) {
            return [
                'supplier' => Supplier::where('id', $id)->first(),
                'preorders' => $preorders,
            ];
        }, $supplierPreorders, array_keys($supplierPreorders));
        // dd($listPreorders);

        // foreach ($listPreorders as $po) {
        // dd($po['preorder']['nomor_po']); }

        return view('preorder.detail-po.daftar-po', compact('title', 'titleHeader', 'suppliers', 'listPreorders'));
    }

    public function showDaftarPo($id)
    {
        $id = dekrip($id);
        $title = 'Show PreOrder';
        $preorder = Preorder::find($id);

        return view('preorder.detail-po.show-daftar-po', compact('title', 'preorder'));
    }

    public function cetakDaftarPo($id)
    {
        $id = dekrip($id);
        $title = 'Cetak PreOrder';
        $preorder = Preorder::find($id);
        $preorder->update(['is_cetak' => $preorder->is_cetak + 1]);

        return view('preorder.detail-po.cetak-daftar-po', compact('title', 'preorder'));
    }

    public function destroyPo($id)
    {
        $id = dekrip($id);
        Preorder::findOrFail($id)->delete();

        return Redirect::route('daftar-po')
            ->with('alert.status', '00')
            ->with('alert.message', "PreOrder Berhasil Dihapus!");
    }

    public function editDaftarPo($id)
    {
        $id = dekrip($id);
        $title = 'Edit PreOrder';
        $titleHeader = 'DAFTAR BARANG YANG HARUS DIPESAN';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        // $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();
        $products = Product::where('status', 1)->where('stok', '>', 0)->orderBy('nama')->get();
        // dd(count($products));

        return view('preorder.detail-po.edit-daftar-po', compact('title', 'titleHeader', 'preorder', 'ppn', 'products'));
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
                'price' => $product->harga_pokok,
                'field_total' => $item['order'] * $product->harga_pokok,
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

    public function storeNewReceive(Request $request)
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
        $getPayment = Hutang::where('nomor_po', $preorder->nomor_po)->first();

        $supplier = Supplier::find($preorder->id_supplier);
        $ppnValue = Ppn::pluck('ppn')->first();
        $detail = json_decode($preorder->detail, true);

        foreach ($request->input('data') as $item) {
            $product = Product::where('kode', $item['kode'])->first();
            $product->update([
                'harga_lama' => $product->harga_pokok,
                'harga_pokok' => $item['price']
            ]);
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
                'price' => $item['price'],
                'field_total' => $item['order'] * $item['price'],
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
        if ($getPayment) {
            $getPayment->update([
                'total' => $jumlahHarga ?? 0,
                'ppn' => $preorder->ppn_global ?? 0,
                'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100) ?? 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'newTotalHarga' => $jumlahHarga
        ]);
    }

    public function updateEditedData(Request $request)
    {
        // dd($request->all());

        $getNetto = str_replace(',', '', $request->netto);
        // $getTotal = str_replace(',', '', $request->total);
        $getoldPrice = str_replace(',', '', $request->oldPrice);

        $preorder = Preorder::find($request->id);
        $getDetail = json_decode($preorder->detail, true);
        $getArray = $getDetail[$request->array];
        $getArray['order'] = $request->order ?? $getArray['order'];
        $getArray['old_price'] = (int)$getoldPrice;
        $getArray['price'] = (int)$getNetto;
        $getArray['field_total'] = (int)$getNetto * ($request->order ?? $getArray['order']);
        $getArray['diskon1'] = $request->diskon1;
        $getArray['diskon2'] = $request->diskon2;
        $getArray['diskon3'] = $request->diskon3;
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

        $product = Product::where('kode', $request->kode)->first();
        $product->update([
            'harga_lama' => $product->harga_pokok,
            'harga_pokok' => $request->price,
            'diskon1' => $request->diskon1,
            'diskon2' => $request->diskon2,
            'diskon3' => $request->diskon3,
            'is_transfer' => null
        ]);

        return response()->json([
            'success' => true,
            'newTotalHarga' => number_format($jumlahHarga),
            'newGrandTotal' => number_format($jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100))
        ]);
    }

    public function updateReceiveData(Request $request)
    {
        // dd($request->all());
        $getNetto = str_replace(',', '', $request->netto);
        // $getTotal = str_replace(',', '', $request->total);
        $getoldPrice = str_replace(',', '', $request->oldPrice);

        $preorder = Preorder::find($request->id);
        $getPayment = Hutang::where('nomor_po', $preorder->nomor_po)->first();

        // update detail
        $getDetail = json_decode($preorder->detail, true);
        $getArray = $getDetail[$request->array];
        $getArray['order'] = $request->order ?? $getArray['order'];
        $getArray['old_price'] = (int)$getoldPrice;
        $getArray['price'] = (int)$getNetto;
        $getArray['field_total'] = (int)$getNetto * ($request->order ?? $getArray['order']);
        $getArray['diskon1'] = $request->diskon1;
        $getArray['diskon2'] = $request->diskon2;
        $getArray['diskon3'] = $request->diskon3;
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
        if ($getPayment) {
            $getPayment->update([
                'total' => $jumlahHarga ?? 0,
                'ppn' => $preorder->ppn_global ?? 0,
                'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100) ?? 0,
            ]);
        }
        $product = Product::where('kode', $request->kode)->first();
        $product->update([
            'harga_lama' => $product->harga_pokok,
            'harga_pokok' => $request->price,
            'diskon1' => $request->diskon1,
            'diskon2' => $request->diskon2,
            'diskon3' => $request->diskon3,
            'is_transfer' => null
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

        if ($preorder->receive_type == 'B') {
            Hutang::where('nomor_receive', $preorder->nomor_receive)->update([
                'total' => $jumlahHarga,
                'ppn' => $preorder->ppn_global,
                'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
            ]);
        }

        return response()->json([
            'success' => true,
            'newTotalHarga' => number_format($jumlahHarga)
        ]);
    }

    public function setPpn(Request $request, $id)
    {
        // dd($request->all(), $id);
        if ($request->ppn == 'on') {
            $valuePpn = 11;
        } else {
            $valuePpn = null;
        }
        $preorder = Preorder::find($id);
        $preorder->update([
            'total_harga' => $request->total_harga,
            'ppn_global' => $valuePpn ?? 0,
            'grand_total' => $request->total_harga + ($request->total_harga * $valuePpn / 100),
        ]);
        return redirect()->back();
    }

    public function setPpnReceive(Request $request, $id)
    {
        // dd($request->all(), $id);
        if ($request->ppn == 'on') {
            $valuePpn = 11;
        } else {
            $valuePpn = null;
        }
        $preorder = Preorder::find($id);
        $getPayment = Hutang::where('nomor_po', $preorder->nomor_po)->first();
        $preorder->update([
            'total_harga' => $request->total_harga,
            'ppn_global' => $valuePpn ?? 0,
            'grand_total' => $request->total_harga + ($request->total_harga * $valuePpn / 100),
        ]);
        if ($getPayment) {
            $getPayment->update([
                'total' => $request->total_harga ?? 0,
                'ppn' => $preorder->ppn_global ?? 0,
                'grand_total' => $request->total_harga + ($request->total_harga * $valuePpn / 100) ?? 0,
            ]);
        }
        return redirect()->back();
    }

    public function cancelReceive($id)
    {
        $id = dekrip($id);
        $preorder = Preorder::find($id);

        if ($preorder->is_persetujuan) {
            return Redirect::route('receive-po.done-detail', enkrip($preorder->id))->with('alert.status', '99')->with('alert.message', "HAPUS DATA PERSETUJUAN TERLEBIH DAHULU!");
        }

        $detail = json_decode($preorder->detail, true);
        foreach ($detail as $data) {
            $product = Product::where('kode', $data['kode'])->first();
            $product->update([
                'stok' => (int)$product->stok - $data['order'],
                'is_transfer' => null,
            ]);
        }
        // dd($preorder);

        // Cek apakah ada data di ProductStock
        if (ProductStock::where('tipe', $preorder->nomor_receive)->exists()) {
            ProductStock::where('tipe', $preorder->nomor_receive)->delete();
        }

        // Cek apakah ada data di Hutang
        $hutang = Hutang::where('nomor_receive', $preorder->nomor_receive)->first();
        if ($hutang) {
            $hutang->update(['is_cancel' => 1]);
        }

        $preorder->update([
            'is_cancel' => 1,
            'is_proses' => null
        ]);

        return Redirect::route('daftar-receive-done-po')->with('alert.status', '0')->with('alert.message', "DATA RECEIVE BERHASIL DI CANCEL!");
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
            'diskon_global' => $request->diskon_global ?? 0,
        ]);
        return redirect()->back();
    }

    public function setBonus(Request $request, $id)
    {
        // dd($request->all());
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

        if ($request->receive_type == 'B') {
            Hutang::where('nomor_receive', $request->nomor_receive)->update([
                'total' => $jumlahHarga,
                'ppn' => $preorder->ppn_global,
                'grand_total' => $jumlahHarga + ($jumlahHarga * $preorder->ppn_global / 100),
            ]);
        }

        return redirect()->back();
    }

    public function receivePo($id)
    {
        $id = dekrip($id);
        $title = 'Receive PO';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();

        return view('preorder.receive-po.index', compact('title', 'preorder', 'ppn', 'products'));
    }

    public function createReceivePo()
    {
        $title = 'Create Receive PO';
        $titleHeader = 'PENERIMAAN BARANG - PURCHASE ORDER';
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
        if (($explodeLastPo[1] ?? $dateNow) == $dateNow) {
            $sequence = (int) ($explodeLastPo[2] ?? 0) + 1;
        } else {
            (int) $sequence;
        }
        $getNomorPo = 'PO-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        return view('preorder.receive-po.create', compact('title', 'titleHeader', 'preorder', 'ppn', 'products', 'getNomorPo', 'suppliers'));
    }

    public function addProductReceivePo(SearchProductDataTable $dataTable, $id)
    {
        $id = dekrip($id);
        $title = 'Add Product';
        $titleHeader = 'PEMESANAN BARANG - PURCHASE ORDER';

        return $dataTable->render('preorder.add-product.index', compact('title', 'id', 'titleHeader'));
    }

    public function updateProductReceivePo(Request $request, $id)
    {
        $id = dekrip($id);
        $ppnValue = Ppn::pluck('ppn')->first();
        $preorder = Preorder::find($id);
        $supplier = Supplier::find($preorder->id_supplier);
        $detail = json_decode($preorder->detail, true);

        $total = 0;
        $selectedIds = $request->input('selected_ids', []);
        foreach ($selectedIds as $sId) {
            $product = Product::find($sId);

            $detail[] = [
                'kode' => $product->kode,
                'nama' => $product->nama,
                'unit_jual' => $product->unit_jual,
                'stok' => $product->stok,
                'order' => 1,
                'price' => $product->harga_pokok,
                'field_total' => 1 * $product->harga_pokok,
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
            $total += $product->harga_pokok;
        }
        $preorder->detail = json_encode($detail);
        $preorder->total_harga = $preorder->total_harga + $total;
        $preorder->grand_total = $preorder->grand_total + $total;
        $preorder->save();

        $hutang = Hutang::where('nomor_po', $preorder->nomor_po)->first();
        if ($hutang) {
            $hutang->update([
                'total' => $hutang->total + $total,
                'grand_total' => $hutang->grand_total + $total
            ]);
        }

        if ($preorder->receive_type == 'A') {
            return redirect()->route('daftar-po.edit', enkrip($id));
        } else {
            return redirect()->route('receive-po.create-detail', enkrip($id));
        }
    }

    public function getPreorderData(Request $request)
    {
        $kode_supplier = $request->query('kode');
        $supplier = Supplier::where('nomor', $kode_supplier)->first();
        $currentDate = now()->toDateString();
        $preorder = Preorder::where('id_supplier', $supplier->id)->where('is_receive', '=', null)->where('date_last', '>', $currentDate)->get();
        // dd($kode_supplier, $supplier, $preorder);

        return response()->json($preorder);
    }

    public function storeReceiveData(Request $request)
    {
        // dd($request->all());
        $supplier = Supplier::where('nomor', $request->supplier)->first();
        $nomor_receive = str_replace('PO', 'RP', $request->nomor_po);

        if ($request->old_nomor_po !== null) {
            $oldPo = Preorder::where('nomor_po', $request->old_nomor_po)->first();

            $preorder = Preorder::create([
                'nomor_po' => $request->nomor_po,
                'nomor_receive' => $nomor_receive,
                'id_supplier' => $supplier->id,
                'detail' => $request->details,
                'date_last' => $request->tanggal_po,
                'date_first' => $request->tanggal_po,
                'receive_type' => 'B',
                'is_receive' => 1,
                'total_harga' => $oldPo->total_harga,
                'ppn_global' => $oldPo->ppn_global,
                'grand_total' => $oldPo->grand_total,
                'diskon_global' => $oldPo->diskon_global,
                'is_cancel' => 1
            ]);

            $oldPo->update(['is_receive' => 1]);
        } else {
            $preorder = Preorder::create([
                'nomor_po' => $request->nomor_po,
                'nomor_receive' => $nomor_receive,
                'id_supplier' => $supplier->id,
                'detail' => $request->details,
                'date_last' => $request->tanggal_po,
                'date_first' => $request->tanggal_po,
                'receive_type' => 'B',
                'is_receive' => 1,
                'is_cancel' => 1
            ]);
        }

        $data = [
            'id_supplier' => $preorder->id_supplier,
            'nomor_po' => $preorder->nomor_po,
            'nomor_receive' => $nomor_receive,
            'date' => now()->format('Y-m-d'),
            'total' => $preorder->total_harga ?? 0,
            'ppn' => $preorder->ppn_global ?? 0,
            'grand_total' => $preorder->grand_total ?? 0,
        ];

        Hutang::create($data);

        return redirect()->route('receive-po.create-detail', enkrip($preorder->id));
        // return redirect()->route('receive-po.create-detail', $id);
    }

    public function destroyReceiveData(Request $request)
    {
        $nomorPos = $request->input('nomor_po');

        // Perform delete operation
        foreach ($nomorPos as $nomorPo) {
            // Assuming you have a model to handle PO records
            Preorder::where('nomor_po', $nomorPo)->delete();
        }

        return response()->json(['message' => 'PO deleted successfully.']);
    }

    public function createDetailReceivePo($id)
    {
        $id = dekrip($id);
        $title = 'Detail Receive PreOrder';
        $titleHeader = 'PENERIMAAN BARANG - PURCHASE ORDER';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        $products = Product::where('status', 1)->where('stok', '>', 0)->orderBy('nama')->get();
        $suppliers = Supplier::where('status', 1)->whereNot('id', $preorder->supplier->id)->get();

        if ($preorder->receive_type == 'A') {
            $preorder->update([
                'receive_type' => 'B',
                'is_receive' => 1,
                'nomor_receive' => str_replace('PO', 'RP', $preorder->nomor_po)
            ]);

            $data = [
                'id_supplier' => $preorder->id_supplier,
                'nomor_po' => $preorder->nomor_po,
                'nomor_receive' => str_replace('PO', 'RP', $preorder->nomor_po),
                'date' => now()->format('Y-m-d'),
                'total' => $preorder->total_harga ?? 0,
                'ppn' => $preorder->ppn_global ?? 0,
                'grand_total' => $preorder->grand_total ?? 0,
            ];
            Hutang::create($data);
        }

        return view('preorder.receive-po.create-detail', compact('title', 'titleHeader', 'preorder', 'ppn', 'products', 'suppliers'));
    }

    public function doneDetailReceivePo($id)
    {
        $id = dekrip($id);
        $title = 'Detail Receive PreOrder';
        $titleHeader = 'PENERIMAAN BARANG - RECEIVE';
        $preorder = Preorder::find($id);
        $ppn = Ppn::pluck('ppn')->first();
        // $products = Product::where('kode_sumber', '=', null)->orderBy('nama', 'asc')->get();
        $products = Product::where('status', 1)->where('stok', '>', 0)->orderBy('nama')->get();
        // dd(count($products));

        return view('preorder.receive-po.done-detail', compact('title', 'titleHeader', 'preorder', 'ppn', 'products'));
    }

    public function previewDataReceivePo($id)
    {
        $id = dekrip($id);
        $title = 'Preview Receive PO';
        $titleHeader = 'PENERIMAAN BARANG - PURCHASE ORDER';
        $preorder = Preorder::find($id);
        $detail = json_decode($preorder->detail, true);
        usort($detail, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return view('preorder.receive-po.preview-data', compact('title', 'titleHeader', 'preorder', 'detail'));
    }

    public function updateReceivePo($id)
    {
        // dd($id);
        $id = dekrip($id);
        $preorder = Preorder::find($id);
        $detail = json_decode($preorder->detail, true);
        foreach ($detail as $data) {
            $product = Product::where('kode', $data['kode'])->first();
            $childProduct = Product::where('kode_sumber', $data['kode'])->exists();
            if ($childProduct); {
                $listProduct = Product::where('kode_sumber', $data['kode'])->get();
                foreach ($listProduct as $lp) {
                    $lp->update([
                        'harga_lama' => $lp->harga_pokok,
                        'harga_pokok' => $data['price'] / $lp->konversi
                    ]);
                }
            }
            $product->update([
                // 'harga_lama' => $data['price'],
                'stok' => $data['order'] + (int)$product->stok,
                'is_transfer' => null,
            ]);

            // update product stock
            ProductStock::create([
                'tipe' => $preorder->nomor_receive,
                'tanggal' => now()->format('Y-m-d'),
                'total' => $data['order'],
                'kode' => $product->kode,
                'stok' => (int)$product->stok,
                'unit_jual' => str_replace('P', '', $product->unit_jual)
            ]);
        }

        $hutang = Hutang::where('nomor_receive', $preorder->nomor_receive)->first();
        if ($hutang) {
            $hutang->update([
                'is_proses' => 1,
                'is_cancel' => null
            ]);
        }
        $preorder->update([
            'is_proses' => 1,
            'is_cancel' => null
        ]);

        return Redirect::route('daftar-receive-done-po', enkrip($preorder->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Data Receive Berhasil Di Proses!");
    }

    public function cetakReceivePo($id)
    {
        $id = dekrip($id);
        $title = 'Cetak Receive PO';
        $preorder = Preorder::find($id);
        $detail = json_decode($preorder->detail, true);
        usort($detail, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return view('preorder.receive-po.cetak-data', compact('title', 'preorder', 'detail'));
    }

    public function cetakReceivePoDoneData($id)
    {
        $id = dekrip($id);
        $title = 'Cetak Receive PO';
        $preorder = Preorder::find($id);
        $detail = json_decode($preorder->detail, true);
        usort($detail, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return view('preorder.receive-po.cetak-done-data', compact('title', 'preorder', 'detail'));
    }

    public function daftarReceivePo(ReceiveDataTable $dataTable)
    {
        $title = 'Daftar Receive PreOrder';
        $titleHeader = 'PENERIMAAN BARANG - PURCHASE ORDER';
        // $preorders = Preorder::where('receive_type', 'B')->get();

        // return view('preorder.receive-po.daftar-receive-po', compact('title', 'preorders'));
        return $dataTable->render('preorder.receive-po.daftar-receive-po', compact('title', 'titleHeader'));
    }

    public function daftarReceiveDonePo(ReceiveDoneDataTable $dataTable)
    {
        $title = 'Daftar Receive Done';
        $titleHeader = 'PENERIMAAN BARANG - RECEIVE';
        // $preorders = Preorder::where('receive_type', 'B')->get();

        // return view('preorder.receive-po.daftar-receive-po', compact('title', 'preorders'));
        return $dataTable->render('preorder.receive-po.daftar-receive-done-po', compact('title', 'titleHeader'));
    }

    // public function storePembayaran(Request $request)
    // {
    //     $preorder = Preorder::find($request->id_po);
    //     $data = [
    //         'id_supplier' => $preorder->id_supplier,
    //         'date' => now()->format('Y-m-d'),
    //         'total' => $preorder->grand_total,
    //     ];

    //     Pembayaran::create($data);
    //     $preorder->update(['is_pay' => 1]);

    //     return Redirect::route('daftar-receive-po')
    //         ->with('alert.status', '00')
    //         ->with('alert.message', "Add Pembayaran Success!");
    // }

    public function persetujuanHargaJual()
    {
        $title = 'Daftar Persetujuan Harga Jual';
        $titleHeader = 'PERSETUJUAN HARGA JUAL - PILIH BARANG';
        $preorders = Preorder::where('receive_type', 'B')->whereNull('is_cancel')->whereNull('is_persetujuan')->get();

        return view('preorder.receive-po.persetujuan-harga-jual', compact('title', 'titleHeader', 'preorders'));
    }

    public function batalPersetujuanHargaJual()
    {
        $title = 'Batal Persetujuan Harga Jual';
        $titleHeader = 'BATAL PERSETUJUAN HARGA JUAL';
        $preorders = Preorder::where('receive_type', 'B')->whereNull('is_cancel')->where('is_persetujuan', '!=', null)->get();

        return view('preorder.receive-po.batal-persetujuan-harga-jual', compact('title', 'titleHeader', 'preorders'));
    }

    public function updateBatalPersetujuanHargaJual($id)
    {
        $id = dekrip($id);
        $preorder = Preorder::find($id);
        HargaSementara::where('nomor_po', $preorder->nomor_po)->delete();
        HargaSementaraPos::where('nomor_po', $preorder->nomor_po)->delete();
        $preorder->update([
            'is_persetujuan' => null
        ]);

        return Redirect::route('batal-persetujuan-harga-jual')
            ->with('alert.status', '00')
            ->with('alert.message', "Hapus Data Persetujuan Harga Jual Berhasil!");
    }

    public function editPersetujuanHargaJual($id)
    {
        $id = dekrip($id);
        $title = 'Edit Persetujuan Harga Jual';
        $titleHeader = 'PERSETUJUAN HARGA JUAL - PILIH BARANG';
        $preorder = Preorder::find($id);
        $detail = json_decode($preorder->detail, true);
        usort($detail, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return view('preorder.receive-po.persetujuan-harga-jual-edit', compact('title', 'titleHeader', 'preorder', 'detail'));
    }

    public function getProductsByKode($kode)
    {
        $allProducts = Product::where('kode_sumber', $kode)->get();

        // Ekstrak angka setelah 'P' dan urutkan berdasarkan angka tersebut secara menurun
        $sortedProducts = $allProducts->sort(function ($a, $b) {
            // Ekstrak angka setelah 'P' dari kode_sumber
            $aNumber = intval(substr(strrchr($a->unit_jual, 'P'), 1));
            $bNumber = intval(substr(strrchr($b->unit_jual, 'P'), 1));

            // Urutkan secara menurun
            return $aNumber - $bNumber;
        });

        // Jika Anda ingin mengubah koleksi menjadi array
        $products = $sortedProducts->values()->all();
        // dd($products);
        return response()->json(['products' => $products]);
    }

    public function updatePersetujuanHargaJual(Request $request)
    {
        // ubah hanya data yang dicentang
        // kirim ke pos hasil update
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'mark_up.*' => 'required|numeric|min:0',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return Redirect::back()->with('alert.status', '99')->with('alert.message', "Mark Up Tidak Boleh Minus!");
        }

        $sortProduct = $request->nama;
        function splitNameApprove($item)
        {
            $parts = explode('/', $item);
            return [
                'name' => $parts[0],
                'quantity' => (int) $parts[1]
            ];
        }

        // Function to sort the array
        usort($sortProduct, function ($a, $b) {
            $aParts = splitNameApprove($a);
            $bParts = splitNameApprove($b);

            // Compare base names
            $nameComparison = strcmp($aParts['name'], $bParts['name']);
            if ($nameComparison !== 0) {
                return $nameComparison;
            }

            // If base names are the same, compare numeric parts
            return $aParts['quantity'] <=> $bParts['quantity'];
        });

        $sortName = [];
        $sortCode = [];
        $sortPrice = [];

        // Memproses inputArray untuk mendapatkan hasil yang diinginkan
        foreach ($sortProduct as $item) {
            // Memisahkan item berdasarkan '/'
            $parts = explode('/', $item);

            // Bagian pertama adalah deskripsi dan kuantitas
            $description = "{$parts[0]}/{$parts[1]}";
            $code = $parts[2];
            $price = $parts[3];

            // Menyimpan hasil ke dalam array yang sesuai
            $sortName[] = $description;
            $sortCode[] = $code;
            $sortPrice[] = $price;
        }

        $newData = array_map(function ($name, $kode, $harga_pokok, $harga_jual, $mark_up) {
            $hargaJual = str_replace(['.', ','], '', $harga_jual);
            return [
                'name' => $name,
                'kode' => $kode,
                'harga_pokok' => $harga_pokok,
                'harga_jual' => $hargaJual,
                'mark_up' => $mark_up
            ];
        }, $sortName, $sortCode, $sortPrice, $request->harga_jual, $request->mark_up);
        // dd($newData);

        $maxNo = HargaSementara::max('nomor');
        $getNext = $maxNo + 1;
        foreach ($newData as $new) {
            // dd($new);
            // update harga baru untuk master product
            $product = Product::where('kode', $new['kode'])->first();
            // $product->update([
            //     // 'harga_pokok' => $new['harga_pokok'],
            //     'harga_jual' => $new['harga_jual'],
            //     'profit' => $new['mark_up'],
            //     'updated_at' => now(),
            //     'is_transfer' => null,
            // ]);
            // $productPos = ProductPos::where('kode', $new['kode'])->first();
            // $productPos->update([
            //     // 'harga_pokok' => $new['harga_pokok'],
            //     'harga_jual' => $new['harga_jual'],
            //     'profit' => $new['mark_up'],
            //     'updated_at' => now()
            // ]);

            HargaSementara::create([
                'id_supplier' => $product->id_supplier,
                'id_product' => $product->id,
                'nomor' => $getNext ?? 1,
                'nama' => $product->nama . '/' . $product->unit_jual,
                'harga_lama' => $product->harga_lama,
                'harga_pokok' => $product->harga_pokok,
                'profit_pokok' => number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) ?? 0.00,
                'harga_jual' => $new['harga_jual'],
                'profit_jual' => $new['mark_up'],
                'harga_sementara' => $new['harga_jual'],
                'date_first' => now()->format('Y-m-d'),
                'date_last' => now()->format('Y-m-d'),
                'naik' => 100,
                'nomor_po' => $request->no_preorder,
            ]);

            $dataProductPos = ProductPos::where('nama', $product->nama)->where('unit_jual', $product->unit_jual)->first();
            if ($dataProductPos) {
                HargaSementaraPos::create([
                    'id_supplier' => $product->id_supplier,
                    'id_product' => $dataProductPos->id,
                    'nomor' => $getNext ?? 1,
                    'nama' => $product->nama . '/' . $product->unit_jual,
                    'harga_lama' => $product->harga_lama,
                    'harga_pokok' => $product->harga_pokok,
                    'profit_pokok' => number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) ?? 0.00,
                    'harga_jual' => $new['harga_jual'],
                    'profit_jual' => $new['mark_up'],
                    'harga_sementara' => $new['harga_jual'],
                    'date_first' => now()->format('Y-m-d'),
                    'date_last' => now()->format('Y-m-d'),
                    'naik' => 100,
                    'is_transfer' => null,
                    'nomor_po' => $request->no_preorder,
                ]);
            }
        }

        $preorder = Preorder::find($request->preorder);
        $preorder->update([
            'is_jual' => 1,
            'is_persetujuan' => 1
        ]);

        return Redirect::route('batal-persetujuan-harga-jual')
            ->with('alert.status', '00')
            ->with('alert.message', "Sukses Tambah Persetujuan Harga!");
    }

    public function daftarHargaJualKecil()
    {
        $title = 'Daftar Harga Jual Kecil';
        $titleHeader = 'HARGA JUAL DIBAWAH HARGA POKOK';
        $allMatchingProducts = new Collection();
        $trackedKodes = [];
        $preorders = Preorder::where('receive_type', 'B')->whereNull('nomor_bukti')->whereNull('is_cancel')->whereNotNull('detail')->get();
        // dd($preorders);

        foreach ($preorders as $preorder) {
            // Decode preorder details
            $details = json_decode($preorder->detail, true);

            foreach ($details as $detail) {
                if (!isset($trackedKodes[$detail['kode']])) {
                    // Query products matching the code and price condition
                    $matchingProduct = Product::where('kode', $detail['kode'])->where('harga_jual', '<', $detail['price'])->first();

                    // If a product is found, add it to the collection and track the kode
                    if ($matchingProduct) {
                        $markupAmount = $matchingProduct->harga_jual - (int)$detail['price'];
                        $markupPercentage = ($markupAmount / (int)$detail['price']) * 100;
                        $allMatchingProducts->push([
                            'id' => $preorder->id,
                            'preorder' => $preorder->nomor_po,
                            'kode' => $matchingProduct->kode,
                            'nama' => $matchingProduct->nama . '/' . $matchingProduct->unit_jual,
                            'unit' => $matchingProduct->unit_jual,
                            'harga_pokok' => (int)$detail['price'],
                            'harga_jual' => $matchingProduct->harga_jual,
                            'mark_up' => number_format($markupPercentage, 2)
                        ]);
                        $trackedKodes[$detail['kode']] = true; // Mark this kode as tracked
                    }
                }
            }
        }

        return view('preorder.harga-jual-kurang.index', compact('title', 'titleHeader', 'allMatchingProducts'));
    }

    public function returnPo()
    {
        $title = 'Return PO';
        $titleHeader = 'RETUR PEMBELIAN BARANG';
        $suppliers = Supplier::where('status', 1)->get();
        // $preorders = Preorder::whereNotNull('nomor_receive')->whereNull('nomor_bukti')->whereNull('is_return')->whereNull('is_cancel')->get();
        $preorders = Preorder::where('receive_type', 'B')->get();
        $products = Product::where('status', 1)->where('stok', '>', 0)->orderBy('nama')->get();
        $jabatan = auth()->user()->jabatan;
        // dd(count($products));

        return view('preorder.return-po.index', compact('title', 'titleHeader', 'suppliers', 'preorders', 'products', 'jabatan'));
    }

    public function createReturnPo(Request $request)
    {
        // dd($request->id_supplier);
        $retur = Pengembalian::create([
            'id_supplier' => $request->id_supplier,
            'date' => now()->format('Y-m-d'),
            'jam' => now()->format('H:i:s'),
            'created_by' => auth()->user()->name,
        ]);

        return redirect()->route('return-po.edit', enkrip($retur->id));
    }

    public function daftarReturnPo()
    {
        $title = 'Daftar Return PO';
        $titleHeader = 'DAFTAR KEMBALI BARANG';
        $returs = Pengembalian::whereNull('nomor_return')->get();
        $jabatan = auth()->user()->jabatan;
        // dd($returs);

        return view('preorder.return-po.list', compact('title', 'titleHeader', 'returs', 'jabatan'));
    }

    public function daftarHistoryReturnPo()
    {
        $title = 'Daftar Return PO';
        $titleHeader = 'DAFTAR KEMBALI BARANG';
        $returs = Pengembalian::whereNotNull('nomor_return')->get();
        // dd($returs);

        return view('preorder.return-po.history', compact('title', 'titleHeader', 'returs'));
    }

    public function showReturnPo($id)
    {
        $id = dekrip($id);
        $title = 'Show Return PO';
        $titleHeader = 'DAFTAR KEMBALI BARANG';

        $retur = Pengembalian::find($id);
        // dd($retur);

        return view('preorder.return-po.show', compact('title', 'titleHeader', 'retur'));
    }

    public function editReturnPo($id)
    {
        $id = dekrip($id);
        $title = 'Edit Return PO';
        $titleHeader = 'DAFTAR KEMBALI BARANG';

        $retur = Pengembalian::find($id);

        return view('preorder.return-po.edit', compact('title', 'titleHeader', 'retur'));
    }

    public function daftarReceiveSupplier($id, $sup)
    {
        $id = dekrip($id);
        $title = 'Edit Return PO';
        $titleHeader = 'DAFTAR KEMBALI BARANG';

        $retur = Pengembalian::find($id);
        $preorders = Preorder::where('receive_type', 'B')->where('id_supplier', $sup)->get();
        // dd($retur, $preorders);

        return view('preorder.return-po.index-edit', compact('title', 'titleHeader', 'retur', 'preorders'));
    }

    public function daftarReturnProduct(SearchProductDataTable $dataTable, $id)
    {
        $id = dekrip($id);
        $title = 'Add Product';
        $titleHeader = 'RETURN BARANG';

        return $dataTable->render('preorder.return-po.add-product', compact('title', 'id', 'titleHeader'));
    }

    public function updateDaftarReturnProduct(Request $request, $id)
    {
        // dd($request->all());
        $id = dekrip($id);
        $retur = Pengembalian::find($id);
        $detail = json_decode($retur->detail, true);

        $total = 0;
        $selectedIds = $request->input('selected_ids', []);
        foreach ($selectedIds as $sId) {
            $product = Product::find($sId);

            $detail[] = [
                'kode' => $product->kode,
                'nama' => $product->nama,
                'unit_jual' => $product->unit_jual,
                'stok' => $product->stok,
                'order' => 1,
                'price' => $product->harga_pokok,
                'field_total' => $product->harga_pokok,
                'kode_sumber' => $product->kode_sumber,
                'diskon1' => 0,
                'diskon2' => 0,
                'diskon3' => 0,
                'penjualan_rata' => 0,
                'waktu_kunjungan' => 0,
                'stok_minimum' => 0,
                'stok_maksimum' => 0,
                'is_ppn' => 0,
            ];
            $total += $product->harga_pokok;
        }
        $retur->detail = json_encode($detail);
        $retur->total = $retur->total + $total;
        $retur->save();

        return redirect()->route('return-po.edit', enkrip($id));
    }

    public function updateNomorReceive(Request $request, $id)
    {
        // dd($request->nomor_receive);
        $id = dekrip($id);

        $retur = Pengembalian::find($id);
        $retur->update([
            'nomor_receive' => $request->nomor_receive
        ]);

        return redirect()->route('return-po.edit', enkrip($id));
    }

    public function destroyReturnItem(Request $request)
    {
        // dd($request->input('index'), $request->input('retur_id'));
        $index = $request->input('index');
        $retur = Pengembalian::find($request->input('retur_id'));

        if (!$retur) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        // Decode detail dan hapus data berdasarkan index
        $details = json_decode($retur->detail, true);
        if (isset($details[$index])) {
            $retur->total -= $details[$index]['field_total'];
            unset($details[$index]);
            $retur->detail = json_encode(array_values($details));
            $retur->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Index tidak valid.']);
    }

    public function saveReturnItem(Request $request)
    {
        $index = $request->input('index');
        $retur = Pengembalian::find($request->input('retur_id'));
        $order = $request->input('order');
        $price = $request->input('price');
        $total = $order * $price;

        if (!$retur) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        $details = json_decode($retur->detail, true);
        if (isset($details[$index])) {
            $getTotal = $total - $details[$index]['field_total'];
            $retur->total += $getTotal;
            $details[$index]['order'] = $order;
            $details[$index]['price'] = $price;
            $details[$index]['field_total'] = $total;
            $retur->detail = json_encode(array_values($details));
            $retur->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Index tidak valid.']);
    }

    public function destroyReturnData($id)
    {
        $retur = Pengembalian::find($id);
        $preorder = Preorder::where('nomor_return', $retur->nomor_return)->first();
        $detail = json_decode($retur->detail, true);
        if ($detail) {
            foreach ($detail as $data) {
                $product = Product::where('kode', $data['kode'])->first();
                $product->update([
                    'stok' => (int)$product->stok + $data['order']
                ]);
            }
        }

        if ($preorder) {
            $preorder->update([
                'is_return' => null,
                'nomor_return' => null
            ]);
        }

        ProductStock::where('tipe', $retur->nomor_return)->delete();
        $retur->delete();

        return response()->json(['success' => true]);
    }

    public function storeReturnData(Request $request, $id)
    {
        // dd($request->all(), $id);
        // get nomor return
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastReturn = Pengembalian::max("nomor_return");
        if ($getLastReturn) {
            $explodeLastReturn = explode('-', $getLastReturn);
            if ($explodeLastReturn[1] == $dateNow) {
                $sequence = (int) $explodeLastReturn[2] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        }
        $getNomorReturn = 'RR-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        $retur = Pengembalian::find($id);
        $retur->update([
            'nomor_return' => $getNomorReturn
        ]);

        foreach (json_decode($retur->detail, true) as $item) {
            // dd($item['order']);
            $product = Product::where('kode', $item['kode'])->first();
            $product->update([
                'stok' => (int)$product->stok - $item['order']
            ]);

            // update product stock
            ProductStock::create([
                'tipe' => $getNomorReturn,
                'tanggal' => now()->format('Y-m-d'),
                'total' => '-' . $item['order'],
                'kode' => $product->kode,
                'stok' => (int)$product->stok,
                'unit_jual' => str_replace('P', '', $product->unit_jual)
            ]);
        }

        return Redirect::route('return-po')->with('alert.status', '00')->with('alert.message', "Sukses Proses Data Return!");
    }

    public function getDataFromBarcode(Request $request)
    {
        // 8992761111212
        $product = Product::where('kode_alternatif', $request['kode'])->first();
        if (isset($product)) {
            $products = Product::where('kode', $product->kode_sumber)->orWhere('kode_sumber', $product->kode_sumber)->get();
            // dd($products);

            return response()->json(['data' => $products]);
        } else {
            return response()->json(['error' => 'KODE BELUM TERSEDIA!']);
        }
    }

    public function storeDataFromBarcode(Request $request)
    {
        // dd($request->all());
        $products = $request->input('products');
        foreach ($products as $data) {
            // dd($data['kode']);
            $product = Product::where('kode', $data['kode'])->first();
            if (isset($product)) {
                $preorder = Preorder::find($request['preorderId']);
                $supplier = Supplier::find($request['supplierId']);
                $ppnValue = Ppn::pluck('ppn')->first();
                // dd($preorder, $supplier, $ppnValue);

                $newEntry = [
                    'kode' => $product->kode,
                    'nama' => $product->nama,
                    'unit_jual' => $product->unit_jual,
                    'stok' => number_format($product->stok + 1, 2),
                    'order' => 1,
                    'price' => $product->harga_pokok,
                    'field_total' => $product->harga_pokok,
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
                $detail = json_decode($preorder->detail, true);
                $detail[] = $newEntry;

                $preorder->detail = json_encode($detail);
                $preorder->total_harga += $product->harga_pokok;
                $preorder->grand_total += $product->harga_pokok;
                $preorder->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function getDataReturnBarcode(Request $request)
    {
        // 8992761111212
        // dd($request->all());
        $product = Product::where('kode_alternatif', $request['kode'])->first();
        if (isset($product)) {
            $retur = Pengembalian::find($request['id']);
            $newEntry = [
                'kode' => $product->kode,
                'nama' => $product->nama,
                'unit_jual' => $product->unit_jual,
                'stok' => number_format($product->stok + 1, 2),
                'order' => 1,
                'price' => $product->harga_pokok,
                'field_total' => $product->harga_pokok,
                'kode_sumber' => $product->kode_sumber,
                'diskon1' => 0,
                'diskon2' => 0,
                'diskon3' => 0,
                'penjualan_rata' => 0,
                'waktu_kunjungan' => 0,
                'stok_minimum' => 0,
                'stok_maksimum' => 0,
                'is_ppn' => 0,
            ];
            $detail = json_decode($retur->detail, true);
            $detail[] = $newEntry;

            $retur->detail = json_encode($detail);
            $retur->total += $product->harga_pokok;
            $retur->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'KODE BELUM TERSEDIA!']);
        }
    }

    public function updateSupplierReceiveData(Request $request)
    {
        // dd($request->all());
        $supplierId = dekrip($request->supplier_id);
        $preorder = Preorder::find($request->preorder_data);
        $preorder->update([
            'id_supplier' => $supplierId
        ]);
        $hutang = Hutang::where('nomor_po', $preorder->nomor_po)->first();
        $hutang->update([
            'id_supplier' => $supplierId
        ]);

        return response()->json(['success' => true]);
    }
}
