@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <div class="card-body">
                <h5 class="text-center">DAFTAR PENERIMAAN BARANG</h5>
                {{ $dataTable->table() }}
                <div class="mx-2 text-center">
                    <a class="btn btn-danger" href="{{ route('index') }}">KEMBALI</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
