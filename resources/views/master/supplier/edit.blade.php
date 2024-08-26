@extends('main')

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

        <form action="{{ route('master.supplier.update', $supplier->id) }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">NOMOR</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="nomor" name="nomor"
                                    value="{{ old('nomor', $supplier->nomor) }}"
                                    class="form-control @error('nomor') is-invalid @enderror"
                                    autocomplete="off" required />
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
                                <label class="form-label h6 mt-2" for="nama_sumber">NAMA</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="nama" name="nama"
                                    value="{{ old('nama', $supplier->nama) }}" required
                                    class="form-control @error('nama') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">ALAMAT 1</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="alamat1" name="alamat1"
                                    value="{{ old('alamat1', $supplier->alamat1) }}"
                                    class="form-control @error('alamat1') is-invalid @enderror"
                                    autocomplete="off" required/>
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
                                <label class="form-label h6 mt-2" for="nama_sumber">ALAMAT 2</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="alamat2" name="alamat2"
                                    value="{{ old('alamat2', $supplier->alamat2) }}" required
                                    class="form-control @error('alamat2') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
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