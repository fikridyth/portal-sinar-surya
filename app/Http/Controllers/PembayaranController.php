<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryPembayaranDataTable;
use App\DataTables\HutangDataTable;
use App\DataTables\PembayaranDataTable;
use App\Models\Bank;
use App\Models\GiroDetail;
use App\Models\Hutang;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Preorder;
use App\Models\Promosi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;

class PembayaranController extends Controller
{
    public function indexHutang(HutangDataTable $dataTable)
    {
        $title = 'List Pembayaran Hutang';
        
        return $dataTable->render('pembayaran.hutang.index', compact('title'));
    }

    public function showHutang($id)
    {
        $title = 'Detail Pembayaran Hutang';

        $supplier = Supplier::find($id);
        $getHutang = Hutang::where('id_supplier', $supplier->id)->whereNull('nomor_bukti')->where('total', '!=', 0)->get();
        $getReturn = Pengembalian::where('id_supplier', $supplier->id)->whereNull('nomor_bukti')->where('total', '!=', 0)->get();
        $hutangData = $getHutang->map(function($item) {
            return [
                'nomor' => $item->nomor_receive,
                'date' => $item->date,
                'total' => $item->grand_total,
            ];
        });
        $returnData = $getReturn->map(function($item) {
            return [
                'nomor' => $item->nomor_return,
                'date' => $item->date,
                'total' => '-' . $item->total,
            ];
        });
        $allData = $hutangData->merge($returnData);
        $getAllData = $allData->toArray();

        // get nomor bukti
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Hutang::max("nomor_bukti");
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
        $getNomorBukti = 'PH-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        
        return view('pembayaran.hutang.show', compact('title', 'supplier', 'getAllData', 'getNomorBukti'));
    }

    public function processHutang(Request $request, $id)
    {
        $title = 'Proses Pembayaran Hutang';

        
        $supplier = Supplier::find($id);
        $promosi = Promosi::select('nomor_promosi as nomor', 'date_last as date', 'total')
                                ->where('id_supplier', $supplier->id)
                                ->where('date_last', '<=', now()->format('Y-m-d'))
                                ->whereNull('nomor_bukti')
                                ->get()
                                ->toArray();
        foreach ($promosi as &$item) {
            if (isset($item['total']) && is_numeric($item['total'])) {
                $item['total'] = '-' . $item['total'];
            }
        }

        $selectedIndicesString = implode($request->input('selectedIndices', '[]'));
        $selectedIndices = json_decode($selectedIndicesString, true);
        if (empty($selectedIndices)) {
            return redirect()->route('pembayaran-hutang.show', $supplier->id)->with('alert.status', '99')->with('alert.message', 'KODE HARUS DIPILIH!');
        }

        // Sample $allData from request
        $allData = $request->only(['nomor', 'date', 'total']);

        // Initialize arrays to store filtered results
        $filteredData = [
            'nomor' => [],
            'date' => [],
            'total' => []
        ];

        // Iterate over the selected indices and filter the data
        foreach ($selectedIndices as $index) {
            $filteredData['nomor'][] = $allData['nomor'][$index];
            $filteredData['date'][] = $allData['date'][$index];
            $filteredData['total'][] = $allData['total'][$index];
        }

        $getHutang = [];
        foreach ($selectedIndices as $index) {
            // Ensure index exists in all arrays
            if (isset($allData['nomor'][$index], $allData['date'][$index], $allData['total'][$index])) {
                $getHutang[$index] = [
                    'nomor' => $allData['nomor'][$index],
                    'date'  => $allData['date'][$index],
                    'total' => $allData['total'][$index],
                ];
            }
        }

        $combinedArray = array_merge($getHutang, $promosi);
        $getHutangPromosi = array_values($combinedArray);
        $totalHutang = array_sum(array_column($getHutangPromosi, 'total'));

        // get nomor bukti
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Hutang::max("nomor_bukti");
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
        $getNomorBukti = 'PH-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);
        
        return view('pembayaran.hutang.process', compact('title', 'supplier', 'getHutangPromosi', 'totalHutang', 'getNomorBukti'));
    }

    public function processFinalHutang(Request $request, $id)
    {
        $title = 'Proses Pembayaran Hutang';
        $supplier = Supplier::find($id);
        $dataArray = array_filter($request->all(), function($value) {
            return is_array($value);
        });

        $getHutang = [];
        for ($i = 0; $i < count($dataArray['nomor']); $i++) {
            $getHutang[$i] = [
                "nomor" => $dataArray['nomor'][$i],
                "date" => $dataArray['date'][$i],
                "total" => $dataArray['total'][$i]
            ];
        }
        $totalHutang = array_sum(array_column($getHutang, 'total'));
        if ($totalHutang < 0) {
            return redirect()->route('pembayaran-hutang.show', $supplier->id)->with('alert.status', '99')->with('alert.message', 'TOTAL BAYAR TIDAK BOLEH NEGATIVE');
        }
        // dd($totalHutang);
        
        // get nomor bukti
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Hutang::max("nomor_bukti");
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
        $getNomorBukti = 'PH-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        return view('pembayaran.hutang.process-final', compact('title', 'supplier', 'getHutang', 'totalHutang', 'getNomorBukti'));
    }

    public function storeHutang(Request $request, $id)
    {
        $dataArray = array_filter($request->all(), function($value) {
            return is_array($value);
        });
        // dd($request->all(), $dataArray);

        $getHutang = [];
        for ($i = 0; $i < count($dataArray['nomor']); $i++) {
            $getHutang[$i] = [
                "nomor" => $dataArray['nomor'][$i],
                "date" => $dataArray['date'][$i],
                "total" => $dataArray['total'][$i]
            ];
        }
        $totalHutang = array_sum(array_column($getHutang, 'total'));

        foreach($getHutang as $data) {
            $tipeData = explode('-', $data['nomor']);
            if ($tipeData[0] == 'RP') {
                Hutang::where('nomor_receive', $data['nomor'])->update(['nomor_bukti' => $request->nomor_bukti]);
                Preorder::where('nomor_receive', $data['nomor'])->update(['nomor_bukti' => $request->nomor_bukti]);
            }
            if ($tipeData[0] == 'RR') {
                Pengembalian::where('nomor_return', $data['nomor'])->update(['nomor_bukti' => $request->nomor_bukti]);
            }
            // if ($tipeData[0] == 'TARGET' || $tipeData[0] == 'GONDOLA') {
            //     $total = explode('-', $data['total']);
            //     Promosi::where('tipe', $data['nomor'])->where('date_last', $data['date'])->where('total', $total[1])->update(['nomor_bukti' => $request->nomor_bukti]);
            // }
            if ($tipeData[0] == 'AM') {
                Promosi::where('nomor_promosi', $data['nomor'])->update(['nomor_bukti' => $request->nomor_bukti]);
            }
        }

        $dataPembayaran = [
            'id_supplier' => $id,
            'date' => $request->date_payment,
            'total' => $totalHutang,
            'grand_total' => $totalHutang,
            'nomor_bukti' => $request->nomor_bukti
        ];
        // dd($dataPembayaran, $request->all(), $getHutang, $totalHutang);

        Pembayaran::create($dataPembayaran);

        return Redirect::route('pembayaran-hutang.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Hutang Success!");
    }

    public function indexHutangHapus(PembayaranDataTable $dataTable)
    {
        $title = 'Hapus Pembayaran Hutang';
        
        return $dataTable->render('pembayaran.hutang.hapus.index', compact('title'));
    }

    public function detailHutangHapus($id)
    {
        $title = 'Detail Hapus Pembayaran Hutang';
        $pembayaran = Pembayaran::find($id);
        $hutang = Hutang::select('nomor_receive as nomor', 'grand_total as total')
            ->where('nomor_bukti', $pembayaran->nomor_bukti)
            ->get()
            ->map(function($item) {
                $item->keterangan = 'PEMBAYARAN HUTANG';
                return $item;
            });

        $pengembalian = Pengembalian::select('nomor_return as nomor', 'total')
            ->where('nomor_bukti', $pembayaran->nomor_bukti)
            ->get()
            ->map(function($item) {
                $item->keterangan = 'PEMBAYARAN HUTANG';
                $item->total = -$item->total;
                return $item;
            });

        $promosi = Promosi::select('nomor_promosi as nomor', 'total')
            ->where('nomor_bukti', $pembayaran->nomor_bukti)
            ->get()
            ->map(function($item) {
                $item->keterangan = 'BIAYA PROMOSI';
                $item->total = -$item->total;
                return $item;
            });
        $dataBukti = $hutang->concat($pengembalian)->concat($promosi);
        $totalHutang = array_sum(array_column($dataBukti->toArray(), 'total'));
        // dd($dataBukti);
        
        return view('pembayaran.hutang.hapus.show', compact('title', 'pembayaran', 'dataBukti', 'totalHutang'));
    }

    public function destroyHutang($id)
    {
        $pembayaran = Pembayaran::find($id);

        Hutang::where('nomor_bukti', $pembayaran->nomor_bukti)->update(['nomor_bukti' => null]);
        Preorder::where('nomor_bukti', $pembayaran->nomor_bukti)->update(['nomor_bukti' => null]);
        Pengembalian::where('nomor_bukti', $pembayaran->nomor_bukti)->update(['nomor_bukti' => null]);
        Promosi::where('nomor_bukti', $pembayaran->nomor_bukti)->update(['nomor_bukti' => null]);
        
        $pembayaran->delete();
        
        return Redirect::route('pembayaran-hutang.index-hapus')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Hutang Success!");
    }

    public function index()
    {
        $title = 'List Pembayaran';
        $pembayarans = Pembayaran::whereNull('is_bayar')->orderByRaw('CASE WHEN id_parent IS NOT NULL THEN 0 ELSE 1 END')->get();
        $banks = Bank::where('status', 1)->whereHas('giro')->orderByRaw('CASE WHEN nama = "MAYORA S" THEN 0 ELSE 1 END')->get();
        
        return view('pembayaran.index', compact('title', 'pembayarans', 'banks'));
    }

    public function show(Request $request, $id)
    {
        $title = 'Detail Pembayaran';
        $pembayaran = Pembayaran::find($id);
        $bank = Bank::find($request->bank_id);
        $giros = GiroDetail::where('id_bank', $bank->id)->whereNull('jumlah')->orderBy('nomor', 'asc')->get();
        
        return view('pembayaran.show', compact('title', 'pembayaran', 'bank', 'giros'));
    }

    public function showGabung(Request $request, $ids)
    {
        $title = 'Detail Pembayaran';
        $bank = Bank::find($request->bank_id);
        $giros = GiroDetail::where('id_bank', $bank->id)->whereNull('jumlah')->orderBy('nomor', 'asc')->get();

        $idArray = explode(',', $ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->get();
        $firstSupplierId = $pembayaran->first()->id_supplier;

        foreach ($pembayaran as $item) {
            if ($item->id_supplier !== $firstSupplierId) {
                return redirect()->back()->withErrors(['error' => 'Data Supplier Tidak Sama.']);
            }
        }
        
        return view('pembayaran.show-gabung', compact('title', 'ids', 'pembayaran', 'bank', 'giros'));
    }

    public function update(Request $request, $id)
    {
        // dd($id, $request->all());
        $typePayment = (int)$request->type_payment;
        $pembayaran = Pembayaran::find($id);
        $getPreorder = Preorder::where('nomor_bukti', $pembayaran->nomor_bukti)->first();
        // dd($getPreorder);
        
        $pembayaran->update([
            'is_bayar' => 1,
        ]);

        if ($typePayment == 0) {
            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $request->tunai_payment ?? 0,
                'nomor_giro' => 'TUNAI',
                'id_parent' => $id
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $request->tunai_other_income ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'id_parent' => $id
            ]);
        } else {
            $giroDetail = GiroDetail::where('nomor', $request->nomor_giro)->first();
            // dd($giroDetail->bank->milik);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $request->giro_payment ?? 0,
                'nomor_giro' => $request->nomor_giro,
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id,
                'date_last' => $request->date_last
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $request->giro_tunai_payment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $request->giro_other_income ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id
            ]);

            $giroDetail->update([
                'nama' => $request->supplier,
                'nomor_bukti' => $request->nomor_bukti,
                'tanggal_awal' => now()->format('Y-m-d'),
                'tanggal_akhir' => $request->date_last ?? now()->format('Y-m-d'),
                'jumlah' => $request->giro_payment,
                'tipe' => 'G',
                'flag' => 2
            ]);
        }

        $getPreorder->update([
            'is_pay' => 1,
        ]);

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Success!");
    }

    public function updateGabung(Request $request, $ids)
    {
        // dd($ids, $request->all());
        $typePayment = (int)$request->type_payment;
        $idArray = explode(',', $ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->get();
        $nomorBukti = $pembayaran->pluck('nomor_bukti')->implode(',');

        if ($typePayment == 0) {
            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $request->tunai_payment ?? 0,
                'nomor_giro' => 'TUNAI',
                'id_parent' => $pembayaran[0]->id
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $request->tunai_other_income ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'id_parent' => $pembayaran[0]->id
            ]);
        } else {
            $giroDetail = GiroDetail::where('nomor', $request->nomor_giro)->first();
            // dd($giroDetail->bank->milik);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $request->giro_payment ?? 0,
                'nomor_giro' => $request->nomor_giro,
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id,
                'date_last' => $request->date_last
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $request->giro_tunai_payment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $request->giro_other_income ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id
            ]);

            $giroDetail->update([
                'nama' => $request->supplier,
                'nomor_bukti' => $request->nomor_bukti,
                'tanggal_awal' => now()->format('Y-m-d'),
                'tanggal_akhir' => $request->date_last ?? now()->format('Y-m-d'),
                'jumlah' => $request->giro_payment,
                'tipe' => 'G',
                'flag' => 2
            ]);
        }

        foreach ($pembayaran as $bayar) {
            $bayar->update(['is_bayar' => 1]);
            Preorder::where('nomor_bukti', $bayar->nomor_bukti)->update(['is_pay' => 1]);
        }

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Success!");
    }

    public function destroyPayment($ids)
    {
        $idArray = explode(',', $ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->get();
        
        if (strpos($pembayaran[0]->nomor_bukti, ',') !== false)
        {
            // GABUNG
            foreach ($pembayaran as $bayar)
            {
                $buktiArray = explode(',', $bayar->nomor_bukti);
                $parent = Pembayaran::whereIn('nomor_bukti', $buktiArray)->get();

                $getGiro = Pembayaran::where('id_parent', $parent[0]->id)->whereNotIn('nomor_giro', ['TUNAI', 'OTHER INCOME'])->first();
                if (isset($getGiro->nomor_giro)) {
                    GiroDetail::where('nomor', $getGiro->nomor_giro)->first()->update([
                        'nama' => null,
                        'nomor_bukti' => '',
                        'tanggal_awal' => null,
                        'tanggal_akhir' => null,
                        'jumlah' => null,
                        'tipe' => '',
                        'flag' => 1
                    ]);
                }
                
                Pembayaran::where('id_parent', $parent[0]->id)->delete();
                foreach ($parent as $prt) {
                    $prt->update(['is_bayar' => null]);
                    Preorder::where('nomor_bukti', $prt->nomor_bukti)->update(['is_pay' => null]);
                }

            }
        } else {
            // SATUAN
            foreach ($pembayaran as $bayar)
            {
                $parent = Pembayaran::find($bayar->id_parent);
                $getPreorder = Preorder::where('nomor_bukti', $parent->nomor_bukti)->first();
                $getGiro = Pembayaran::where('id_parent', $parent->id)->whereNotIn('nomor_giro', ['TUNAI', 'OTHER INCOME'])->first();
                if (isset($getGiro->nomor_giro)) {
                    GiroDetail::where('nomor', $getGiro->nomor_giro)->first()->update([
                        'nama' => null,
                        'nomor_bukti' => '',
                        'tanggal_awal' => null,
                        'tanggal_akhir' => null,
                        'jumlah' => null,
                        'tipe' => '',
                        'flag' => 1
                    ]);
                }
                Pembayaran::where('id_parent', $parent->id)->delete();
                $parent->update(['is_bayar' => null]);
                $getPreorder->update(['is_pay' => null]);
            }
        }
        
        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Pembayaran Success!");
    }

    public function paramCetakPayment($ids)
    {
        $title = 'Parameter Pembayaran';
        $idArray = explode(',', $ids);

        return view('pembayaran.param-cetak-bayar', compact('title', 'ids'));
    }

    public function cetakPayment(Request $request)
    {
        $title = 'Cetak Pembayaran';
        $parameter = $request->param;
        $idArray = explode(',', $request->ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->where('nomor_giro', '!=', 'OTHER INCOME')->whereNotNull('nomor_giro')->whereNull('is_cetak')->get()->groupBy('nomor_bukti');

        $pembayaran1 = $pembayaran->filter(function ($item) {
            // dd($item[0]->nomor_bukti);
            if (isset($item[0]->nomor_bukti)) {
                if (strpos($item[0]->nomor_bukti, ',') !== false) {
                    $getFirst = explode(',', $item[0]->nomor_bukti);
                    $explode = explode('-', $getFirst[0]);
                    return isset($explode[2]) && $explode[2] % 2 !== 0; // Ganjil
                } else {
                    $explode = explode('-', $item->first()->nomor_bukti);
                    return isset($explode[2]) && $explode[2] % 2 !== 0; // Ganjil
                }
            }
        });
        
        $pembayaran2 = $pembayaran->filter(function ($item) {
            if (isset($item[0]->nomor_bukti)) {
                if (strpos($item[0]->nomor_bukti, ',') !== false) {
                    $getFirst = explode(',', $item[0]->nomor_bukti);
                    $explode = explode('-', $getFirst[0]);
                    return $explode[2] % 2 === 0; // Genap
                } else {
                    $explode = explode('-', $item->first()->nomor_bukti);
                    return $explode[2] % 2 === 0; // Genap
                }
            }
        });

        if ($pembayaran1->isEmpty()) {
            if ($pembayaran2->count() >= 2) {
                // Bagi data genap ke pembayaran1 dan pembayaran2
                $pembayaran1 = $pembayaran2->take(1); // Ambil satu item untuk pembayaran1
                $pembayaran2 = $pembayaran2->slice(1); // Sisanya untuk pembayaran2
            } else {
                $pembayaran1 = $pembayaran2; // Semua genap dipindahkan ke pembayaran1
                $pembayaran2 = collect(); // Kosongkan pembayaran2 jika semua dipindahkan
            }
        }

        // update cetak
        $pembayaranAll = Pembayaran::whereIn('id', $idArray)->update(['is_cetak' => 1]);

        return view('pembayaran.cetak-bayar', compact('title', 'parameter', 'pembayaran1', 'pembayaran2'));
    }

    public function konfirmasiPayment($ids)
    {
        $idArray = explode(',', $ids);
        Pembayaran::whereIn('id', $idArray)->update(['is_bayar' => 1]);

        return Redirect::route('index')
        ->with('alert.status', '00')
        ->with('alert.message', "Konfirmasi Pembayaran Success!");
    }

    public function indexHistory(HistoryPembayaranDataTable $dataTable)
    {
        $title = 'Hapus Pembayaran Hutang';
        
        return $dataTable->render('pembayaran.index-history', compact('title'));
    }

    public function destroyHistory($id)
    {
        $pembayaran = Pembayaran::find($id);
        Pembayaran::where('id_parent', $pembayaran->id_parent)->delete();
        Pembayaran::where('id', $pembayaran->id_parent)->update(['is_bayar' => null]);
        
        return Redirect::route('pembayaran.index-history')
            ->with('alert.status', '00')
            ->with('alert.message', "Hapus History Pembayaran Success!");
    }
}
