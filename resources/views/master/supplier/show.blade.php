@extends('main')

@include('master.product.add-on.styles')

@section('content')
    <div class="container">
        <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">NOMOR SUPPLIER</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->nomor }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">WILAYAH</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="kode" name="kode" value=""
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">NAMA SUPPLIER</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->nama }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">KONTAK - 1</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->kontak }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">ALAMAT - 1</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->alamat1 }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">KONTAK - 2</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->kontak1 }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">ALAMAT - 2</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->alamat2 }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">TELEPON</label>
                        </div>
                        <div class="col-5">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->no_telp }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">KOTA</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->alamat3 }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">FAX</label>
                        </div>
                        <div class="col-5">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->fax }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">LAMA KREDIT</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->tcrd }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="kode">HARI</label>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">NPWP</label>
                        </div>
                        <div class="col-5">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->npwp }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">JENIS</label>
                        </div>
                        <div class="col-3">
                            {{-- <input type="text" id="kode" name="kode" value="{{ $supplier->tipe == 1 ? 'PKP' : 'Non PKP' }}" --}}
                            <input type="text" id="kode" name="kode"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="nama_sumber">HP</label>
                        </div>
                        <div class="col-5">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->hp }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">PEMBAYARAN</label>
                        </div>
                        <div class="col-7">
                            {{-- <input type="text" id="kode" name="kode" value="TUNAI / KREDIT / KONSINYASI" --}}
                            <input type="text" id="kode" name="kode"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">DISKON</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->disc }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">%</label>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-4">
                            <a href="{{ route('master.history-preorder.index', enkrip($supplier->id)) }}" class="btn btn-sm btn-primary">SEJARAH PEMBELIAN</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">FREELANCE (Y/T)</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="kode" name="kode" value="T"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">KUNJUNGAN SALESMAN</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->waktu_kunjungan }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">Hari</label>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">HARI</label>
                        </div>
                        <div class="col-3">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->hari }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">NILAI ORDER</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="0"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">STOK MINIMUM</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->stok_minimum }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">Hari</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">JUMLAH DIBAYAR</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="0"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">STOK MAKSIMUM</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->stok_maksimum }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">Hari</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-6 mb-2">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <label class="form-label h6 mt-2" for="kode">SALDO RP</label>
                        </div>
                        <div class="col-7">
                            <input type="text" id="kode" name="kode" value="0"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">PENJUALAN RATA2</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->penjualan_rata }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">Hari</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-4 mb-2">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">ORDER TERAKHIR</label>
                        </div>
                        <div class="col-5" style="margin-left: 20px;">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->lastord }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row align-items-center">
                        <div class="col-2"></div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">PPN</label>
                        </div>
                        <div class="col-1">
                            <div class="row align-items-center">
                                <div class="slider-container">
                                    <input type="checkbox" id="ppn" name="ppn" {{ $supplier->is_ppn ? 'checked' : '' }} @disabled(true) class="slider-checkbox">
                                    <label for="ppn" class="slider-label"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-2">
                            <label class="form-label h6 mt-2" for="nama_sumber">BIAYA MATERAI</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value="{{ number_format($supplier->materai) }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-8 mb-2">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">KETERANGAN</label>
                        </div>
                        <div class="col-8" style="margin-left: 20px;">
                            <input type="text" id="kode" name="kode" value=""
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">UPAH / KOLI</label>
                        </div>
                        <div class="col-4">
                            <input type="text" id="kode" name="kode" value="{{ $supplier->koli }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-8 mb-2">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">KODE PERKIRAAN</label>
                        </div>
                        <div class="col-8" style="margin-left: 20px;">
                            <input type="text" id="kode" name="kode" value=""
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label h6 mt-2" for="nama_sumber">BONUS PRODUK</label>
                        </div>
                        <div class="col-4">
                            <input type="text" id="kode" name="kode" value="{{ number_format($supplier->bonus, 2) }}"
                                class="form-control readonly-input @error('kode') is-invalid @enderror text-end"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-1">
                            <label class="form-label h6 mt-2" for="nama_sumber">%</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-8 mb-2">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">F. PAJAK GABUNG</label>
                        </div>
                        <div class="col-1" style="margin-left: 20px;">
                            <input type="text" id="kode" name="kode"
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                        <div class="col-2">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">1 = Ya &nbsp;&nbsp; 2 = Tidak</label>
                        </div>
                        <div class="col-1">
                        </div>
                        <div class="col-2">
                            <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">TOLERANSI</label>
                        </div>
                        <div class="col-2">
                            <input type="text" id="kode" name="kode" value=""
                                class="form-control readonly-input @error('kode') is-invalid @enderror"
                                autocomplete="off" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2-5 mb-2"></div>
                <div class="col-1-5">
                    <a href="{{ route('master.promosi.index') }}" class="btn btn-success" style="width: 100%">DATA PROMOSI</a>
                </div>
                <div class="col-1-5">
                    <a href="{{ route('master.promosi.index-all') }}" class="btn btn-success" style="width: 100%">LIST PROMOSI</a>
                </div>
                <div class="col-1-5">
                    <a href="{{ route('master.materai.index') }}" class="btn btn-success" style="width: 100%">MATERAI</a>
                </div>
            </div>
            {{-- end border --}}
        </div>

        <div class="row d-flex justify-content-start mb-7">
            <div class="col-1">
                <a href="{{ route('master.supplier.create',  enkrip($supplier->id)) }}" class="btn btn-success" style="min-width: 100px;" title="TAMBAH DATA">TAMBAH</i></a>
            </div>
            <div class="col-1">
                <a href="{{ route('master.supplier.edit',  enkrip($supplier->id)) }}" class="btn btn-warning" style="min-width: 100px;" title="EDIT DATA">UBAH</a>
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-primary" disabled style="min-width: 100px;" title="SIMPAN DATA">SIMPAN</button>
            </div>
            <div class="col-6"></div>
            <div class="col-1 ml-auto">
                <a href="{{ route('master.supplier.index') }}" class="btn btn-primary" style="min-width: 100px;" title="CARI DATA">CARI</i></a>
            </div>
            <div class="col-1">
                <button type="button" onclick="window.history.back()" class="btn btn-warning" style="min-width: 100px;" title="KEMBALI">KEMBALI</button>
            </div>
            <div class="col-1">
                <a href="{{ route('index') }}" class="btn btn-danger" style="min-width: 100px;" title="KELUAR">KELUAR</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // // Mengambil elemen input dengan ID 'kode'
    // var rataElement = document.getElementById('penjualan_rata');
    // var waktuElement = document.getElementById('waktu_kunjungan');
    // var minElement = document.getElementById('stok_minimum');
    // var maxElement = document.getElementById('stok_maksimum');
    
    // // Mengambil nilai saat ini dari elemen input
    // var rataValue = rataElement.value;
    // var waktuValue = waktuElement.value;
    // var minValue = minElement.value;
    // var maxValue = maxElement.value;
    
    // // Menambahkan teks 'hari' ke nilai saat ini
    // var rataNewValue = rataValue + ' Hari';
    // var WaktuNewValue = waktuValue + ' Hari';
    // var minNewValue = minValue + ' Hari';
    // var maxNewValue = maxValue + ' Hari';
    
    // // Mengupdate nilai elemen input
    // rataElement.value = rataNewValue;
    // waktuElement.value = WaktuNewValue;
    // minElement.value = minNewValue;
    // maxElement.value = maxNewValue;
</script>
@endsection