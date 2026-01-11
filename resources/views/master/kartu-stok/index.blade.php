@extends('main')

@section('content')
    <div class="container mt-n2">
        <div class="card">
            <div class="card-body">
                <div class="card-body mt-n4">
                    <h6>INVENTORY</h6>
                    <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                        {{ $dataTable->table() }}
                    </div>
                </div>
                
                <div class="text-center mt-n3">
                    <button type="button" onclick="window.history.back()" class="btn btn-danger">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
