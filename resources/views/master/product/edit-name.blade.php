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
        <form action="{{ route('master.product.update-edit-nama') }}" method="POST" id="filter-form">
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
                                            <th class="text-center">ISI</th>
                                            <th class="text-center">NAMA BARANG BARU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                        @php
                                            $no = $index + 1;
                                        @endphp
                                        <tr id="product-{{ $no }}">
                                            <td>
                                                <input type="text" readonly name="old_fisik[{{ $product->id }}]" value="{{ $product->nama }}">
                                            </td>
                                            <td class="text-center">
                                                {{ $product->unit_jual }}
                                            </td>
                                            <td class="product-cell">
                                                <input type="text" 
                                                       name="fisik[{{ $product->id }}]" 
                                                       value="{{ $product->nama }}" 
                                                       data-original-value="{{ $product->nama }}" 
                                                       class="product-name-input"
                                                       style="width: 300px;">
                                                
                                                <input type="checkbox" 
                                                       hidden 
                                                       name="selected_ids[]" 
                                                       value="{{ $product->id }}" 
                                                       class="product-checkbox">
                                                
                                            </td>
                                            <td hidden class="text-center">
                                                {{ $product->nama }}
                                            </td>
                                       </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div style="margin-right: 2%;">
                            <!-- <button type="button" id="openModalBtn" class="btn btn-warning">CETAK</button> -->
                            <button type="submit" id="proses-submit" class="btn btn-primary mx-3">PROSES</button>
                            <a href="{{ route('master.product.show', enkrip($product->id)) }}" class="btn btn-danger">KEMBALI</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Awasi setiap input nama produk
            document.querySelectorAll('.product-name-input').forEach(function(input) {
                const originalValue = input.getAttribute('data-original-value');
                const row = input.closest('tr');
                const checkbox = row.querySelector('.product-checkbox');

                checkbox.checked = false; // Awal tidak dicentang

                input.addEventListener('input', function () {
                    checkbox.checked = input.value !== originalValue;
                });
            });

            // Saat submit form, hanya input yang terkait checkbox checked yang akan dikirim
            document.getElementById('filter-form').addEventListener('submit', function () {
                document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
                    if (!checkbox.checked) {
                        const row = checkbox.closest('tr');
                        // Nonaktifkan semua input terkait produk ini
                        row.querySelectorAll('input').forEach(function(input) {
                            input.disabled = true;
                        });
                    }
                });
            });
        });
    </script>
@endsection

