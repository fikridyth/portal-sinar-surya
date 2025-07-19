@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER LANGGANAN</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="{{ route('master.langganan.store') }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nomor">NOMOR</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="nomor" name="nomor" disabled value="GENERATE-OTOMATIS"
                                    class="form-control @error('nomor') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('nomor')
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
                                <label class="form-label h6 mt-2" for="nama">NAMA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="nama" name="nama" autofocus
                                    class="form-control @error('nama') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="alamat1">ALAMAT - 1</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="alamat1" name="alamat1"
                                    class="form-control @error('alamat1') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('alamat1')
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
                                <label class="form-label h6 mt-2" for="alamat2">ALAMAT - 2</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="alamat2" name="alamat2"
                                    class="form-control @error('alamat2') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('alamat2')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kota">KOTA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kota" name="kota"
                                    class="form-control @error('kota') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('kota')
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
                                <label class="form-label h6 mt-2" for="kontak">KONTAK</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kontak" name="kontak"
                                    class="form-control @error('kontak') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('kontak')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="telepon">NO TELEPON</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="telepon" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('telepon')
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
                                <label class="form-label h6 mt-2" for="fax">NO FAX</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="fax" name="fax"
                                    class="form-control @error('fax') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('fax')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="zona">ZONA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="zona" name="zona"
                                    class="form-control @error('zona') is-invalid @enderror"
                                    autocomplete="off" />
                                @error('zona')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
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
                    <a href="{{ route('master.langganan.index') }}" class="btn btn-primary" style="min-width: 100px;" title="CARI DATA">CARI</i></a>
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