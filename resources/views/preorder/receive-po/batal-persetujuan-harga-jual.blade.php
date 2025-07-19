@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <div class="card-body">
                <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                    <table id="table-supplier" class="table table-bordered" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">DAFTAR PERSETUJUAN HARGA</th>
                            </tr>
                            <tr>
                                <th class="text-center">NOMOR BUKTI</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">NAMA SUPPLIER</th>
                                <th class="text-center">JUMLAH RP</th>
                                <th class="text-center">PROSES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($preorders->isEmpty())
                                <tr>
                                    <td class="text-center" colspan="5">TIDAK ADA DATA</td>
                                </tr>
                            @else
                                @foreach ($preorders as $po)
                                    <tr>
                                        <td class="text-center">{{ $po->nomor_receive }}</td>
                                        <td class="text-center">{{ $po->date_first }}</td>
                                        <td class="text-start">{{ $po->supplier->nama }}</td>
                                        <td class="text-end">{{ number_format($po->grand_total) }}</td>
                                        <td class="text-center">
                                            {{-- ubah jadi modal saat klik, masuk ke controller, hapus data sementara dengan po yg sama, is_persetujuan null --}}
                                            <button 
                                                class="btn btn-danger btn-sm mx-2"
                                                onclick="confirmDelete('{{ route('batal-persetujuan-harga-jual-update', enkrip($po->id)) }}')">
                                                Batal
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Yakin ingin membatalkan?',
            text: "Batal persetujuan harga ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
@endsection
