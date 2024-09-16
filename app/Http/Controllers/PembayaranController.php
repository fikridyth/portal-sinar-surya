<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryPembayaranDataTable;
use App\DataTables\HutangDataTable;
use App\DataTables\PembayaranDataTable;
use App\Models\Bank;
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
        $pembayarans = Pembayaran::whereNull('is_bayar')->get();
        $banks = Bank::orderByRaw('is_default desc')->get();
        
        return view('pembayaran.index', compact('title', 'pembayarans', 'banks'));
    }

    public function show($id)
    {
        $title = 'Detail Pembayaran';
        $pembayaran = Pembayaran::find($id);
        $banks = Bank::orderBy('nama', 'desc')->get();
        
        return view('pembayaran.show', compact('title', 'pembayaran', 'banks'));
    }

    public function update(Request $request, $id)
    {
        // dd($id, $request->all());
        $pembayaran = Pembayaran::find($id);
        $getPreorder = Preorder::where('nomor_bukti', $pembayaran->nomor_bukti)->first();
        // dd($getPreorder);

        $pembayaran->update([
            'is_bayar' => 1,
        ]);

        Pembayaran::create([
            'id_supplier' => $pembayaran->id_supplier,
            'date' => now()->format('Y-m-d'),
            'nomor_po' => $pembayaran->nomor_po,
            'nomor_bukti' => $pembayaran->nomor_bukti,
            'grand_total' => $request->payment ?? 0,
            'nomor_giro' => 'TUNAI',
            'is_bayar' => 1,
            'id_parent' => $id
        ]);

        Pembayaran::create([
            'id_supplier' => $pembayaran->id_supplier,
            'date' => now()->format('Y-m-d'),
            'nomor_po' => $pembayaran->nomor_po,
            'nomor_bukti' => $pembayaran->nomor_bukti,
            'grand_total' => $request->other_income ?? 0,
            'nomor_giro' => 'OTHER INCOME',
            'is_bayar' => 1,
            'id_parent' => $id
        ]);

        $getPreorder->update([
            'is_pay' => 1,
        ]);

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Pembayaran Success!");
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::find($id);
        $parent = Pembayaran::find($pembayaran->id_parent);
        $getPreorder = Preorder::where('nomor_po', $parent->nomor_po)->first();
        
        Pembayaran::where('id_parent', $parent->id)->delete();
        $parent->update(['is_bayar' => null]);
        $getPreorder->update(['is_pay' => null]);
        
        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Pembayaran Success!");
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
