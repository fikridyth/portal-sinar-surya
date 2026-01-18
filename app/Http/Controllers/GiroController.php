<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\GiroDetail;
use App\Models\GiroHeader;
use App\Models\Pembayaran;
use App\Models\Preorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GiroController extends Controller
{
    public function indexBank()
    {
        $title = 'Master Bank';
        $titleHeader = 'MASTER BANK';
        $banks = Bank::orderBy('nama')->get();

        return view('master.bank.index', compact('title', 'titleHeader', 'banks'));
    }

    public function storeBank(Request $request)
    {
        // dd($request->all());
        Bank::create([
            'nama' => $request->bank_nama,
            'no_rekening' => $request->bank_no_rekening,
            'milik' => $request->bank_milik,
            'status' => 1
        ]);

        return Redirect::route('master.bank.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Tambah Bank Success!");
    }

    public function updateBank(Request $request, $id)
    {
        Bank::find($id)->update([
            'nama' => $request->bank_nama,
            'no_rekening' => $request->bank_no_rekening,
            'milik' => $request->bank_milik
        ]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function destroyBank($id)
    {
        Bank::find($id)->delete();

        return Redirect::route('master.bank.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Bank Success!");
    }

    public function indexCekGiro()
    {
        $title = 'Informasi Giro';

        return view('master.giro.index-cek', compact('title'));
    }

    public function showCekGiro(Request $request)
    {
        $title = 'Show Informasi Giro';
        $giroDetail = GiroDetail::where('nomor', $request->nomor)->first();
        // dd($giroDetail);

        return view('master.giro.show-cek', compact('title', 'giroDetail'));
    }

    public function index()
    {
        $title = 'Master Giro';
        $banks = Bank::where('status', 1)->get();

        return view('master.giro.index', compact('title', 'banks'));
    }

    public function getData(Request $request)
    {
        // dd($request->input('id_bank'));
        $idBank = $request->input('id_bank');
        $rekening = $request->input('rekening');

        // Fetch data from database
        $dataHeader = GiroHeader::where('id_bank', $idBank)->where('kode', $rekening)->orderBy('dari', 'desc')->limit(10)->get();
        foreach ($dataHeader as $data) {
            $data->id_enkrip = enkrip($data->id);
            // dd($data);
            $data->status = 'HABIS';
            // Retrieve detail records for the current header
            // $dataDetail = GiroDetail::where('id_bank', $idBank)->where('kode', $rekening)->where('dari', $data->dari)->where('nomor', $data->sampai)->first();
            // if ($dataDetail->jumlah == null) $data->status = "BARU";
            $dataDetails = GiroDetail::where('id_bank', $idBank)->where('kode', $rekening)->where('dari', $data->dari)->get();
            $status = "BARU";

            // Cek apakah ada data detail yang ditemukan
            if ($dataDetails->isNotEmpty()) {
                // Cek apakah semua detail memiliki nilai jumlah yang terisi
                $allFilled = $dataDetails->every(function ($detail) {
                    return !is_null($detail->jumlah);
                });

                // Cek apakah ada detail yang memiliki nilai jumlah dan ada yang kosong
                $anyFilled = $dataDetails->contains(function ($detail) {
                    return !is_null($detail->jumlah);
                });
                $anyEmpty = $dataDetails->contains(function ($detail) {
                    return is_null($detail->jumlah);
                });

                // Tentukan status berdasarkan kondisi
                if ($allFilled) {
                    $status = "HABIS";
                } elseif ($anyFilled && $anyEmpty) {
                    $status = "TERPAKAI";
                } elseif (!$anyFilled && !$anyEmpty) {
                    $status = "BARU";
                }
            }

            // Atur status pada data
            $data->status = $status;
        }

        return response()->json($dataHeader);
    }

    public function create($id)
    {
        $id = dekrip($id);
        $title = 'Create Giro';
        $bank = Bank::find($id);
        $maxNomor = GiroHeader::max('sampai');
        $getNomor = sprintf('%09d', $maxNomor + 1);

        return view('master.giro.create', compact('title', 'bank', 'getNomor'));
    }

    public function store(Request $request, $id)
    {
        $id = dekrip($id);
        // dd($request->all());
        $total = $request->input('total'); // Total number of records to create
        $count = $request->input('count'); // Number of items per record
        $startNumber = $request->input('get_nomor'); // Starting number

        $startNumberInt = (int) $startNumber;
        $increment = 0;

        for ($i = 0; $i < $total; $i++) {
            $from = $startNumberInt + $increment;
            $to = $from + $count - 1;

            GiroHeader::create([
                'id_bank' => $id,
                'kode' => 'GR',
                'dari' => str_pad($from, 9, '0', STR_PAD_LEFT), // Assuming a 9 digit format
                'sampai' => str_pad($to, 9, '0', STR_PAD_LEFT)
            ]);

            $increment += $count; // Move to the next range
        }

        $startNumberIntDetail = (int) $startNumber;
        $allDetails = [];

        for ($i = 0; $i < $total; $i++) {
            $currentTo = $startNumberIntDetail;
            // dd($startNumberIntDetail);

            for ($j = 0; $j < $count; $j++) {
                $allDetails[] = [
                    'id_bank' => $id,
                    'kode' => 'GR',
                    'dari' => str_pad($startNumberIntDetail, 9, '0', STR_PAD_LEFT),
                    'nomor' => str_pad($currentTo, 9, '0', STR_PAD_LEFT),
                ];

                // Increment the range
                $currentTo++;
            }

            // Set the starting number for the next set
            $startNumberIntDetail = $currentTo;
        }

        // Bulk insert all generated details
        GiroDetail::insert($allDetails);

        return Redirect::route('master.giro.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Buat Giro Success!");
    }

    public function show($id)
    {
        $id = dekrip($id);
        $title = 'Detail Giro';
        $giroHeader = GiroHeader::find($id);
        $giroDetail = GiroDetail::where('dari', $giroHeader->dari)->get();
        // $banks = Bank::find($giroHeader->id_bank);
        // dd($giroDetail);

        return view('master.giro.show', compact('title', 'giroDetail', 'giroHeader'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $pembayaran = Pembayaran::find($id);

        // buat rusak
        $getAllData = Pembayaran::where('id_parent', $pembayaran->id_parent)->get();
        if ($getAllData[0]->nomor_giro !== 'TUNAI' && $getAllData[0]->nomor_giro !== 'TRANSFER') {
            $giroDetail = GiroDetail::where('nomor', $getAllData[0]->nomor_giro)->first();
            $giroDetail->update(['flag' => 3]);
        }

        // kembalikan saat belum bayar
        $explodeData = explode(',', $pembayaran->nomor_bukti);
        // dd(count($explodeData));
        if (count($explodeData) > 1) {
            foreach ($explodeData as $data) {
                Pembayaran::where('nomor_bukti', $data)->whereNull('id_parent')->update(['is_bayar' => null]);
                
                // update po
                Preorder::where('nomor_bukti', $data)->update(['is_pay' => null]);
            }
        } else {
            Pembayaran::where('nomor_bukti', $pembayaran->nomor_bukti)->whereNull('id_parent')->update(['is_bayar' => null]);

            // update po
            Preorder::where('nomor_bukti', $pembayaran->nomor_bukti)->update(['is_pay' => null]);
        }

        // delete sudah bayar
        Pembayaran::where('nomor_bukti', $pembayaran->nomor_bukti)->whereNotNull('id_parent')->delete();

        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Giro Success!");
    }

    public function updateReserve(Request $request, $id)
    {
        // dd($request->all());
        $giro = GiroDetail::find($request->giro_id);
        // dd($giro);
        if ($giro->flag == 1) {
            $giro->update([
                'flag' => 5
            ]);
        } else {
            $giro->update([
                'flag' => 1
            ]);
        }

        // return Redirect::route('master.giro.show', enkrip($request->giro_header))
        return Redirect::route('pembayaran.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Giro Success!");
    }

    // public function update(Request $request, $id)
    // {
    //     dd($request->all(), $id);
    //     $giroDetail = GiroDetail::find($id);

    //     // kembalikan saat belum bayar
    //     $explodeData = explode(',', $giroDetail->nomor_bukti);
    //     // dd(count($explodeData));
    //     if (count($explodeData) > 1) {
    //         foreach ($explodeData as $data) {
    //             Pembayaran::where('nomor_bukti', $data)->whereNull('id_parent')->update(['is_bayar' => null]);
                
    //             // update po
    //             Preorder::where('nomor_bukti', $data)->update(['is_pay' => null]);
    //         }
    //     } else {
    //         Pembayaran::where('nomor_bukti', $giroDetail->nomor_bukti)->whereNull('id_parent')->update(['is_bayar' => null]);

    //         // update po
    //         Preorder::where('nomor_bukti', $giroDetail->nomor_bukti)->update(['is_pay' => null]);
    //     }

    //     // delete sudah bayar
    //     Pembayaran::where('nomor_bukti', $giroDetail->nomor_bukti)->whereNotNull('id_parent')->delete();

    //     // buat rusak
    //     $giroDetail->update(['flag' => 3]);

    //     return Redirect::route('master.giro.show', $request->giro_header)
    //         ->with('alert.status', '00')
    //         ->with('alert.message', "Update Giro Success!");
    // }

    public function getDataBayar(Request $request)
    {
        $idBank = $request->input('id_bank');
        $rekening = $request->input('rekening');

        // Fetch data from database
        $dataHeader = GiroHeader::where('id_bank', $idBank)->where('kode', $rekening)->orderBy('dari', 'desc')->limit(10)->get();
        foreach ($dataHeader as $data) {
            $dataDetails = GiroDetail::where('id_bank', $idBank)->where('kode', $rekening)->where('dari', $data->dari)->get();

            $data->remainingAmount = count($dataDetails);
            $data->rusakGiro = 0;

            foreach ($dataDetails as $detail) {
                // Check if 'jumlah' is not null or empty and add to remaining amount
                if (!is_null($detail->jumlah) && $detail->jumlah !== '') {
                    // Accumulate the amount
                    $data->remainingAmount -= 1;
                }

                if ($detail->flag == 3) {
                    $data->rusakGiro += 1;
                }
            }
        }

        $filledData = GiroDetail::where('id_bank', $idBank)->whereNotNull('jumlah')->orderBy('nomor', 'desc')->limit(5)->get()->reverse();
        $emptyData = GiroDetail::where('id_bank', $idBank)->whereNull('jumlah')->limit(10)->get();
        $dataDetail = $filledData->merge($emptyData);
        $dataDetail = $dataDetail->sortBy('nomor')->values();
        // dd($dataDetails, $dataDetail);

        return response()->json([
            'dataHeader' => $dataHeader,
            'dataDetail' => $dataDetail
        ]);
    }
}
