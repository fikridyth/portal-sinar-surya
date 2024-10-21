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
        gap: 10px;
        /* Optional: Adds space between columns */
    }

    .column {
        flex: 1 1 10%;
        /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
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
        flex: 1 1 20%;
        /* Adjust width for column with different size */
    }

    .fs-need {
        font-size: 14px;
    }
</style>

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 82%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                PEMBAYARAN CEK/GIRO/TUNAI
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST" class="form">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="row w-100">
                                    <div class="form-group col-12">
                                        <table id="details-table" class="table table-bordered">
                                            <thead>
                                                <tr class="fs-need">
                                                    <th class="text-center">NAMA CABANG</th>
                                                    <th class="text-center">NAMA SERVER</th>
                                                    <th class="text-center">KODE</th>
                                                    <th class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cabangs as $cabang)
                                                    <tr>
                                                        <td>{{ $cabang->nama }}</td>
                                                        <td>{{ $cabang->server }}</td>
                                                        <td class="text-center">{{ $cabang->kode }}</td>
                                                        <td class="text-center"><a href="{{ route('pembayaran.cabang.show', enkrip($cabang->id)) }}" class="btn btn-sm btn-primary">PILIH</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('pembayaran.cabang-index') }}" class="btn btn-danger mx-2">KEMBALI</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
