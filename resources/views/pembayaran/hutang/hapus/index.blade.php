@extends('main')

@section('content')
    <div class="container mb-7">
        <a href="{{ route('pembayaran-hutang.index-history') }}" class="btn btn-primary mb-2">HISTORY HUTANG</a>
        <div class="card">
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
