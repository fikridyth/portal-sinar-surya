@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb mb-5">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">CETAK PEMBAYARAN</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <form action="{{ route('pembayaran.cetak-payment', $ids) }}" method="GET" class="form" enctype="multipart/form-data">
            @csrf
            <div class="container mb-4 w-25" style="border: 1px solid #000000; padding: 15px;">
                <div class="row">
                    <div class="col">
                        <div class="row d-flex justify-content-start">
                            <div class="col-7">
                                <label class="form-label h6 mt-2 text-center" for="nama_sumber">INPUT PARAMETER (ISI RANGE 0 - 10)</label>
                            </div>
                            <div class="col-4 mt-2">
                                <input type="number" min="0" max="10" name="param" class="form-control" required autocomplete="off" />
                                <input type="text" hidden name="ids" value="{{ $ids }}">
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start mt-3">
                        <div class="col-6 text-center">
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                        <div class="col-6 text-center">
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-danger">BATAL</a>
                        </div>
                    </div>
                </div>
                {{-- end border --}}
            </div>
        </form>
    </div>
@endsection
