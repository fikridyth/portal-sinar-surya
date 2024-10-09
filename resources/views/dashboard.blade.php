@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 90%;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card mb-8">
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center">
                        <h6 class="mb-2">{{ auth()->user()->name }} - {{ auth()->user()->role }}</h6>
                    </div>
                    <div class="d-flex justify-content-start align-items-center mt-2">
                        @if (auth()->user()->role == 'SUPER_USER')
                            <a href="{{ route('master.user.index') }}" class="btn btn-primary mb-2">MASTER USER & ROLE</a>
                        @else
                            <a href="{{ route('master.user.index') }}" class="btn btn-primary mb-2">UBAH DATA DIRI</a>
                        @endif
                        {{-- <a href="{{ route('master.role.index') }}" class="btn btn-primary mb-2 mx-3">MASTER ROLE</a> --}}
                    </div>
                    <div class="dropdown-divider mt-3 mb-3"></div>
                    <div class="d-flex justify-content-center">
                        <div class="mx-5">
                            <h5 class="text-center">MASTER</h5>
                            <div style="display: flex; flex-direction: column;">
                                <a href="{{ route('master.unit.show', $unit->id) }}" class="btn btn-primary mb-2">UNIT</a>
                                <a href="{{ route('master.departemen.show', $departemen->id) }}" class="btn btn-primary mb-2">DEPARTEMEN</a>
                                <a href="{{ route('master.supplier.show', $supplier->id) }}" class="btn btn-primary mb-2">SUPPLIER</a>
                                <a href="{{ route('master.kunjungan.index') }}" class="btn btn-primary mb-2">KUNJUNGAN</a>
                                <a href="{{ route('master.product.show', $product->id) }}" class="btn btn-primary mb-2">PERSEDIAAN</a>
                                <a href="{{ route('master.harga.index') }}" class="btn btn-primary mb-2">HARGA</a>
                                <a href="{{ route('master.kartu-stok.index') }}" class="btn btn-primary mb-2">KARTU STOK</a>
                                <a href="{{ route('master.bank.index') }}" class="btn btn-primary mb-2">BANK</a>
                                <a href="{{ route('master.giro.index') }}" class="btn btn-primary mb-2">PENDAFTARAN GIRO</a>
                                <a href="{{ route('master.ppn.edit', $ppn->id) }}" class="btn btn-primary mb-2">PPN</a>
                                {{-- <a href="{{ route('master.generate-qrcode') }}" class="btn btn-primary mb-2">QR Code</a> --}}
                                <a href="{{ route('master.generate-barcode') }}" class="btn btn-primary mb-2">Barcode</a>
                            </div>
                        </div>
                        <div class="mx-5">  
                            <h5 class="text-center">ORDER</h5>
                            <div style="display: flex; flex-direction: column;">
                                <a href="{{ route('preorder.index') }}" class="btn btn-primary mb-2">PREORDER</a>
                                <a href="{{ route('daftar-po') }}" class="btn btn-primary mb-2">DAFTAR PREORDER</a>
                            </div>
                        </div>
                        <div class="mx-5">  
                            <h5 class="text-center">RECEIVE</h5>
                            <div style="display: flex; flex-direction: column;">
                                @if (isset($preorder->id))
                                    <a href="{{ route('receive-po', $preorder->id) }}" class="btn btn-primary mb-2">PENERIMAAN - P.O</a>
                                @else
                                    <a href="#" class="btn btn-primary mb-2">PENERIMAAN - P.O</a>
                                @endif
                                <a href="{{ route('daftar-receive-po') }}" class="btn btn-primary mb-2">DAFTAR PENERIMAAN BARANG</a>
                                <a href="{{ route('return-po') }}" class="btn btn-primary mb-2">RETUR BARANG</a>
                                <a href="{{ route('daftar-return-po') }}" class="btn btn-primary mb-2">DAFTAR RETUR BARANG</a>
                                <a href="{{ route('persetujuan-harga-jual') }}" class="btn btn-primary mb-2">PERSETUJUAN HARGA JUAL</a>
                            </div>
                        </div>
                        <div class="mx-5">  
                            <h5 class="text-center">PEMBAYARAN</h5>
                            <div style="display: flex; flex-direction: column;">
                                <a href="{{ route('pembayaran-hutang.index') }}" class="btn btn-primary mb-2">PEMBAYARAN HUTANG</a>
                                <a href="{{ route('pembayaran-hutang.index-hapus') }}" class="btn btn-primary mb-2">HAPUS PEMBAYARAN HUTANG</a>
                                <a href="{{ route('pembayaran.index') }}" class="btn btn-primary mb-2">PEMBAYARAN CEK/GIRO</a>
                                <a href="{{ route('pembayaran-konfirmasi.index') }}" class="btn btn-primary mb-2">KONFIRMASI CEK/GIRO</a>
                                <a href="{{ route('pembayaran-piutang.index') }}" class="btn btn-primary mb-2">PEMBAYARAN PIUTANG</a>
                            </div>
                        </div>
                        <div class="mx-5">  
                            <h5 class="text-center">LAPORAN</h5>
                            <div style="display: flex; flex-direction: column;">
                                <a href="{{ route('daftar-harga-jual-kecil') }}" class="btn btn-primary mb-2">HARGA JUAL < HARGA BELI</a>
                                <a href="{{ route('master.harga-sementara.index') }}" class="btn btn-primary mb-2">HARGA SEMENTARA</a>
                                <a href="{{ route('master.opname') }}" class="btn btn-primary mb-2">STOCK OPNAME</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
