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
        <div class="mb-2" style="width: 82%">
            <div class="card mt-n1">
                <div class="card-body mt-n4">
                    <form action="#" method="POST" class="form">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="row w-100">
                                    <div class="form-group col-12">
                                        <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                                            <table id="details-table" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                                <thead>
                                                    <tr class="fs-need">
                                                        <th class="text-center">NOMOR PO</th>
                                                        <th class="text-center">KODE</th>
                                                        <th class="text-center">NAMA</th>
                                                        <th class="text-center">UNIT</th>
                                                        <th class="text-center">HARGA POKOK</th>
                                                        <th class="text-center">H JUAL B OFFICE</th>
                                                        <th class="text-center">H JUAL POS</th>
                                                        <th class="text-center">MARK UP</th>
                                                        <th class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($allMatchingProducts->isEmpty())
                                                        <tr>
                                                            <td colspan="9" class="text-center"><b>TIDAK ADA DATA RECEIVE</b></td>
                                                        </tr>
                                                    @else
                                                        @foreach ($allMatchingProducts as $product)
                                                            <tr class="fs-need">
                                                                <td class="text-center">{{ $product['preorder'] }}</td>
                                                                <td class="text-center">{{ $product['kode'] }}</td>
                                                                <td>{{ $product['nama'] }}</td>
                                                                <td>{{ $product['unit'] }}</td>
                                                                <td class="text-end">{{ number_format($product['harga_pokok']) }}</td>
                                                                <td class="text-end">{{ number_format($product['harga_jual']) }}</td>
                                                                <td class="text-end">{{ number_format($product['harga_jual']) }}</td>
                                                                <td class="text-end">{{ $product['mark_up'] }}</td>
                                                                <td class="text-center"><a href="{{ route('persetujuan-harga-jual-edit', enkrip($product['id'])) }}" class="btn btn-sm btn-primary">DETAIL</a></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        
    </script>
@endsection
