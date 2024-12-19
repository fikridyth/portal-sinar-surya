@extends('main')

@include('master.product.add-on.styles')
                    
@include('master.product.add-on.modal')

@section('content')
    <div class="container">
        <form action="{{ route('master.product.update', enkrip($product->id)) }}" method="POST" class="form" enctype="multipart/form-data">
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
                                <input type="text" id="kode" name="kode" value="{{ old('kode', $product->kode) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror" autocomplete="off" readonly />
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
                                        class="form-control readonly-input @error('nama_sumber') is-invalid @enderror" autocomplete="off" readonly />
                                @else
                                    <input type="text" name="nama_sumber" value=""
                                        class="form-control readonly-input @error('nama_sumber') is-invalid @enderror" autocomplete="off" readonly />
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
                                <input type="text" id="kode_alternatif" name="kode_alternatif" value="{{ old('kode_alternatif', $product->kode_alternatif) }}"
                                    class="form-control @error('kode_alternatif') is-invalid @enderror" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('nama_barang2').focus();" />
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
                                <input type="text" id="nama_barang2" name="nama_barang" value="{{ old('nama_barang', $product->nama) }}" required
                                    class="form-control @error('nama_barang') is-invalid @enderror uppercase-input" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('merek').focus();" />
                            </div>
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
                                <input type="text" id="kode_sumber" name="kode_sumber" value="{{ $product->kode_sumber }}"
                                    class="form-control readonly-input @error('kode_sumber') is-invalid @enderror" autocomplete="off" readonly />
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
                                <input type="text" id="merek" name="merek" value="{{ old('merek', $product->merek) }}"
                                    class="form-control @error('merek') is-invalid @enderror uppercase-input" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('label').focus();" />
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
                                <input type="text" id="unit_beli" name="unit_beli" value="{{ old('unit_beli', $product->unit_beli) }}" required
                                    class="form-control readonly-input @error('unit_beli') is-invalid @enderror uppercase-input" autocomplete="off" readonly />
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
                                <input type="text" id="label" name="label" value="{{ old('label', $product->label) }}"
                                    class="form-control @error('label') is-invalid @enderror uppercase-input" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('harga_pokok').focus();" />
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
                                <input type="text" id="unit_jual" name="unit_jual" value="{{ old('unit_jual', $product->unit_jual) }}"
                                    class="form-control readonly-input @error('unit_jual') is-invalid @enderror" autocomplete="off" readonly />
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
                                <label class="form-label h6 mt-2" id="label_harga_pokok" for="harga_pokok">HARGA POKOK/{{ $product->unit_jual }}</label>
                            </div>
                            @if($product->kode_sumber !== null)
                                <div class="col">
                                    <input type="text" id="harga_pokok" name="harga_pokok" value="{{ $product->harga_pokok }}"
                                        class="form-control readonly-input @error('harga_pokok') is-invalid @enderror" autocomplete="off" readonly />
                                        
                                    <input type="text" hidden id="harga_pokok_2" name="harga_pokok_2" value="{{ $product->harga_pokok }}"
                                        class="form-control readonly-input @error('harga_pokok_2') is-invalid @enderror" autocomplete="off" readonly />
                                </div>
                            @else
                            <div class="col">
                                <input type="text" id="harga_pokok_2" name="harga_pokok_2" value="{{ $product->harga_pokok }}"
                                    class="form-control readonly-input @error('harga_pokok_2') is-invalid @enderror" autocomplete="off" readonly />
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="profit">PROFIT</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="profit" name="profit" value="{{ old('profit', $product->profit) }}"
                                    class="form-control @error('profit') is-invalid @enderror" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('input-supplier-id').focus();" />
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
                                <input type="text" id="konversi" name="konversi" value="{{ old('konversi', str_replace('P', '', $product->unit_beli . '.00/' . $product->unit_jual . '.00')) }}"
                                    class="form-control readonly-input @error('konversi') is-invalid @enderror" autocomplete="off" readonly />
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
                                <label class="form-label h6 mt-2" id="label_harga_pokok_2" for="harga_pokok_2">HARGA POKOK/{{ $product->unit_beli }}</label>
                            </div>
                            @if($product->kode_sumber !== null)
                                <div class="col">
                                    <input type="text" id="harga_pokok"  name="harga_pokok" value="{{ old('harga_pokok', $parentProduct->harga_pokok ?? $product->harga_pokok) }}"
                                        class="form-control readonly-input @error('harga_pokok') is-invalid @enderror" autocomplete="off" readonly
                                        onkeydown="if(event.key === 'Enter') document.getElementById('harga_jual').focus();" />
                                </div>
                            @else
                                <div class="col">
                                    <input type="text" id="harga_pokok" name="harga_pokok" value="{{ old('harga_pokok', $parentProduct->harga_pokok ?? $product->harga_pokok) }}"
                                        class="form-control @error('harga_pokok') is-invalid @enderror"
                                        onkeydown="if(event.key === 'Enter') document.getElementById('harga_jual').focus();" autocomplete="off" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-2 mt-2">
                        {{-- <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="ppn">PPN</label>
                            </div>
                            <div class="col-6">
                                <div class="slider-container">
                                    <input type="checkbox" id="ppn" name="ppn" {{ $product->is_ppn ? 'checked' : '' }} class="slider-checkbox">
                                    <label for="ppn" class="slider-label"></label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" id="label_stok_awal" for="stok_awal">STOK AWAL</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="stok_awal" name="stok_awal" value="{{ old('stok_awal') }}" readonly
                                    class="form-control readonly-input @error('stok_awal') is-invalid @enderror" autocomplete="off" />
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
                                <input type="text" id="harga_pokok_rata" name="harga_pokok_rata" value="{{ $product->harga_pokok }}" readonly
                                    class="form-control readonly-input @error('harga_pokok_rata') is-invalid @enderror" autocomplete="off" />
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
                                <input type="text" id="stok_akhir" name="stok_akhir" value="{{ old('stok_akhir') }}" readonly
                                    class="form-control readonly-input @error('stok_akhir') is-invalid @enderror" autocomplete="off" />
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
                                <label class="form-label h6 mt-2" id="label_harga_jual" for="harga_jual">HARGA JUAL/{{ $product->unit_jual }}</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}"
                                    class="form-control @error('harga_jual') is-invalid @enderror" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('profit').focus();" />
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
                                <input type="text" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok') }}" readonly
                                    class="form-control readonly-input @error('jumlah_stok') is-invalid @enderror" autocomplete="off" />
                                @error('jumlah_stok')
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
                                <label class="form-label h6 mt-2" for="stok_terkecil">STOK TERKECIL</label>
                            </div>
                            <div class="col">
                                <input type="text" id="stok_terkecil" name="stok_terkecil" value="" readonly
                                    class="form-control readonly-input @error('stok_terkecil') is-invalid @enderror" autocomplete="off" />
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
                                <input type="text" id="proses_order" name="proses_order" value="{{ old('proses_order') }}" readonly
                                    class="form-control readonly-input @error('proses_order') is-invalid @enderror" autocomplete="off" />
                                @error('proses_order')
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
                                <label class="form-label h6 mt-2" for="rata_terkecil">RATA2 TERKECIL</label>
                            </div>
                            <div class="col">
                                <input type="text" id="rata_terkecil" name="rata_terkecil" value="" readonly
                                    class="form-control readonly-input @error('rata_terkecil') is-invalid @enderror" autocomplete="off" />
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
                                <label class="form-label h6 mt-2" for="unit">UNIT</label>
                            </div>
                            <div class="col-5">
                                <div class="custom-select-unit">
                                    <input type="text" id="search-input-unit" name="unit" value="{{ $product->id_unit }}" class="search-input-unit" required placeholder="Search..." onkeyup="filterFunction()">
                                    <div class="select-items-unit" id="select-items-unit">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>
                
                                <select id="unit" style="width: 300px;" hidden>
                                    <option value="">---Select Unit---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" data-nama="{{ $unit->nama }}">{{ $unit->id }} - {{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_unit" readonly class="form-control readonly-input" value="{{ $product->unit->nama }}" autocomplete="off" />
                    </div>
                    <div class="col-1">
                        {{-- <a href="{{ route('master.product.child', $parentProduct->id ?? $product->id) }}" class="btn btn-primary">Anak</a> --}}
                        <button disabled class="btn btn-primary" style="width: 95px">ANAK</button>
                    </div>
                    <div class="col-1">
                        <button disabled class="btn btn-primary">SUMBER</button>
                    </div>
                    <div class="col-1">
                        <button disabled class="btn btn-primary">KELOMPOK</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="departemen">DEPARTEMEN</label>
                            </div>
                            <div class="col-5">
                                <div class="custom-select-departemen">
                                    <input type="text" class="search-input-departemen" value="{{ $product->id_departemen }}" name="departemen" required placeholder="Search..." onkeyup="filterFunction()">
                                    <div class="select-items-departemen" id="select-items-departemen">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>
                
                                <select id="departemen" style="width: 300px;" hidden>
                                    <option value="">---Select Departemen---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($departemens as $departemen)
                                        <option value="{{ $departemen->id }}" data-nama="{{ $departemen->nama }}">{{ $departemen->id }} - {{ $departemen->nama }}</option>
                                    @endforeach
                                </select>
                                @error('departemen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_departemen" value="{{ $product->departemen->nama }}" readonly class="form-control readonly-input" autocomplete="off" />
                    </div>
                    {{-- <div class="col-2">
                        <button type="button" class="btn btn-primary" style="width: 170px;" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#departemenModal">LIST DEPARTEMEN</button>
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
                                    <input type="text" class="search-input-supplier" id="input-supplier-id" value="{{ $product->supplier->nomor }}" name="supplier" required placeholder="Search..." onkeyup="filterFunction()">
                                    <div class="select-items-supplier" id="select-items-supplier">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>
                
                                <select id="supplier" style="width: 300px;" hidden>
                                    <option value="">---Select Supplier---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" id="nama_supplier" value="{{ $product->supplier->nama }}" readonly class="form-control readonly-input" autocomplete="off" />
                    </div>
                    <div class="col-2">
                        <a href="{{ route('master.product.index') }}" class="btn btn-primary">LIST INVENTORY</a>
                    </div>
                </div>
            {{-- end border --}}
            </div>
            
            <div class="row d-flex justify-content-start mb-7">
                <div class="col-0-5">
                    <a href="#" class="btn btn-success disabled-link" title="TAMBAH DATA"><i class="fas fa-plus"></i></a>
                </div>
                <div class="col-0-5">
                    <a href="#" class="btn btn-warning disabled-link" title="EDIT DATA"><i class="fas fa-edit"></i></a>
                </div>
                <div class="col-0-5">
                    <button type="submit" class="btn btn-primary" title="SIMPAN DATA"><i class="fas fa-save"></i></button>
                </div>
                <div class="col-8"></div>
                <div class="col-0-5 ml-auto">
                    <a href="{{ route('master.product.index') }}" class="btn btn-primary" title="CARI DATA"><i class="fas fa-search"></i></a>
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
    {{-- @include('master.product.add-on.edit-scripts') --}}
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
                console.log(unitId)
                if (unitId) {
                    $.ajax({
                        url: "{{ route('master.get-departemen') }}",
                        method: 'GET',
                        data: { unit_id: unitId },
                        success: function(data) {
                            var $departemenSelect = $('#departemen');
                            $departemenSelect.empty().append('<option value="">---Select Departemen---</option>');
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
        });
    </script>
@endsection