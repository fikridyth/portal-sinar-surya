<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Models\Departemen;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
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
        $products = Product::where('stok', '>', 0)->where('kode_sumber', null)->orderBy('nama', 'asc')->get();
        // dd(count($products));

        return view('master.adjustment.index', compact('title', 'titleHeader', 'grups', 'departemens', 'suppliers', 'products'));
    }

    public function show(Request $request) {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        $query = Product::query();
        $kodeProduk = [];
        // dd($kodeProduk, $request->all());

        $query->where(function ($q) use ($request) {
            if (!empty($request->selected_products)) {
                $kodeProduk = Product::whereIn('id', $request->selected_products)
                    ->pluck('kode')
                    ->toArray();
                $q->orWhereIn('kode_sumber', $kodeProduk);
            }
    
            if (!empty($request->selected_suppliers)) {
                $q->orWhereIn('id_supplier', $request->selected_suppliers);
            }
    
            if (!empty($request->selected_grups)) {
                $q->orWhereIn('id_unit', $request->selected_grups);
            }
    
            if (!empty($request->selected_departemens)) {
                $q->orWhereIn('id_departemen', $request->selected_departemens);
            }
        });

        $products = $query
            ->whereRaw("
                CAST(SUBSTRING(unit_jual, 2) AS UNSIGNED) = (
                    SELECT MIN(CAST(SUBSTRING(p2.unit_jual, 2) AS UNSIGNED))
                    FROM products p2
                    WHERE p2.nama = products.nama
                )
            ")
            ->orderBy('nama', 'asc')
            ->get();
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
        $products = Product::where('stok', '>', 0)->where('kode_sumber', null)->orderBy('nama', 'asc')->get();
        // dd(count($products));

        return view('master.adjustment.index-edit', compact('title', 'titleHeader', 'grups', 'departemens', 'suppliers', 'products'));
    }

    public function password(Request $request) {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';
        $query = Product::query();
        $kodeProduk = [];
        // dd($kodeProduk, $request->all());

        $query->where(function ($q) use ($request) {
            if (!empty($request->selected_products)) {
                $kodeProduk = Product::whereIn('id', $request->selected_products)
                    ->pluck('kode')
                    ->toArray();
                $q->orWhereIn('kode_sumber', $kodeProduk);
            }
    
            if (!empty($request->selected_suppliers)) {
                $q->orWhereIn('id_supplier', $request->selected_suppliers);
            }
    
            if (!empty($request->selected_grups)) {
                $q->orWhereIn('id_unit', $request->selected_grups);
            }
    
            if (!empty($request->selected_departemens)) {
                $q->orWhereIn('id_departemen', $request->selected_departemens);
            }
        });

        $products = $query
            ->whereRaw("
                CAST(SUBSTRING(unit_jual, 2) AS UNSIGNED) = (
                    SELECT MIN(CAST(SUBSTRING(p2.unit_jual, 2) AS UNSIGNED))
                    FROM products p2
                    WHERE p2.nama = products.nama
                )
            ")
            ->orderBy('nama', 'asc')
            ->get();
        session(['products' => $products]);
        // dd(count($products));

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

    public function cetak() {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';

        $products = session('products');

        return view('master.adjustment.cetak', compact('title', 'titleHeader', 'products'));
    }

    public function cetakRokok() {
        $title = 'Adjustment';
        $titleHeader = 'PENYESUAIAN PERSEDIAAN';

        $productsAll = session('products');
        $products = [];
        foreach ($productsAll as $product) {
            if ($product->unit_jual == 'P1') {
                $products[] = [
                    'nama' => $product->nama,
                    'unit_jual' => $product->unit_jual,
                    'stok' => $product->stok,
                ];
            }
        }
        // dd(count($products));

        return view('master.adjustment.cetak-rokok', compact('title', 'titleHeader', 'products'));
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
        // dd($request->all());
        $selectedFisik = $request->input('fisik'); // Ini akan berupa array dengan ID produk dan nilai fisik yang baru
        $selectedStok = $request->input('stok');
        $selectedIds = $request->input('selected');
        $selectedQty = $request->input('qty');
        $selectedRupiah = $request->input('rupiah');
        $selectedName = $request->input('name');

        $combined = [];
        if (isset($selectedIds)) {
            foreach ($selectedIds as $id) {
                if ($selectedRupiah[$id] !== null) {
                    $combined[] = [
                        'id' => $id,
                        'name' => $selectedName[$id] ?? null,
                        'stok' => $selectedStok[$id] ?? null,
                        'fisik' => $selectedFisik[$id] ?? null,
                        'qty' => (int)$selectedQty[$id] ?? 0,
                        'rupiah' => $selectedRupiah[$id] ?? 0,
                    ];
                }
            }
        } else {
            return Redirect::route('master.adjustment.index-edit');
        }
        // dd($combined);

        if ($combined == []) {
            return Redirect::route('master.adjustment.index-edit');
        }

        $maxNo = Adjustment::max('nomor');
        $getNext = $maxNo + 1;
        // buat data di tabel adjustment
        if (count($combined) > 0) {
            foreach ($combined as $data) {
                $dataAdjust = [
                    'id_product' => $data['id'],
                    'nomor' => $getNext ?? 1,
                    'nama' => $data['name'],
                    'stok_lama' => $data['stok'] ?? 0,
                    'stok_baru' => $data['fisik'] ?? 0,
                    'selisih_qty' => $data['qty'],
                    'selisih_rupiah' => $data['rupiah'],
                    'tanggal' => now()->format('Y-m-d')
                ];
                // dd($dataAdjust);
                Adjustment::create($dataAdjust);
            }
        }

        // Lakukan pembaruan data stok ke tabel product
        foreach ($selectedFisik as $productId => $newFisik) {
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

    public function indexHistory() {
        $title = 'Adjustment';
        $titleHeader = 'HISTORY PENYESUAIAN PERSEDIAAN';

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $adjustments = Adjustment::Filter(request(['periode']))->get();
        // dd($adjustments->isNotEmpty());

        return view('master.adjustment.history-selisih.index', compact('title', 'titleHeader', 'adjustments'));
    }
}
