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
                            <button type="button" id="checkAllBtn" class="btn btn-primary">Centang Semua</button>
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
        $(document).ready(function () {
            const $checkboxes = $('.product-checkbox');
            const $buttonSelesai = $('#button-selesai');
            const $checkAllBtn = $('#checkAllBtn');

            function updateRowHighlight(checkbox) {
                const $row = $(checkbox).closest('tr');
                const isChecked = $(checkbox).is(':checked');
                const selectedSupplierName = $('#supplierSelect option:selected').data('nama');

                if (isChecked) {
                    $row.find('td').css('background-color', '#FFFAA0');
                    $row.find('.warna-5').text(selectedSupplierName).css('color', 'red');
                } else {
                    $row.find('td').css('background-color', '#FFFFFF');
                    $row.find('.warna-5').text($(checkbox).data('original-supplier')).css('color', 'black');
                }
            }

            // 1. Centang Semua Checkbox
            let isAllChecked = false;
            $checkAllBtn.on('click', function () {
                isAllChecked = !isAllChecked;
                $checkboxes.each(function () {
                    $(this).prop('checked', isAllChecked);
                    updateRowHighlight(this);
                });

                // Update tampilan tombol
                $checkAllBtn
                    .text(isAllChecked ? 'Hapus Centang Semua' : 'Centang Semua')
                    .removeClass(isAllChecked ? 'btn-primary' : 'btn-danger')
                    .addClass(isAllChecked ? 'btn-danger' : 'btn-primary');

                // Aktifkan / nonaktifkan tombol selesai
                $buttonSelesai.prop('disabled', !isAllChecked);
            });

            // 2. Event per checkbox
            $checkboxes.each(function () {
                const supplierName = $(this).closest('tr').find('.warna-5').text();
                $(this).data('original-supplier', supplierName);
            });

            $checkboxes.on('change', function () {
                updateRowHighlight(this);

                // Update tombol "selesai"
                const anyChecked = $checkboxes.is(':checked');
                $buttonSelesai.prop('disabled', !anyChecked);

                // Update tombol centang semua bila status berubah
                const allChecked = $checkboxes.length === $('.product-checkbox:checked').length;
                $checkAllBtn
                    .text(allChecked ? 'Hapus Centang Semua' : 'Centang Semua')
                    .toggleClass('btn-danger', allChecked)
                    .toggleClass('btn-primary', !allChecked);
                isAllChecked = allChecked;
            });

            // 3. Select2 init
            $('.supplier-select').select2({
                placeholder: '---Select Supplier---',
                allowClear: true
            }).on('select2:open', function () {
                const searchBox = $(this).data('select2').dropdown.$search[0];
                if (searchBox) searchBox.focus();
            });

            // 4. Submit filter form - hapus input jika tidak dicentang
            $('#filter-form').on('submit', function () {
                $checkboxes.each(function () {
                    if (!this.checked) {
                        const hiddenInput = $(`input[name="id_product[${this.value}]"]`);
                        if (hiddenInput.length) hiddenInput.remove();
                    }
                });
            });
        });
    </script>
@endsection