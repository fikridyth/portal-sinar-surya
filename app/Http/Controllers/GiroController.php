<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\GiroDetail;
use App\Models\GiroHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GiroController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Master Giro';
        $banks = Bank::all();

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
        $title = 'Create Giro';
        $bank = Bank::find($id);
        $maxNomor = GiroHeader::max('sampai');
        $getNomor = sprintf('%09d', $maxNomor + 1);

        return view('master.giro.create', compact('title', 'bank', 'getNomor'));
    }

    public function store(Request $request, $id)
    {
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
}
