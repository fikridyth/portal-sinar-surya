@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN CEK/GIRO/TUNAI
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6>SISTEM ADMINISTRATOR</h6>
                <hr>
                <div class="d-flex justify-content-between mt-2">
                    <div class="row w-100">
                        <div class="form-group col-5">
                            <div class="row mb-1">
                                <div class="col-4">
                                    <label for="date-input">TANGGAL</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" readonly id="date-input" class="form-control readonly-input" value="{{ now()->format('d/m/Y') }}">
                                </div>
                                <div class="col-4 text-right">
                                    {{-- <form action="{{ route('pembayaran.default-bank') }}" method="POST" class="form">
                                        @csrf
                                        <input type="number" hidden name="id" value="1">
                                        <button type="submit" class="btn btn-sm btn-danger">DEFAULT BANK</button>
                                    </form> --}}
                                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-danger">DEFAULT BANK</a>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4">
                                    <label for="">BANK</label>
                                </div>
                                <div class="col-8">
                                    <select id="select-banks" class="product-select btn-block">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}" data-no-rekening="{{ $bank->no_rekening }}">{{ $bank->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4">
                                    <label for="">NO REKENING</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" readonly class="btn-block readonly-input" id="no-rekening" value="23289">
                                </div>
                            </div>
                            <div class="row mb-1 mt-3">
                                <div class="col-2"></div>
                                <div class="col-2" style="background-color: brown;">
                                    <input type="radio" id="cek" name="rekening" value="cek">
                                    <label for="cek" style="color: white;">CEK</label>
                                </div>
                                <div class="col-2" style="background-color: brown;">
                                    <input type="radio" id="giro" name="rekening" value="giro">
                                    <label for="giro" style="color: white;">GIRO</label>
                                </div>
                                <div class="col-3" style="background-color: brown;">
                                    <input type="radio" id="transfer" name="rekening" value="transfer">
                                    <label for="transfer" style="color: white;">TRANSFER</label>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">JUMLAH RP</th>
                                        <th class="text-center">SALDO RP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-7">
                            <table class="table table-bordered">
                                <thead>
                                    {{-- <tr>
                                        <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG SUDAH DIBUATKAN P.O</th>
                                    </tr> --}}
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">JUMLAH RP</th>
                                        <th class="text-center">NOMOR GIRO</th>
                                        <th class="text-center">V</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $previousIdParent = null;
                                    @endphp
                                    @foreach ($pembayarans as $pmb)
                                        <tr>
                                            {{-- <td class="text-center">{{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}</td> --}}
                                            <td class="text-center">
                                                @if ($pmb->id_parent !== $previousIdParent)
                                                    {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                    @php $counter++; @endphp
                                                @else
                                                    {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                @endif
                                            </td>
                                            <td>{{ $pmb->supplier->nama }}</td>
                                            <td class="text-end">{{ number_format($pmb->total) }}</td>
                                            <td>{{ $pmb->nomor_giro }}</td>
                                            @if ($pmb->nomor_giro == null)
                                                <td class="text-center"><a href="{{ route('pembayaran.show', $pmb->id) }}" class="btn btn-sm btn-primary">BAYAR</a></td>
                                                {{-- <td class="text-center"><button class="btn btn-sm btn-primary" disabled>HAPUS</button></td> --}}
                                                @else
                                                {{-- <td class="text-center"><button class="btn btn-sm btn-primary" disabled>BAYAR</button></td> --}}
                                                <td class="text-center">
                                                    <form action="{{ route('pembayaran.destroy', $pmb->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                        @if ($pmb->id_parent)
                                        @php $previousIdParent = $pmb->id_parent; @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#select-banks').select2();

            $('#select-banks').on('select2:select', function(e) {
                var selectedOption = e.params.data.element;
                var noRekening = $(selectedOption).data('no-rekening');
                $('#no-rekening').val(noRekening);
            });
        });
    </script>
@endsection
