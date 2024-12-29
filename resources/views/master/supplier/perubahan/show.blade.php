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
            <form action="{{ route('master.change-supplier.update', enkrip($supplier->id)) }}" id="filter-form" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mt-n2" style="margin-right: 345px;">
                            <h6><u>SUPPLIER PENGGANTI</u></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <label class="mx-4">KODE SUPPLIER</label>
                                <input type="text" disabled value="{{ $supplier->nomor }} - {{ $supplier->nama }}" size="40">
                            </div>
                            <div>
                                <label class="mx-4">KODE SUPPLIER</label>
                                <select class="supplier-select" required name="supplier_target" id="supplierSelect">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
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
                                        @foreach ($products as $index => $product)
                                        <tr>
                                            <input type="text" hidden id="id_product_{{ $index }}" name="id_product[{{ $product->id }}]" value="{{ $product->id }}">
                                            <td class="warna-1">{{ $product->nama }}</td>
                                            <td class="text-end warna-2">{{ number_format($product->stok) }}</td>
                                            <td class="text-end warna-3">{{ number_format($product->harga_pokok) }}</td>
                                            <td class="text-end warna-4">{{ number_format($product->harga_jual) }}</td>
                                            <td class="warna-5">{{ $product->supplier->nama }}</td>
                                            <td class="text-center warna-6"><input type="checkbox" class="product-checkbox" data-row-index="{{ $index }}" value="{{ $product->id }}"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" id="button-selesai" disabled class="btn btn-primary mx-4">PROSES</button>
                            <a href="{{ route('master.change-supplier.index') }}" class="btn btn-danger">KEMBALI</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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

        $('#supplierSelect').on('change', function() {
            var selectedSupplierName = this.options[this.selectedIndex].getAttribute('data-nama');

            document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var row = checkbox.closest('tr');
                    var warna1 = row.querySelector('.warna-1');
                    var warna2 = row.querySelector('.warna-2');
                    var warna3 = row.querySelector('.warna-3');
                    var warna4 = row.querySelector('.warna-4');
                    var warna5 = row.querySelector('.warna-5');
                    var warna6 = row.querySelector('.warna-6');

                    // Mengecek apakah checkbox dicentang
                    if (checkbox.checked) {
                        // Menambahkan background color pada kolom pertama (product name)
                        warna1.style.backgroundColor = '#FFFAA0';
                        warna2.style.backgroundColor = '#FFFAA0';
                        warna3.style.backgroundColor = '#FFFAA0';
                        warna4.style.backgroundColor = '#FFFAA0';
                        warna5.style.backgroundColor = '#FFFAA0';
                        warna5.innerHTML = selectedSupplierName;
                        warna5.style.color = 'red';
                        warna6.style.backgroundColor = '#FFFAA0';
                    } else {
                        // Mengembalikan warna latar belakang ke warna semula
                        warna1.style.backgroundColor = '#FFFFFF';
                        warna2.style.backgroundColor = '#FFFFFF';
                        warna3.style.backgroundColor = '#FFFFFF';
                        warna4.style.backgroundColor = '#FFFFFF';
                        warna5.style.backgroundColor = '#FFFFFF';
                        warna5.innerHTML = "{{ $product->supplier->nama }}";
                        warna5.style.color = 'black';
                        warna6.style.backgroundColor = '#FFFFFF';
                    }
                });
            });
        });

        // Mengaktifkan tombol "Proses" hanya ketika ada checkbox yang dicentang
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const buttonSelesai = document.getElementById('button-selesai');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                buttonSelesai.disabled = !anyChecked;
            });
        });

        // Mengubah form submission agar hanya mengirimkan produk yang dipilih
        const form = document.getElementById('filter-form');
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    // Hapus input harga_sementara yang tidak dicentang
                    const hiddenInput = document.querySelector(`input[name="id_product[${checkbox.value}]"]`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                    }
                }
            });
        });
    </script>
@endsection