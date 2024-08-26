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
                <div class="d-flex justify-content-between mt-2">
                    <div class="row w-100">
                        <div class="form-group col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG HARUS DIBUATKAN P.O</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Nama Supplier</th>
                                        <th class="text-center">Nomor PO</th>
                                        <th class="text-center">Ref</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td class="text-center">{{ $supplier->nama }}</td>
                                            @if (in_array($supplier->id, $orderSupplier))
                                                @foreach ($preorders as $preorder)
                                                <td class="text-center">{{ $preorder->nomor_po }}</td>
                                                @php
                                                    $expiredDate = new DateTime(now()->format('Y-m-d'));
                                                    $currentDate = new DateTime($preorder->date_last);
                                                    $interval = $expiredDate->diff($currentDate);
                                                    $days = $interval->days;
                                                @endphp
                                                <td class="text-center">A{{ $days }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('daftar-po.show', $preorder->id) }}" class="btn btn-primary btn-sm">Detail PO</a>
                                                    <a href="{{ route('daftar-po.edit', $preorder->id) }}" class="btn btn-primary btn-sm mx-2">Detail</a>
                                                </td>
                                            @endforeach
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
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
