@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="container mb-7">
        <div class="card mt-n3 mb-2">
            <div class="card-body">
                {{-- TAMBAH PO --}}
                <form action="{{ route('preorder.get-data-penjualan') }}" method="POST">
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
                        <input type="text" autocomplete="off" id="searchInputSupplier" class="mb-2" style="width: 30%;" placeholder="Cari Supplier" onkeyup="searchTableSupplier()">
                        <div class="table-responsive" style="height: 420px; border: 1px solid #ccc;">
                            <table id="table-supplier" class="table table-bordered table-sm text-center" style="width: 100%; table-layout: auto;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th colspan="3" class="text-center">DAFTAR SUPPLIER YANG HARUS DIBUATKAN P.O</th>
                                        <th colspan="6" class="text-center">DAFTAR SUPPLIER YANG SUDAH DIBUATKAN P.O</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">DATANG</th>
                                        <th class="text-center">&#9989;</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">NOMOR PO</th>
                                        <th class="text-center" style="width: 70px;">REF</th>
                                        <th class="text-center" style="width: 70px;">DETAIL</th>
                                        <th class="text-center" style="width: 70px;">CTK</th>
                                        <th class="text-center" style="width: 70px;">HAPUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listPreorders as $po)
                                        <tr>
                                            <td class="text-center">{{ empty($po['preorders']) ? $po['supplier']['nama'] : '' }}</td>
                                            <td class="text-center">{{ $po['supplier']['waktu_kunjungan'] }}</td>
                                            <td class="text-center" style="margin: 0; padding: 0; {{ empty($po['preorders']) ? 'background-color: red' : '' }}">
                                                <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="dataSupplier1" value="{{ $po['supplier']['nama'] }}">
                                                    <input type="checkbox" class="auto-submit-checkbox" {{ empty($po['preorders']) ? '' : 'disabled' }} style="width: 16px; height: 16px; margin-top: 8px;">
                                                </form>
                                            </td>
                                            <td class="text-center">{{ empty($po['preorders']) ? '': $po['supplier']['nama'] }}</td>
                                            @if ($po['preorders'] !== [])
                                                <td class="text-center">{{ $po['preorders'][0]['nomor_po'] ?? null }}</td>
                                                @php
                                                    $expiredDate = new DateTime(now()->format('Y-m-d'));
                                                    $currentDate = new DateTime($po['preorders'][0]['date_last'] ?? null );
                                                    $interval = $expiredDate->diff($currentDate);
                                                    $days = $interval->days;
                                                @endphp
                                                <td class="text-center">{{ $po['preorders'][0]['receive_type'] . $days }}</td>
                                                <td class="text-center" style="margin: 0; padding: 0;">
                                                    <form action="{{ route('daftar-po.edit', enkrip($po['preorders'][0]['id'])) }}" method="GET">
                                                        <input type="checkbox" class="auto-submit-checkbox-2" style="width: 16px; height: 16px; margin-top: 8px;">
                                                    </form>
                                                </td>
                                                <td class="text-center">{{ $po['preorders'][0]['is_cetak'] }}</td>
                                                <td class="text-center" style="margin: 0; padding: 0;">
                                                    <input type="checkbox" style="width: 16px; height: 16px; margin-top: 8px;">
                                                </td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
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
    @include('preorder.add-on.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.auto-submit-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if(this.checked){
                        this.closest('form').submit(); // submit form saat dicentang
                    }
                });
            });

            const checkboxes2 = document.querySelectorAll('.auto-submit-checkbox-2');
            checkboxes2.forEach(cb => {
                cb.addEventListener('change', function () {
                    if(this.checked){
                        this.closest('form').submit(); // submit form saat dicentang
                    }
                });
            });
        });
        
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
