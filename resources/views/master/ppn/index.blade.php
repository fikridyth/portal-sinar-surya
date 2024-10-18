@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER PPN</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="{{ route('master.ppn.update', enkrip($ppn->id)) }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    {{-- <div class="col-6 mb-2">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label class="form-label h6 mt-2" for="kode">ID UNIT</label>
                            </div>
                            <div class="col-5">
                                <input type="text" id="kode" name="kode"
                                    value="{{ old('kode', $unit->id) }}"
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
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-2">
                                <label class="form-label h6 mt-2" for="nama_sumber">UBAH JUMLAH PPN (%)</label>
                            </div>
                            <div class="col-2">
                                <input type="text" id="ppn" name="ppn"
                                    value="{{ old('ppn', $ppn->ppn) }}"
                                    class="form-control @error('ppn') is-invalid @enderror"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>

                </div>
                {{-- end border --}}
            </div>

            <div class="row d-flex justify-content-start mb-7">
                {{-- <div class="col-0-5">
                    <a href="#" class="btn btn-success disabled-link" title="TAMBAH DATA"><i class="fas fa-plus"></i></a>
                </div>
                <div class="col-0-5">
                    <a href="#" class="btn btn-warning disabled-link" title="EDIT DATA"><i class="fas fa-edit"></i></a>
                </div> --}}
                <div class="col-0-5 mx-4">
                    <button type="submit" class="btn btn-primary" title="SIMPAN DATA"><i class="fas fa-save"></i></button>
                </div>
                <div class="col-10"></div>
                {{-- <div class="col-0-5 ml-auto">
                    <a href="{{ route('master.ppn.index') }}" class="btn btn-primary" title="CARI DATA"><i class="fas fa-search"></i></a>
                </div> --}}
                {{-- <div class="col-0-5">
                    <button type="button" onclick="window.history.back()" class="btn btn-warning" title="KEMBALI"><i class="fas fa-arrow-left"></i></button>
                </div> --}}
                <div class="col-0-5">
                    <a href="{{ route('index') }}" class="btn btn-danger" title="KELUAR"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </form>
    </div>
@endsection