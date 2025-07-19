@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="container mb-7">
        <div class="card mt-n3 mb-2">
            <div class="card-body">
                {{-- TAMBAH PO --}}
                <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                    @csrf
                    <div class="card-body mt-n4">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-2 col-form-label">Supplier Header</label>
                                        <div class="col-sm-3">
                                            <div class="custom-select-supplier">
                                                <input type="text" class="search-input-supplier_1" autofocus name="supplier" value="{{ old('supplier') }}" autocomplete="off" required placeholder="Search..." onkeyup="filterFunction()">
                                                <div class="select-items-supplier" id="select-items-supplier_1">
                                                    <!-- Options will be added here dynamically -->
                                                </div>
                                            </div>
                            
                                            <select id="supplier_1" style="width: 200px;" hidden>
                                                <option value="">---Select Supplier---</option>
                                                <!-- Example options; replace with server-side data as needed -->
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" id="nama_supplier_1" name='dataSupplier1' value="" readonly class="form-control readonly-input" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Penjualan Rata2</label>
                                        <div class="col-sm-3">
                                            <input type="password" disabled class="form-control" id="inputPassword3">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 2</label>
                                        <div class="col-sm-3">
                                            <div class="custom-select-supplier">
                                                <input type="text" class="search-input-supplier_2" name="supplier" value="{{ old('supplier') }}" autocomplete="off" placeholder="Search..." onkeyup="filterFunction()">
                                                <div class="select-items-supplier" id="select-items-supplier_2">
                                                    <!-- Options will be added here dynamically -->
                                                </div>
                                            </div>
                            
                                            <select id="supplier_2" style="width: 200px;" hidden>
                                                <option value="">---Select Supplier---</option>
                                                <!-- Example options; replace with server-side data as needed -->
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" id="nama_supplier_2" name='dataSupplier2' value="" readonly class="form-control readonly-input" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                        <div class="col-sm-3">
                                            <input type="password" disabled class="form-control" id="inputPassword3">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 3</label>
                                        <div class="col-sm-3">
                                            <div class="custom-select-supplier">
                                                <input type="text" class="search-input-supplier_3" name="supplier" value="{{ old('supplier') }}" autocomplete="off" placeholder="Search..." onkeyup="filterFunction()">
                                                <div class="select-items-supplier" id="select-items-supplier_3">
                                                    <!-- Options will be added here dynamically -->
                                                </div>
                                            </div>
                            
                                            <select id="supplier_3" style="width: 200px;" hidden>
                                                <option value="">---Select Supplier---</option>
                                                <!-- Example options; replace with server-side data as needed -->
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" id="nama_supplier_3" name='dataSupplier3' value="" readonly class="form-control readonly-input" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Minimum</label>
                                        <div class="col-sm-3">
                                            <input type="password" disabled class="form-control" id="inputPassword3">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Maksimum</label>
                                        <div class="col-sm-3">
                                            <input type="password" disabled class="form-control" id="inputPassword3">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-n4 mb-n5">
                            <a href="{{ route('index') }}" class="btn btn-danger mx-4">BATAL</a>
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- DAFTAR PO --}}
                {{-- <a href="{{ route('preorder.index') }}" class="btn btn-primary mb-2">BUAT PO MANUAL</a> --}}
                <div class="d-flex justify-content-between">
                    <div class="row w-100">
                        <div class="form-group col-5">
                            <input type="text" autocomplete="off" id="searchInputSupplier" class="mb-2" size="45" placeholder="Cari Supplier" onkeyup="searchTableSupplier()">
                            <div style="overflow-x: auto; height: 470px; border: 1px solid #ccc;">
                                <table id="table-supplier" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG HARUS DIBUATKAN P.O</th>
                                        </tr>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            <th class="text-center">NAMA SUPPLIER</th>
                                            <th class="text-center">DATANG</th>
                                            <th class="text-center">DETAIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listPreorders as $po)
                                            @if (empty($po['preorders']))
                                                <tr>
                                                    <td class="text-center">{{ $po['supplier']['nama'] }}</td>
                                                    <td class="text-center">{{ $po['supplier']['waktu_kunjungan'] }}</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                                                            @csrf
                                                            <input type="text" hidden name="dataSupplier1" value="{{ $po['supplier']['nama'] }}">
                                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size: 10px;">BUAT</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group col-7" style="margin-top: 38px;">
                            <div style="overflow-x: auto; height: 470px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG SUDAH DIBUATKAN P.O</th>
                                        </tr>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            <th class="text-center">NAMA SUPPLIER</th>
                                            <th class="text-center">NOMOR PO</th>
                                            <th class="text-center">REF</th>
                                            <th class="text-center">DETAIL</th>
                                            <th class="text-center">CTK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listPreorders as $po)
                                            @foreach ($po['preorders'] as $order)
                                                @if ($order !== [])
                                                    <tr>
                                                        <td class="text-center">{{ $po['supplier']['nama'] }}</td>
                                                        <td class="text-center">{{ $order['nomor_po'] ?? null }}</td>
                                                        @php
                                                            $expiredDate = new DateTime(now()->format('Y-m-d'));
                                                            $currentDate = new DateTime($order['date_last'] ?? null );
                                                            $interval = $expiredDate->diff($currentDate);
                                                            $days = $interval->days;
                                                        @endphp
                                                        <td class="text-center">{{ $order['receive_type'] . $days }}</td>
                                                        <td class="text-center">
                                                            {{-- <a href="{{ route('daftar-po.show', enkrip($order['id'])) }}" class="btn btn-primary btn-sm">Detail</a> --}}
                                                            <a href="{{ route('daftar-po.edit', enkrip($order['id'])) }}" class="btn btn-primary btn-sm mx-1">DETAIL</a>
                                                            {{-- @if ($order['is_cetak'] == null)
                                                                <a href="{{ route('daftar-po.edit', enkrip($order['id'])) }}" class="btn btn-primary btn-sm mx-1">Edit</a>
                                                            @else
                                                                <a href="{{ route('daftar-po.edit', enkrip($order['id'])) }}" class="btn btn-primary btn-sm mx-1 disabled-link">Edit</a>
                                                            @endif --}}
                                                        </td>
                                                        <td class="text-center">{{ $order['is_cetak'] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('preorder.add-on.scripts')
    <script>
        $(document).ready(function() {
            function formatNumber(num) {
                // Format number with commas and two decimal places
                return num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function calculateTotal(row) {
                var quantity = parseFloat(row.find('.order').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var total = quantity * price;
                var formattedTotal = formatNumber(total);
                row.find('.total').val(formattedTotal);
                row.find('.fieldtotal').val(total);
            }

            // Handle input events for quantity and price fields
            $('.order, .price').on('input', function() {
                var row = $(this).closest('tr'); // Find the closest row
                calculateTotal(row); // Calculate the total for this row
            });

            // Initialize totals on page load
            $('tr').each(function() {
                calculateTotal($(this));
            });
        });

        function searchTableSupplier() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputSupplier");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-supplier");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Mencari pada kolom pertama (nama supplier)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
