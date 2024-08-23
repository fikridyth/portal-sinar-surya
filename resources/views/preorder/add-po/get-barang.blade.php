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
                <form action="{{ route('preorder.process-barang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-2 col-form-label">Supplier Header</label>
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nomor }}" placeholder="Email">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nama }}" placeholder="Email">
                                            <input type="hidden" name="supplierName" value="{{ $supplier1->nama }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Penjualan Rata2</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->penjualan_rata }}" disabled class="form-control" id="inputPassword3">
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
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->waktu_kunjungan }}" disabled class="form-control" id="inputPassword3">
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
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Minimum</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->stok_minimum }}" disabled class="form-control" id="inputPassword3">
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
                                            <input type="text" value="{{ $penjualan->stok_maksimum }}" disabled class="form-control" id="inputPassword3">
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
                                                <th class="text-center">Nama Barang</th>
                                                <th class="text-center">Isi</th>
                                                <th class="text-center">Stok</th>
                                                <th class="text-center">Harga Beli</th>
                                                <th class="text-center">Harga Jual</th>
                                                <th class="text-center">Pilih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allProducts as $product)
                                                <tr>
                                                    <td>{{ $product->nama . '/' . $product->unit_jual }}</td>
                                                    <td class="text-end">{{ str_replace('P', '', $product->unit_jual) }}</td>
                                                    <td class="text-end">{{ $product->stok }}</td>
                                                    <td class="text-end">{{ number_format($product->harga_pokok) }}</td>
                                                    <td class="text-end">{{ number_format($product->harga_jual) }}</td>
                                                    <td class="text-center"><input type="checkbox" id="products[]"
                                                            name="products[]" value="{{ $product->id }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary">Proses</button>
                            <a class="btn btn-danger mx-5" href="{{ route('preorder.index') }}">Batal</a>
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
            var selectedProducts = [];
            $('input[name="products[]"]:checked').each(function() {
                selectedProducts.push($(this)
            .val());
            });
        })
    </script>
@endsection
