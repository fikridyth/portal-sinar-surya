@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">HAPUS PEMBAYARAN HUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('pembayaran-hutang.destroy-hutang', enkrip($pembayaran->id)) }}" method="POST" class="form" id="myForm"">
            @csrf
            @method('DELETE')
            <div class="d-flex justify-content-center mb-4">
                <div class="card" style="width: 50%;">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">NOMOR BUKTI</label></div>
                                    <div class="col-8"><input type="text" name="nomor_bukti" class="mx-2 readonly-input"
                                            size="42" readonly value="{{ $pembayaran->nomor_bukti }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">NOMOR SUPPLIER</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42"
                                            readonly value="{{ $pembayaran->supplier->nomor }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">NAMA SUPPLIER</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42"
                                            readonly value="{{ $pembayaran->supplier->nama }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">TANGGAL</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42"
                                            readonly value="{{ $pembayaran->date }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">KETERANGAN</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42"
                                            readonly value="PEMBAYARAN HUTANG"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mb-4">
                <div class="card" style="width: 55%;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">NOMOR DOKUMEN</th>
                                        <th class="text-center">KETERANGAN</th>
                                        <th class="text-center">JUMLAH RP</th>
                                        {{-- <th class="text-center">DETAIL</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataBukti as $data)
                                        <tr>
                                            <td>{{ $data->nomor }}</td>
                                            <td>{{ $data->keterangan }}</td>
                                            <td class="text-end">{{ number_format($data->total, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-9"></div>
                            <div class="col-3">Total: {{ number_format($totalHutang, 0) }}</div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <div class="mx-2">
                                <button type="button" onclick="window.history.back()"
                                    class="btn btn-warning">KEMBALI</button>
                            </div>
                            <div class="mx-2">
                                @if ($isBayar == null)
                                    <button type="submit" class="btn btn-danger" onclick="confirmAlert(event, 'Hapus data hutang?')">HAPUS</button>
                                @else
                                    <button type="button" disabled class="btn btn-danger">HAPUS</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
@endsection
