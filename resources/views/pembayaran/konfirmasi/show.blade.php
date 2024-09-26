@extends('main')

@section('styles')
    <style>
        .table thead th {
            position: sticky;
            top: 0;
            background-color: white; /* Optional: set background color for better visibility */
            z-index: 10; /* Ensure the header is above other content */
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 95%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                KONFIRMASI CEK / GIRO / TUNAI
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">DOKUMEN</th>
                                        <th class="text-center">TANGGAL BAYAR</th>
                                        <th class="text-center">JUMLAH RP</th>
                                        <th class="text-center">NOMOR GIRO</th>
                                        <th class="text-center">JATUH TEMPO</th>
                                        <th class="text-center">V</th>
                                        <th class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $previousIdParent = null;
                                    @endphp
                                    @foreach ($pembayarans as $index => $pmb)
                                        <tr>
                                            <td class="text-center" style="color: <?= $pmb->tipe_giro == 'CABANG' ? 'red' : 'black'; ?>">
                                                @if ($pmb->nomor_bukti !== $previousIdParent)
                                                    {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                    @php $counter++; @endphp
                                                @else
                                                    {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                @endif
                                            </td>
                                            @if ($pmb->tipe_giro == 'CABANG')
                                                <td style="color: red;">{{ $pmb->supplier->nama }}/LOGO</td>
                                            @else
                                                <td>{{ $pmb->supplier->nama }}</td>
                                            @endif
                                            <td>{{ $pmb->nomor_bukti }}</td>
                                            <td class="text-center">{{ $pmb->date }}</td>
                                            <td class="text-end">{{ number_format($pmb->total_with_materai) }}</td>
                                            <td class="text-center">{{ $pmb->nomor_giro }}</td>
                                            <td class="text-center">{{ $pmb->tanggal_akhir ?? '-' }}</td>
                                            <td class="text-center"><input type="checkbox" checked></td>
                                            <td>KONFORM</td>
                                        </tr>
                                        @if ($pmb->nomor_bukti)
                                            @php $previousIdParent = $pmb->nomor_bukti; @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('pembayaran-konfirmasi.index') }}" class="btn btn-danger">SELESAI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection