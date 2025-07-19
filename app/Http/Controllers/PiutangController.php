<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryPiutangDataTable;
use App\DataTables\KreditDataTable;
use App\DataTables\KreditHistoryDataTable;
use App\DataTables\SearchProductDataTable;
use App\Models\Kredit;
use App\Models\Langganan;
use App\Models\Pembayaran;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PiutangController extends Controller
{
    public function index()
    {
        $title = "Pembayaran Piutang";
        $titleHeader = 'PEMBAYARAN PIUTANG';
        $listTunai = [];
        $listBayar = [];

        // get nomor piutang
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Piutang::max("nomor_piutang");
        if ($getLastPo) {
            $explodeLastPo = explode('-', $getLastPo);
            if ($explodeLastPo[0] == $dateNow) {
                $sequence = (int) $explodeLastPo[1] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        } 
        // $getNomor = 'PHL-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        $getNomor = $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        $langganans = Langganan::all();
        
        return view('pembayaran.piutang.index',compact('title', 'titleHeader', 'listTunai', 'listBayar', 'getNomor', 'langganans'));
    }
    public function show($id)
    {
        $id = dekrip($id);
        $title = "Pembayaran Piutang";
        $titleHeader = 'PEMBAYARAN PIUTANG';
        $listTunai = [];
        $listBayar = [];

        // get nomor piutang
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Piutang::max("nomor_piutang");
        if ($getLastPo) {
            $explodeLastPo = explode('-', $getLastPo);
            if ($explodeLastPo[0] == $dateNow) {
                $sequence = (int) $explodeLastPo[1] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        } 
        // $getNomor = 'PHL-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        $getNomor = $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        $langganans = Langganan::where('id', '!=', $id)->get();
        $pelanggan = Langganan::find($id);

        // get data kredit
        if ($id == 1) {
            $listTunai = Pembayaran::where('tipe_giro', 'TUNAI')->where('nomor_giro', 'TUNAI')->whereNotNull('is_bayar')->whereNull('is_piutang')->whereNotNull('is_cabang')->with('supplier')
            ->get()
            ->map(function ($pembayaran) {
                return [
                    'nomor_bukti' => $pembayaran->nomor_bukti,
                    'date' => $pembayaran->date,
                    'total_with_materai' => $pembayaran->total_with_materai,
                    'beban_materai' => 0,
                    'supplier_name' => $pembayaran->supplier->nama,
                ];
            });
            $listBayar = Pembayaran::where('tipe_giro', 'CABANG')->whereNotNull('date_last')->whereNotNull('is_bayar')->whereNull('is_piutang')->with('supplier')
            ->get()
            ->map(function ($pembayaran) {
                return [
                    'nomor_bukti' => $pembayaran->nomor_bukti,
                    'date' => $pembayaran->date,
                    'total_with_materai' => $pembayaran->total_with_materai,
                    'beban_materai' => $pembayaran->supplier->materai ?? 0,
                    'supplier_name' => $pembayaran->supplier->nama,
                ];
            });

            // dd($listTunai, $listBayar);
            if ($listTunai->isEmpty() && !$listBayar->isEmpty()) {
                $dataKredit = $listBayar;
            } elseif ($listBayar->isEmpty() && !$listTunai->isEmpty()) {
                $dataKredit = $listTunai;
            } elseif (!$listTunai->isEmpty() && !$listBayar->isEmpty()) {
                $dataKredit = $listTunai->merge($listBayar);
            } else {
                $dataKredit = collect([]);
            }
            
            $dataKredit2 = Kredit::where('id_langganan', $id)->whereNotNull('nomor')->whereNull('is_piutang')->get();
            $dataKredit = $dataKredit->merge($dataKredit2);
            // dd($dataKredit);
        } else {
            $dataKredit = Kredit::where('id_langganan', $id)->whereNotNull('nomor')->whereNull('is_piutang')->get();
        }
        
        return view('pembayaran.piutang.show',compact('title', 'titleHeader', 'listTunai', 'listBayar', 'getNomor', 'langganans', 'pelanggan', 'dataKredit'));
    }

    public function getPembayaranData() {
        $listTunai = Pembayaran::where('tipe_giro', 'TUNAI')->where('nomor_giro', 'TUNAI')->whereNotNull('is_bayar')->whereNull('is_piutang')->whereNotNull('is_cabang')->with('supplier')
        ->get()
        ->map(function ($pembayaran) {
            return [
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'date' => $pembayaran->date,
                'total_with_materai' => $pembayaran->total_with_materai,
                'beban_materai' => 0,
                'supplier_name' => $pembayaran->supplier->nama,
            ];
        });
        $listBayar = Pembayaran::where('tipe_giro', 'CABANG')->whereNotNull('date_last')->whereNotNull('is_bayar')->whereNull('is_piutang')->with('supplier')
        ->get()
        ->map(function ($pembayaran) {
            return [
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'date' => $pembayaran->date,
                'total_with_materai' => $pembayaran->total_with_materai,
                'beban_materai' => $pembayaran->supplier->materai ?? 0,
                'supplier_name' => $pembayaran->supplier->nama,
            ];
        });
    
        return response()->json([
            'listTunai' => $listTunai,
            'listBayar' => $listBayar
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $detail = [];
        $total = $materai = 0;

        $data = $request->input('check.bayar');
        $filteredData = array_filter($data, function ($item) {
            return isset($item['nomor_bukti'], $item['beban_materai']) && !empty($item['nomor_bukti']) && $item['beban_materai'] !== '';
        });
        
        // Periksa jika ada kombinasi nilai 0 dan lebih dari 0
        $hasZeroMaterai = array_filter($filteredData, function ($item) {
            return isset($item['beban_materai']) && $item['beban_materai'] == 0;
        });
        $hasNonZeroMaterai = array_filter($filteredData, function ($item) {
            return isset($item['beban_materai']) && $item['beban_materai'] > 0;
        });
        if (!empty($hasZeroMaterai) && !empty($hasNonZeroMaterai)) {
            return redirect()->back()->with('alert.status', '99')->with('alert.message', 'GIRO DAN TUNAI TIDAK BISA DIGABUNG!');
        }
        // dd($filteredData);

        if (!empty($filteredData)) {
            foreach ($filteredData as $data) {
                // dd($data['nomor_bukti']);
                
                $listData = Pembayaran::where('nomor_bukti', $data['nomor_bukti'])->whereNotNull('id_parent')->whereNotNull('date_last')->first();
                if ($listData) {
                    $detail[] = [
                        'nama' => $listData->supplier->nama,
                        'nomor_bukti' => $listData->nomor_bukti,
                        'date' => $listData->date,
                        'grand_total' => $listData->grand_total,
                        'beban_materai' => $data['beban_materai'] ?? 0,
                        'total_with_materai' => $listData->total_with_materai,
                    ];
                    $total += (int) $listData->total_with_materai;
                    $materai += (int) $data['beban_materai'] ?? 0;

                    $listData->update(['is_piutang' => 1]);
                } else {
                    $dataKredit = Kredit::where('nomor', $data['nomor_bukti'])->first();
                    $detail[] = [
                        'nama' => $dataKredit->langganan->nama,
                        'nomor_bukti' => $dataKredit->nomor,
                        'date' => $dataKredit->date,
                        'grand_total' => $dataKredit->total,
                        'beban_materai' => 10000,
                        'total_with_materai' => $dataKredit->total + 10000,
                    ];
                    $total += (int) $dataKredit->total;
                    $materai += 10000;

                    $dataKredit->update(['is_piutang' => 1]);
                }
            }
        } else {
            return redirect()->back()->withInput()->withErrors(["TIDAK ADA DATA YANG DIPILIH!"]);
        }
        
        $data = [
            'nomor_piutang' => $request->nomor_piutang,
            'wilayah' => 'BOGOR',
            'total' => $total,
            'materai' => $materai,
            'date' => now()->format('Y-m-d'),
            'detail' => json_encode($detail),
            'created_by' => $request->created_by
        ];
        // dd($data);
        Piutang::create($data);

        return Redirect::route('pembayaran-piutang.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Store Piutang Success!");
    }

    public function indexTagihan()
    {
        $title = "Daftar Tagihan Langganan";
        $titleHeader = "PEMBAYARAN PIUTANG";
        $listPiutang = Piutang::whereNull('is_done')->get();
        
        return view('pembayaran.piutang.index-tagihan',compact('title', 'titleHeader', 'listPiutang'));
    }

    public function showTagihan($id)
    {
        $id = dekrip($id);
        $title = "Detail Tagihan Langganan";
        $titleHeader = "PEMBAYARAN PIUTANG";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-tagihan',compact('title', 'titleHeader', 'piutang'));
    }

    public function cetakTagihan($id)
    {
        $id = dekrip($id);
        $title = "Cetak Tagihan Langganan";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.cetak-tagihan',compact('title', 'piutang'));
    }

    public function prosesTagihan($id)
    {
        $id = dekrip($id);
        $title = "Pembayaran Piutang";
        $titleHeader = "DAFTAR PIUTANG YANG AKAN DIBAYAR";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-final-tagihan',compact('title', 'titleHeader', 'piutang'));
    }

    public function prosesFinalTagihan(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all(), $id);
        $piutang = Piutang::find($id);
        $detail = json_decode($piutang->detail, true)[0]['nama'];
        $idPelanggan = Langganan::where('nama', $detail)->pluck('id')->first();
        $piutang->update([
            'is_done' => 1,
            'jenis_bayar' => $request->jenis_pembayaran,
            'nama_bank' => $request->nama_bank ?? null,
            'id_langganan' => $idPelanggan ?? 1,
        ]);

        return Redirect::route('daftar-tagihan.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Piutang Success!");
    }

    public function destroyTagihan($id)
    {
        $piutang = Piutang::find($id);

        foreach (json_decode($piutang->detail, true) as $detail) {
            $data = Pembayaran::where('nomor_bukti', $detail['nomor_bukti'])->whereNotNull('id_parent')->whereNotNull('date_last')->first();
            if ($data) {
                $data->update(['is_piutang' => null]);
            } else {
                $kredit = Kredit::where('nomor', $detail['nomor_bukti'])->first();
                $kredit->update(['is_piutang' => null]);
            }
        }

        $piutang->delete();

        return Redirect::route('daftar-tagihan.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Pembatalan Piutang Success!");
    }

    public function historyTagihan()
    {
        $title = "Daftar History Tagihan Langganan";
        $titleHeader = "PEMBAYARAN PIUTANG";
        $listPiutang = Piutang::whereNotNull('is_done')->get();
        
        return view('pembayaran.piutang.history-tagihan',compact('title', 'titleHeader', 'listPiutang'));
    }

    public function showHistoryTagihan($id)
    {
        $id = dekrip($id);
        $title = "Detail History Tagihan Langganan";
        $titleHeader = "PEMBAYARAN PIUTANG";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-history-tagihan',compact('title', 'titleHeader', 'piutang'));
    }
    
    // not used
    public function indexHistoryPiutang(HistoryPiutangDataTable $dataTable)
    {
        $title = 'History Pembayaran Piutang';
        
        return $dataTable->render('pembayaran.piutang.index-history', compact('title'));
    }

    // KREDIT
    public function indexKredit()
    {
        $title = "Proses Order Penjualan";
        $titleHeader = "PROSES ORDER PENJUALAN";
        $langganans = Langganan::all();
        
        return view('pembayaran.kredit.index',compact('title', 'titleHeader', 'langganans'));
    }

    public function createDataKredit(Request $request)
    {
        // dd($request->id_langganan);
        $kredit = Kredit::create([
            'id_langganan' => $request->id_langganan,
            'date' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('kredit.edit', enkrip($kredit->id));
    }

    public function editKredit($id)
    {
        $id = dekrip($id);
        $title = "Proses Order Penjualan";
        $titleHeader = "PROSES ORDER PENJUALAN";
        $kredit = Kredit::find($id);


        return view('pembayaran.kredit.edit',compact('title', 'titleHeader', 'kredit'));
    }

    public function listKredit(KreditDataTable $dataTable)
    {
        $title = "Proses Order Penjualan";
        $titleHeader = "PROSES ORDER PENJUALAN";

        return $dataTable->render('pembayaran.kredit.list', compact('title', 'titleHeader'));
    }

    public function daftarKreditProduct(SearchProductDataTable $dataTable, $id)
    {
        $id = dekrip($id);
        $title = 'Add Product';
        $titleHeader = 'LIST BARANG';

        return $dataTable->render('pembayaran.kredit.add-barang', compact('title', 'id', 'titleHeader'));
    }

    public function updateDaftarKreditProduct(Request $request, $id)
    {
        // dd($request->all());
        $id = dekrip($id);
        $kredit = Kredit::find($id);
        $detail = json_decode($kredit->detail, true);

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
        $kredit->detail = json_encode($detail);
        $kredit->total = $kredit->total + $total;
        $kredit->save();

        return redirect()->route('kredit.edit', enkrip($id));
    }

    public function getDataKreditBarcode(Request $request)
    {
        // 8992761111212
        // dd($request->all());
        $product = Product::where('kode_alternatif', $request['kode'])->first();
        if (isset($product)) {
            $kredit = Kredit::find($request['id']);
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
            $detail = json_decode($kredit->detail, true);
            $detail[] = $newEntry;

            $kredit->detail = json_encode($detail);
            $kredit->total += $product->harga_pokok;
            $kredit->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'KODE BELUM TERSEDIA!']);
        }
    }
    
    public function destroyKreditItem(Request $request)
    {
        // dd($request->input('index'), $request->input('retur_id'));
        $index = $request->input('index');
        $kredit = Kredit::find($request->input('kredit_id'));

        if (!$kredit) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        // Decode detail dan hapus data berdasarkan index
        $details = json_decode($kredit->detail, true);
        if (isset($details[$index])) {
            $kredit->total -= $details[$index]['field_total'];
            unset($details[$index]);
            $kredit->detail = json_encode(array_values($details));
            $kredit->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Index tidak valid.']);
    }

    public function saveKreditItem(Request $request)
    {
        $index = $request->input('index');
        $kredit = Kredit::find($request->input('kredit_id'));
        $order = $request->input('order');
        $price = $request->input('price');
        $total = $order * $price;

        if (!$kredit) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        $details = json_decode($kredit->detail, true);
        if (isset($details[$index])) {
            $getTotal = $total - $details[$index]['field_total'];
            $kredit->total += $getTotal;
            $details[$index]['order'] = $order;
            $details[$index]['price'] = $price;
            $details[$index]['field_total'] = $total;
            $kredit->detail = json_encode(array_values($details));
            $kredit->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Index tidak valid.']);
    }

    public function storeKreditData(Request $request, $id)
    {
        // dd($request->all(), $id);

        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastReturn = Kredit::max("nomor");
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
        $getNomorReturn = 'RC-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        $retur = Kredit::find($id);
        $retur->update([
            'nomor' => $getNomorReturn
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

        return Redirect::route('index')->with('alert.status', '00')->with('alert.message', "Sukses Proses Data Kredit!");
    }
    
    public function indexKreditHistory($id)
    {
        $id = dekrip($id);
        $title = "Penjualan Kredit";
        $titleHeader = "PENJUALAN KREDIT";
        $kredit = Kredit::find($id);
        
        return view('pembayaran.kredit.index-history',compact('title', 'titleHeader', 'kredit'));
    }

    public function listKreditHistory(KreditHistoryDataTable $dataTable)
    {
        $title = "Penjualan Kredit";
        $titleHeader = "PENJUALAN KREDIT";

        return $dataTable->render('pembayaran.kredit.list-history', compact('title', 'titleHeader'));
    }
    
    public function cetakKreditHistory($id)
    {
        $id = dekrip($id);
        $title = "Penjualan Kredit";
        $kredit = Kredit::find($id);
        
        return view('pembayaran.kredit.cetak-history',compact('title', 'kredit'));
    }
    
    public function returKredit($id)
    {
        $id = dekrip($id);
        $kredit = Kredit::find($id);
        $kredit->update(['nomor' => null]);
        
        return Redirect::route('index')->with('alert.status', '00')->with('alert.message', "Sukses Retur Data Kredit!");
    }
}
