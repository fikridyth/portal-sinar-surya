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
            {{-- <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                MASTER HARGA
                            </li>
                        </ol>
                    </nav>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <label class="mx-4">KODE SUPPLIER</label>
                        <select class="supplier-select" id="supplierSelect">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <h5 class="text-center mt-3 mb-n3">DAFTAR HARGA SEMENTARA</h5>
                    <div class="d-flex justify-content-center mt-4">
                        {{-- <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA BARANG</th>
                                        <th class="text-center">HARGA BELI LAMA</th>
                                        <th class="text-center">HARGA BELI BARU</th>
                                        <th class="text-center">%</th>
                                        <th class="text-center">HARGA JUAL</th>
                                        <th class="text-center">MARK UP</th>
                                        <th class="text-center">HARGA SEMENTARA</th>
                                        <th class="text-center">TANGGAL AWAL</th>
                                        <th class="text-center">TANGGAL AKHIR</th>
                                        <th class="text-center">V</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div> --}}
                        <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 1000px; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">DARI TANGGAL</th>
                                        <th class="text-center">SAMPAI TANGGAL</th>
                                        <th class="text-center">(%)</th>
                                        <th class="text-center">PILIH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $product->supplier->nama }}</td>
                                            <td class="text-center">{{ $product->date_first }}</td>
                                            <td class="text-center">{{ $product->date_last }}</td>
                                            <td class="text-center">{{ $product->naik }}</td>
                                            <td class="text-center"><a href="{{ route('master.harga-sementara.show', enkrip($product->id)) }}" class="btn btn-sm btn-primary">PILIH</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#supplierSelect').change(function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    var url = "{{ route('master.harga.show', ':id') }}".replace(':id', supplierId);
                    window.location.href = url;
                }
            });
        });

        $(`.supplier-select`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });
    </script>
@endsection