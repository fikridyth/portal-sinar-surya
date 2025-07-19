@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    {{-- <button type="button" id="tambahButton" class="btn btn-primary btn-tambah" onclick="addPromosi()">TAMBAH</button> --}}
                    <a href="{{ route('master.supplier.show', enkrip(1)) }}" class="btn btn-danger mx-2">KEMBALI</a>
                </div>

                <form id="promoForm" action="{{ route('master.promosi.store') }}" method="POST" class="form">
                    @csrf
                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                            <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center">DATA-DATA PROMOSI</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">DARI TGL</th>
                                        <th class="text-center">SAMPAI TGL</th>
                                        <th class="text-center">JUMLAH</th>
                                        <th class="text-center">JENIS</th>
                                        <th class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="promoTableBody">
                                    @foreach ($promosi as $promo)
                                        <tr>
                                            <td>{{ $promo->supplier->nama }}</td>
                                            <td class="supplier" hidden>{{ $promo->supplier->id }}</td>
                                            <td class="date_first text-center">{{ $promo->date_first }}</td>
                                            <td class="date_last text-center">{{ $promo->date_last }}</td>
                                            <td class="total text-end">{{ number_format($promo->total, 0) }}</td>
                                            <td class="tipe">{{ $promo->tipe }}</td>
                                            <td class="text-center">
                                                @if ($promo->nomor_bukti == null)
                                                    <span class="badge badge-primary" style="font-size: 0.7rem;">BELUM DIPAKAI</span>
                                                @else
                                                    <span class="badge badge-danger" style="font-size: 0.7rem;">SUDAH DIPAKAI</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success">SUBMIT</button>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
