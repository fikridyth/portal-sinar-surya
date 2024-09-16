@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">BUKU CEK / GIRO
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="6" class="text-center">GIRO</th>
                        </tr>
                        <tr>
                            <th class="text-center">NOMOR</th>
                            <th class="text-center">DIBAYARKAN KEPADA</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">JATUH TEMPO</th>
                            <th class="text-center">JUMLAH</th>
                            <th class="text-center">RUSAK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($giroDetail as $detail)
                            <tr>
                                <td class="text-center">{{ $detail->nomor }}</td>
                                <td>{{ $detail->nama }}</td>
                                <td class="text-center">{{ $detail->tanggal_awal }}</td>
                                <td class="text-center">{{ $detail->tanggal_akhir }}</td>
                                <td class="text-end">{{ number_format($detail->jumlah, 0) }}</td>
                                <td class="text-center">
                                    <input type="checkbox" name="" id="">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="align-items-center mt-4">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('master.giro.index') }}" class="btn btn-warning mx-2">KEMBALI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
