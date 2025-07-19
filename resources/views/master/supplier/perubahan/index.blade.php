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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mt-n2" style="margin-right: 345px;">
                        <h6><u>SUPPLIER PENGGANTI</u></h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label class="mx-4">KODE SUPPLIER</label>
                            <select class="supplier-select" id="supplierSelect">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mx-4">KODE SUPPLIER</label>
                            <select class="supplier-select2" disabled id="supplierSelect2">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 580px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 1000px; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NAMA BARANG</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">HARGA DASAR</th>
                                        <th class="text-center">HARGA JUAL</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">GANTI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" id="button-selesai" disabled class="btn btn-primary mx-4">PROSES</button>
                        <a href="{{ route('index') }}" class="btn btn-danger">KEMBALI</a>
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
                    var url = "{{ route('master.change-supplier.show', ':id') }}".replace(':id', supplierId);
                    window.location.href = url;
                }
            });
        });

        $(`.supplier-select`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });

        $(`.supplier-select`).on('select2:open', function(e) {
            // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
            const searchBox = $(this).data('select2').dropdown.$search[0];
            if (searchBox) {
                searchBox.focus();
            }
        });

        $(`.supplier-select2`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });
    </script>
@endsection