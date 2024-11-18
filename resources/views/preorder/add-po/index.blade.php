@extends('main')

@include('preorder.add-on.styles')

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
                            <button type="button" onclick="window.history.back()" class="btn btn-danger mx-5">BATAL</button>
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('preorder.add-on.scripts')
@endsection
