@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">DAFTAR BARANG YANG HARUS DIPESAN
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-2 col-form-label">Nomor Supplier 1</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="dataSupplier1" name="dataSupplier1" list="supplier" size="70"
                                                required />
                                            <datalist id="supplier">
                                                @foreach ($suppliers as $supplier)
                                                    <option>{{ $supplier->nama }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        {{-- <div class="col-sm-5">
                                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                        </div> --}}
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
                                        <div class="col-sm-8">
                                            <input type="text" id="dataSupplier2" name="dataSupplier2" list="supplier" size="70" />
                                            <datalist id="supplier">
                                                @foreach ($suppliers as $supplier)
                                                    <option>{{ $supplier->nama }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        {{-- <div class="col-sm-5">
                                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                        <div class="col-sm-2">
                                            <input type="password" disabled class="form-control" id="inputPassword3">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-4 col-form-label">Hari Sekali</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 3</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="dataSupplier3" name="dataSupplier3" list="supplier" size="70" />
                                            <datalist id="supplier">
                                                @foreach ($suppliers as $supplier)
                                                    <option>{{ $supplier->nama }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        {{-- <div class="col-sm-5">
                                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                        </div> --}}
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

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Supplier</th>
                                                <th class="text-center">Alamat -1</th>
                                                <th class="text-center">Alamat -2</th>
                                                <th class="text-center">Pilih</th>
                                                <th class="text-center">Header</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="targetRow1" hidden>
                                                <td class="text-center">1</td>
                                                <td id="supplierName11"></td>
                                                <td id="supplierAddress11"></td>
                                                <td id="supplierAddress12"></td>
                                                <td class="text-center">&#9989;</td>
                                                <td class="text-center">&#9989;</td>
                                            </tr>
                                            <tr id="targetRow2" hidden>
                                                <td class="text-center">2</td>
                                                <td id="supplierName21"></td>
                                                <td id="supplierAddress21"></td>
                                                <td id="supplierAddress22"></td>
                                                <td class="text-center">&#9989;</td>
                                                <td class="text-center">-</td>
                                            </tr>
                                            <tr id="targetRow3" hidden>
                                                <td class="text-center">3</td>
                                                <td id="supplierName31"></td>
                                                <td id="supplierAddress31"></td>
                                                <td id="supplierAddress32"></td>
                                                <td class="text-center">&#9989;</td>
                                                <td class="text-center">-</td>
                                            </tr>
                                            <tr>
                                                <td id="targetRow4" colspan="6" class="text-center">Tidak ada data yang dipilih</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary">Proses</button>
                            <a class="btn btn-danger mx-5" href="{{ route('index') }}">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataSupplier1').on('input', function() {
                var inputValue = $(this).val();
                var options = $('#supplier option').map(function() {
                    return $(this).val();
                }).get();

                if (options.includes(inputValue)) {
                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: inputValue
                        },
                        success: function(response) {
                            $('#supplierName11').text(response.nama);
                            $('#supplierAddress11').text(response.alamat1);
                            $('#supplierAddress12').text(response.alamat2);

                            $('#targetRow1').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                            // $('#dataSupplier2').removeAttr('disabled');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow1').attr('hidden', 'hidden');
                            $('#targetRow4').removeAttr('hidden');
                            // $('#targetRow2').attr('hidden', 'hidden');
                            // $('#targetRow3').attr('hidden', 'hidden');
                            // $('#dataSupplier2').attr('disabled', 'disabled');
                            // $('#dataSupplier3').attr('disabled', 'disabled');
                            // $('#dataSupplier2').val('');
                            // $('#dataSupplier3').val('');
                        }
                    });
                } else {
                    $('#targetRow1').attr('hidden', 'hidden');
                    $('#targetRow4').removeAttr('hidden');
                    // $('#targetRow2').attr('hidden', 'hidden');
                    // $('#targetRow3').attr('hidden', 'hidden');
                    // $('#dataSupplier2').attr('disabled', 'disabled');
                    // $('#dataSupplier3').attr('disabled', 'disabled');
                    // $('#dataSupplier2').val('');
                    // $('#dataSupplier3').val('');
                }
            });
            $('#dataSupplier2').on('input', function() {
                var inputValue = $(this).val();
                var options = $('#supplier option').map(function() {
                    return $(this).val();
                }).get();

                if (options.includes(inputValue)) {
                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: inputValue
                        },
                        success: function(response) {
                            $('#supplierName21').text(response.nama);
                            $('#supplierAddress21').text(response.alamat1);
                            $('#supplierAddress22').text(response.alamat2);

                            $('#targetRow2').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                            // $('#dataSupplier3').removeAttr('disabled');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow2').attr('hidden', 'hidden');
                            // $('#targetRow3').attr('hidden', 'hidden');
                            // $('#dataSupplier3').attr('disabled', 'disabled');
                            // $('#dataSupplier3').val('');
                        }
                    });
                } else {
                    $('#targetRow2').attr('hidden', 'hidden');
                    // $('#targetRow3').attr('hidden', 'hidden');
                    // $('#dataSupplier3').attr('disabled', 'disabled');
                }
            });
            $('#dataSupplier3').on('input', function() {
                var inputValue = $(this).val();
                var options = $('#supplier option').map(function() {
                    return $(this).val();
                }).get();

                if (options.includes(inputValue)) {
                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: inputValue
                        },
                        success: function(response) {
                            $('#supplierName31').text(response.nama);
                            $('#supplierAddress31').text(response.alamat1);
                            $('#supplierAddress32').text(response.alamat2);

                            $('#targetRow3').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow3').attr('hidden', 'hidden');
                        }
                    });
                } else {
                    $('#targetRow3').attr('hidden', 'hidden');
                }
            });
        });
    </script>
@endsection
