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
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                MASTER HARGA
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <label class="mx-4">KODE SUPPLIER</label>
                        <select class="supplier-select" id="supplierSelect">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
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