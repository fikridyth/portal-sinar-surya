@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER DEPARTEMEN</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="{{ route('master.departemen.store') }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    {{-- <div class="col-2 mb-2">
                        <div class="row align-items-center">
                            <div class="col-1">
                                <label class="form-label h6 mt-2" for="kode">ID</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $departemen->id) }}"
                                    class="form-control readonly-input @error('kode') is-invalid @enderror"
                                    autocomplete="off" readonly />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-6 mx-5">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="nama_sumber">DEPARTEMEN</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="nama" name="nama" autocomplete="off" 
                                    value="{{ old('nama', $departemen->nama) }}" required
                                    class="form-control @error('nama') is-invalid @enderror" />
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row align-items-center">
                            <div class="col-2">
                                <label class="form-label h6 mt-2" for="nama_sumber">UNIT</label>
                            </div>
                            <div class="col-8">
                                <select class="form-select @error('id_unit') is-invalid @enderror" id="id_unit" name="id_unit"
                                        data-control="select" required>
                                    <option value="">---Select Unit---</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                                @error('id_unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
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
                    <a href="{{ route('master.departemen.index') }}" class="btn btn-primary" style="min-width: 100px;" title="CARI DATA">CARI</i></a>
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