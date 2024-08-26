@extends('main')

@include('master.product.add-on.styles')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER SUPPLIER</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="#" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row mb-2">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">NOMOR</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $supplier->nomor) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">NAMA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $supplier->nama) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">ALAMAT 1</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $supplier->alamat1) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">ALAMAT 2</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $supplier->alamat2) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">PENJUALAN RATA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="penjualan_rata" name="kode"
                                    value="{{ old('kode', $supplier->penjualan_rata) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">WAKTU KUNJUNGAN</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="waktu_kunjungan" name="kode"
                                    value="{{ old('kode', $supplier->waktu_kunjungan) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">STOK MINIMUM</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="stok_minimum" name="kode"
                                    value="{{ old('kode', $supplier->stok_minimum) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">STOK MAKSIMUM</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="stok_maksimum" name="kode"
                                    value="{{ old('kode', $supplier->stok_maksimum) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">PPN</label>
                            </div>
                            <div class="col-5">
                                <div class="slider-container">
                                    <input type="checkbox" id="ppn" name="ppn" {{ $supplier->is_ppn ? 'checked' : '' }} @disabled(true) class="slider-checkbox">
                                    <label for="ppn" class="slider-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end border --}}
            </div>

            <div class="row d-flex justify-content-start mb-7">
                <div class="col-0-5">
                    <a href="{{ route('master.supplier.create', $supplier->id) }}" class="btn btn-success" title="TAMBAH DATA"><i class="fas fa-plus"></i></a>
                </div>
                <div class="col-0-5">
                    <a href="{{ route('master.supplier.edit', $supplier->id) }}" class="btn btn-warning" title="EDIT DATA"><i class="fas fa-edit"></i></a>
                </div>
                <div class="col-0-5">
                    <button type="submit" class="btn btn-primary" disabled title="SIMPAN DATA"><i class="fas fa-save"></i></button>
                </div>
                <div class="col-8"></div>
                <div class="col-0-5 ml-auto">
                    <a href="{{ route('master.supplier.index') }}" class="btn btn-primary" title="CARI DATA"><i class="fas fa-search"></i></a>
                </div>
                <div class="col-0-5">
                    <button type="button" onclick="window.history.back()" class="btn btn-warning" title="KEMBALI"><i class="fas fa-arrow-left"></i></button>
                </div>
                <div class="col-0-5">
                    <a href="{{ route('index') }}" class="btn btn-danger" title="KELUAR"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Mengambil elemen input dengan ID 'kode'
    var rataElement = document.getElementById('penjualan_rata');
    var waktuElement = document.getElementById('waktu_kunjungan');
    var minElement = document.getElementById('stok_minimum');
    var maxElement = document.getElementById('stok_maksimum');
    
    // Mengambil nilai saat ini dari elemen input
    var rataValue = rataElement.value;
    var waktuValue = waktuElement.value;
    var minValue = minElement.value;
    var maxValue = maxElement.value;
    
    // Menambahkan teks 'hari' ke nilai saat ini
    var rataNewValue = rataValue + ' Hari';
    var WaktuNewValue = waktuValue + ' Hari';
    var minNewValue = minValue + ' Hari';
    var maxNewValue = maxValue + ' Hari';
    
    // Mengupdate nilai elemen input
    rataElement.value = rataNewValue;
    waktuElement.value = WaktuNewValue;
    minElement.value = minNewValue;
    maxElement.value = maxNewValue;
</script>
@endsection