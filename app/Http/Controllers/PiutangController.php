<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryPiutangDataTable;
use App\Models\Pembayaran;
use App\Models\Piutang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PiutangController extends Controller
{
    public function index()
    {
        $title = "Pembayaran Piutang";
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
        
        return view('pembayaran.piutang.index',compact('title', 'listTunai', 'listBayar', 'getNomor'));
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
        $check = $request->input('check');
        if (count($check) > 1) {
            return redirect()->back()->with('alert.status', '99')->with('alert.message', 'GIRO DAN TUNAI TIDAK BISA DIGABUNG!');
        }

        if ($check !== null) {
            if (isset($check['bayar'])) {
                foreach($check['bayar'] as $checked) {
                    $listData = Pembayaran::where('nomor_bukti', $checked)->whereNotNull('id_parent')->whereNotNull('date_last')->first();
                    $detail[] = [
                        'nama' => $listData->supplier->nama,
                        'nomor_bukti' => $listData->nomor_bukti,
                        'date' => $listData->date,
                        'grand_total' => $listData->grand_total,
                        'beban_materai' => $listData->supplier->materai,
                        'total_with_materai' => $listData->total_with_materai,
                    ];
                    $total += (int) $listData->total_with_materai;
                    $materai += (int) $listData->supplier->materai;

                    $listData->update(['is_piutang' => 1]);
                }
            } else {
                foreach($check['tunai'] as $checked) {
                    $listData = Pembayaran::where('nomor_bukti', $checked)->whereNotNull('id_parent')->whereNotNull('date_last')->first();
                    $detail[] = [
                        'nama' => $listData->supplier->nama,
                        'nomor_bukti' => $listData->nomor_bukti,
                        'date' => $listData->date,
                        'grand_total' => $listData->grand_total,
                        'beban_materai' => 0,
                        'total_with_materai' => $listData->total_with_materai,
                    ];
                    $total += (int) $listData->total_with_materai;

                    $listData->update(['is_piutang' => 1]);
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
        $listPiutang = Piutang::whereNull('is_done')->get();
        
        return view('pembayaran.piutang.index-tagihan',compact('title', 'listPiutang'));
    }

    public function showTagihan($id)
    {
        $id = dekrip($id);
        $title = "Detail Tagihan Langganan";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-tagihan',compact('title', 'piutang'));
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
        $piutang = Piutang::find($id);
        $piutang->update(['is_done' => 1]);

        return Redirect::route('daftar-tagihan.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Piutang Success!");
    }

    public function destroyTagihan($id)
    {
        $piutang = Piutang::find($id);

        foreach (json_decode($piutang->detail, true) as $detail) {
            $data = Pembayaran::where('nomor_bukti', $detail['nomor_bukti'])->whereNotNull('id_parent')->whereNotNull('date_last')->first();
            $data->update(['is_piutang' => null]);
        }

        $piutang->delete();

        return Redirect::route('daftar-tagihan.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Pembatalan Piutang Success!");
    }

    public function historyTagihan()
    {
        $title = "Daftar History Tagihan Langganan";
        $listPiutang = Piutang::whereNotNull('is_done')->get();
        
        return view('pembayaran.piutang.history-tagihan',compact('title', 'listPiutang'));
    }

    public function showHistoryTagihan($id)
    {
        $id = dekrip($id);
        $title = "Detail History Tagihan Langganan";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-history-tagihan',compact('title', 'piutang'));
    }
    
    // not used
    public function indexHistoryPiutang(HistoryPiutangDataTable $dataTable)
    {
        $title = 'History Pembayaran Piutang';
        
        return $dataTable->render('pembayaran.piutang.index-history', compact('title'));
    }
}
