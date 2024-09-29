@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN HUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- {{ $dataTable->table() }} --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NAMA SUPPLIER</th>
                            <th>ALAMAT -1</th>
                            <th>ALAMAT -2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($getSupplier as $supplier)
                            <tr>
                                <td><a href="{{ route('pembayaran-hutang.show', $supplier->id) }}" class="mx-2">{{ $supplier->nama }}</a></td>
                                <td>{{ $supplier->alamat1 }}</td>
                                <td>{{ $supplier->alamat2 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- {{ $dataTable->scripts() }} --}}
@endsection
