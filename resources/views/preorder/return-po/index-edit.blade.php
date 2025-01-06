@extends('main')

@section('styles')
    <style>
        .sticky-column {
        position: sticky;
        left: 0;
        background-color: white; /* Sesuaikan warna latar belakang */
        z-index: 1; /* Agar tetap di atas saat scroll */
        border-right: 2px solid #ccc; /* Border kanan untuk sticky column */
    }

    th, td {
        border: 1px solid #ccc; /* Border untuk semua cell */
        padding: 10px; /* Menambah padding untuk keterbacaan */
    }

    thead th {
        background-color: #f9f9f9; /* Latar belakang header tabel */
        box-shadow: 0 2px 2px -2px gray; /* Bayangan untuk header */
    }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 95%">
            <div class="card mt-n3">
                <div class="card-body mt-n3">
                    {{-- <h5 class="text-center mt-3 mb-n3">DAFTAR HARGA SEMENTARA</h5> --}}
                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 670px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 1000px; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NOMOR BUKTI</th>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">KODE SUPPLIER</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">PILIH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($preorders as $preorder)
                                        <tr>
                                            <td class="text-center">{{ $preorder->nomor_receive }}</td>
                                            <td class="text-center">{{ Carbon\Carbon::parse($preorder->date_first)->format('d/m/Y') }}</td>
                                            <td>{{ $preorder->supplier->nomor }}</td>
                                            <td>{{ $preorder->supplier->nama }}</td>
                                            <td class="text-end">{{ number_format($preorder->grand_total) }}</td>
                                            <td class="text-center">Terima</td>
                                            <td class="text-center">
                                                <form action="{{ route('update-nomor-receive', enkrip($retur->id)) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="nomor_receive" value="{{ $preorder->nomor_receive }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">PILIH</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" onclick="window.history.back()" class="btn btn-danger mt-3">KEMBALI</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection