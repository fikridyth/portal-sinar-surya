<?php

namespace App\Http\Controllers;

use App\Models\HargaSementara;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HargaController extends Controller
{
    public function index()
    {
        $title = "Master Harga";
        $titleHeader = 'MASTER HARGA';
        $suppliers = Supplier::where('status', 1)->get();
        $products = HargaSementara::get()->unique('nomor');
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
        $hargaJual = $request->input('harga_jual');
        $profit = $request->input('profit');
        $hargaSementara = $request->input('harga_sementara');
        $selectedIds = $request->input('selected_ids');

        $combined = [];
        foreach ($selectedIds as $id) {
            $combined[] = [
                'id' => $id,
                'harga_jual' => $hargaJual[$id] ?? null,
                'profit' => $profit[$id] ?? null,
                'harga_sementara' => $hargaSementara[$id] ?? null,
            ];
        }
        
        // Proses data berdasarkan ID yang dipilih
        $maxNo = HargaSementara::max('nomor');
        $getNext = $maxNo + 1;
        foreach ($combined as $data) {
            $product = Product::find($data['id']);
            if ($product) {
                HargaSementara::create([
                    'id_supplier' => $request->id_supplier,
                    'nomor' => $getNext,
                    'nama' => $product->nama . '/' . $product->unit_jual,
                    'harga_lama' => $product->harga_lama,
                    'harga_pokok' => $product->harga_pokok,
                    'profit_pokok' => number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) ?? 0.00,
                    'harga_jual' => $data['harga_jual'],
                    'profit_jual' => $data['profit'],
                    'harga_sementara' => $data['harga_sementara'],
                    'date_first' => $request->from_date,
                    'date_last' => $request->to_date,
                    'naik' => 100,
                ]);
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
        $products = Product::where('id_supplier', $id)->where('status', 1)->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        // dd($id, count($products));

        return view('master.harga.show', compact('title', 'titleHeader', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all(), $id);
        Product::find($id)->update([
            'harga_lama' => $request->harga_lama,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'profit' => number_format((($request->harga_jual - $request->harga_pokok) / $request->harga_pokok) * 100, 2),
            'harga_sementara' => $request->harga_sementara,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'is_transfer' => null
        ]);

        return Redirect::route('master.harga.show', enkrip($request->id_supplier))
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
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)
            ->whereNotNull('harga_sementara')
            ->where('tanggal_awal', '<=', now()->format('Y-m-d'))
            ->where('tanggal_akhir', '>=', now()->format('Y-m-d'))
            ->get();
        // dd($id, count($products));

        return view('master.harga.show-sementara', compact('title', 'titleHeader', 'suppliers', 'products'));
    }
}
