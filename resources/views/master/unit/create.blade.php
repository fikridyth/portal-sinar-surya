@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">Master Unit</li>
                        <li class="breadcrumb-item active h3" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="mt-2">
                <a href="{{ route('master.unit.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <form action="{{ route('master.unit.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="mt-5 mb-4">
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

            <div class="mt-5">
                <button type="submit" class="btn btn-primary btn-block mt-4 mb-7">Tambah</button>
            </div>
        </form>
    </div>
@endsection