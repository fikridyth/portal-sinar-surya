<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryHutangDataTable;
use App\DataTables\SupplierHutangDataTable;
use App\DataTables\PembayaranDataTable;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\GiroDetail;
use App\Models\HistoryPembayaran;
use App\Models\Hutang;
use App\Models\HutangCetak;
use App\Models\Pembayaran;
use App\Models\PembayaranSecond;
use App\Models\Pengembalian;
use App\Models\Preorder;
use App\Models\Promosi;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use NumberFormatter;

class PembayaranController extends Controller
{
    // public function indexHutang(HutangDataTable $dataTable)
    // {
    //     $title = 'List Pembayaran Hutang';
        
    //     return $dataTable->render('pembayaran.hutang.index', compact('title'));
    // }

    // public function indexHutang()
    // {
    //     $title = 'List Pembayaran Hutang';

    //     $hutang = Hutang::select('id_supplier')->whereNull('nomor_bukti')->where('total', '!=', 0)->whereNull('is_cancel')->whereNotNull('is_proses')->groupBy('id_supplier')->pluck('id_supplier');
    //     $pengembalian = Pengembalian::select('id_supplier')->whereNull('nomor_bukti')->groupBy('id_supplier')->pluck('id_supplier');

    //     $mergedData = $hutang->merge($pengembalian)->unique();
    //     $getSupplier = Supplier::whereIn('id', $mergedData)->get();
    //     $suppliers = Supplier::all();
        
    //     return view('pembayaran.hutang.index', compact('title', 'getSupplier'));
    // }

    public function indexHutang(SupplierHutangDataTable $dataTable)
    {
        $title = 'List Pembayaran Hutang';
        $titleHeader = 'PEMBAYARAN HUTANG';

        return $dataTable->render('pembayaran.hutang.index', compact('title', 'titleHeader'));
    }

    public function showHutang($id)
    {
        $id = dekrip($id);
        $title = 'Detail Pembayaran Hutang';

        $supplier = Supplier::find($id);
        $getHutang = Hutang::where('id_supplier', $supplier->id)->whereNull('nomor_bukti')->whereNull('is_cancel')->whereNotNull('is_proses')->where('total', '!=', 0)->get();
        $getReturn = Pengembalian::where('id_supplier', $supplier->id)->whereNotNull('nomor_return')->whereNull('nomor_bukti')->where('total', '!=', 0)->get();
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
        if ($getHutang->isEmpty()) {
            $allData = $returnData;
        } else {
            $allData = $hutangData->merge($returnData);
        }
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
        // dd($request->all());
        $id = dekrip($id);
        $title = 'Proses Pembayaran Hutang';
        $supplier = Supplier::find($id);
        $promosi = Promosi::select('nomor_promosi as nomor', 'date_first as date', 'date_last', 'total')
                                ->where('id_supplier', $supplier->id)
                                ->where('date_first', '<=', now()->format('Y-m-d'))
                                ->where('date_last', '>=', now()->format('Y-m-d'))
                                ->whereNull('nomor_bukti')
                                ->get()
                                ->toArray();
        foreach ($promosi as &$item) {
            if (isset($item['total']) && is_numeric($item['total'])) {
                $item['total'] = '-' . $item['total'];
            }
        }

        $paramIndices = $request->input('selectedIndices', '[]');
        $paramNomor = $request->input('nomor', '[]');
        $paramDate = $request->input('date', '[]');
        $paramTotal = $request->input('total', '[]');

        $selectedIndicesString = implode($request->input('selectedIndices', '[]'));
        $selectedIndices = json_decode($selectedIndicesString, true);
        if (empty($selectedIndices)) {
            return redirect()->route('pembayaran-hutang.show', enkrip($supplier->id))->with('alert.status', '99')->with('alert.message', 'KODE HARUS DIPILIH!');
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
        $sumTotal = array_sum(array_map('intval', $filteredData['total']));
        
        // cek bila hanya return bawa ke halaman password
        // dd($request->donePass == null);
        if ($request->donePass == null) {
            foreach ($filteredData['nomor'] as $nomor) {
                $parts = explode('-', $nomor);
                if ($parts[0] === 'RP' && $sumTotal > 0) {
                    break;
                } else {
                    $getUser = User::where('name', 'LO HARYANTO')->first();
                    $passUser = $getUser->show_password;
                    $titleHeader = 'Proses Pembayaran Hutang';
                    
                    return view('pembayaran/hutang/password', compact('title', 'titleHeader', 'supplier', 'passUser', 'paramIndices', 'paramNomor', 'paramDate', 'paramTotal'));
                }
            }
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
        
        return view('pembayaran.hutang.process', compact('title', 'supplier', 'getHutangPromosi', 'promosi', 'totalHutang', 'getNomorBukti'));
    }

    public function processFinalHutang(Request $request, $id)
    {
        // dd($request->all());
        $id = dekrip($id);
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
        // if ($totalHutang < 0) {
        //     return redirect()->route('pembayaran-hutang.show', $supplier->id)->with('alert.status', '99')->with('alert.message', 'TOTAL BAYAR TIDAK BOLEH NEGATIVE');
        // }
        // dd($totalHutang);

        if ($request->tipe == 'cetak') {
            $title = "Cetak Hutang";
            $supplier = Supplier::find($id);
            $getNomorBukti = $request->nomor_bukti;

            $formatter = new NumberFormatter('id_ID', NumberFormatter::SPELLOUT);
            $formatTotal = $formatter->format($totalHutang - $supplier->materai);

            $isCetak = HutangCetak::where('nomor', $getHutang[0]['nomor'])->first();
            if (!$isCetak) {
                $isCetak = HutangCetak::create(['nomor' => $getHutang[0]['nomor']]);
                $isCetak->update(['is_cetak' => 1]);
            } else {
                $isCetak->update(['is_cetak' => 1]);
            }

            return view('pembayaran.hutang.cetak', compact('title', 'supplier', 'getHutang', 'totalHutang', 'getNomorBukti', 'formatTotal'));
        }
        
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

        $isCetak = HutangCetak::where('nomor', $getHutang[0]['nomor'])->first();
        if (!$isCetak) {
            $isCetak = HutangCetak::create(['nomor' => $getHutang[0]['nomor']]);
        }

        $materai = $request->materai;
        // dd($materai);

        return view('pembayaran.hutang.process-final', compact('title', 'supplier', 'materai', 'getHutang', 'totalHutang', 'getNomorBukti', 'isCetak'));
    }

    public function storeHutang(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all(), $id);
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
        // dd($getHutang);

        if ($request->tipe == 'cetak') {
            $title = "Cetak Hutang";
            $supplier = Supplier::find($id);
            $getNomorBukti = $request->nomor_bukti;

            $formatter = new NumberFormatter('id_ID', NumberFormatter::SPELLOUT);
            $formatTotal = $formatter->format($totalHutang - $supplier->materai);

            HutangCetak::where('nomor', $getHutang[0]['nomor'])->first()->update(['is_cetak' => 1]);

            return view('pembayaran.hutang.cetak', compact('title', 'supplier', 'getHutang', 'totalHutang', 'getNomorBukti', 'formatTotal'));
        }

        // dd('store');
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
            'nomor_bukti' => $request->nomor_bukti,
            'beban_materai' => $request->materai
        ];
        // dd($dataPembayaran, $request->all(), $getHutang, $totalHutang);

        Pembayaran::create($dataPembayaran);

        return Redirect::route('pembayaran-hutang.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Hutang Success!");
    }

    public function indexHistoryHutangHapus(HistoryHutangDataTable $dataTable)
    {
        $title = 'History Pembayaran Hutang';
        
        return $dataTable->render('pembayaran.hutang.hapus.index-history', compact('title'));
    }

    public function indexHutangHapus(PembayaranDataTable $dataTable)
    {
        $title = 'Hapus Pembayaran Hutang';
        $titleHeader = 'HAPUS PEMBAYARAN HUTANG';
        
        return $dataTable->render('pembayaran.hutang.hapus.index', compact('title', 'titleHeader'));
    }

    public function detailHutangHapus($id)
    {
        $id = dekrip($id);
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
        $isBayar = $pembayaran->is_bayar;
        
        return view('pembayaran.hutang.hapus.show', compact('title', 'pembayaran', 'dataBukti', 'totalHutang', 'isBayar'));
    }

    public function destroyHutang($id)
    {
        $id = dekrip($id);
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
        $titleHeader = 'PEMBAYARAN CEK/GIRO/TUNAI';
        $pembayarans = Pembayaran::whereNull('is_bayar')->whereNull('is_hold')->orderByRaw('CASE WHEN id_parent IS NOT NULL THEN 0 ELSE 1 END')->get();
        $banks = Bank::where('status', 1)->whereHas('giro')->orderByRaw('CASE WHEN nama = "MAYORA S" THEN 0 ELSE 1 END')->get();
        
        return view('pembayaran.index', compact('title', 'titleHeader', 'pembayarans', 'banks'));
    }

    public function show(Request $request, $id)
    {
        $id = dekrip($id);
        $title = 'Detail Pembayaran';
        $titleHeader = 'PEMBAYARAN CEK/GIRO/TUNAI';
        $pembayaran = Pembayaran::find($id);
        $bank = Bank::find($request->bank_id);
        $giros = GiroDetail::where('id_bank', $bank->id)->whereNull('jumlah')->where('flag', '!=', 5)->orderBy('nomor', 'asc')->get();
        
        return view('pembayaran.show', compact('title', 'titleHeader', 'pembayaran', 'bank', 'giros'));
    }

    public function showGabung(Request $request, $ids)
    {
        // $ids = dekrip($ids);
        $request->bank_id = dekrip($request->bank_id);
        $title = 'Detail Pembayaran';
        $titleHeader = 'PEMBAYARAN CEK/GIRO/TUNAI';
        $bank = Bank::find($request->bank_id);
        $giros = GiroDetail::where('id_bank', $bank->id)->whereNull('jumlah')->where('flag', '!=', 5)->orderBy('nomor', 'asc')->get();

        $idArray = explode(',', $ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->get();
        $firstSupplierId = $pembayaran->first()->id_supplier;

        foreach ($pembayaran as $item) {
            if ($item->id_supplier !== $firstSupplierId) {
                return redirect()->back()->withErrors(['error' => 'Data Supplier Tidak Sama.']);
            }
        }
        
        return view('pembayaran.show-gabung', compact('title', 'titleHeader', 'ids', 'pembayaran', 'bank', 'giros'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        $typePayment = (int)$request->type_payment;
        $tunaiPayment = str_replace('.', '', $request->tunai_payment);
        $tunaiOtherIncome = str_replace('.', '', $request->tunai_other_income);
        $giroPayment = str_replace('.', '', $request->giro_payment);
        $giroTunaiPayment = str_replace('.', '', $request->giro_tunai_payment);
        $giroOtherIncome = str_replace('.', '', $request->giro_other_income);
        $transferPayment = str_replace('.', '', $request->transfer_payment);
        $transferTunaiPayment = str_replace('.', '', $request->transfer_tunai_payment);
        $transferOtherIncome = str_replace('.', '', $request->transfer_other_income);
        $bebanMaterai = str_replace('.', '', $request->beban_materai ?? 0);
        if ($typePayment == 0) {
            if ($tunaiPayment + $tunaiOtherIncome > $pembayaran->grand_total) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        } else if ($typePayment == 1) {
            if ($giroPayment + $giroTunaiPayment + $giroOtherIncome > $pembayaran->grand_total) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        } else {
            if ($transferPayment + $transferTunaiPayment + $transferOtherIncome > $pembayaran->grand_total) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        }

        $giroIncrement = GiroDetail::max('increment');
        if ($giroIncrement) {
            // Jika ada nilai increment sebelumnya, tambah 1
            $giroIncrement++;
        } else {
            // Jika tidak ada nilai increment, mulai dari 1
            $giroIncrement = 1;
        }
        // dd($id, $request->all(), $giroPayment + $giroTunaiPayment + $giroOtherIncome, $pembayaran->grand_total);
        
        $getPreorder = Preorder::where('nomor_bukti', $pembayaran->nomor_bukti)->first();
        $getParent = Pembayaran::where('nomor_bukti', $pembayaran->nomor_bukti)->whereNull('id_parent')->first();
        $dataDetail[] = [
            'nomor_bukti' => $getParent->nomor_bukti,
            'date' => $getParent->date,
            'total' => $getParent->grand_total,
        ];
        $dataBukti = response()->json($dataDetail);

        if ($typePayment == 0) {
            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $tunaiPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($tunaiPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => 'TUNAI',
                'date_last' => now()->format('Y-m-d'),
                'tipe_giro' => 'TUNAI',
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $tunaiOtherIncome ?? 0,
                'total_with_materai' => $tunaiOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => 'TUNAI',
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);
        } else if ($typePayment == 1) {
            $giroDetail = GiroDetail::where('nomor', $request->nomor_giro)->first();
            // dd($giroDetail->bank->milik);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $giroPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($giroPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => $request->nomor_giro,
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id,
                'date_last' => $request->date_last ?? now()->format('Y-m-d'),
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $giroTunaiPayment ?? 0,
                'total_with_materai' => $giroTunaiPayment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $giroOtherIncome ?? 0,
                'total_with_materai' => $giroOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            $giroDetail->update([
                'tanggal_awal' => now()->format('Y-m-d'),
                'tanggal_akhir' => $request->date_last ?? now()->format('Y-m-d'),
                'nama' => $request->supplier,
                'jumlah' => $giroPayment,
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'tipe' => 'G',
                'flag' => 2,
                'increment' => $giroIncrement
            ]);
        } else {
            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $transferPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($transferPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => 'TRANSFER',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $id,
                'date_last' => $request->date_last ?? now()->format('Y-m-d'),
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $transferTunaiPayment ?? 0,
                'total_with_materai' => $transferTunaiPayment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $pembayaran->nomor_bukti,
                'grand_total' => $transferOtherIncome ?? 0,
                'total_with_materai' => $transferOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $id,
                'data_bukti' => json_encode($dataBukti->original),
                'is_cabang' => ($pembayaran->tipe_giro == 'CABANG') ? 1 : null
            ]);
        }

        $pembayaran->update(['is_bayar' => 1]);
        if(isset($getPreorder)) {
            $getPreorder->update(['is_pay' => 1]);
        }

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Success!");
    }

    public function updateGabung(Request $request, $ids)
    {
        $idArray = explode(',', $ids);
        $pembayaran = Pembayaran::whereIn('id', $idArray)->get();
        $totalPembayaran = $pembayaran->sum('grand_total');
        $typePayment = (int)$request->type_payment;
        $tunaiPayment = str_replace('.', '', $request->tunai_payment);
        $tunaiOtherIncome = str_replace('.', '', $request->tunai_other_income);
        $giroPayment = str_replace('.', '', $request->giro_payment);
        $giroTunaiPayment = str_replace('.', '', $request->giro_tunai_payment);
        $giroOtherIncome = str_replace('.', '', $request->giro_other_income);
        $transferPayment = str_replace('.', '', $request->transfer_payment);
        $transferTunaiPayment = str_replace('.', '', $request->transfer_tunai_payment);
        $transferOtherIncome = str_replace('.', '', $request->transfer_other_income);
        $bebanMaterai = str_replace('.', '', $request->beban_materai ?? 0);
        if ($typePayment == 0) {
            if ($tunaiPayment + $tunaiOtherIncome > $totalPembayaran) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        } else if ($typePayment == 1) {
            if ($giroPayment + $giroTunaiPayment + $giroOtherIncome > $totalPembayaran) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        } else {
            if ($transferPayment + $transferTunaiPayment + $transferOtherIncome > $totalPembayaran) {
                return redirect()->back()->with('alert.status', '99')->with('alert.message', 'NOMINAL HARUS DIBAWAH TOTAL!');
            }
        }
        // dd($ids, $request->all(), $totalPembayaran);

        $giroIncrement = GiroDetail::max('increment');
        if ($giroIncrement) {
            // Jika ada nilai increment sebelumnya, tambah 1
            $giroIncrement++;
        } else {
            // Jika tidak ada nilai increment, mulai dari 1
            $giroIncrement = 1;
        }

        $nomorBukti = $pembayaran->pluck('nomor_bukti')->implode(',');
        foreach (explode(',', $nomorBukti) as $nomorBkt) {
            $getParent = Pembayaran::where('nomor_bukti', $nomorBkt)->whereNull('id_parent')->first();
            $dataDetail[] = [
                'nomor_bukti' => $getParent->nomor_bukti,
                'date' => $getParent->date,
                'total' => $getParent->grand_total,
            ];
        }
        $dataBukti = response()->json($dataDetail);

        if ($typePayment == 0) {
            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $tunaiPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($tunaiPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => 'TUNAI',
                'date_last' => now()->format('Y-m-d'),
                'tipe_giro' => 'TUNAI',
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $tunaiOtherIncome ?? 0,
                'total_with_materai' => $tunaiOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => 'TUNAI',
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
            ]);
        } else if ($typePayment == 1) {
            $giroDetail = GiroDetail::where('nomor', $request->nomor_giro)->first();
            // dd($giroDetail->bank->milik);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $giroPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($giroPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => $request->nomor_giro,
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id,
                'date_last' => $request->date_last ?? now()->format('Y-m-d'),
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $giroTunaiPayment ?? 0,
                'total_with_materai' => $giroTunaiPayment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $giroOtherIncome ?? 0,
                'total_with_materai' => $giroOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => $giroDetail->bank->milik,
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            $giroDetail->update([
                'tanggal_awal' => now()->format('Y-m-d'),
                'tanggal_akhir' => $request->date_last ?? now()->format('Y-m-d'),
                'nama' => $request->supplier,
                'jumlah' => $giroPayment,
                'nomor_bukti' => $nomorBukti,
                'tipe' => 'G',
                'flag' => 2,
                'increment' => $giroIncrement
            ]);
        } else {
            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $transferPayment ?? 0,
                'beban_materai' => $bebanMaterai,
                'total_with_materai' => ($transferPayment ?? 0) - ($bebanMaterai ?? 0),
                'nomor_giro' => 'TRANSFER',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $pembayaran[0]->id,
                'date_last' => $request->date_last ?? now()->format('Y-m-d'),
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $transferTunaiPayment ?? 0,
                'total_with_materai' => $transferTunaiPayment ?? 0,
                'nomor_giro' => 'TUNAI',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
            ]);

            Pembayaran::create([
                'id_supplier' => $pembayaran[0]->id_supplier,
                'date' => now()->format('Y-m-d'),
                'nomor_bukti' => $nomorBukti,
                'grand_total' => $transferOtherIncome ?? 0,
                'total_with_materai' => $transferOtherIncome ?? 0,
                'nomor_giro' => 'OTHER INCOME',
                'tipe_giro' => 'TRANSFER',
                'id_parent' => $pembayaran[0]->id,
                'data_bukti' => json_encode($dataBukti->original)
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

                $getGiro = Pembayaran::where('id_parent', $parent[0]->id)->whereNotIn('nomor_giro', ['TRANSFER', 'TUNAI', 'OTHER INCOME'])->first();
                if (isset($getGiro)) {
                    $giroDetail = GiroDetail::where('nomor', $getGiro->nomor_giro)->where('flag', 2)->first();
                    $giroDetailIncrement = GiroDetail::whereNotNull('increment')->where('increment', '>', $giroDetail->increment)->get();
                    foreach ($giroDetailIncrement as $incrementItem) {
                        // Lakukan pengurangan 1 pada increment yang lebih besar dari giroDetail->increment
                        $incrementItem->increment = $incrementItem->increment - 1;
                        $incrementItem->save(); // Simpan perubahan
                    }

                    if ($giroDetail) {
                        $giroDetail->update([
                            'nama' => null,
                            'nomor_bukti' => '',
                            'tanggal_awal' => null,
                            'tanggal_akhir' => null,
                            'jumlah' => null,
                            'tipe' => '',
                            'flag' => 1,
                            'increment' => null
                        ]);
                    }
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
                $getGiro = Pembayaran::where('id_parent', $parent->id)->whereNotIn('nomor_giro', ['TRANSFER', 'TUNAI', 'OTHER INCOME'])->first();
                if (isset($getGiro)) {
                    $giroDetail = GiroDetail::where('nomor', $getGiro->nomor_giro)->where('flag', 2)->first();
                    $giroDetailIncrement = GiroDetail::whereNotNull('increment')->where('increment', '>', $giroDetail->increment)->get();
                    foreach ($giroDetailIncrement as $incrementItem) {
                        // Lakukan pengurangan 1 pada increment yang lebih besar dari giroDetail->increment
                        $incrementItem->increment = $incrementItem->increment - 1;
                        $incrementItem->save(); // Simpan perubahan
                    }
                    
                    if ($giroDetail) {
                        $giroDetail->update([
                            'nama' => null,
                            'nomor_bukti' => '',
                            'tanggal_awal' => null,
                            'tanggal_akhir' => null,
                            'jumlah' => null,
                            'tipe' => '',
                            'flag' => 1,
                            'increment' => null
                        ]);
                    }
                }
                Pembayaran::where('id_parent', $parent->id)->delete();
                $parent->update(['is_bayar' => null]);
                if(isset($getPreorder)) {
                    $getPreorder->update(['is_pay' => 1]);
                }
            }
        }
        
        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Pembayaran Success!");
    }

    public function listCetakPayment()
    {
        $title = 'List Cetak Pembayaran';
        $tunai = Pembayaran::where('tipe_giro', 'TUNAI')->where('nomor_giro', 'TUNAI')->whereNull('is_bayar')->get();
        $transfer = Pembayaran::where('tipe_giro', 'TRANSFER')->where('nomor_giro', 'TRANSFER')->whereNull('is_bayar')->get();
        $giro = Pembayaran::whereIn('tipe_giro', ['CABANG', 'SENDIRI'])->whereNotNull('date_last')->whereNull('is_bayar')->get();
        $pembayarans = $tunai->merge($transfer)->merge($giro);

        return view('pembayaran.list-cetak-bayar', compact('title', 'pembayarans'));
    }

    public function paramCetakPayment($ids)
    {
        $title = 'Parameter Cetak Pembayaran';

        return view('pembayaran.param-cetak-bayar', compact('title', 'ids'));
    }

    public function cetakPayment(Request $request)
    {
        $title = 'Cetak Pembayaran';
        $parameter = $request->param;
        $idArray = explode(',', $request->ids);
        $getBayar = [];
        foreach ($idArray as $id) {
            $bayar = Pembayaran::find($id);
            $getBayarIds = Pembayaran::where('id_parent', $bayar->id_parent)->pluck('id')->toArray();
            $getBayar = array_merge($getBayar, $getBayarIds);
        }
        $pembayaran = Pembayaran::whereIn('id', $getBayar)->where('nomor_giro', '!=', 'OTHER INCOME')->whereNotNull('nomor_giro')->get()->groupBy('nomor_bukti');
        $increment = 1;
        $pembayaran = $pembayaran->map(function ($items, $key) use (&$increment) {
            $nomor = str_pad($increment++, 3, 0, STR_PAD_LEFT); // Menambahkan increment 1
            return [
                'nomor' => $nomor,
                'data' => $items,
            ];
        });
        $pembayaran1 = []; // Untuk nomor ganjil
        $pembayaran2 = []; // Untuk nomor genap

        foreach ($pembayaran as $item) {
            $nomorList = $item['nomor'];
            if (isset($nomorList[2])) {
                if ($nomorList[2] % 2 !== 0) {
                    $pembayaran1[] = $item; // Masukkan ke pembayaran1 (ganjil)
                } else {
                    $pembayaran2[] = $item; // Masukkan ke pembayaran2 (genap)
                }
            }
        }
        // dd($pembayaran, $pembayaran1, $pembayaran2);

        // update cetak
        $pembayaranAll = Pembayaran::whereIn('id', $getBayar)->update(['is_cetak' => 1]);

        return view('pembayaran.cetak-bayar', compact('title', 'parameter', 'pembayaran1', 'pembayaran2'));
    }

    public function konfirmasiPayment($ids)
    {
        $idArray = explode(',', $ids);
        // dd($idArray);
        Pembayaran::whereIn('id', $idArray)->update(['is_bayar' => 1]);
        GiroDetail::query()->update(['increment' => null]);

        return Redirect::route('index')
        ->with('alert.status', '00')
        ->with('alert.message', "Konfirmasi Pembayaran Success!");
    }

    public function indexKonfirmasi()
    {
        $title = 'Konfirmasi Pembayaran';
        $suppliers = Supplier::all();
        
        return view('pembayaran.konfirmasi.index', compact('title', 'suppliers'));
    }

    public function showKonfirmasi(Request $request)
    {
        $request->id_supplier = dekrip($request->id_supplier);
        $title = 'Show Konfirmasi Pembayaran';
        $titleHeader = 'KONFIRMASI CEK / GIRO / TUNAI';
        $supplier = Supplier::find($request->id_supplier);

        if ($request->range == 1) {
            $explodeDate = explode(' - ', $request->periode);
            $pembayarans = Pembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('nomor_giro')
                ->where('is_bayar', 1)
                ->where('date', '>=', $explodeDate[0])
                ->where('date', '<=', $explodeDate[1])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->orderBy('id')
                ->get();
            
            $historypmb = HistoryPembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('date')
                ->where('date', '>=', $explodeDate[0])
                ->where('date', '<=', $explodeDate[1])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->get();
        } else if ($request->range == 2) {
            $pembayarans = Pembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('nomor_giro')
                ->where('is_bayar', 1)
                ->where('date', '=', now()->format('Y-m-d'))
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->orderBy('id')
                ->get();
            
            $historypmb = HistoryPembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('date')
                ->where('date', '=', now()->format('Y-m-d'))
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->get();
        } else if ($request->range == 3) {
            $pembayarans = Pembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('nomor_giro')
                ->where('is_bayar', 1)
                ->whereBetween('date', [now()->subDays(7)->format('Y-m-d'), now()->format('Y-m-d')])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->orderBy('id')
                ->get();
            
            $historypmb = HistoryPembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('date')
                ->whereBetween('date', [now()->subDays(7)->format('Y-m-d'), now()->format('Y-m-d')])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->get();
        } else if ($request->range == 4) {
            $pembayarans = Pembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('nomor_giro')
                ->where('is_bayar', 1)
                ->whereBetween('date', [now()->subDays(30)->format('Y-m-d'), now()->format('Y-m-d')])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->orderBy('id')
                ->get();
            
            $historypmb = HistoryPembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('date')
                ->whereBetween('date', [now()->subDays(30)->format('Y-m-d'), now()->format('Y-m-d')])
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->get();
        } else {
            $pembayarans = Pembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('nomor_giro')
                ->where('is_bayar', 1)
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->orderBy('id')
                ->get();
            
            $historypmb = HistoryPembayaran::where('id_supplier', $supplier->id)
                ->whereNotNull('date')
                ->orderBy('date', 'desc')
                ->orderBy('nomor_bukti', 'desc')
                ->get();
            
            // dd($historypmb, count($historypmb));
        }
        // dd($request->all(), $pembayarans);
        
        return view('pembayaran.konfirmasi.show', compact('title', 'titleHeader', 'supplier', 'pembayarans', 'historypmb'));
    }

    public function indexCabang()
    {
        $title = 'List Cabang';
        $pembayarans = Pembayaran::whereNull('is_bayar')->whereNotNull('is_hold')->get();
        return view('pembayaran.cabang.index', compact('title', 'pembayarans'));
    }

    public function browseCabang()
    {
        $title = 'Browse Cabang';
        $cabangs = Cabang::all();
        return view('pembayaran.cabang.browse', compact('title', 'cabangs'));
    }

    public function showCabang($id)
    {
        $id = dekrip($id);
        $title = 'Show Cabang';
        $cabang = Cabang::find($id);
        $suppliers = Supplier::orderBy('nama', 'asc')->get();
        try {
            // Cek koneksi ke database client
            DB::connection('mysql_second')->getPdo();
            $listBayar = PembayaranSecond::whereNull('is_cabang')->get();
        } catch (\PDOException $e) {
            return Redirect::route('pembayaran.cabang-index')
                ->with('alert.status', '99')
                ->with('alert.message', "Ambil data dari cabang gagal! (Koneksi Jaringan)");
        } catch (QueryException $e) {
            return Redirect::route('pembayaran.cabang-index')
                ->with('alert.status', '99')
                ->with('alert.message', "Ambil data dari cabang gagal! (Gagal Query)");
        }
        return view('pembayaran.cabang.show', compact('title', 'cabang', 'listBayar', 'suppliers'));
    }

    public function storeCabang(Request $request)
    {
        try {
            // Cek koneksi ke database client
            DB::connection('mysql_second')->getPdo();
            $bayar = PembayaranSecond::find($request->nomor_bukti);
            $supplier = Supplier::where('nomor', $request->data)->first();
            Pembayaran::create([
                'id_supplier' => $supplier->id,
                'date' => $bayar->date,
                'total' => $bayar->total,
                'ppn' => $bayar->ppn,
                'grand_total' => $bayar->grand_total,
                'nomor_bukti' => $bayar->nomor_bukti,
                'tipe_giro' => 'CABANG',
                'is_hold' => 1
            ]);
            $bayar->update([
                'is_cabang' => 1,
                'is_bayar' => 1,
                'nomor_giro' => 1,
                'total_with_materai' => $bayar->grand_total
            ]);
    
            return Redirect::route('pembayaran.cabang-index')
                ->with('alert.status', '00')
                ->with('alert.message', "Add Pembayaran Cabang Success!");
        } catch (\PDOException $e) {
            return Redirect::route('pembayaran.cabang-index')
                ->with('alert.status', '99')
                ->with('alert.message', "Ambil data dari cabang gagal! (Koneksi Jaringan)");
        } catch (QueryException $e) {
            return Redirect::route('pembayaran.cabang-index')
                ->with('alert.status', '99')
                ->with('alert.message', "Ambil data dari cabang gagal! (Gagal Query)");
        }
    }

    public function updateCabang(Request $request)
    {
        foreach ($request->input('id') as $id) {
            $bayar = Pembayaran::find($id);
            $bayar->update(['is_hold' => null]);
        }

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Cabang Success!");
    }
}
