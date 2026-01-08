@extends('main')

@include('master.product.add-on.styles')
                    
@include('master.product.add-on.modal')

@section('content')
    <div class="container">
        <form action="{{ route('master.product.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="kode">KODE BARANG</label>
                            </div>
                            <div class="col">
                                <input type="text" id="kode" name="kode" value="{{ $newCode }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror" autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="nama_sumber">NAMA SUMBER</label>
                            </div>
                            <div class="col mx-n2">
                                <input type="text" id="nama_sumber" name="nama_sumber" value=""
                                    class="form-control readonly-input @error('nama_sumber') is-invalid @enderror" autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-1">
                        {{-- <div class="row align-items-center">
                            <div class="col">
                                <label class="form-label h6 mt-2" for="nama_barang">/</label>
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
                                <input type="text" id="kode_alternatif" name="kode_alternatif" value="{{ old('kode_alternatif') }}"
                                    class="form-control @error('kode_alternatif') is-invalid @enderror" autocomplete="off" autofocus
                                    onkeydown="if(event.key === 'Enter') document.getElementById('unit_beli').focus();" />
                                @error('kode_alternatif')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="row align-items-center">
                            <div class="col-5">
                                <label class="form-label h6 mt-2" for="nama_barang">NAMA BARANG</label>
                            </div>
                            <div class="col mx-n2">
                                <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required
                                    class="form-control @error('nama_barang') is-invalid @enderror uppercase-input" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('merek').focus();" />
                            </div>
                        </div>
                    </div>

                    <div class="col-1">
                        <div class="row align-items-center">
                            <div class="col">
                                <label class="form-label h6 mt-2" id="label_nama_barang" for="nama_barang"></label>
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
                                <label class="form-label h6 mt-2" for="kode_sumber">KODE SUMBER</label>
                            </div>
                            <div class="col">
                                <input type="text" id="kode_sumber" name="kode_sumber" value=""
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
                                <input type="text" id="merek" name="merek" value="{{ old('merek') }}"
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
                                <input type="text" id="unit_beli" name="unit_beli" value="{{ old('unit_beli') }}" required
                                    class="form-control @error('unit_beli') is-invalid @enderror uppercase-input" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('nama_barang').focus();" />
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
                                <input type="text" id="label" name="label" value="{{ old('label') }}"
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
                                <input type="text" id="unit_jual" name="unit_jual" value="{{ old('unit_jual') }}"
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
                                <label class="form-label h6 mt-2" id="label_harga_pokok_2" for="harga_pokok_2">HARGA POKOK</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_pokok_2" name="harga_pokok_2" value=""
                                    class="form-control readonly-input @error('harga_pokok_2') is-invalid @enderror" autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="profit">PROFIT</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="profit" name="profit" value="{{ old('profit') }}"
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
                                <input type="text" id="konversi" name="konversi" value="{{ old('konversi') }}"
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
                                <label class="form-label h6 mt-2" id="label_harga_pokok" for="harga_pokok">HARGA POKOK</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_pokok" name="harga_pokok" value="{{ old('harga_pokok') }}" required
                                    class="form-control @error('harga_pokok') is-invalid @enderror" autocomplete="off"
                                    onkeydown="if(event.key === 'Enter') document.getElementById('harga_jual').focus();" />
                            </div>
                        </div>
                    </div>

                    <div class="col-2 mt-2">
                        {{-- <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="ppn">PPN</label>
                            </div>
                            <div class="col-6">
                                <div class="slider-container">
                                    <input type="checkbox" id="ppn" name="ppn" {{ old('ppn') ? 'checked' : '' }} class="slider-checkbox">
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
                                <input type="text" id="harga_pokok_rata" name="harga_pokok_rata" value="" readonly
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
                                <label class="form-label h6 mt-2" id="label_harga_jual" for="harga_jual">HARGA JUAL</label>
                            </div>
                            <div class="col">
                                <input type="text" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required
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
                                    <input type="text" id="search-input-unit" name="unit" value="{{ $product->id_unit }}" class="search-input-unit" autocomplete="off" required placeholder="Search..." onkeyup="filterFunction()">
                                    <div class="select-items-unit" id="select-items-unit">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>
                
                                <select id="unit" style="width: 300px;" hidden>
                                    <option value="">---Select Unit---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" data-kode="{{ $unit->id }}" data-nama="{{ $unit->nama }}">{{ $unit->id }} - {{ $unit->nama }}</option>
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
                    <div class="col-3">
                        <input type="text" id="nama_unit" value="{{ $product->unit->nama }}" readonly class="form-control readonly-input" autocomplete="off" />
                    </div>
                    <div class="col-1">
                        <button disabled class="btn btn-primary" style="width: 95px;">ANAK</button>
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
                                    <input type="text" class="search-input-departemen" name="departemen" value="{{ $product->id_departemen }}" autocomplete="off" required placeholder="Search..." onkeyup="filterFunction()">
                                    <div class="select-items-departemen" id="select-items-departemen">
                                        <!-- Options will be added here dynamically -->
                                    </div>
                                </div>
                
                                <select id="departemen" style="width: 300px;" hidden>
                                    <option value="">---Select Departemen---</option>
                                    <!-- Example options; replace with server-side data as needed -->
                                    @foreach ($departemens as $departemen)
                                        <option value="{{ $departemen->id }}" data-kode="{{ $departemen->id }}" data-nama="{{ $departemen->nama }}">{{ $departemen->id }} - {{ $departemen->nama }}</option>
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
                    <div class="col-3">
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
                                    <input type="text" class="search-input-supplier" id="input-supplier-id" name="supplier" value="{{ old('supplier') }}" autocomplete="off"
                                    required placeholder="Search..." onkeyup="filterFunction()">
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
                    <div class="col-3">
                        <input type="text" id="nama_supplier" value="" readonly class="form-control readonly-input" autocomplete="off" />
                    </div>
                    <div class="col-2">
                        <a href="{{ route('master.product.index') }}" class="btn btn-primary">LIST INVENTORY</a>
                    </div>
                </div>
            {{-- end border --}}
            </div>

            <div class="row d-flex justify-content-start mb-3">
                <div class="col-1">
                    <a href="#" class="btn btn-success disabled-link" style="min-width: 100px;" title="TAMBAH DATA">TAMBAH</i></a>
                </div>
                <div class="col-1">
                    <a href="#" class="btn btn-warning disabled-link" style="min-width: 100px;" title="EDIT DATA">UBAH</a>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary" style="min-width: 100px;" title="SIMPAN DATA">SIMPAN</button>
                </div>
                <div class="col-6"></div>
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

        document.getElementById('nama_barang').addEventListener('input', function () {
            this.value = this.value.replace(/"/g, '');
        });
    </script>
@endsection