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
                <form action="{{ route('pembayaran-piutang.store') }}" method="POST" class="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3 d-flex align-items-center">
                        <div class="col-2">
                            <a href="{{ route('daftar-tagihan.index') }}" class="btn btn-sm btn-dark" style="color: blueviolet; font-size: 14px;">DAFTAR TAGIHAN</a>
                        </div>
                    </div>
                    <div class="row mb-3 d-flex align-items-center">
                        <div class="col-1">
                            <label for="">COLLECTOR</label>
                        </div>
                        <div class="col-2">
                            <select id="user-select" class="user-select btn-block" name="created_by">
                                @foreach ($listUser as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6"></div>
                        <div class="col-1">
                            <label for="">NO BUKTI</label>
                        </div>
                        <div class="col-2">
                            <input type="text" class="readonly-input" readonly name="nomor_piutang" value="{{ $getNomor }}">
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NAMA LANGGANAN</th>
                                <th class="text-center">NO DOKUMEN</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">JUMLAH RP</th>
                                <th class="text-center">MATERAI</th>
                                <th class="text-center">V</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($listBayar->isEmpty() && $listTunai->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center"><b>TIDAK ADA DATA PEMBAYARAN</b></td>
                                </tr>
                            @else
                                @foreach ($listBayar as $index => $bayar)
                                    <tr>
                                        <td>{{ $bayar->supplier->nama }}</td>
                                        <td>{{ $bayar->nomor_bukti }}</td>
                                        <td class="text-center">{{ $bayar->date }}</td>
                                        <td class="text-end">{{ number_format($bayar->total_with_materai, 0) }}</td>
                                        <td class="text-end">{{ number_format($bayar->beban_materai, 0) ?? 0 }}</td>
                                        <td class="text-center"><input type="checkbox" name="check[{{ $index }}]" value="{{ $bayar->nomor_bukti }}"></td>
                                    </tr>
                                @endforeach
                                @foreach ($listTunai as $index => $tunai)
                                    <tr>
                                        <td>{{ $tunai->supplier->nama }}</td>
                                        <td>{{ $tunai->nomor_bukti }}</td>
                                        <td class="text-center">{{ $tunai->date }}</td>
                                        <td class="text-end">{{ number_format($tunai->total_with_materai, 0) }}</td>
                                        <td class="text-end">{{ number_format($tunai->beban_materai, 0) ?? 0 }}</td>
                                        <td class="text-center"><input type="checkbox" name="check[{{ $index }}]" value="{{ $tunai->nomor_bukti }}"></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" class="btn btn-primary">PROSES</button>
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
            $('#user-select').select2();
        });
    </script>
@endsection
