@extends('main')

@php
    $totalPrice = 0;
    $totalOrder = 0;
@endphp
<style>
    .container-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Optional: Adds space between columns */
        }
    .column {
        flex: 1 1 10%; /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
        box-sizing: border-box;
    }
    .form-group {
        margin: 0;
        text-align: center;
    }
    .col-form-label {
        font-size: 12px;
    }
    .col-2 {
        flex: 1 1 20%; /* Adjust width for column with different size */
    }
</style>

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">KARTU STOK
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="#" method="POST">
                    {{-- @csrf --}}
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">NOMOR BARANG</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="col">
                                        <input type="text" value="{{ $product->kode }}" disabled class="form-control" id="nomorSupplier2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">NAMA BARANG</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="col">
                                        <input type="text" value="{{ $product->nama }}" disabled class="form-control" id="nomorSupplier2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">DARI TANGGAL</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="col">
                                        <input type="text" value="{{ now()->format('d/m/Y') }}" disabled class="form-control" id="nomorSupplier2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">SAMPAI TANGGAL</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="col">
                                        <input type="text" value="{{ now()->format('d/m/Y') }}" disabled class="form-control" id="nomorSupplier2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">TANGGAL/SUPPLIER / LANGGANAN</th>
                                                <th class="text-center">TIPE</th>
                                                <th class="text-center"></th>
                                                @foreach ($allProducts as $prd)
                                                    <th class="text-center">{{ $prd['unit_jual'] }}</th>
                                                @endforeach
                                                <th class="text-center">MASUK</th>
                                                <th class="text-center">KELUAR</th>
                                                <th class="text-center">STOK AKHIR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ now()->format('d/m/Y') . " ' " . $product->supplier->nama }}</td>
                                                <td>STOK</td>
                                                <td class="text-center"><input type="checkbox" name="" id=""></td>
                                                @foreach ($allProducts as $prd)
                                                    <td class="text-end">{{ $prd['stok'] }}</td>
                                                @endforeach
                                                <td class="text-end"></td>
                                                <td class="text-end"></td>
                                                <td class="text-end">{{ $totalMasuk }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ now()->format('d/m/Y') . " ' " . $product->supplier->nama }}</td>
                                                <td>POS</td>
                                                <td class="text-center"><input type="checkbox" name="" id=""></td>
                                                @foreach ($allProducts as $prd)
                                                    <td class="text-end"></td>
                                                @endforeach
                                                <td class="text-end"></td>
                                                <td class="text-end">10</td>
                                                <td class="text-end"></td>
                                            </tr>
                                            <tr>
                                                <td>{{ now()->format('d/m/Y') . " ' " . $product->supplier->nama }}</td>
                                                <td>SALDO</td>
                                                <td class="text-center"><input type="checkbox" name="" id=""></td>
                                                @foreach ($allProducts as $prd)
                                                    <td class="text-end"></td>
                                                @endforeach
                                                <td class="text-end"></td>
                                                <td class="text-end"></td>
                                                <td class="text-end">44</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <div class="mx-2">
                                <a class="btn btn-primary" href="{{ route('master.kartu-stok.index') }}">BARANG LAIN</a>
                            </div>
                            <div class="mx-2">
                                <a class="btn btn-danger" href="{{ route('index') }}">KELUAR</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
