@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mt-n2 mb-2 mx-auto" style="width: 40%;">
                    <form action="{{ route('master.promosi.index-all') }}" method="GET"
                        style="width: 100%; max-width: 800px;">

                        {{-- Row 1: Label & Periode --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <label class="form-label fw-semibold mb-0" style="font-size: 18px">Periode :</label>
                            </div>
                            <div class="flex-grow-1">
                                <input class="form-control form-control-solid" placeholder="Pilih Periode"
                                    autocomplete="off" id="periode" readonly name="periode" value="{{ request('periode') }}" />
                            </div>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-center mt-2">
                    <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                        <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center">PROMOSI YANG SUDAH SELESAI</th>
                                </tr>
                                <tr>
                                    <th class="text-center">NAMA SUPPLIER</th>
                                    <th class="text-center">JENIS</th>
                                    <th class="text-center">DOKUMEN</th>
                                    <th class="text-center">TANGGAL BAYAR</th>
                                    <th class="text-center">JUMLAH RP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($promosi->count())
                                    @foreach ($promosi as $promo)
                                        <tr>
                                            <td>{{ $promo->supplier->nama }}</td>
                                            <td>{{ $promo->tipe }}</td>
                                            <td class="text-center">{{ $promo->nomor_bukti }}</td>
                                            <td class="text-center">{{ $promo->updated_at->format('d/m/Y') }}</td>
                                            <td class="text-end">{{ number_format($promo->total, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center fw-bold" colspan="5">TIDAK ADA DATA</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($promosi->count())
                    <div class="text-end fw-bold" style="font-size: 18px; margin-top: 10px; width: 75%;">
                        TOTAL : {{ number_format($promosi->sum('total'), 0) }}
                    </div>
                @endif
                <div class="d-flex justify-content-center mt-3">
                    <a href="{{ route('master.promosi.print', request()->all()) }}"
                        class="btn btn-primary mx-2"
                        style="min-width: 100px;"
                        title="PRINT">PRINT</a>
                 
                    <button type="button"
                        onclick="window.history.back()"
                        class="btn btn-danger mx-2"
                        style="min-width: 100px;"
                        title="KEMBALI">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection