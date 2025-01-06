@extends('main')

@section('content')
    <div class="container-fluid w-100" style="max-width: 90%; width: 90%;">
        <div class="card mt-n3">
            <div class="card-body mt-n4">
                <div class="card-body">
                    <div class="form-group">
                        <div style="overflow-x: auto; height: 670px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr>
                                        <th class="text-center">NOMOR BUKTI</th>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">KODE SUPPLIER</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">PILIH</th>
                                    </tr>
                                </thead>
                                <tbody id="preorderTableBody">
                                    @foreach ($returs as $retur)
                                        <tr data-id="{{ $retur->id }}">
                                            <td class="text-center">{{ $retur->nomor_return }}</td>
                                            <td class="text-center">{{ $retur->date }}</td>
                                            <td class="text-center">{{ $retur->supplier->nomor }}</td>
                                            <td class="text-start">{{ $retur->supplier->nama }}</td>
                                            <td class="text-end">{{ number_format($retur->total) }}</td>
                                            <td class="text-center"><input type="checkbox" class="show-checkbox" data-detail="{{ enkrip($retur->id) }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mb-2">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '.show-checkbox', function () {
            if ($(this).is(':checked')) {
                let detailId = $(this).data('detail'); // Ambil ID dari data-detail
                let route = `/return-po/${detailId}`; // Sesuaikan dengan format route Anda
                
                // Redirect ke route
                window.location.href = route;
            }
        });
    </script>
@endsection