@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN HUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <form action="{{ route('pembayaran-hutang.store', enkrip($supplier->id)) }}" method="POST" class="form" id="myForm">
            @csrf
            <div class="d-flex justify-content-center mb-4">
                <div class="card" style="width: 50%;">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">NOMOR BUKTI</label></div>
                                    <div class="col-8"><input type="text" name="nomor_bukti" class="mx-2 readonly-input" size="42" readonly value="{{ $getNomorBukti }}"></div>
                                    <input type="text" hidden name="nomor_po" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">NAMA</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42" readonly value="{{ $supplier->nama }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">ALAMAT</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42" readonly value="{{ $supplier->alamat1 }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">TANGGAL DIBAYAR</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42" readonly value="{{ now()->format('d/m/Y') }}"></div>
                                    <input type="text" name="date_payment" hidden value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-4"><label for="">KETERANGAN</label></div>
                                    <div class="col-8"><input type="text" class="mx-2 readonly-input" size="42" readonly value="PEMBAYARAN HUTANG"></div>
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
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">JUMLAH DIBAYAR</th>
                                        {{-- <th class="text-center">DETAIL</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getHutang as $index => $hutang)
                                        <tr id="row-{{ $index }}">
                                            <input type="text" hidden name="nomor[]" value="{{ $hutang['nomor'] }}" data-row-id="{{ $index }}">
                                            <input type="text" hidden name="date[]" value="{{ $hutang['date'] }}" data-row-id="{{ $index }}">
                                            <input type="text" hidden name="total[]" value="{{ $hutang['total'] }}" data-row-id="{{ $index }}">
                                            <td class="text-center">{{ $hutang['nomor'] }}</td>
                                            <td class="text-center">{{ $hutang['date'] }}</td>
                                            <td class="text-end">{{ number_format($hutang['total'], 0) }}</td>
                                        </tr>
                                    @endforeach
                                    <input type="text" hidden name="materai" value="{{ $materai }}">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-9"></div>
                            <div class="col-3">Total: {{ number_format($totalHutang, 0) }}</div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <div class="mx-2">
                                <button type="button" onclick="location.reload()" class="btn btn-primary">REFRESH</button>
                            </div>
                            <div class="mx-2">
                                <button type="button" onclick="window.history.back()" class="btn btn-danger">KEMBALI</button>
                            </div>
                            <div class="mx-2">
                                <button type="submit" name="tipe" value="cetak" class="btn btn-warning" onclick="setTarget('_self')">CETAK</button>
                            </div>
                            <div class="mx-2">
                                @if ($isCetak->is_cetak == 1)
                                    <button type="submit" name="tipe" value="store" class="btn btn-primary" onclick="setTarget('_self')">PROSES</button>
                                @else
                                    <button type="button" disabled class="btn btn-primary">PROSES</button>
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
    <script>
        function setTarget(target) {
            document.getElementById('myForm').target = target;
        }
    </script>
@endsection
