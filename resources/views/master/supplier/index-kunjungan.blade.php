@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                    {{ $dataTable->table() }}
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-4 mb-7">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
