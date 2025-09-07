@extends('main')

@include('master.product.add-on.styles')

@section('content')
    <div class="container">
        <form action="{{ route('master.supplier.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row mb-1">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">NOMOR SUPPLIER</label>
                            </div>
                            <div class="col-3">
                                <input type="text" name="nomor" readonly required value="{{ $nextNomor }}"
                                    class="form-control @error('nomor') is-invalid @enderror  readonly-input"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">WILAYAH</label>
                            </div>
                            <div class="col-3">
                                <input type="text" name="wilayah" value="{{ old('wilayah') }}"
                                    class="form-control @error('wilayah') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="nama" required value="{{ old('nama') }}"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">KONTAK - 1</label>
                            </div>
                            <div class="col-7">
                                <input type="text" name="kontak" value="{{ old('kontak') }}"
                                    class="form-control @error('kontak') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="alamat1" required value="{{ old('alamat1') }}"
                                    class="form-control @error('alamat1') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">KONTAK - 2</label>
                            </div>
                            <div class="col-7">
                                <input type="text" name="kontak1" value="{{ old('kontak1') }}"
                                    class="form-control @error('kontak1') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="alamat2" value="{{ old('alamat2') }}"
                                    class="form-control @error('alamat2') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">TELEPON</label>
                            </div>
                            <div class="col-5">
                                <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="alamat3" value="{{ old('alamat3') }}"
                                    class="form-control @error('alamat3') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">FAX</label>
                            </div>
                            <div class="col-5">
                                <input type="text" name="fax" value="{{ old('fax') }}"
                                    class="form-control @error('fax') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="tcrd" value="{{ old('tcrd') }}"
                                    class="form-control @error('tcrd') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <input type="text" name="npwp" value="{{ old('npwp') }}"
                                    class="form-control @error('npwp') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="tes-10"
                                    class="form-control readonly-input @error('tes-10') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                {{-- <select name="tipe" class="form-control @error('tipe') is-invalid @enderror">
                                    <option value="1">PKP</option>
                                    <option value="2">Non PKP</option>
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">HP</label>
                            </div>
                            <div class="col-5">
                                <input type="text" name="hp" value="{{ old('hp') }}"
                                    class="form-control @error('hp') is-invalid @enderror"
                                    autocomplete="off" />
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
                                <input type="text" name="tes-1"
                                    class="form-control readonly-input @error('tes-1') is-invalid @enderror"
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
                                <input type="text" name="disc" value="{{ old('disc') }}"
                                    class="form-control @error('disc') is-invalid @enderror text-end"
                                    autocomplete="off" />
                            </div>
                            <div class="col-1">
                                <label class="form-label h6 mt-2" for="nama_sumber">%</label>
                            </div>
                            {{-- <div class="col-1"></div>
                            <div class="col-4">
                                <a href="{{ route('master.history-preorder.index', $supplier->id) }}"
                                    class="btn btn-sm btn-primary">SEJARAH PEMBELIAN</a>
                            </div> --}}
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
                                <input type="text" name="tanda" value="{{ old('tanda') }}"
                                    class="form-control @error('tanda') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <label class="form-label h6 mt-2" for="nama_sumber">KUNJUNGAN SALESMAN</label>
                            </div>
                            <div class="col-2">
                                <input type="text" name="waktu_kunjungan" required value="{{ old('waktu_kunjungan') }}"
                                    class="form-control @error('waktu_kunjungan') is-invalid @enderror text-end"
                                    autocomplete="off" />
                            </div>
                            <div class="col-1">
                                <label class="form-label h6 mt-2" for="nama_sumber">Hari</label>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-1">
                                <label class="form-label h6 mt-2" for="nama_sumber">HARI</label>
                            </div>
                            <div class="col-3">
                                <select name="hari" required class="form-control @error('hari') is-invalid @enderror">
                                    <option value="">-- PILIH HARI --</option>
                                    <option value="SENIN" {{ old('hari') == 'SENIN' ? 'selected' : '' }}>SENIN</option>
                                    <option value="SELASA" {{ old('hari') == 'SELASA' ? 'selected' : '' }}>SELASA</option>
                                    <option value="RABU" {{ old('hari') == 'RABU' ? 'selected' : '' }}>RABU</option>
                                    <option value="KAMIS" {{ old('hari') == 'KAMIS' ? 'selected' : '' }}>KAMIS</option>
                                    <option value="JUMAT" {{ old('hari') == 'JUMAT' ? 'selected' : '' }}>JUMAT</option>
                                    <option value="SABTU" {{ old('hari') == 'SABTU' ? 'selected' : '' }}>SABTU</option>
                                    <option value="MINGGU" {{ old('hari') == 'MINGGU' ? 'selected' : '' }}>MINGGU</option>
                                </select>
                            
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <input type="text" name="tes-2" value="0"
                                    class="form-control readonly-input @error('tes-2') is-invalid @enderror text-end"
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
                                <input type="text" name="stok_minimum" required value="{{ old('stok_minimum') }}"
                                    class="form-control @error('stok_minimum') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <input type="text" name="tes-3" value="0"
                                    class="form-control readonly-input @error('tes-3') is-invalid @enderror text-end"
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
                                <input type="text" name="stok_maksimum" required value="{{ old('stok_maksimum') }}"
                                    class="form-control @error('stok_maksimum') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <input type="text" name="tes-4" value="0"
                                    class="form-control readonly-input @error('tes-4') is-invalid @enderror text-end"
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
                                <input type="text" name="penjualan_rata" required value="{{ old('penjualan_rata') }}"
                                    class="form-control @error('penjualan_rata') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">ORDER
                                    TERAKHIR</label>
                            </div>
                            <div class="col-5" style="margin-left: 20px;">
                                <input type="text" name="tes-9"
                                    class="form-control readonly-input @error('tes-9') is-invalid @enderror"
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
                                        <input type="checkbox" id="ppn" name="ppn"
                                            class="slider-checkbox">
                                        <label for="ppn" class="slider-label"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-2">
                                <label class="form-label h6 mt-2" for="nama_sumber">BIAYA MATERAI</label>
                            </div>
                            <div class="col-2">
                                <input type="text" name="materai" value="{{ old('materai') }}"
                                    class="form-control @error('materai') is-invalid @enderror text-end"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-8 mb-2">
                        <div class="row align-items-center">
                            <div class="col-2">
                                <label class="form-label h6 mt-2" style="font-size: 14px"
                                    for="kode">KETERANGAN</label>
                            </div>
                            <div class="col-8" style="margin-left: 20px;">
                                <input type="text" name="tes-6"
                                    class="form-control readonly-input @error('tes-6') is-invalid @enderror"
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
                                <input type="text" name="koli" value="{{ old('koli') }}"
                                    class="form-control @error('koli') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <input type="text" name="tes-7"
                                    class="form-control readonly-input @error('tes-7') is-invalid @enderror"
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
                                <input type="text" name="bonus" value="{{ old('bonus') }}"
                                    class="form-control @error('bonus') is-invalid @enderror text-end"
                                    autocomplete="off" />
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
                                <input type="number" name="rpus" min="1" max="2"
                                    class="form-control @error('rpus') is-invalid @enderror" disabled
                                    autocomplete="off" />
                            </div>
                            <div class="col-2">
                                <label class="form-label h6 mt-2" style="font-size: 14px" for="kode">1 = Ya
                                    &nbsp;&nbsp; 2 = Tidak</label>
                            </div>
                            <div class="col-1">
                            </div>
                            <div class="col-2">
                                <label class="form-label h6 mt-2" style="font-size: 14px"
                                    for="kode">TOLERANSI</label>
                            </div>
                            <div class="col-2">
                                <input type="text" name="tes-8"
                                    class="form-control readonly-input @error('tes-8') is-invalid @enderror"
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
                        <a href="{{ route('master.promosi.index') }}" class="btn btn-success" style="width: 100%">DATA
                            PROMOSI</a>
                    </div>
                    <div class="col-1-5">
                        <a href="{{ route('master.promosi.index-all') }}" class="btn btn-success"
                            style="width: 100%">LIST PROMOSI</a>
                    </div>
                    <div class="col-1-5">
                        <a href="{{ route('master.materai.index') }}" class="btn btn-success"
                            style="width: 100%">MATERAI</a>
                    </div>
                </div>
                {{-- end border --}}
            </div>

            <div class="row d-flex justify-content-start mb-7">
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
                    <a href="{{ route('master.supplier.index') }}" class="btn btn-primary" style="min-width: 100px;" title="CARI DATA">CARI</i></a>
                </div>
                <div class="col-1">
                    <button type="button" onclick="window.history.back()" class="btn btn-warning" style="min-width: 100px;" title="KEMBALI">KEMBALI</button>
                </div>
                <div class="col-1">
                    <a href="{{ route('index') }}" class="btn btn-danger" style="min-width: 100px;" title="KELUAR">KELUAR</a>
                </div>
            </div>
        </form>
    </div>
@endsection
