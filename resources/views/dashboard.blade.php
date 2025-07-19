@extends('main')
@php
    $role = explode(',', auth()->user()->role->otorisasi);
@endphp

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="card mb-2 mt-2" style="background-color: rgb(255, 200, 194);">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <h4 class="mb-2">{{ auth()->user()->name }} - {{ auth()->user()->role->nama }}</h4>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        @if (in_array("31", $role))<a href="{{ route('master.user.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">OTORISASI USER</a>@endif
                        @if (in_array("32", $role))<a href="{{ route('master.role.index') }}" class="btn btn-light mb-2 mx-3" style="color: blue; border: 1px solid black">OTORISASI STANDAR</a>@endif
                    </div>
                    <div class="dropdown-divider mt-3 mb-3"></div>
                    <div class="d-flex justify-content-center">
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                @if (in_array("1", $role))<a href="#" class="btn btn-danger disabled-not-blur mb-2">MASTER</a>@endif
                                @if (in_array("2", $role))<a href="{{ route('master.unit.show', enkrip($unit->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">UNIT</a>@endif
                                @if (in_array("3", $role))<a href="{{ route('master.departemen.show', enkrip($departemen->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">DEPARTEMEN</a>@endif
                                @if (in_array("4", $role))<a href="{{ route('master.supplier.show', enkrip($supplier->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">SUPPLIER</a>@endif
                                @if (in_array("5", $role))<a href="{{ route('master.kunjungan.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">KUNJUNGAN</a>@endif
                                @if (in_array("6", $role))<a href="{{ route('master.product.show', enkrip($product->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PERSEDIAAN</a>@endif
                                {{-- @if (in_array("7", $role))<a href="{{ route('master.harga.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">HARGA</a>@endif --}}
                                @if (in_array("8", $role))<a href="{{ route('master.kartu-stok.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">KARTU STOK</a>@endif
                                @if (in_array("9", $role))<a href="{{ route('master.bank.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">BANK</a>@endif
                                @if (in_array("10", $role))<a href="{{ route('master.giro.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENDAFTARAN GIRO</a>@endif
                                @if (in_array("11", $role))<a href="{{ route('master.ppn.edit', enkrip($ppn->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PPN</a>@endif
                                @if (in_array("37", $role))<a href="{{ route('master.langganan.show', enkrip($langganan->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">LANGGANAN</a>@endif
                                {{-- <a href="{{ route('master.generate-qrcode') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">QR Code</a>
                                <a href="{{ route('master.generate-barcode') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">Barcode</a> --}}
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                @if (in_array("12", $role))<a href="#" class="btn btn-danger disabled-not-blur mb-2">ORDER</a>@endif
                                @if (in_array("13", $role))<a href="{{ route('preorder.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PREORDER</a>@endif
                                @if (in_array("14", $role))<a href="{{ route('daftar-po') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">DAFTAR PREORDER</a>@endif
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                @if (in_array("15", $role))<a href="#" class="btn btn-danger disabled-not-blur mb-2">RECEIVE</a>@endif
                                @if (isset($preorder->id))
                                    @if (in_array("16", $role))<a href="{{ route('receive-po.create-detail', enkrip($preorder->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENERIMAAN - P.O</a>@endif
                                @else
                                    @if (in_array("16", $role))<a href="{{ route('receive-po.create') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENERIMAAN - P.O</a>@endif
                                @endif
                                @if (in_array("17", $role))<a href="{{ route('daftar-receive-done-po') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENERIMAAN</a>@endif
                                @if (in_array("18", $role))<a href="{{ route('return-po') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">RETUR BARANG</a>@endif
                                {{-- @if (in_array("19", $role))<a href="{{ route('daftar-return-po') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">DAFTAR RETUR BARANG</a>@endif --}}
                                @if (in_array("20", $role))<a href="{{ route('persetujuan-harga-jual') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PERSETUJUAN HARGA JUAL</a>@endif
                                @if (in_array("40", $role))<a href="{{ route('batal-persetujuan-harga-jual') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">BATAL PERSETUJUAN HARGA JUAL</a>@endif
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                @if (in_array("21", $role))<a href="#" class="btn btn-danger disabled-not-blur mb-2">PEMBAYARAN</a>@endif
                                @if (in_array("22", $role))<a href="{{ route('pembayaran-hutang.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PEMBAYARAN HUTANG</a>@endif
                                @if (in_array("23", $role))<a href="{{ route('pembayaran-hutang.index-hapus') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">HAPUS PEMBAYARAN HUTANG</a>@endif
                                @if (in_array("24", $role))<a href="{{ route('pembayaran.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PEMBAYARAN CEK/GIRO</a>@endif
                                @if (in_array("25", $role))<a href="{{ route('pembayaran-konfirmasi.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">KONFIRMASI CEK/GIRO</a>@endif
                                @if (in_array("38", $role))<a href="{{ route('kredit.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">ORDER PENJUALAN</a>@endif
                                @if (in_array("39", $role))<a href="{{ route('kredit.index-history', enkrip($kredit->id)) }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENJUALAN KREDIT</a>@endif
                                @if (in_array("26", $role))<a href="{{ route('pembayaran-piutang.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PEMBAYARAN PIUTANG</a>@endif
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                @if (in_array("27", $role))<a href="#" class="btn btn-danger disabled-not-blur mb-2">LAPORAN</a>@endif
                                @if (in_array("28", $role))<a href="{{ route('daftar-harga-jual-kecil') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">HARGA JUAL < HARGA BELI</a>@endif
                                @if (in_array("29", $role))<a href="{{ route('master.harga.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">HARGA SEMENTARA</a>@endif
                                {{-- @if (in_array("30", $role))<a href="{{ route('master.opname') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">STOCK OPNAME</a>@endif --}}
                                @if (in_array("33", $role))<a href="{{ route('master.change-supplier.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PERUBAHAN SUPPLIER</a>@endif
                                @if (in_array("34", $role))<a href="{{ route('master.adjustment.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">CETAK ADJUSTMENT</a>@endif
                                @if (in_array("35", $role))<a href="{{ route('master.adjustment.index-edit') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">PENYESUAIAN PERSEDIAAN</a>@endif
                                @if (in_array("36", $role))<a href="{{ route('master.adjustment.history.index') }}" class="btn btn-light mb-2" style="color: blue; border: 1px solid black">HISTORY PENYESUAIAN PERSEDIAAN</a>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
