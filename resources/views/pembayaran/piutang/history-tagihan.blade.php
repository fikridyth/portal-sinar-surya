@extends('main')

@section('content')
    <div class="container mb-3">
        {{-- <a href="{{ route('daftar-tagihan.index-history') }}" class="btn btn-primary mb-2">HISTORY PIUTANG</a> --}}
        <div class="card">
            <div class="card-body">
                <h6 class="text-center mb-3">HISTORY TAGIHAN LANGGANAN</h6>
                <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
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
                                {{-- <th class="text-center">BATAL</th> --}}
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
                                        <td class="text-center"><input type="checkbox" class="proses-detail" data-id="{{ enkrip($piutang->id) }}"></td>
                                        {{-- <td class="text-center"><input type="checkbox" class="proses-batal" data-id="{{ $piutang->id }}"></td> --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
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
                    window.location.href = `/history-tagihan-langganan/${id}`;
                }
            });
        });
    </script>
@endsection
