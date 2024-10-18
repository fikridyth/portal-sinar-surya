@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PERSETUJUAN HARGA JUAL - PILIH BARANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center">DAFTAR PENERIMAAN BARANG</th>
                        </tr>
                        <tr>
                            <th class="text-center">NOMOR BUKTI</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">NAMA SUPPLIER</th>
                            <th class="text-center">JUMLAH RP</th>
                            <th class="text-center">PROSES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preorders as $po)
                            <tr>
                                <td class="text-center">{{ $po->nomor_po }}</td>
                                <td class="text-center">{{ $po->date_first }}</td>
                                <td class="text-start">{{ $po->supplier->nama }}</td>
                                <td class="text-end">{{ number_format($po->grand_total) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('persetujuan-harga-jual-edit', enkrip($po->id)) }}"
                                        class="btn btn-primary btn-sm mx-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
