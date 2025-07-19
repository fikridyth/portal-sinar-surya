@extends('main')

@include('master.product.add-on.styles')

@include('master.product.add-on.modal')

<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 9999; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.4); 
}
.modal-content {
  background-color: #fefefe;
  margin: 10% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 30%;       /* agar lebarnya hanya 30% layar */
  min-width: 300px; /* biar tetap bisa terbaca di layar kecil */
  max-width: 500px; /* supaya tidak kebesaran di layar besar */
  border-radius: 10px;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}
.close:hover,
.close:focus {
  color: black;
}
</style>

@section('content')
    <div class="container mt-n3">
        <form action="{{ route('master.product.update', enkrip($product->id)) }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="kode">KODE BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $product->kode) }}"
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
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="nama_sumber">NAMA SUMBER</label>
                            </div>
                            <div class="col">
                                @if ($product->kode_sumber !== null)
                                    <input type="text" id="nama_sumber" name="nama_sumber" value="{{ $product->nama }}"
                                        class="form-control readonly-input @error('nama_sumber') is-invalid @enderror"
                                        autocomplete="off" readonly />
                                @else
                                    <input type="text" name="nama_sumber" value=""
                                        class="form-control readonly-input @error('nama_sumber') is-invalid @enderror"
                                        autocomplete="off" readonly />
                                    <input type="text" hidden id="nama_sumber">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-1">
                        {{-- <div class="row align-items-center">
                            <div class="col">
                                @if ($product->kode_sumber !== null)
                                    <label class="form-label h6 mt-2" for="nama_barang">/{{ $product->unit_beli }}</label>
                                @endif
                            </div>
                        </div> --}}
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="kode_alternatif">KODE ALTERNATIF</label>
                            </div>
                            <div class="col">
                                <input type="text" id="kode_alternatif" name="kode_alternatif" readonly
                                    value="{{ old('kode_alternatif', $product->kode_alternatif) }}"
                                    class="form-control readonly-input @error('kode_alternatif') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('kode_alternatif')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="nama_barang">NAMA BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" id="nama_barang2" name="nama_barang"
                                    value="{{ old('nama_barang', $product->nama) }}" required
                                    class="form-control readonly-input @error('nama_barang') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-2">
                        <div class="row align-items-center">
                            <a href="{{ route('master.product.edit-nama', enkrip($product->id)) }}" style="width: 200px" class="btn btn-primary">GANTI NAMA</a>
                        </div>
                    </div>

                    <div class="col-1">
                        {{-- <div class="row align-items-center">
                            <div class="col">
                                <label class="form-label h6 mt-2" for="nama_barang">/{{ $product->unit_jual }}</label>
                            </div>
                        </div> --}}
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="kode_sumber">KODE SUMBER</label>
                            </div>
                            <div class="col">
                                <input type="text" id="kode_sumber" name="kode_sumber"
                                    value="{{ $product->kode_sumber }}"
                                    class="form-control readonly-input @error('kode_sumber') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode_sumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="merek">MEREK</label>
                            </div>
                            <div class="col">
                                <input type="text" id="merek" name="merek" readonly
                                    value="{{ old('merek', $product->merek) }}"
                                    class="form-control readonly-input @error('merek') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="unit_beli">UNIT BELI</label>
                            </div>
                            <div class="col-3">
                                <input type="text" id="unit_beli" name="unit_beli"
                                    value="{{ old('unit_beli', $product->unit_beli) }}" required
                                    class="form-control readonly-input @error('unit_beli') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('unit_beli')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="label">LABEL</label>
                            </div>
                            <div class="col">
                                <input type="text" id="label" name="label" readonly
                                    value="{{ old('label', $product->label) }}"
                                    class="form-control readonly-input @error('label') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="unit_jual">UNIT JUAL</label>
                            </div>
                            <div class="col-3">
                                <input type="text" id="unit_jual" name="unit_jual"
                                    value="{{ old('unit_jual', $product->unit_jual) }}"
                                    class="form-control readonly-input @error('unit_jual') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('unit_jual')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" id="label_harga_pokok_2" for="harga_pokok_2">HARGA
                                    POKOK/{{ $product->unit_jual }}</label>
                            </div>
                            <div class="col">
                                <input type="text" name="harga_pokok_2"
                                    value="{{ number_format($product->harga_pokok) }}"
                                    class="form-control readonly-input @error('harga_pokok_2') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="profit">PROFIT</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="profit" name="profit"
                                    value="{{ old('profit', $product->profit) }}" readonly
                                    class="form-control readonly-input @error('profit') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                            <div class="col-1">
                                <label class="form-label h6 mt-2">%</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="konversi">KONVERSI</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="konversi" name="konversi"
                                    value="{{ old('konversi', str_replace('P', '', $product->unit_beli . '.00/' . $product->unit_jual . '.00')) }}"
                                    class="form-control readonly-input @error('konversi') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('konversi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" id="label_harga_pokok" for="harga_pokok">HARGA
                                    POKOK/{{ $product->unit_beli }}</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_pokok" name="harga_pokok"
                                    value="{{ old('harga_pokok', number_format($parentProduct->harga_pokok ?? $product->harga_pokok)) }}"
                                    class="form-control readonly-input @error('harga_pokok') is-invalid @enderror"
                                    autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-2 mt-2">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="ppn">PPN</label>
                            </div>
                            <div class="col-6">
                                <div class="slider-container">
                                    <input type="checkbox" id="ppn" name="ppn" {{ $product->supplier->is_ppn ? 'checked' : '' }} @disabled(true) class="slider-checkbox">
                                    <label for="ppn" class="slider-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" id="label_stok_awal" for="stok_awal">STOK AWAL</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="stok_awal" name="stok_awal" value="{{ old('stok_awal') }}"
                                    readonly class="form-control readonly-input @error('stok_awal') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('stok_awal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="harga_pokok_rata">HARGA POKOK RATA2</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_pokok_rata" name="harga_pokok_rata"
                                    value="{{ number_format($product->harga_pokok) }}" readonly
                                    class="form-control readonly-input @error('harga_pokok_rata') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="stok_akhir">STOK AKHIR</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="stok_akhir" name="stok_akhir" value="{{ old('stok_akhir') }}"
                                    readonly class="form-control readonly-input @error('stok_akhir') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('stok_akhir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" id="label_harga_jual" for="harga_jual">HARGA
                                    JUAL/{{ $product->unit_jual }}</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_jual" name="harga_jual"
                                    value="{{ old('harga_jual', number_format($product->harga_jual)) }}" readonly
                                    class="form-control readonly-input @error('harga_jual') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="jumlah_stok">JUMLAH STOK</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="jumlah_stok" name="jumlah_stok"
                                    value="{{ number_format($product->stok,0) }}" readonly
                                    class="form-control readonly-input @error('jumlah_stok') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('jumlah_stok')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                    </div>

                    <div class="col-3">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <label class="form-label h6 mt-2" for="stok_terkecil">STOK TERKECIL</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="stok_terkecil" name="stok_terkecil" value="" readonly
                                    class="form-control readonly-input @error('stok_terkecil') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="proses_order">PROSES ORDER</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="proses_order" name="proses_order"
                                    value="{{ old('proses_order') }}" readonly
                                    class="form-control readonly-input @error('proses_order') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('proses_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="status">DISFUNCTION</label>
                            </div>
                            <div class="col-3">
                                <div class="slider-container">
                                    <input type="checkbox" id="status" name="status" {{ $product->status == 0 ? 'checked' : '' }} class="slider-checkbox">
                                    <label for="status" class="slider-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <label class="form-label h6 mt-2" for="rata_terkecil">RATA2 TERKECIL</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="rata_terkecil" name="rata_terkecil" value="" readonly
                                    class="form-control readonly-input @error('rata_terkecil') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="proses_order">ORDER TERAKHIR</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="proses_order" name="proses_order"
                                    value="{{ $lastOrder !== null ? $lastOrder->format('d/m/Y') : '-' }}" readonly
                                    class="form-control readonly-input @error('proses_order') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('proses_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <a href="{{ route('master.history-product.index', enkrip($product->id)) }}" style="width: 190px" class="btn btn-primary">SEJARAH ORDER</a>
                    </div>

                    <div class="col-2">
                        <button type="button" onclick="openModal()" style="width: 190px" class="btn btn-primary">DIBUAT OLEH</button>

                        <!-- Modal -->
                        <div id="createdByModal" class="modal">
                            <div class="modal-content">
                              <span class="close" onclick="closeModal()">&times;</span>
                              <h5 class="mb-3 mt-2">Informasi Pembuat Produk</h5>
                              <p><strong>Nama:</strong> {{ $product->createdBy->name ?? '-' }}</p>
                              <p><strong>Dibuat pada:</strong> {{ $product->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="unit">UNIT</label>
                            </div>
                            <div class="col-5">
                                <div class="custom-select-unit">
                                    <input type="text" id="search-input-unit" name="unit" readonly
                                        value="{{ $product->id_unit }}" class="search-input-unit readonly-input" required
                                        placeholder="Search...">
                                    <div class="select-items-unit" id="select-items-unit">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>

                                {{-- <select id="unit" style="width: 300px;" hidden>
                                    <option value="">---Select Unit---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" data-nama="{{ $unit->nama }}">
                                            {{ $unit->id }} - {{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_unit" readonly class="form-control readonly-input"
                            value="{{ $product->unit->nama }}" autocomplete="off" />
                    </div>
                    @if (isset($parentProduct->id))
                        <div class="col-1">
                            <a href="{{ route('master.product.child', enkrip($parentProduct->id)) }}" style="width: 100px" class="btn btn-primary">ANAK</a>
                        </div>
                        <div class="col-1">
                            <a href="{{ route('master.product.parent', enkrip($parentProduct->id)) }}" style="width: 100px" class="btn btn-primary">SUMBER</a>
                        </div>
                        <div class="col-1">
                            <a href="{{ route('master.product.child-view', enkrip($parentProduct->id)) }}" style="width: 110px" class="btn btn-primary">KELOMPOK</a>
                        </div>
                    @else
                        <div class="col-1">
                            <a href="{{ route('master.product.child', enkrip($product->id)) }}" style="width: 100px" class="btn btn-primary">ANAK</a>
                        </div>
                        <div class="col-1">
                            <a href="{{ route('master.product.parent', enkrip($product->id)) }}" style="width: 100px" class="btn btn-primary">SUMBER</a>
                        </div>
                        <div class="col-1">
                            <a href="{{ route('master.product.child-view', enkrip($product->id)) }}" style="width: 110px" class="btn btn-primary">KELOMPOK</a>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="departemen">DEPARTEMEN</label>
                            </div>
                            <div class="col-5">
                                <div class="custom-select-departemen">
                                    <input type="text" class="search-input-departemen readonly-input"
                                        value="{{ $product->id_departemen }}" readonly name="departemen" required
                                        placeholder="Search...">
                                    <div class="select-items-departemen" id="select-items-departemen">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>

                                {{-- <select id="departemen" style="width: 300px;" hidden>
                                    <option value="">---Select Departemen---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($departemens as $departemen)
                                        <option value="{{ $departemen->id }}" data-nama="{{ $departemen->nama }}">
                                            {{ $departemen->id }} - {{ $departemen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('departemen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_departemen" value="{{ $product->departemen->nama }}" readonly
                            class="form-control readonly-input" autocomplete="off" />
                    </div>
                    {{-- <div class="col-2">
                        <button type="button" class="btn btn-primary" style="width: 170px;" data-mdb-ripple-init
                            data-mdb-modal-init data-mdb-target="#departemenModal">SUMBER</button>
                    </div> --}}
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="supplier">SUPPLIER</label>
                            </div>
                            <div class="col-5">
                                <div class="custom-select-supplier">
                                    <input type="text" class="search-input-supplier readonly-input" readonly
                                        value="{{ $product->supplier->nomor }}" name="supplier" required
                                        placeholder="Search...">
                                    <div class="select-items-supplier" id="select-items-supplier">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>

                                {{-- <select id="supplier" style="width: 300px;" hidden>
                                    <option value="">---Select Supplier---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}"
                                            data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} -
                                            {{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_supplier" value="{{ $product->supplier->nama }}" readonly
                            class="form-control readonly-input" autocomplete="off" />
                    </div>
                    <div class="col-2">
                        <a href="{{ route('master.product.index') }}" class="btn btn-primary">LIST INVENTORY</a>
                    </div>
                </div>
                {{-- end border --}}
            </div>

            <div class="row d-flex justify-content-start mt-n2 mb-3">
                <div class="col-1">
                    <a href="{{ route('master.product.create',  enkrip($product->id)) }}" class="btn btn-success" style="min-width: 100px;" title="TAMBAH DATA">TAMBAH</i></a>
                </div>
                <div class="col-1">
                    <a href="{{ route('master.product.edit',  enkrip($product->id)) }}" class="btn btn-warning" style="min-width: 100px;" title="EDIT DATA">UBAH</a>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary" disabled style="min-width: 100px;" title="SIMPAN DATA">SIMPAN</button>
                </div>
                <div class="col-2"></div>
                <div class="col-2"><a href="{{ route('master.store-to-pos') }}" class="btn btn-primary">TRANSFER KE POS</a></div>
                <div class="col-2"></div>
                <div class="col-1 ml-auto">
                    <a href="{{ route('master.product.index') }}" class="btn btn-primary" style="min-width: 100px;" title="CARI DATA">CARI</i></a>
                </div>
                <div class="col-1">
                    <button type="button" onclick="window.history.back()" class="btn btn-warning" style="min-width: 100px;" title="KEMBALI">KEMBALI</button>
                </div>
                <div class="col-1">
                    <a href="{{ route('master.store-to-pos') }}" class="btn btn-danger" style="min-width: 100px;" title="KELUAR">KELUAR</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @include('master.product.add-on.scripts')

    <script>
        // Mengambil elemen input dengan ID 'kode'
        var namaSElement = document.getElementById('nama_sumber');
        var namaBElement = document.getElementById('nama_barang2');

        // Mengambil nilai saat ini dari elemen input
        var namaSValue = namaSElement.value;
        var namaBValue = namaBElement.value;

        // Menambahkan teks 'hari' ke nilai saat ini
        var namaSNewValue = namaSValue + "/{{ $product->unit_beli }}";
        var namaBNewValue = namaBValue + "/{{ $product->unit_jual }}";

        // Mengupdate nilai elemen input
        namaSElement.value = namaSNewValue;
        namaBElement.value = namaBNewValue;
    </script>

    <script>
        $(document).ready(function() {
            $('#search-input-unit').on('change', function() {
                var unitId = $(this).val();
                // console.log(unitId)
                if (unitId) {
                    $.ajax({
                        url: "{{ route('master.get-departemen') }}",
                        method: 'GET',
                        data: {
                            unit_id: unitId
                        },
                        success: function(data) {
                            var $departemenSelect = $('#departemen');
                            $departemenSelect.empty().append(
                                '<option value="">---Select Departemen---</option>');
                            $.each(data.departemen, function(index, departemen) {
                                $departemenSelect.append($('<option>', {
                                    value: departemen.id,
                                    text: departemen.nama
                                }));
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching departemen data:', xhr);
                        }
                    });
                } else {
                    $('#departemen').empty().append('<option value="">---Select Departemen---</option>');
                }
            });

            var id = {{ $product->id }};
            // Detect change on the slider checkbox
            $('#status').on('change', function() {
                // Get the new status value based on the checkbox state (checked or unchecked)
                var status = $(this).is(':checked') ? 1 : 0;

                // AJAX request to update the status in the database
                $.ajax({
                    url: '/master/product/update-status/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token for security
                        status: status // Send the new status
                    },
                    success: function(response) {
                        // You can handle the response here, for example, show a success message
                        alert('SUKSES UBAH STATUS');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle any error that occurs
                        console.error('Update failed: ', error);
                    }
                });
            });
        });
    </script>

    <script>
        function openModal() {
            document.getElementById('createdByModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('createdByModal').style.display = 'none';
        }
        window.onclick = function(event) {
            const modal = document.getElementById('createdByModal');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection
