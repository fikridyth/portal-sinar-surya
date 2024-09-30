<?php

namespace App\Http\Controllers;

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
        $listBayar = Pembayaran::where('tipe_giro', 'CABANG')->whereNotNull('date_last')->whereNull('is_piutang')->get();
        $listUser = User::all();

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
        
        return view('pembayaran.piutang.index',compact('title', 'listBayar', 'listUser', 'getNomor'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $detail = [];
        $total = $materai = 0;
        if ($request->input('check') !== null) {
            foreach($request->input('check') as $checked) {
                $listData = Pembayaran::where('nomor_bukti', $checked)->whereNotNull('id_parent')->whereNotNull('date_last')->first();
                $detail[] = [
                    'nama' => $listData->supplier->nama,
                    'nomor_bukti' => $listData->nomor_bukti,
                    'date' => $listData->date,
                    'grand_total' => $listData->grand_total,
                    'beban_materai' => $listData->beban_materai,
                    'total_with_materai' => $listData->total_with_materai,
                ];
                $total += (int) $listData->total_with_materai;
                $materai += (int) $listData->beban_materai;

                $listData->update(['is_piutang' => 1]);
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
        $listPiutang = Piutang::all();
        
        return view('pembayaran.piutang.index-tagihan',compact('title', 'listPiutang'));
    }

    public function showTagihan($id)
    {
        $title = "Detail Tagihan Langganan";
        $piutang = Piutang::find($id);
        
        return view('pembayaran.piutang.show-tagihan',compact('title', 'piutang'));
    }

    public function cetakTagihan($id)
    {
        $title = "Cetak Tagihan Langganan";
        $piutang = Piutang::find($id);
        dd($piutang);
        
        return view('pembayaran.piutang.cetak-tagihan',compact('title', 'piutang'));
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
}
