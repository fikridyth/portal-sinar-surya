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
            {{-- <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                MASTER HARGA
                            </li>
                        </ol>
                    </nav>
                </div>
            </div> --}}

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
                                        <input style="margin-left: 3%;" type="date" name="from_date" value="{{ now()->format("Y-m-d") }}">
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
                                        <input style="margin-left: 3%;" type="date" name="to_date" value="{{ now()->format("Y-m-d") }}">
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
                                            <th class="text-center">V</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                            <tr>
                                                <input type="hidden" name="id_supplier" value="{{ $product->id_supplier }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->nama }}/{{ $product->unit_jual }}</td>
                                                <input type="number" hidden id="harga_lama_{{ $index }}" name="harga_lama[{{ $product->id }}]" required value="{{ $product->harga_lama }}" style="width: 100px;">
                                                <td class="text-end">{{ number_format($product->harga_lama) }}</td>
                                                <td><input type="number" id="harga_pokok_{{ $index }}" name="harga_pokok[{{ $product->id }}]" required value="{{ $product->harga_pokok }}" style="width: 100px;"></td>
                                                @if (isset($product->harga_lama) && $product->harga_lama !== 0)
                                                    <td>{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                                @else
                                                    <td>0.00</td>
                                                @endif
                                                <td><input type="number" id="harga_jual_{{ $index }}" name="harga_jual[{{ $product->id }}]" required value="{{ $product->harga_jual }}" style="width: 100px;" oninput="updateHargaSementara({{ $index }})"></td>
                                                <td><input type="text" id="profit_{{ $index }}" name="profit[{{ $product->id }}]" value="{{ $product->profit }}" style="width: 70px;" oninput="updateHargaSementara({{ $index }})"></td>
                                                <td><input type="text" id="harga_sementara_{{ $index }}" name="harga_sementara[{{ $product->id }}]" required value="{{ round((($product->harga_jual * $product->profit) / 100) + $product->harga_jual) }}" style="width: 100px;"></td>
                                                <td><input type="checkbox" name="selected_ids[]" value="{{ $product->id }}" class="product-checkbox"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div></div>
                            <div style="margin-left: 10%;">
                                <button type="submit" id="button-selesai" disabled class="btn btn-primary mx-4">PROSES</button>
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

        function updateHargaSementara(index) {
            var hargaJual = parseFloat(document.getElementById('harga_jual_' + index).value) || 0;
            var profit = parseFloat(document.getElementById('profit_' + index).value) || 0;

            // Menghitung harga_sementara
            var hargaSementara = (hargaJual * profit) / 100 + hargaJual;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('harga_sementara_' + index).value = hargaSementara.toFixed(0); // Menggunakan 2 desimal
        }

        // Memanggil fungsi pada saat halaman pertama kali dimuat untuk memastikan nilai sudah benar
        window.onload = function() {
            @foreach ($products as $index => $product)
                updateHargaSementara({{ $index }});
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
    </script>
@endsection