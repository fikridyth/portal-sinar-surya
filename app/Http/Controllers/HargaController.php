<?php

namespace App\Http\Controllers;

use App\Models\HargaSementara;
use App\Models\HargaSementaraPos;
use App\Models\Product;
use App\Models\ProductPos;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HargaController extends Controller
{
    public function index()
    {
        $title = "Master Harga";
        $titleHeader = 'MASTER HARGA';
        $suppliers = Supplier::where('status', 1)->get();
        $products = HargaSementara::whereNotNull('nomor')->where('date_last', '>', now()->format('Y-m-d'))->get()->unique('nomor');
        // dd($products[0]->date_first, now()->format('Y-m-d'));
        // $products = Product::whereNotNull('harga_sementara')
        //     ->where('tanggal_awal', '<=', now()->format('Y-m-d'))
        //     ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))
        //     ->get()
        //     ->groupBy(function($product) {
        //         return $product->supplier->nama; // Menggunakan nama supplier sebagai key
        //     });

        return view('master.harga.index', compact('title', 'titleHeader', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $hargaLama = $request->input('harga_lama');
        $hargaPokok = $request->input('harga_pokok');
        // $hargaJual = $request->input('harga_jual');
        $profit = $request->input('profit');
        $hargaSementara = $request->input('harga_sementara');
        $selectedIds = $request->input('selected_ids');

        $combined = [];
        if (isset($selectedIds)) {
            foreach ($profit as $id => $value) {
                $combined[] = [
                    'id' => $id,
                    'harga_lama' => $hargaLama[$id] ?? null,
                    'harga_pokok' => $hargaPokok[$id] ?? null,
                    // 'harga_jual' => $hargaJual[$id] ?? null,
                    'profit' => $profit[$id] ?? null,
                    'harga_sementara' => $hargaSementara[$id] ?? null,
                ];
            }
        } else {
            return Redirect::route('master.harga.show', enkrip($request->id_supplier))
                ->with('alert.status', '99')
                ->with('alert.message', "DATA PERLU DI ISI!");
        }
        // dd($combined, $hargaPokok);

        // Proses data berdasarkan ID yang dipilih
        $maxNo = HargaSementara::selectRaw('MAX(CAST(nomor AS UNSIGNED)) as max_nomor')->value('max_nomor');
        $getNext = (int)$maxNo + 1;
        foreach ($combined as $data) {
            $product = Product::find($data['id']);
            if ($product) {
                if ($data['harga_sementara'] && $data['harga_pokok']) {
                    $product->update([
                        // 'harga_lama' => $data['harga_lama'],
                        'harga_jual' => $data['harga_sementara'],
                        'harga_pokok' => $data['harga_pokok'],
                        'profit' => number_format((($data['harga_sementara'] - $data['harga_pokok']) / $data['harga_pokok']) * 100, 2) ?? 0.00,
                        'is_transfer' => null
                    ]);
                    $productPos = ProductPos::where('nama', $product->nama)->where('unit_jual', $product->unit_jual)->first();
                    if ($productPos) {
                        $productPos->update([
                            // 'harga_lama' => $data['harga_lama'],
                            'harga_jual' => $data['harga_sementara'],
                            'harga_pokok' => $data['harga_pokok'],
                            'profit' => number_format((($data['harga_sementara'] - $data['harga_pokok']) / $data['harga_pokok']) * 100, 2) ?? 0.00,
                        ]);
                    }
                }
                HargaSementara::create([
                    'id_supplier' => $request->id_supplier,
                    'id_product' => $data['id'],
                    'nomor' => $getNext ?? null,
                    'nama' => $product->nama . '/' . $product->unit_jual,
                    'harga_lama' => $data['harga_lama'],
                    'harga_pokok' => $data['harga_pokok'],
                    'profit_pokok' => number_format((($data['harga_pokok'] - $data['harga_lama']) / $data['harga_lama']) * 100, 2) ?? 0.00,
                    'harga_jual' => $data['harga_sementara'],
                    'profit_jual' => $data['profit'],
                    'harga_sementara' => $data['harga_sementara'],
                    'date_first' => $request->from_date,
                    'date_last' => $request->to_date,
                    'naik' => $request->kenaikan ?? 100,
                ]);
                $dataProductPos = ProductPos::where('nama', $product->nama)->where('unit_jual', $product->unit_jual)->first();
                if ($dataProductPos) {
                    HargaSementaraPos::create([
                        'id_supplier' => $request->id_supplier,
                        'id_product' => $dataProductPos->id,
                        'nomor' => $getNext ?? null,
                        'nama' => $product->nama . '/' . $product->unit_jual,
                        'harga_lama' => $data['harga_lama'],
                        'harga_pokok' => $data['harga_pokok'],
                        'profit_pokok' => number_format((($data['harga_pokok'] - $data['harga_lama']) / $data['harga_lama']) * 100, 2) ?? 0.00,
                        'harga_jual' => $data['harga_sementara'],
                        'profit_jual' => $data['profit'],
                        'harga_sementara' => $data['harga_sementara'],
                        'date_first' => $request->from_date,
                        'date_last' => $request->to_date,
                        'naik' => $request->kenaikan ?? 100,
                        'is_transfer' => null
                    ]);
                }

                // update data dalam kelompok product tersebut
                $getKelompok = Product::where('kode', $product->kode_sumber)->orWhere('kode_sumber', $product->kode_sumber)->whereNot('id', $product->id)->get();
                $getMarkPokok = round((($data['harga_pokok'] - $data['harga_lama']) / $data['harga_lama']) * 100, 2);
                // dd($getKelompok, $data['harga_lama'], $data['harga_pokok'], $data['harga_jual'], $getMarkPokok, $getMarkJual);
                foreach ($getKelompok as $kelompok) {
                    $kelompok->update([
                        'harga_pokok' => (int)round((($kelompok->harga_pokok * $getMarkPokok) / 100) + $kelompok->harga_pokok,0)
                    ]);
                }
            }
        }

        return Redirect::route('master.harga.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Create Form Success!");
    }

    public function show($id)
    {
        $id = dekrip($id);
        $title = "Show Master Harga";
        $titleHeader = 'MASTER HARGA';
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)->where('status', 1)->orderBy('nama', 'asc')->get();
        $owner = User::where('JABATAN', 'OWNER')->pluck('show_password')->first();
        // dd($id, count($products));

        return view('master.harga.show', compact('title', 'titleHeader', 'suppliers', 'products', 'owner'));
    }

    public function update(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all(), $id);

        $loop = $request->input('loop');
        $idProduct = $request->input('data_id');
        $hargaLama = $request->input('harga_lama');
        $hargaPokok = $request->input('harga_pokok');
        $hargaJual = $request->input('harga_jual');
        $profit = $request->input('profit');
        $hargaSementara = $request->input('harga_sementara');
        // dd($loop);

        $combined = [];
        foreach ($loop as $id) {
            $combined[] = [
                'id' => $id,
                'id_product' => $idProduct[$id] ?? null,
                'harga_lama' => $hargaLama[$id] ?? null,
                'harga_pokok' => $hargaPokok[$id] ?? null,
                'harga_jual' => $hargaJual[$id] ?? null,
                'profit' => $profit[$id] ?? null,
                'harga_sementara' => $hargaSementara[$id] ?? null,
            ];
        }

        foreach ($combined as $data) {
            $product = Product::find($data['id_product']);
            if ($product) {
                if ($data['harga_sementara'] && $data['harga_pokok']) {
                    $product->update([
                        // 'harga_lama' => $data['harga_lama'],
                        'harga_jual' => $data['harga_sementara'],
                        'harga_pokok' => $data['harga_pokok'],
                        'profit' => number_format((($data['harga_sementara'] - $data['harga_pokok']) / $data['harga_pokok']) * 100, 2) ?? 0.00,
                        'is_transfer' => null
                    ]);
                    $productPos = ProductPos::where('nama', $product->nama)->where('unit_jual', $product->unit_jual)->first();
                    if ($productPos) {
                        $productPos->update([
                            // 'harga_lama' => $data['harga_lama'],
                            'harga_jual' => $data['harga_sementara'],
                            'harga_pokok' => $data['harga_pokok'],
                            'profit' => number_format((($data['harga_sementara'] - $data['harga_pokok']) / $data['harga_pokok']) * 100, 2) ?? 0.00,
                        ]);
                    }
                }
                HargaSementara::create([
                    'id_supplier' => $request->id_supplier,
                    'id_product' => $data['id_product'],
                    'nomor' => null,
                    'nama' => $product->nama . '/' . $product->unit_jual,
                    'harga_lama' => $data['harga_lama'],
                    'harga_pokok' => $data['harga_pokok'],
                    'profit_pokok' => number_format((($data['harga_pokok'] - $data['harga_lama']) / $data['harga_lama']) * 100, 2) ?? 0.00,
                    'harga_jual' => $data['harga_sementara'],
                    'profit_jual' => $data['profit'],
                    'harga_sementara' => $data['harga_sementara'],
                    'date_first' => $request->from_date,
                    'date_last' => $request->to_date,
                    'naik' => $request->kenaikan ?? 100,
                ]);
                $dataProductPos = ProductPos::where('nama', $product->nama)->where('unit_jual', $product->unit_jual)->first();
                if ($dataProductPos) {
                    HargaSementaraPos::create([
                        'id_supplier' => $request->id_supplier,
                        'id_product' => $dataProductPos->id,
                        'nomor' => null,
                        'nama' => $product->nama . '/' . $product->unit_jual,
                        'harga_lama' => $data['harga_lama'],
                        'harga_pokok' => $data['harga_pokok'],
                        'profit_pokok' => number_format((($data['harga_pokok'] - $data['harga_lama']) / $data['harga_lama']) * 100, 2) ?? 0.00,
                        'harga_jual' => $data['harga_sementara'],
                        'profit_jual' => $data['profit'],
                        'harga_sementara' => $data['harga_sementara'],
                        'date_first' => $request->from_date,
                        'date_last' => $request->to_date,
                        'naik' => $request->kenaikan ?? 100,
                        'is_transfer' => null
                    ]);
                }
            }
        }

        return Redirect::route('master.harga.index', enkrip($request->id_supplier))
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
        $titleHeader = 'DATA HARGA SEMENTARA';
        $hargaSementara = HargaSementara::find($id);
        $listProduct = HargaSementara::where('nomor', $hargaSementara->nomor)->get();
        // dd($listProduct);

        return view('master.harga.show-sementara', compact('title', 'titleHeader', 'hargaSementara', 'listProduct'));
    }
}
