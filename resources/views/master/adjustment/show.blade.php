@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="mb-7 mx-5">
        <form action="{{ route('master.adjustment.update') }}" method="POST">
            @csrf
            <div class="card mt-n3">
                <div class="card-body mt-n3">
                        <div class="form-group">
                            <input type="text" autocomplete="off" id="searchInputProduct" class="mb-2" size="45" placeholder="Cari Product" onkeyup="searchTableProduct()">
                            <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                                <table id="table-product" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            <th class="text-center">NO</th>
                                            <th class="text-center">NAMA BARANG</th>
                                            <th class="text-center">KONV</th>
                                            <th class="text-center">ISI</th>
                                            <th class="text-center">HARGA</th>
                                            <th class="text-center">STOK</th>
                                            <th class="text-center">FISIK</th>
                                            <th class="text-center">RUPIAH</th>
                                            <th class="text-center">SELISIH QTY</th>
                                            <th class="text-center">SELISIH RP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="product-cell">
                                                {{ $product->nama }}/{{ $product->unit_jual }}
                                                <input type="checkbox" hidden class="checkbox-product" data-id="{{ $product->id }}">
                                            </td>
                                            <td class="text-end">{{ $product->konversi }}</td>
                                            <td class="text-end">{{ str_replace('P', '', $product->unit_jual) }}</td>
                                            <td class="text-end">{{ number_format($product->harga_jual) }}</td>
                                            <td class="text-end">{{ $product->stok }}</td>
                                            <td class="text-center"><input type="number" class="text-end" value="{{ $product->stok }}"></td>
                                            <td class="text-end">{{ number_format($product->harga_jual * $product->stok) }}</td>
                                            <td class="text-end">{{ number_format(0, 2) }}</td>
                                            <td class="text-end">0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="selected-products" style="display: none;">
                            <!-- Dynamically added hidden inputs will appear here -->
                        </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div style="margin-right: 2%;">
                            <button type="submit" class="btn btn-primary mx-3">PROSES</button>
                            <a href="{{ route('master.adjustment.index') }}" class="btn btn-danger">KEMBALI</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function searchTableProduct() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputProduct");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-product");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                let match = false;

                // Loop through all columns
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            match = true;
                            break;  // Break the loop once a match is found in any column
                        }
                    }
                }
                tr[i].style.display = match ? "" : "none";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize your search functionality after the page loads.
            document.getElementById("searchInputProduct").addEventListener("keyup", searchTableProduct);
        });
    </script>
@endsection
