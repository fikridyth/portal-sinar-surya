@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">KARTU STOK</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body">
                    <h6>INVENTORY</h6>
                    {{ $dataTable->table() }}
                    {{-- <div class="row">
                        <div class="col-8">
                        </div>
                        <div class="col-4">
                            <h6>INVENTORY HISTORICAL</h6>
                            <table class="table table-bordered" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th class="text-center">NAMA BARANG</th>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">JAM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-4 mb-7">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
