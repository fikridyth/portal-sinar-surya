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
                    <form action="{{ route('master.harga.update', $hargaSementara->id) }}" method="POST" id="filter-form">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-between">
                            <div>
                                <label class="mx-4">KODE SUPPLIER</label>
                                <input type="text" value="{{ $hargaSementara->supplier->nomor }} - {{ $hargaSementara->supplier->nama }}" size="42" disabled>
                            </div>
                            <div style="margin-left: 20%;">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="mx-3" style="font-size: 13px;">DARI TANGGAL</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" id="from_date" style="margin-left: 3%;" name="from_date" value="{{ \Carbon\Carbon::parse($hargaSementara->date_first)->format("Y-m-d") }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" value="{{ \Carbon\Carbon::parse($hargaSementara->date_first)->format("Y-m-d") }}" disabled style="background-color: #90ee90; color: red;">
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-4">
                                        <label class="mx-3" style="font-size: 13px;">SAMPAI TANGGAL</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" id="to_date" style="margin-left: 3%;" name="to_date" readonly value="{{ \Carbon\Carbon::parse($hargaSementara->date_last)->format("Y-m-d") }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" value="{{ \Carbon\Carbon::parse($hargaSementara->date_last)->format("Y-m-d") }}" disabled style="background-color: #90ee90; color: red;">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listProduct as $index => $product)
                                            <tr>
                                                <input type="hidden" name="id_supplier" value="{{ $product->id_supplier }}">
                                                <input type="hidden" name="loop[{{ $product->id }}]" value="{{ $product->id }}">
                                                <input type="hidden" name="data_id[{{ $product->id }}]" value="{{ $product->id_product }}">
                                                <input type="hidden" id="harga_lama_{{ $index }}" name="harga_lama[{{ $product->id }}]" value="{{ $product->harga_lama }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->nama }}</td>
                                                <td>{{ number_format($product->harga_lama) }}</td>
                                                <td><input type="number" id="harga_pokok_{{ $index }}" name="harga_pokok[{{ $product->id }}]" required value="{{ $product->harga_pokok }}" style="width: 100px;" oninput="updateProfitPokok({{ $index }})"></td>
                                                @if (isset($product->harga_lama) && $product->harga_lama !== 0)
                                                    <td id="profit_pokok_{{ $index }}">{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                                @else
                                                    <td id="profit_pokok_{{ $index }}">0.00</td>
                                                @endif
                                                <input type="number" hidden id="harga_jual_{{ $index }}" name="harga_jual[{{ $product->id }}]" required value="{{ $product->harga_jual }}" style="width: 100px;" oninput="updateHargaSementara({{ $index }})">
                                                <td class="text-end">{{ number_format($product->harga_jual) }}</td>
                                                <td><input type="text" id="profit_{{ $index }}" name="profit[{{ $product->id }}]" value="{{ $product->profit_jual }}" style="width: 70px;" oninput="updateHargaSementara({{ $index }})"></td>
                                                <td><input type="text" id="harga_sementara_{{ $index }}" name="harga_sementara[{{ $product->id }}]" required value="{{ $product->harga_sementara }}" style="width: 100px;" oninput="updateHargaSementara2({{ $index }})"></td>
                                                <input type="text" id="read_sementara_{{ $index }}" hidden value="{{ $product->harga_sementara }}">
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
                                <input type="number" id="kenaikan" name="kenaikan" autocomplete="off" max="100" value="{{ $hargaSementara->naik }}" style="width: 70px;" 
                                    onblur="handleBlurKenaikan('{{ $hargaSementara->naik }}')" onkeydown="handleEnterKenaikan(event, '{{ $hargaSementara->naik }}')" onfocus="this.value = '';">
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
        function updateHargaSementara(index) {
            var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + index).value) || 0;
            var profit = parseFloat(document.getElementById('profit_' + index).value) || 0;

            // Menghitung harga_sementara
            var hargaSementara = (hargaPokok * profit) / 100 + hargaPokok;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('harga_sementara_' + index).value = hargaSementara.toFixed(0); // Menggunakan 2 desimal
        }

        function updateHargaSementara2(index) {
            var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + index).value) || 0;
            var hargaSementara = parseFloat(document.getElementById('harga_sementara_' + index).value) || 0;

            // Menghitung harga_sementara
            var hitung = hargaSementara - hargaPokok;
            var hargaSementara = (hitung / hargaPokok) * 100;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('profit_' + index).value = hargaSementara.toFixed(2); // Menggunakan 2 desimal
        }

        function updateProfitPokok(index) {
            var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + index).value) || 0;
            var hargaLama = parseFloat(document.getElementById('harga_lama_' + index).value) || 0;

            // Menghitung harga_sementara
            var hitung = hargaPokok - hargaLama;
            var hargaSementara = (hitung / hargaLama) * 100;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('profit_pokok_' + index).innerHTML = hargaSementara.toFixed(2); // Menggunakan 2 desimal
        }

        function handleEnterKenaikan(event, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('kenaikan').value || 1;
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                updateHargaKenaikan()
            }
        }

        function handleBlurKenaikan(originalValue) {
            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (document.getElementById('kenaikan').value === '') {
                document.getElementById('kenaikan').value = originalValue;
            }
        }
        
        function updateHargaKenaikan() {
            // Ambil nilai kenaikan dari input
            var kenaikan = parseFloat(document.getElementById('kenaikan').value) || 1;
            
            // Loop untuk setiap produk dan update harga sementaranya
            @foreach ($listProduct as $index => $product)
                var hargaPokok = parseFloat(document.getElementById('harga_pokok_{{ $index }}').value) || 0;
                var hargaSementara = parseFloat(document.getElementById('harga_sementara_{{ $index }}').value) || 0;
                var readSementara = document.getElementById('read_sementara_{{ $index }}').value || 0;

                // Menghitung harga sementara setelah kenaikan
                var hitung = readSementara - hargaPokok;
                var hitung2 = (kenaikan / 100) * hitung;
                var hargaSetelahKenaikan = hargaPokok + hitung2;
                var profit = ((hargaSetelahKenaikan - hargaPokok) / hargaPokok) * 100

                // Update nilai harga sementara pada input
                document.getElementById('harga_sementara_{{ $index }}').value = hargaSetelahKenaikan.toFixed(0);
                document.getElementById('profit_{{ $index }}').value = profit.toFixed(2);
            @endforeach
        }

        // Memanggil fungsi pada saat halaman pertama kali dimuat untuk memastikan nilai sudah benar
        // window.onload = function() {
        //     @foreach ($listProduct as $index => $product)
        //         updateHargaSementara({{ $index }});
        //     @endforeach
        // };

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