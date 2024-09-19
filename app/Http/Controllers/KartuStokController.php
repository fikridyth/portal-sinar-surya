<?php

namespace App\Http\Controllers;

use App\DataTables\KartuStokDataTable;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(KartuStokDataTable $dataTable)
    {
        $title = 'Master Kartu Stok';

        return $dataTable->render('master.kartu-stok.index', compact('title'));
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
        $title = 'Show Master Kartu Stok';
        $product = Product::find($id);
        
        $parentAndChildUnits = Product::where(function($query) use ($product) {
            $query->where('kode', $product->kode)
            ->orWhere('kode_sumber', $product->kode);
        })
        ->orderBy('kode_sumber', 'asc')  // Sort by `kode_sumber` to group parent and child
        ->get(['unit_jual', 'stok', 'kode']) // Fetch relevant fields
        ->groupBy('unit_jual') // Group by `kode_sumber`
        ->map(function($items) {
            $stok = $items->pluck('stok')->first();
            $unit_jual = $items->pluck('unit_jual')->first();
            $get_number = str_replace('P', '', $unit_jual);
            return [
                'kode' => $items->pluck('kode')->first(),
                'unit_jual' => $unit_jual,
                'stok' => number_format($stok, 0),
                'masuk' => (int)number_format($get_number * $stok, 0),
                'get_number' => (int)$get_number
            ];
        })
        ->sortByDesc('get_number') // Sort by the transformed number
        ->values()
        ->toArray();

        $totalMasuk = array_sum(array_column($parentAndChildUnits, 'masuk'));

        // Sort child units in descending order
        $allProducts = array_values($parentAndChildUnits);
        // dd($allProducts, $totalMasuk);

        $productFlow = []; // Inisialisasi array untuk menyimpan hasil
        foreach ($allProducts as $prd) {
            $productFlows = ProductStock::select('tipe', 'tanggal', 'qty', 'kode')
                ->where('kode', $prd['kode'])
                ->get();

            // Menggabungkan hasil ke dalam array
            $productFlow = array_merge($productFlow, $productFlows->toArray());
        }
        
        // Mengurutkan hasil berdasarkan 'tanggal'
        usort($productFlow, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        return view('master.kartu-stok.show', compact('title', 'product', 'allProducts', 'totalMasuk', 'productFlow'));
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
