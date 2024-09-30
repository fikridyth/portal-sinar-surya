@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN PIUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <h6 class="text-center mb-3">DAFTAR TAGIHAN LANGGANAN</h6>
                    <thead>
                        <tr>
                            <th class="text-center">NAMA COLLECTOR</th>
                            <th class="text-center">NO DOKUMEN</th>
                            <th class="text-center">WILAYAH</th>
                            <th class="text-center">TANGGAL</th>
                            <th class="text-center">JUMLAH RP</th>
                            <th class="text-center">BONUS</th>
                            <th class="text-center">MATERAI</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">PILIH</th>
                            <th class="text-center">BATAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($listPiutang->isEmpty())
                            <tr>
                                <td colspan="10" class="text-center"><b>TIDAK ADA DATA PIUTANG</b></td>
                            </tr>
                        @else
                            @foreach ($listPiutang as $index => $piutang)
                                <tr>
                                    <td>{{ $piutang->created_by }}</td>
                                    <td class="text-center">{{ $piutang->nomor_piutang }}</td>
                                    <td>{{ $piutang->wilayah }}</td>
                                    <td class="text-center">{{ $piutang->date }}</td>
                                    <td class="text-end">{{ number_format($piutang->total, 0) }}</td>
                                    <td class="text-end">{{ number_format($piutang->bonus, 0) }}</td>
                                    <td class="text-end">{{ number_format($piutang->materai, 0) ?? 0 }}</td>
                                    <td class="text-end">{{ number_format($piutang->total + $piutang->materai) }}</td>
                                    <td class="text-center"><input type="checkbox" class="proses-detail" data-id="{{ $piutang->id }}"></td>
                                    <td class="text-center"><input type="checkbox" class="proses-batal" data-id="{{ $piutang->id }}"></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <form id="delete-form" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="delete-id">
                </form>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="mx-2">
                        {{-- <button type="button" onclick="window.history.back()" class="btn btn-warning">BATAL</button> --}}
                        <a href="{{ route('pembayaran-piutang.index') }}" class="btn btn-danger">BATAL</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#user-select').select2();

            $('.proses-batal').change(function () {
                const id = $(this).data('id');
                if ($(this).is(':checked')) {
                    if (confirm('Apakah Anda yakin ingin membatalkan daftar ini?')) {
                        $('#delete-id').val(id); // Set the ID for deletion
                        $('#delete-form').attr('action', `/daftar-tagihan-langganan/${id}/destroy`);
                        $('#delete-form').submit(); // Submit the form
                    } else {
                        $(this).prop('checked', false); // Uncheck if the user cancels
                    }
                }
            });

            $('.proses-detail').change(function () {
                const id = $(this).data('id');
                if ($(this).is(':checked')) {
                    window.location.href = `/daftar-tagihan-langganan/${id}`;
                }
            });
        });
    </script>
@endsection
