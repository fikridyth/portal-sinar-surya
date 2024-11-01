@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">HAPUS PEMBAYARAN HUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

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
