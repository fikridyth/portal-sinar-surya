@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('preorder.order-barang') }}" method="POST">
                    @csrf
                    <div class="card-body mt-n4">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-7">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-3 col-form-label">Nama Supplier</label>
                                        {{-- <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nomor }}" placeholder="Email">
                                        </div> --}}
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nama }}" placeholder="Email">
                                                <input type="hidden" name="supplierId" value="{{ $supplier1->id }}">
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
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr>
                                                    <th colspan="5" class="text-center">DAFTAR BARANG YANG AKAN DIPESAN</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Nama Barang</th>
                                                    <th class="text-center">Stok</th>
                                                    <th class="text-center">Order</th>
                                                    <th class="text-center">Harga</th>
                                                    <th class="text-center">Jumlah Rp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $result)
                                                    <tr>
                                                        <input type="text" name="nama[]" hidden value="{{ $result['product']->nama }}">
                                                        <input type="text" name="stok[]" hidden value="{{ $result['details']['stok'] }}">
                                                        <input type="text" name="order[]" hidden value="{{ $result['details']['order'] }}">
                                                        <input type="text" name="unit_jual[]" hidden value="{{ $result['product']->unit_jual }}">
                                                        <input type="text" name="kode[]" hidden value="{{ $result['product']->kode }}">
                                                        <input type="text" name="kode_sumber[]" hidden value="{{ $result['product']->kode_sumber }}">
                                                        <input type="text" name="is_ppn[]" hidden value="{{ $supplier1->is_ppn }}">
                                                        <input type="text" name="diskon1[]" hidden value="{{ $result['product']->diskon1 }}">
                                                        <input type="text" name="diskon2[]" hidden value="{{ $result['product']->diskon2 }}">
                                                        <input type="text" name="diskon3[]" hidden value="{{ $result['product']->diskon3 }}">
                                                        <input type="text" name="id_supplier[]" hidden value="{{ $result['product']->id_supplier }}">
                                                        <input type="text" name="old_price[]" hidden value="{{ $result['product']->harga_lama }}">
                                                        <td>{{ $result['product']->nama . '/' . $result['product']->unit_jual }}</td>
                                                        <td class="text-end">{{ $result['details']['stok'] }}</td>
                                                        <td class="text-end">
                                                            <input type="number" required class="form-control order" value="{{ $result['details']['order'] }}">
                                                        </td>
                                                        <td class="text-end">
                                                            <h6 style="text-align: center; align-items: center;">{{ number_format($result['details']['harga'], 2) }}</h6>
                                                            {{-- <h6 style="text-align: center; align-items: center;">{{ number_format($result['details']['harga'], 2) . '/' . number_format($result['product']->harga_pokok, 2) }}</h6> --}}
                                                            <input type="number" hidden class="form-control price" name="price[]"
                                                                value="{{ $result['details']['harga'] }}" step="0.01">
                                                        </td>
                                                        <td class="text-end">
                                                            <input type="text" class="form-control total" name="total[]"
                                                                value="0.00" readonly>
                                                            <input type="text" hidden class="form-control fieldtotal" name="fieldtotal[]"
                                                            value="0.00" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('preorder.add-po.cetak', ['results' => urlencode(json_encode($results))]) }}" class="btn btn-warning mx-3">CETAK</a>
                            <button type="submit" class="btn btn-primary mx-3">BUAT PO</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function formatNumber(num) {
                // Format number with commas and two decimal places
                return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
