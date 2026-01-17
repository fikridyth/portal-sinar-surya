<?php

namespace App\Http\Controllers;

use App\DataTables\KartuStokDataTable;
use App\Models\Product;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index(KartuStokDataTable $dataTable)
    {
        $title = 'Master Kartu Stok';
        $titleHeader = 'KARTU STOK';

        return $dataTable->render('master.kartu-stok.index', compact('title', 'titleHeader'));
    }

    public function show(string $id)
    {
        $id = dekrip($id);
        $title = 'Show Master Kartu Stok';
        $titleHeader = 'KARTU STOK';
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
                'masuk' => (int) $stok * (int) $get_number,
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

        $now = Carbon::now();
        $startOfMonth = $now->firstOfMonth()->format('Y-m-d');
        $endOfMonth = $now->lastOfMonth()->format('Y-m-d');

        $periodeData = explode(' - ', request(['periode'])['periode'] ?? "$startOfMonth - $endOfMonth");
        // $periodeData = explode(' - ', request(['periode'])['periode']);
        $periodeAwal = $periodeData[0] ?? '1970-01-01';
        $periodeAkhir = $periodeData[1] ?? now()->format('Y-m-d');
        foreach ($allProducts as $prd) {
            $productFlows = ProductStock::select('tipe', 'tanggal', 'total', 'kode', 'stok', 'unit_jual')
                ->where('kode', $prd['kode'])
                ->whereNotNull('total')
                ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
                ->get();

            // Menggabungkan hasil ke dalam array
            $productFlow = array_merge($productFlow, $productFlows->toArray());
        }
        
        // Mengurutkan hasil berdasarkan 'tanggal'
        usort($productFlow, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        return view('master.kartu-stok.show', compact('title', 'product', 'allProducts', 'totalMasuk', 'productFlow', 'titleHeader'));
    }
}
