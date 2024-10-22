@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 60%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                LIST CETAK PEMBAYARAN
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-4">
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr style="border: 1px solid black; font-size: 12px;">
                                    <th class="text-center">NO</th>
                                    <th class="text-center">NOMOR BUKTI</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">TIPE</th>
                                    <th class="text-center">V</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayarans as $pembayaran)
                                    <tr>
                                        <td class="text-center">{{ str_pad($loop->iteration, 3, 0, STR_PAD_LEFT) }}</td>
                                        <td>{{ str_replace(',', ' , ', $pembayaran->nomor_bukti) }}</td>
                                        <td class="text-end">{{ number_format($pembayaran->grand_total) }}</td>
                                        @if (!is_numeric($pembayaran->nomor_giro))
                                            <td class="text-center">{{ $pembayaran->tipe_giro }}</td>
                                        @else
                                            <td class="text-center">{{ $pembayaran->nomor_giro }} - {{ $pembayaran->tipe_giro }}</td>
                                        @endif
                                        <td class="text-center">
                                            <input type="checkbox" class="payment-checkbox" value="{{ $pembayaran->id }}" onchange="updateRoute()">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-primary" id="button-cetak" href="#" role="button">PROSES</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateRoute() {
            const selectedIds = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(checkbox => checkbox.value);
            const buttonCetak = document.getElementById('button-cetak');
            
            if (selectedIds.length > 0) {
                buttonCetak.setAttribute('href', `{{ route('pembayaran.param-cetak-payment', '') }}/${selectedIds.join(',')}`);
            } else {
                buttonCetak.setAttribute('href', '#'); // Reset to default if no checkbox is selected
            }
        }
    </script>
@endsection