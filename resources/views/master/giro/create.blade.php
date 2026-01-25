@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">BUKU CEK / GIRO</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="{{ route('master.giro.store', enkrip($bank->id)) }}" method="POST" class="form"
            enctype="multipart/form-data">
            @csrf
            <div class="container mb-4" style="border: 1px solid #000000; padding: 15px; width: 30%;">
                <div class="row align-items-center">
                    <div class="col-6">
                        <label class="form-label h6 mt-2" for="nama_sumber">NAMA BANK</label>
                    </div>
                    <div class="col-6">
                        <input type="text" id="nama" value="{{ $bank->nama }}" readonly class="form-control readonly-input" autocomplete="off" />
                    </div>
                </div>
                <div class="row align-items-center mt-2">
                    <div class="col-6">
                        <label class="form-label h6 mt-2" for="nama_sumber">JUMLAH BUKU</label>
                    </div>
                    <div class="col-3">
                        <input type="text" id="nama" name="total" value="" required class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="row align-items-center mt-2">
                    <div class="col-6">
                        <label class="form-label h6 mt-2" for="nama_sumber">SATU BUKU ADA</label>
                    </div>
                    <div class="col-3">
                        <input type="text" id="nama" name="count" value="25" readonly class="form-control" autocomplete="off" />
                    </div>
                    <div class="col-2">
                        <label class="form-label h6 mt-2" for="nama_sumber">LEMBAR</label>
                    </div>
                </div>
                <div class="row align-items-center mt-2">
                    <div class="col-6">
                        <label class="form-label h6 mt-2" for="nama_sumber">MULAI DARI NOMOR</label>
                    </div>
                    <div class="col-6">
                        <input type="number" id="nama" name="get_nomor" required class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="align-items-center mt-4">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mx-2">PROSES</button>
                        <a href="{{ route('master.giro.index') }}" class="btn btn-warning mx-2">BATAL</a>
                    </div>
                </div>
                {{-- end border --}}
            </div>

        </form>
    </div>
@endsection