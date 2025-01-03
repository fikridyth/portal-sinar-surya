@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="mb-7 mx-5">
        <form action="{{ route('master.adjustment.update-edit') }}" method="POST">
            @csrf
            <div class="card mt-n3">
                <div class="card-body mt-n3">
                        <div class="form-group">
                            <input type="text" autocomplete="off" id="searchInputProduct" class="mb-2" size="45" placeholder="Cari Product" onkeyup="searchTableProduct()">
                            <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                                <table id="table-product" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                            {{-- <th class="text-center">NO</th> --}}
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
                                        @foreach ($products as $index => $product)
                                        @php
                                            $no = $index + 1;
                                        @endphp
                                        <tr id="product-{{ $no }}">
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td class="product-cell">
                                                {{ $product->nama }}/{{ $product->unit_jual }}
                                                <input type="checkbox" hidden class="checkbox-product" data-id="{{ $product->id }}">
                                            </td>
                                            <td class="text-end">{{ $product->konversi }}</td>
                                            <td class="text-end">{{ str_replace('P', '', $product->unit_jual) }}</td>
                                            <td id="product-harga" class="text-end">{{ number_format($product->harga_jual) }}</td>
                                            <td id="product-stok" class="text-end">{{ $product->stok }}</td>
                                            <td id="product-fisik" class="text-center">
                                                <input type="number" class="text-end" name="fisik[{{ $product->id }}]" value="{{ $product->stok }}" 
                                                       oninput="calculateSelisih({{ $no }}, {{ $product->harga_jual }}, {{ $product->stok }}, {{ $product->id }})">
                                            </td>
                                            <td class="text-end">{{ number_format($product->harga_jual * $product->stok) }}</td>
                                            <td id="product-selisih-qty-{{ $no }}" class="text-end">{{ number_format(0, 2) }}</td>
                                            <td id="product-selisih-rupiah-{{ $no }}" class="text-end">0</td>
                                            <input type="text" hidden name="selected[{{ $product->id }}]" value="{{ $product->id }}">
                                            <input type="text" hidden name="name[{{ $product->id }}]" value="{{ $product->nama . '/' . $product->unit_jual }}">
                                            <input type="text" hidden name="stok[{{ $product->id }}]" value="{{ $product->stok }}">
                                            <input type="text" hidden name="qty[{{ $product->id }}]" id="input-selisih-qty-{{ $no }}" value="0.00">
                                            <input type="text" hidden name="rupiah[{{ $product->id }}]" id="input-selisih-rupiah-{{ $no }}" value="0">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div style="margin-right: 2%;">
                            <a href="{{ route('master.adjustment.cetak') }}" class="btn btn-warning">CETAK</a>
                            <button type="submit" class="btn btn-primary mx-3">PROSES</button>
                            <a href="{{ route('master.adjustment.index-edit') }}" class="btn btn-danger">KEMBALI</a>
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
            // calculateSelisih();
        });

        let lastValues = {};  // Menyimpan nilai terakhir untuk memeriksa perubahan

        function calculateSelisih(no, productHarga, productStok, productId) {
            const inputElement = document.querySelector(`#product-${no} input[name="fisik[${productId}]"]`);
            const productFisik = parseFloat(inputElement.value);

            // Periksa apakah nilai fisik berubah
            if (lastValues[no] !== undefined && lastValues[no] === productFisik) {
                return;  // Jika tidak ada perubahan, tidak melakukan apa-apa
            }

            // Hitung selisih-qty dan selisih-rupiah
            const selisihQty = productFisik - productStok;
            const selisihRupiah = selisihQty * productHarga;

            // Update tampilan selisih-qty dan selisih-rupiah
            document.querySelector(`#product-${no} #product-selisih-qty-${no}`).textContent = selisihQty.toFixed(2);
            document.querySelector(`#product-${no} #product-selisih-rupiah-${no}`).textContent = number_format(selisihRupiah);
            document.getElementById(`input-selisih-qty-${no}`).value = selisihQty.toFixed(2);
            document.getElementById(`input-selisih-rupiah-${no}`).value = selisihRupiah;

            if (selisihQty === 0) {
                document.getElementById(`input-selisih-qty-${index}`).value = "";
            }
            if (selisihRupiah === 0) {
                document.getElementById(`input-selisih-rupiah-${index}`).value = "";
            }

            // Simpan nilai terbaru untuk perbandingan berikutnya
            lastValues[no] = productFisik;
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // function filterZeroValues() {
        //     // Loop through all hidden inputs and remove those with zero values
        //     const qtyInputs = document.querySelectorAll('input[name^="qty"]');
        //     const rupiahInputs = document.querySelectorAll('input[name^="rupiah"]');

        //     // Loop through qty inputs and clear the ones with zero value
        //     qtyInputs.forEach(input => {
        //         if (parseFloat(input.value) === 0) {
        //             input.value = "";  // Remove zero values
        //         }
        //     });

        //     // Loop through rupiah inputs and clear the ones with zero value
        //     rupiahInputs.forEach(input => {
        //         if (parseFloat(input.value) === 0) {
        //             input.value = "";  // Remove zero values
        //         }
        //     });
        // }

        // // Attach filterZeroValues function to form submission (for example, using the submit event)
        // document.querySelector('form').addEventListener('submit', function(event) {
        //     filterZeroValues();  // Call the function before submitting
        // });
    </script>
@endsection
