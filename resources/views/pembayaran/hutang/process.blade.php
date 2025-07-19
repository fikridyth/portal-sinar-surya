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

        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">NO SUPPLIER</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->nomor }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">TANGGAL</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ now()->format('d/m/Y') }}"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">NAMA</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->nama }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">NOMOR BUKTI</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $getNomorBukti }}"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">ALAMAT</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->alamat1 }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">KETERANGAN</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="PEMBAYARAN HUTANG"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('pembayaran-hutang.process-final', enkrip($supplier->id)) }}" method="POST" class="form">
            @csrf
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">NO DOKUMEN</th>
                                <th class="text-center">CURRENCY</th>
                                <th class="text-center">RATE</th>
                                <th class="text-center">JUMLAH</th>
                                <th class="text-center">HAPUS</th>
                                {{-- <th class="text-center">DETAIL</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getHutangPromosi as $index => $hutang)
                                @php
                                    $isPromosi = explode('-', $hutang['nomor']);
                                @endphp
                                <tr id="row-{{ $index }}">
                                    <input type="text" hidden name="nomor[]" value="{{ $hutang['nomor'] }}" data-row-id="{{ $index }}">
                                    <input type="text" hidden name="date[]" value="{{ $hutang['date'] }}" data-row-id="{{ $index }}">
                                    <input type="text" hidden name="total[]" value="{{ $hutang['total'] }}" data-row-id="{{ $index }}">
                                    <td class="text-center" style="{{ $isPromosi[0] == 'AM' ? 'color: red;' : '' }}">{{ $hutang['date'] }}</td>
                                    <td class="text-center" style="{{ $isPromosi[0] == 'AM' ? 'color: red;' : '' }}">{{ $hutang['nomor'] }}</td>
                                    <td class="text-center" style="{{ $isPromosi[0] == 'AM' ? 'color: red;' : '' }}">IND</td>
                                    <td class="text-end" style="{{ $isPromosi[0] == 'AM' ? 'color: red;' : '' }}">1</td>
                                    <td class="text-end" style="{{ $isPromosi[0] == 'AM' ? 'color: red;' : '' }}">{{ number_format($hutang['total'], 0) }}</td>
                                    <td class="text-center"><input type="checkbox" class="remove-checkbox" data-row-id="{{ $index }}"></td>
                               </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row align-items-center mt-4">
                        <div class="col-12 col-md-1"></div>
                        <div class="col-12 col-md-4">
                            <button type="button" onclick="window.history.back()" class="btn btn-danger mx-2">KEMBALI</button>
                            <button type="submit" name="tipe" value="cetak" class="btn btn-warning mx-2" onclick="setTarget('_self')">CETAK</button>
                            <button type="submit" name="tipe" value="store" class="btn btn-primary mx-2" onclick="setTarget('_self')">PROSES</button>
                        </div>
                        <div class="col-12 col-md-3 mx-3"></div>
                        <div class="col-12 col-md-2 mx-5" style="font-size: 18px;">
                            <b>TOTAL : {{ number_format($totalHutang, 0) }}</b>
                        </div>
                        <div class="col-12 col-md-2"></div>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <h5 class="p-1" style="background-color: black; color: aqua;">BIAYA YANG MENJADI BEBAN SUPPLIER</h5>
                    </div>
                    <div class="row align-items-center mt-1">
                        <div class="col-12 col-md-3"></div>
                        <div class="col-12 col-md-2">
                            <label for="materai" class="mx-1">MATERAI</label>
                            <input type="text" size="8" name="materai" class="text-end" value="{{ $supplier->materai }}">
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="promosi" class="mx-1">PROMOSI</label>
                            @if(isset($promosi[0]['total']))
                                <input type="text" size="8" class="text-end" readonly value="{{ number_format(abs($promosi[0]['total'])) }}">
                            @else
                                <input type="text" size="8" class="text-end" readonly value="0">
                            @endif
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="bonus" class="mx-1">BONUS</label>
                            <input type="text" size="8" readonly class="text-end" value="0">
                        </div>
                        <div class="col-12 col-md-3"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function removeRowAndInputs(rowId, prefix) {
                var row = document.getElementById(prefix + rowId);
                if (row) {
                    // Remove the row
                    row.remove();
                    // Remove the hidden inputs
                    document.querySelectorAll(`input[name^="nomor"][data-row-id="${rowId}"], input[name^="date"][data-row-id="${rowId}"], input[name^="total"][data-row-id="${rowId}"]`).forEach(function(input) {
                input.remove();
            });
                }
            }

            document.querySelectorAll('.remove-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        var rowId = this.getAttribute('data-row-id');
                        removeRowAndInputs(rowId, 'row-');
                    }
                });
            });
        });
    </script>
@endsection
