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
                {{-- <form action="{{ route('preorder.order-barang') }}" method="POST">
                    @csrf --}}
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                {{-- <div class="form-group col-7">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-3 col-form-label">Nama Supplier</label>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nama }}" placeholder="Email">
                                            <input type="hidden" name="supplierName" value="{{ $supplier1->nama }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-4">
                                            <input type="text" disabled class="form-control" value="IDR"
                                                id="inputPassword3">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-2">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-4 col-form-label">Rate</label>
                                        <div class="col-sm-4">
                                            <input type="text" disabled class="form-control" value="1"
                                                id="inputPassword3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

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
                                            @foreach ($preorders as $preorder)
                                                <tr>
                                                    <td class="text-center">{{ $preorder->supplier->nama }}</td>
                                                    <td class="text-center">{{ $preorder->nomor_po }}</td>
                                                    <td class="text-center">{{ $preorder->ref }}</td>
                                                    <td class="text-center"><a href="{{ route('daftar-po.show', $preorder->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye "></i> Detail</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="d-flex justify-content-center">
                            <button class="btn btn-primary">Buat PO</button>
                            <a class="btn btn-danger mx-5" href="{{ route('preorder.index') }}">Batal</a>
                        </div> --}}
                    </div>
                {{-- </form> --}}
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
