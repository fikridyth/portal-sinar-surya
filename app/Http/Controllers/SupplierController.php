<?php

namespace App\Http\Controllers;

use App\DataTables\KunjunganDataTable;
use App\DataTables\MateraiDataTable;
use App\DataTables\SupplierDataTable;
use App\Models\HistoryPreorder;
use App\Models\HistoryPreorderDetail;
use App\Models\Pengembalian;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Promosi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SupplierDataTable $dataTable)
    {
        $title = 'Master Supplier';
        $titleHeader = 'MASTER SUPPLIER';

        return $dataTable->render('master.supplier.index', compact('title', 'titleHeader'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Supplier';
        $titleHeader = 'MASTER SUPPLIER';
        $lastNomor = Supplier::max('nomor');
        $nextNomor = str_pad(((int)$lastNomor + 1), 5, '0', STR_PAD_LEFT);

        return view('master/supplier/create', compact('title', 'titleHeader', 'nextNomor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = [
            'nomor' => $request->nomor,
            'wilayah' => $request->wilayah,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'alamat3' => $request->alamat3,
            'kontak' => $request->kontak,
            'kontak1' => $request->kontak1,
            'no_telp' => $request->no_telp,
            'fax' => $request->fax,
            'tcrd' => $request->tcrd ?? 0,
            'npwp' => $request->npwp,
            'hp' => $request->hp,
            'disc' => $request->disc ?? 0,
            'tanda' => $request->tanda,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'hari' => $request->hari,
            'stok_minimum' => $request->stok_minimum,
            'stok_maksimum' => $request->stok_maksimum,
            'penjualan_rata' => $request->penjualan_rata,
            'materai' => $request->materai ?? 0,
            'koli' => $request->koli ?? 0,
            'bonus' => $request->bonus ?? 0,
            'rpus' => $request->rpus,
            'is_ppn' => ($request->ppn === 'on') ? 1 : 0,
            'status' => 1
        ];

        $supplier = Supplier::create($data);

        return Redirect::route('master.supplier.show', enkrip($supplier->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Add Supplier Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = dekrip($id);
        $title = 'Show Supplier';
        $titleHeader = 'MASTER SUPPLIER';
        $supplier = Supplier::find($id);

        return view('master/supplier/show', compact('title', 'supplier', 'titleHeader'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = dekrip($id);
        $title = 'Edit Supplier';
        $titleHeader = 'MASTER SUPPLIER';
        $supplier = Supplier::find($id);

        return view('master/supplier/edit', compact('title', 'supplier', 'titleHeader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $id = dekrip($id);
        $supplier = Supplier::find($id);

        $data = [
            'nomor' => $request->nomor,
            'wilayah' => $request->wilayah,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'alamat3' => $request->alamat3,
            'kontak' => $request->kontak,
            'kontak1' => $request->kontak1,
            'no_telp' => $request->no_telp,
            'fax' => $request->fax,
            'tcrd' => $request->tcrd,
            'npwp' => $request->npwp,
            'hp' => $request->hp,
            'disc' => $request->disc,
            'tanda' => $request->tanda,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'hari' => $request->hari,
            'stok_minimum' => $request->stok_minimum,
            'stok_maksimum' => $request->stok_maksimum,
            'penjualan_rata' => $request->penjualan_rata,
            'materai' => $request->materai,
            'koli' => $request->koli,
            'bonus' => $request->bonus,
            'rpus' => $request->rpus,
            'is_ppn' => ($request->ppn === 'on') ? 1 : 0,
            'status' => 1
        ];

        $supplier->update($data);

        return Redirect::route('master.supplier.show', enkrip($supplier->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Supplier Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = dekrip($id);
        Supplier::find($id)->delete();

        return Redirect::route('master.supplier.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Supplier Success!");
    }

    public function indexPromosi()
    {
        $title = 'Master Promosi';
        $titleHeader = 'MASTER PROMOSI';
        $promosi = Promosi::whereNull('nomor_bukti')->get();
        $suppliers = Supplier::all();
        $now = now()->format('Y-m-d');

        return view('master/supplier/promosi/index', compact('title', 'titleHeader', 'promosi', 'suppliers', 'now'));
    }

    public function indexAllPromosi()
    {
        $title = 'Master Promosi';
        $titleHeader = 'MASTER PROMOSI';
        $promosi = Promosi::all();

        return view('master/supplier/promosi/index-all', compact('title', 'titleHeader', 'promosi'));
    }

    public function storePromosi(Request $request)
    {
        // dd($request->all());

        // get nomor promosi
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Promosi::max("nomor_promosi");
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
        $getNomorPromo = 'AM-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        Promosi::create([
            'id_supplier' => $request->supplier_id,
            'nomor_promosi' => $getNomorPromo,
            'tipe' => $request->tipe,
            'total' => $request->total,
            'date_first' => $request->date_first,
            'date_last' => $request->date_last,
        ]);

        return Redirect::route('master.promosi.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Tambah Promosi Success!");
    }

    public function updatePromosi(Request $request, $id)
    {
        // dd($request->id_supplier, $id);
        Promosi::find($id)->update([
            'id_supplier' => $request->id_supplier,
            'tipe' => $request->tipe,
            'total' => $request->total,
            'date_first' => $request->date_first,
            'date_last' => $request->date_last,
        ]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function destroyPromosi(string $id)
    {
        Promosi::find($id)->delete();

        return Redirect::route('master.promosi.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Promosi Success!");
    }

    public function indexMaterai(MateraiDataTable $dataTable)
    {
        $title = 'Master Materai';
        $titleHeader = 'MASTER MATERAI';

        return $dataTable->render('master.supplier.index-materai', compact('title', 'titleHeader'));
    }

    public function updateMaterai($id)
    {
        $id = dekrip($id);
        $supplier = Supplier::find($id);
        if ($supplier->materai == 10000) $supplier->update(['materai' => 0]);
        else $supplier->update(['materai' => 10000]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function indexHistoryPo($id)
    {
        $id = dekrip($id);
        $title = 'Master History Preorder';
        $titleHeader = 'MASTER HISTORY PREORDER';
        $supplier = Supplier::find($id);
        $suppliers = Supplier::all();
        $getPo = Preorder::select('nomor_receive as nomor', 'date_first as date', 'detail')->Filter(request(['periode']))->where('id_supplier', $id)->where('receive_type', 'B')->where('detail', '!=', '[]')->whereNotNull('nomor_receive')->orderBy('created_at', 'desc')->get();
        $getRetur = Pengembalian::select('nomor_return as nomor', 'date', 'detail')->Filter(request(['periode']))->where('id_supplier', $id)->whereNotNull('nomor_return')->where('nomor_return', '!=', '')->where('detail', '!=', '[]')->orderBy('date', 'desc')->get();
        $getData = $getPo->concat($getRetur)->sortByDesc('date')->values()->toArray();
        $getHistory = HistoryPreorder::select('nomor_receive', 'date')->Filter(request(['periode']))->where('nomor', $supplier->nomor)->orderBy('date', 'desc')->get();
        // dd($getHistory);

        return view('master.supplier.index-history', compact('title', 'titleHeader', 'supplier', 'getData', 'getHistory', 'suppliers'));
    }

    public function getHistoryPo(Request $request)
    {
        // dd($request->all());
        $nomor = $request->input('nomor');

        // Fetch data from database
        $detail = HistoryPreorderDetail::where('nomor_receive', $nomor)->get();
        $detailData = $detail->map(function($item) {
            $item->product_nama = $item->product->nama;
            $item->product_unit_jual = $item->product->unit_jual;
            return $item;
        });
        // dd($detail[0]->product->nama);

        return response()->json(['dataDetail' => $detailData]);
    }

    public function indexKunjungan(KunjunganDataTable $dataTable)
    {
        $title = 'Master Kunjungan';
        $titleHeader = 'MASTER KUNJUNGAN';

        return $dataTable->render('master.supplier.index-kunjungan', compact('title', 'titleHeader'));
    }

    public function indexChangeSupplier()
    {
        $title = "Perubahan Supplier";
        $titleHeader = 'PERUBAHAN SUPPLIER';
        $suppliers = Supplier::where('status', 1)->get();

        return view('master.supplier.perubahan.index', compact('title', 'titleHeader', 'suppliers'));
    }

    public function showChangeSupplier($id)
    {
        $id = dekrip($id);
        $title = "Perubahan Supplier";
        $titleHeader = 'PERUBAHAN SUPPLIER';
        $supplier = Supplier::find($id);
        $suppliers = Supplier::where('status', 1)->get();
        $products = Product::where('id_supplier', $id)->whereNotNull('stok')->where('stok', '>', 0)->get();
        // dd(count($products));

        return view('master.supplier.perubahan.show', compact('title', 'titleHeader', 'supplier', 'suppliers', 'products'));
    }

    public function updateChangeSupplier(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($id, $request->all());

        $idProduct = $request->input('id_product');
        foreach ($idProduct as $idP) {
            $product = Product::find($idP);
            if ($product) {
                $product->update([
                    'id_supplier' => $request->supplier_target
                ]);
            }
        }

        return Redirect::route('master.change-supplier.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Change Supplier Success!");
    }

    public function cetakFakturSupplier($id, $nama, $dari, $sampai)
    {
        $start = Carbon::parse($dari)->startOfDay();
        $end = Carbon::parse($sampai)->endOfDay();

        // Ambil data Pengembalian
        $pengembalian = Pengembalian::where('id_supplier', $id)->whereNotNull('nomor_return')->where('total', '!=', 0)->whereBetween('date', [$start, $end])
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'RETUR',
                    'nomor' => $item->nomor_return,
                    'date' => $item->date,
                    'harga' => $item->total,
                ];
            });

        // Ambil data Preorder
        $preorder = Preorder::where('id_supplier', $id)->whereNotNull('nomor_receive')->where('grand_total', '!=', 0)->whereBetween('date_first', [$start, $end])
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'PEMBELIAN',
                    'nomor' => $item->nomor_receive,
                    'date' => $item->date_first,
                    'harga' => $item->grand_total,
                ];
            });

            // dd($pengembalian);

        // Gabungkan dan urutkan berdasarkan tanggal
        $pengembalian = collect($pengembalian); // pastikan ini adalah Collection
        $preorder = collect($preorder);         // pastikan ini juga Collection

        $dataGabungan = $pengembalian
            ->merge($preorder)
            ->sortBy('date')
            ->values();

        return view('master.supplier.print-faktur', compact('dataGabungan', 'dari', 'sampai', 'nama'));
    }
}
