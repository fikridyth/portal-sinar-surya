@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN PIUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <h6 class="text-center mb-3">HISTORY TAGIHAN LANGGANAN</h6>
                    <thead>
                        <tr>
                            <th class="text-center">NAMA SUPPLIER</th>
                            <th class="text-center">NO DOKUMEN</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">JUMLAH</th>
                            <th class="text-center">BONUS</th>
                            <th class="text-center">MATERAI</th>
                            <th class="text-center">TOTAL</th>
                            {{-- <th class="text-center">SEMUA</th>
                            <th class="text-center">CICIL</th>
                            <th class="text-center">CICILAN</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (json_decode($piutang->detail, true) as $detail)
                            <tr>
                                <td>{{ $detail['nama'] }}</td>
                                <td>{{ $detail['nomor_bukti'] }}</td>
                                <td class="text-center">{{ $detail['date'] }}</td>
                                <td class="text-end">{{ number_format($detail['total_with_materai'], 0) }}</td>
                                <td class="text-end">0</td>
                                <td class="text-end">{{ number_format($detail['beban_materai'], 0) }}</td>
                                <td class="text-end">{{ number_format($detail['total_with_materai'] + $detail['beban_materai'], 0) }}</td>
                                {{-- <td class="text-center"><input type="checkbox" disabled></td>
                                <td class="text-center"><input type="checkbox" disabled></td>
                                <td></td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="mx-2">
                        <a href="{{ route('daftar-tagihan.cetak', enkrip($piutang->id)) }}" class="btn btn-warning">CETAK</a>
                    </div>
                    <div class="mx-2">
                        <a href="{{ route('history-tagihan.index') }}" class="btn btn-danger">BATAL</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
