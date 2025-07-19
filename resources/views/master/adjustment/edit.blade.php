@extends('main')

@include('preorder.add-on.styles')
@section('styles')
    <style>
        /* Modal Style */
        .modal {
            display: none; /* Sembunyikan modal secara default */
            position: fixed;
            z-index: 1; /* Letakkan modal di atas konten lainnya */
            left: 0;
            top: 0;
            width: 100%; /* Lebar penuh */
            height: 100%; /* Tinggi penuh */
            background-color: rgba(0, 0, 0, 0.4); /* Latar belakang gelap */
            overflow: auto; /* Konten bisa di-scroll jika terlalu besar */
            padding-top: 20px; /* Jarak atas untuk konten */
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 20%; /* Lebar modal */
            max-width: 1000px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
@endsection

@section('content')
    <div class="mb-7 mx-5">
        <form action="{{ route('master.adjustment.update-edit') }}" method="POST" id="filter-form">
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
                                            <td id="product-stok" class="text-end">{{  number_format($product->stok,0) }}</td>
                                            <td id="product-fisik" class="text-center">
                                                <input type="number" class="text-end product-input" name="fisik[{{ $product->id }}]" value="{{ number_format($product->stok,0) }}" 
                                                       oninput="calculateSelisih({{ $no }}, {{ $product->harga_jual }}, {{ $product->stok }}, {{ $product->id }})"
                                                       data-original-value="{{ $product->stok }}" style="width: 70px;">
                                            </td>
                                            <td class="text-end">{{ number_format($product->harga_jual * $product->stok) }}</td>
                                            <td id="product-selisih-qty-{{ $no }}" class="text-end">{{ number_format(0, 2) }}</td>
                                            <td id="product-selisih-rupiah-{{ $no }}" class="text-end">0</td>
                                            <input type="text" hidden name="selected[{{ $product->id }}]" value="{{ $product->id }}">
                                            <input type="text" hidden name="name[{{ $product->id }}]" value="{{ $product->nama . '/' . $product->unit_jual }}">
                                            <input type="text" hidden name="stok[{{ $product->id }}]" value="{{ $product->stok }}">
                                            <input type="text" hidden name="qty[{{ $product->id }}]" id="input-selisih-qty-{{ $no }}" value="0.00">
                                            <input type="text" hidden name="rupiah[{{ $product->id }}]" id="input-selisih-rupiah-{{ $no }}" value="0">
                                            <input type="checkbox" hidden id="checkbox_select_{{ $no }}" name="selected_ids[]" value="{{ $product->id }}" class="product-checkbox">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div style="margin-right: 2%;">
                            <button type="button" id="openModalBtn" class="btn btn-warning">CETAK</button>
                            <button type="submit" id="proses-submit" disabled class="btn btn-primary mx-3">PROSES</button>
                            <a href="{{ route('master.adjustment.index-edit') }}" class="btn btn-danger">KEMBALI</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h5 class="text-center"><u>JENIS LAPORAN</u></h5>
            <a href="{{ route('master.adjustment.cetak') }}" class="btn btn-primary mt-2">HASIL ADJUSTMENT</a>
            <a href="{{ route('master.adjustment.cetak-rokok') }}" class="btn btn-danger mt-3">LAPORAN ROKOK</a>
            <button class="btn btn-warning mt-3" id="closeBtn">SELESAI</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.product-input')
                // Saat fokus, set value jadi null
                .focus(function() {
                    const originalValue = $(this).val();
                    $(this).data('original-value', originalValue); // Simpan nilai awal
                    $(this).val(''); // Kosongkan input
                })
                // Jika blur (kehilangan fokus), kembalikan nilai awal jika tetap kosong
                .blur(function() {
                    if ($(this).val() === '') {
                        $(this).val($(this).data('original-value')); // Kembalikan nilai awal
                    }
                })
                // Saat Enter, cek apakah value kosong
                .keydown(function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // Mencegah submit form

                        if ($(this).val() === '') {
                            $(this).val($(this).data('original-value')); // Kembalikan nilai awal jika kosong
                            const nextInput = $('.product-input').eq($('.product-input').index(this) + 1);
                            if (nextInput.length) {
                                nextInput.focus();
                            }
                        } else {
                            // Fokus ke input berikutnya
                            const nextInput = $('.product-input').eq($('.product-input').index(this) + 1);
                            if (nextInput.length) {
                                nextInput.focus();
                            }
                        }
                    }
                });
        });

        // JavaScript untuk menghapus input harga_sementara jika checkbox tidak dicentang
        const form = document.getElementById('filter-form');
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    // Hapus input harga_sementara yang tidak dicentang
                    const hiddenInput = document.querySelector(`input[name="selected[${checkbox.value}]"]`);
                    const hiddenInput2 = document.querySelector(`input[name="name[${checkbox.value}]"]`);
                    const hiddenInput3 = document.querySelector(`input[name="stok[${checkbox.value}]"]`);
                    const hiddenInput4 = document.querySelector(`input[name="qty[${checkbox.value}]"]`);
                    const hiddenInput5 = document.querySelector(`input[name="rupiah[${checkbox.value}]"]`);
                    const hiddenInput6 = document.querySelector(`input[name="fisik[${checkbox.value}]"]`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                        hiddenInput2.remove();
                        hiddenInput3.remove();
                        hiddenInput4.remove();
                        hiddenInput5.remove();
                        hiddenInput6.remove();
                    }
                }
            });
        });

        // Ambil elemen modal dan tombol
        const modal = document.getElementById("productModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementsByClassName("close-btn")[0];
        const closeBtn = document.getElementById("closeBtn");

        // Ketika tombol CETAK diklik, tampilkan modal dengan fade-in
        openModalBtn.onclick = function () {
            modal.style.display = "block"; // Tampilkan modal
            modal.style.animation = "fadeIn 0.5s ease"; // Tambahkan efek animasi fade-in
        };

        // Ketika tombol "X" diklik, sembunyikan modal
        closeModalBtn.onclick = function () {
            modal.style.display = "none"; // Sembunyikan modal
        };

        // Ketika area di luar modal diklik, sembunyikan modal
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; // Sembunyikan modal
            }
        };

        closeBtn.onclick = function () {
            modal.style.display = "none"; // Sembunyikan modal setelah simpan
        };

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
                document.getElementById(`input-selisih-qty-${no}`).value = "";
            }
            if (selisihRupiah === 0) {
                document.getElementById(`input-selisih-rupiah-${no}`).value = "";
            }

            // Simpan nilai terbaru untuk perbandingan berikutnya
            lastValues[no] = productFisik;
            document.getElementById('checkbox_select_' + no).checked = true;
            document.getElementById('proses-submit').disabled = false;
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    </script>
@endsection
