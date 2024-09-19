@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">DAFTAR BARANG YANG HARUS DIPESAN
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <a href="{{ route('preorder.index') }}" class="btn btn-primary mb-2">BUAT PO MANUAL</a>
                <div class="d-flex justify-content-between mt-2">
                    <div class="row w-100">
                        <div class="form-group col-5">
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG HARUS DIBUATKAN P.O</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">NAMA SUPPLIER</th>
                                            <th class="text-center">DATANG</th>
                                            <th class="text-center">DETAIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listPreorders as $po)
                                            @if (empty($po['preorders']))
                                                <tr>
                                                    <td class="text-center">{{ $po['supplier']['nama'] }}</td>
                                                    <td class="text-center">{{ $po['supplier']['waktu_kunjungan'] }}</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                                                            @csrf
                                                            <input type="text" hidden name="dataSupplier1" value="{{ $po['supplier']['nama'] }}">
                                                            <button type="submit" class="btn btn-sm btn-primary" style="font-size: 10px;">BUAT</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group col-7">
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG SUDAH DIBUATKAN P.O</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">NAMA SUPPLIER</th>
                                            <th class="text-center">NOMOR PO</th>
                                            <th class="text-center">REF</th>
                                            <th class="text-center">DETAIL</th>
                                            <th class="text-center">CTK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listPreorders as $po)
                                            @foreach ($po['preorders'] as $order)
                                                @if ($order !== [])
                                                    <tr>
                                                        <td class="text-center">{{ $po['supplier']['nama'] }}</td>
                                                        <td class="text-center">{{ $order['nomor_po'] ?? null }}</td>
                                                        @php
                                                            $expiredDate = new DateTime(now()->format('Y-m-d'));
                                                            $currentDate = new DateTime($order['date_last'] ?? null );
                                                            $interval = $expiredDate->diff($currentDate);
                                                            $days = $interval->days;
                                                        @endphp
                                                        <td class="text-center">{{ $order['receive_type'] . $days }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('daftar-po.show', $order['id']) }}" class="btn btn-primary btn-sm">Detail</a>
                                                            <a href="{{ route('daftar-po.edit', $order['id']) }}" class="btn btn-primary btn-sm mx-1">Edit</a>
                                                        </td>
                                                        <td class="text-center">{{ $order['is_cetak'] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function formatNumber(num) {
                // Format number with commas and two decimal places
                return num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function calculateTotal(row) {
                var quantity = parseFloat(row.find('.order').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var total = quantity * price;
                var formattedTotal = formatNumber(total);
                row.find('.total').val(formattedTotal);
                row.find('.fieldtotal').val(total);
            }

            // Handle input events for quantity and price fields
            $('.order, .price').on('input', function() {
                var row = $(this).closest('tr'); // Find the closest row
                calculateTotal(row); // Calculate the total for this row
            });

            // Initialize totals on page load
            $('tr').each(function() {
                calculateTotal($(this));
            });
        });
    </script>
@endsection
