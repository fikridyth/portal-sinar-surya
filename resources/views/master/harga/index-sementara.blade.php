@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 60%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                DAFTAR HARGA SEMENTARA
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">V</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $supplierName => $items)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $supplierName }}</td>
                                            @foreach ($items->unique('id_supplier') as $item)
                                                <td class="text-center"><a href="{{ route('master.harga-sementara.show', $item->id_supplier) }}" class="btn btn-sm btn-primary">PILIH</a></td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection