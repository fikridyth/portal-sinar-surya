@extends('main')

@section('content')
    <div class="container mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-1">
                        <div class="form-group">
                            <div class="row">
                                <label for="nomorSupplier2" class="col col-form-label" style="font-size: 13px;">LANGGANAN</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" value="{{ $piutang->langganan->nama }}" disabled class="form-control" id="nomorSupplier2" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-2">
                        <div class="form-group">
                            <div class="row">
                                <label for="nomorSupplier2" class="col col-form-label" style="font-size: 15px;">JENIS PEMBAYARAN</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" value="{{ $piutang->jenis_bayar }}" disabled class="form-control" id="nomorSupplier2" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-1">
                        <div class="form-group">
                            <div class="row">
                                <label for="nomorSupplier2" class="col col-form-label" style="font-size: 14px;">NAMA BANK</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <input type="text" value="{{ $piutang->nama_bank ?? '-' }}" disabled class="form-control" id="nomorSupplier2" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc;">
                    <table class="table table-bordered">
                        <h6 class="text-center mt-1 mb-3">HISTORY TAGIHAN LANGGANAN</h6>
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
                </div>
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
