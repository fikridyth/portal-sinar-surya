@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMESANAN BARANG - PURCHASE ORDER
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center">DAFTAR PENERIMAAN BARANG</th>
                        </tr>
                        <tr>
                            <th class="text-center">NAMA SUPPLIER</th>
                            <th class="text-center">NOMOR PO</th>
                            <th class="text-center">REF</th>
                            <th class="text-center">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preorders as $po)
                            <tr>
                                <td class="text-center">{{ $po->supplier->nama }}</td>
                                <td class="text-center">{{ $po->nomor_po }}</td>
                                <td class="text-center">{{ $po->receive_type . 0 }}</td>
                                <td class="text-center">
                                    @if ($po->is_pay !== 1)
                                        <a href="{{ route('receive-po.create-detail', $po->id) }}" class="btn btn-primary btn-sm mx-2">DETAIL</a>
                                    @else
                                        <button disabled class="btn btn-primary btn-sm mx-2">SUDAH BAYAR</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
