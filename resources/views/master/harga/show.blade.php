@extends('main')

@section('styles')
    <style>
        .sticky-column {
        position: sticky;
        left: 0;
        background-color: white; /* Sesuaikan warna latar belakang */
        z-index: 1; /* Agar tetap di atas saat scroll */
        border-right: 2px solid #ccc; /* Border kanan untuk sticky column */
    }

    th, td {
        border: 1px solid #ccc; /* Border untuk semua cell */
        padding: 10px; /* Menambah padding untuk keterbacaan */
    }

    thead th {
        background-color: #f9f9f9; /* Latar belakang header tabel */
        box-shadow: 0 2px 2px -2px gray; /* Bayangan untuk header */
    }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 80%">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('master.harga.store') }}" method="POST" id="filter-form">
                        @csrf
                        <div class="d-flex justify-between">
                            <div>
                                <label class="mx-4">KODE SUPPLIER</label>
                                <select class="supplier-select" id="supplierSelect">
                                    <option value="{{ $products[0]->id_supplier }}" selected>{{ $products[0]->supplier->nomor }} - {{ $products[0]->supplier->nama }}</option>
                                    @foreach ($suppliers as $supplier)
                                        @if ($supplier->id !== $products[0]->id_supplier)
                                            <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-left: 20%;">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="mx-3" style="font-size: 13px;">DARI TANGGAL</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" id="from_date" style="margin-left: 3%;" name="from_date" value="{{ now()->format("Y-m-d") }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" value="{{ now()->format("Y-m-d") }}" disabled style="background-color: #90ee90; color: red;">
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-4">
                                        <label class="mx-3" style="font-size: 13px;">SAMPAI TANGGAL</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" id="to_date" style="margin-left: 3%;" readonly name="to_date" value="{{ now()->format("Y-m-d") }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" value="{{ now()->format("Y-m-d") }}" disabled style="background-color: #90ee90; color: red;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <div style="overflow-x: auto; height: 580px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr style="border: 1px solid black; font-size: 12px;">
                                            <th class="text-center">NO</th>
                                            <th class="text-center">NAMA BARANG</th>
                                            <th class="text-center">HARGA BELI LAMA</th>
                                            <th class="text-center">HARGA BELI BARU</th>
                                            <th class="text-center">%</th>
                                            <th class="text-center">HARGA JUAL</th>
                                            <th class="text-center">MARK UP</th>
                                            <th class="text-center">HARGA JUAL BARU</th>
                                            {{-- <th class="text-center">V</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                            @php
                                                $no = $index + 1;
                                            @endphp
                                            <tr>
                                                <input type="hidden" name="id_supplier" value="{{ $product->id_supplier }}">
                                                <input type="number" hidden id="harga_lama_{{ $no }}" name="harga_lama[{{ $product->id }}]" required value="{{ $product->harga_lama }}" style="width: 100px;">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->nama }}/{{ $product->unit_jual }}</td>
                                                <td class="text-end">{{ number_format($product->harga_lama) }}</td>
                                                <td><input type="number" id="harga_pokok_{{ $no }}" name="harga_pokok[{{ $product->id }}]" required value="{{ $product->harga_pokok }}" style="width: 100px;" 
                                                    onblur="handleBlurPokok({{ $no }}, '{{ $product->harga_pokok }}')"  oninput="updateProfitPokok({{ $no }})" onkeydown="handleEnterPokok(event, {{ $no }}, '{{ $product->harga_pokok }}')" onfocus="this.value = '';"></td>
                                                @if (isset($product->harga_lama) && $product->harga_lama !== 0)
                                                    <td id="profit_pokok_{{ $no }}">{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                                @else
                                                    <td id="profit_pokok_{{ $no }}">0.00</td>
                                                @endif
                                                <td><input type="number" id="harga_jual_{{ $no }}" name="harga_jual[{{ $product->id }}]" required value="{{ $product->harga_jual }}" style="width: 100px;" 
                                                    onblur="handleBlurJual({{ $no }}, '{{ $product->harga_jual }}')" oninput="updateHargaSementara({{ $no }})" onkeydown="handleEnterJual(event, {{ $no }}, '{{ $product->harga_jual }}')" onfocus="this.value = '';"></td>
                                                <td><input type="text" id="profit_{{ $no }}" name="profit[{{ $product->id }}]" value="{{ $product->profit }}" style="width: 70px;" 
                                                    onblur="handleBlurProfit({{ $no }}, '{{ $product->profit }}')" oninput="updateHargaSementara({{ $no }})" onkeydown="handleEnterProfit(event, {{ $no }}, '{{ $product->profit }}')" onfocus="this.value = '';"></td>
                                                <td id="td_harga_sementara_{{ $no }}"><input type="text" id="harga_sementara_{{ $no }}" name="harga_sementara[{{ $product->id }}]" required value="{{ round((($product->harga_jual * $product->profit) / 100) + $product->harga_jual) }}" style="width: 100px;" 
                                                    onblur="handleBlurSementara({{ $no }}, '{{ round((($product->harga_jual * $product->profit) / 100) + $product->harga_jual) }}')" oninput="updateHargaSementara2({{ $no }})" onkeydown="handleEnterSementara(event, {{ $no }}, '{{ round((($product->harga_jual * $product->profit) / 100) + $product->harga_jual) }}')" onfocus="this.value = '';"></td>
                                                <input type="checkbox" hidden id="checkbox_select_{{ $no }}" name="selected_ids[]" value="{{ $product->id }}" class="product-checkbox">
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div></div>
                            <div style="margin-left: 10%;">
                                <button type="submit" id="button-selesai" class="btn btn-primary mx-4">PROSES</button>
                                <a href="{{ route('master.harga.index') }}" class="btn btn-danger">KEMBALI</a>
                            </div>
                            <div>
                                <label class="mx-3" style="font-size: 13px;">KENAIKAN</label>
                                <input type="text" readonly value="100.00" size="5">
                                <label>%</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#supplierSelect').change(function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    var url = "{{ route('master.harga.show', ':id') }}".replace(':id', supplierId);
                    window.location.href = url;
                }
            });
        });

        $(`.supplier-select`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });

        function handleEnterPokok(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('harga_pokok_' + no);
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                document.getElementById('harga_jual_' + no).focus();
            }
        }

        function handleBlurPokok(no, originalValue) {
            var inputField = document.getElementById('harga_pokok_' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function handleEnterJual(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('harga_jual_' + no);
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                document.getElementById('profit_' + no).focus();
            }
        }

        function handleBlurJual(no, originalValue) {
            var inputField = document.getElementById('harga_jual_' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function handleEnterProfit(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('profit_' + no);
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                document.getElementById('harga_sementara_' + no).focus();
            }
        }

        function handleBlurProfit(no, originalValue) {
            var inputField = document.getElementById('profit_' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function handleEnterSementara(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('harga_sementara_' + no);
                var hargaJual = parseFloat(document.getElementById('harga_jual_' + no).value) || 0;
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                document.getElementById('checkbox_select_' + no).checked = true;
                document.getElementById('td_harga_sementara_' + no).style.backgroundColor = 'red';
                
                if (inputField.value < hargaJual) {
                    alert('HARGA SEMENTARA TIDAK BOLEH LEBIH KECIL');
                    inputField.value = originalValue;
                    document.getElementById('checkbox_select_' + no).checked = false;
                    document.getElementById('td_harga_sementara_' + no).style.backgroundColor = 'white';
                }
            }
        }

        function handleBlurSementara(no, originalValue) {
            var inputField = document.getElementById('harga_sementara_' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function updateHargaSementara(no) {
            var hargaJual = parseFloat(document.getElementById('harga_jual_' + no).value) || 0;
            var profit = parseFloat(document.getElementById('profit_' + no).value) || 0;

            // Menghitung harga_sementara
            var hargaSementara = (hargaJual * profit) / 100 + hargaJual;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('harga_sementara_' + no).value = hargaSementara.toFixed(0); // Menggunakan 2 desimal
        }

        function updateHargaSementara2(no) {
            var hargaJual = parseFloat(document.getElementById('harga_jual_' + no).value) || 0;
            var hargaSementara = parseFloat(document.getElementById('harga_sementara_' + no).value) || 0;

            // Menghitung harga_sementara
            var hitung = hargaSementara - hargaJual;
            var hargaSementara = (hitung / hargaJual) * 100;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('profit_' + no).value = hargaSementara.toFixed(2); // Menggunakan 2 desimal
        }

        function updateProfitPokok(no) {
            var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + no).value) || 0;
            var hargaLama = parseFloat(document.getElementById('harga_lama_' + no).value) || 0;

            // Menghitung harga_sementara
            var hitung = hargaPokok - hargaLama;
            var hargaSementara = (hitung / hargaLama) * 100;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('profit_pokok_' + no).innerHTML = hargaSementara.toFixed(2); // Menggunakan 2 desimal
        }

        // Memanggil fungsi pada saat halaman pertama kali dimuat untuk memastikan nilai sudah benar
        window.onload = function() {
            @foreach ($products as $index => $product)
                updateHargaSementara({{ $index + 1 }});
            @endforeach
        };

        // Mengaktifkan tombol "Proses" hanya ketika ada checkbox yang dicentang
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const buttonSelesai = document.getElementById('button-selesai');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                buttonSelesai.disabled = !anyChecked;
            });
        });

        // JavaScript untuk menghapus input harga_sementara jika checkbox tidak dicentang
        const form = document.getElementById('filter-form');
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    // Hapus input harga_sementara yang tidak dicentang
                    const hiddenInput = document.querySelector(`input[name="harga_jual[${checkbox.value}]"]`);
                    const hiddenInput2 = document.querySelector(`input[name="profit[${checkbox.value}]"]`);
                    const hiddenInput3 = document.querySelector(`input[name="harga_sementara[${checkbox.value}]"]`);
                    const hiddenInput4 = document.querySelector(`input[name="harga_pokok[${checkbox.value}]"]`);
                    const hiddenInput5 = document.querySelector(`input[name="harga_lama[${checkbox.value}]"]`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                        hiddenInput2.remove();
                        hiddenInput3.remove();
                        hiddenInput4.remove();
                        hiddenInput5.remove();
                    }
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            // focus barcode
            if (event.key === 'Enter') {
                event.preventDefault();
                
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const fromDate = document.getElementById("from_date");
            const toDate = document.getElementById("to_date");

            // Set initial min value for "SAMPAI TANGGAL"
            toDate.min = fromDate.value;

            // Update min value of "SAMPAI TANGGAL" when "DARI TANGGAL" changes
            fromDate.addEventListener("change", function () {
                toDate.min = this.value;
                toDate.value = this.value;
            });
        });
    </script>
@endsection