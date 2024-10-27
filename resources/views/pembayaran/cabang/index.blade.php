@extends('main')

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
                    <form action="{{ route('pembayaran.cabang.update') }}" method="POST" class="form">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="row w-100">
                                    <div class="form-group col-12">
                                        <div style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                                <thead>
                                                    <tr class="fs-need">
                                                        <th class="text-center">NAMA SUPPLIER</th>
                                                        <th class="text-center">NOMOR BUKTI</th>
                                                        <th class="text-center">JUMLAH RP</th>
                                                        {{-- <th class="text-center">LAIN2</th>
                                                        <th class="text-center">PROMOSI</th>
                                                        <th class="text-center">MATERAI</th>
                                                        <th class="text-center">BIAYA</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($pembayarans as $bayar)
                                                        <tr>
                                                            <input type="hidden" name="id[]" value="{{ $bayar->id }}">
                                                            <td>{{ $bayar->supplier->nama }}</td>
                                                            <td class="text-center">{{ $bayar->nomor_bukti }}</td>
                                                            <td class="text-end">{{ number_format($bayar->grand_total) }}</td>
                                                            {{-- <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td> --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('pembayaran.cabang.browse') }}" class="btn btn-dark mx-2" style="color: red">BROWSE HUTANG CABANG</a>
                                <a href="{{ route('pembayaran.index') }}" class="btn btn-danger mx-2">BATAL</a>
                                <button type="submit" class="btn btn-primary mx-2">PROSES</button>
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
