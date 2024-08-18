@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">Master Supplier</li>
                        <li class="breadcrumb-item active h3" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="mt-2">
                <a href="{{ route('master.supplier.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <form action="{{ route('master.supplier.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="mt-4 mb-4">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="nomor" name="nomor" value="{{ old('nomor') }}" required
                        class="form-control @error('nomor') is-invalid @enderror" autocomplete="off" />
                    <label class="form-label" for="nomor">Nomor</label>
                    @error('nomor')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                        class="form-control @error('nama') is-invalid @enderror" autocomplete="off" />
                    <label class="form-label" for="nama">Nama</label>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="alamat1" name="alamat1" value="{{ old('alamat1') }}" required
                        class="form-control @error('alamat1') is-invalid @enderror" autocomplete="off" />
                    <label class="form-label" for="alamat1">Alamat 1</label>
                    @error('alamat1')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="alamat2" name="alamat2" value="{{ old('alamat2') }}" required
                        class="form-control @error('alamat2') is-invalid @enderror" autocomplete="off" />
                    <label class="form-label" for="alamat2">Alamat 2</label>
                    @error('alamat2')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn btn-primary btn-block mt-4 mb-7">Tambah</button>
            </div>
        </form>
    </div>
@endsection