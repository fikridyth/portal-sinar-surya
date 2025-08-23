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

        .modal-password {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            z-index: 1000;
        }
        
        .modal-content-password {
            display: flex;
            flex-direction: column;
        }

        #passwordInput {
            padding: 8px;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
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
                                            <th class="text-center">V</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                            @php
                                                $productHarga = App\Models\HargaSementara::where('id_product', $product->id)->orderBy('created_at', 'desc')->first();
                                                $no = $index + 1;
                                            @endphp
                                            <tr>
                                                <input type="hidden" name="id_supplier" value="{{ $product->id_supplier }}">
                                                <input type="number" hidden id="harga_lama_{{ $no }}" name="harga_lama[{{ $product->id }}]" required value="{{ $product->harga_lama }}" style="width: 100px;">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->nama }}/{{ $product->unit_jual }}</td>
                                                <td class="text-end">{{ number_format($productHarga->harga_lama ?? $product->harga_lama) }}</td>
                                                <td>
                                                    <input 
                                                        type="number" 
                                                        autocomplete="off" 
                                                        id="harga_pokok_{{ $no }}" 
                                                        name="harga_pokok[{{ $product->id }}]" 
                                                        required 
                                                        value="{{ $product->harga_pokok }}" 
                                                        style="width: 100px;" 
                                                        data-id="{{ $product->id }}"
                                                        data-index="{{ $no }}"
                                                        data-nama="{{ $product->nama }}" 
                                                        data-harga-lama="{{ $product->harga_pokok }}"
                                                        onblur="handleBlurPokok({{ $no }}, '{{ $product->harga_pokok }}')"  
                                                        oninput="updateProfitPokok({{ $no }}, {{ $product->id }})" 
                                                        onkeydown="handleEnterPokok(event, {{ $no }}, '{{ $product->harga_pokok }}')" 
                                                        onfocus="this.value = '';"
                                                    >
                                                </td>
                                                @if (isset($product->harga_lama) && $product->harga_lama !== 0)
                                                    <td id="profit_pokok_{{ $no }}">{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                                @else
                                                    <td id="profit_pokok_{{ $no }}">0.00</td>
                                                @endif
                                                <input type="number" id="harga_jual_{{ $no }}" hidden name="harga_jual[{{ $product->id }}]" required value="{{ $product->harga_jual }}" style="width: 100px;" 
                                                    onblur="handleBlurJual({{ $no }}, '{{ $product->harga_jual }}')" oninput="updateHargaSementara({{ $no }})" onkeydown="handleEnterJual(event, {{ $no }}, '{{ $product->harga_jual }}')" onfocus="this.value = '';">
                                                <td class="text-end">{{ number_format($productHarga->harga_jual ?? $product->harga_jual) }}</td>
                                                <td><input type=" text"autocomplete="off" id="profit_{{ $no }}" name="profit[{{ $product->id }}]" value="{{ $product->profit }}" style="width: 70px;" 
                                                    onblur="handleBlurProfit({{ $no }}, '{{ $product->profit }}')" oninput="updateHargaSementara({{ $no }})" onkeydown="handleEnterProfit(event, {{ $no }}, '{{ $product->profit }}')" onfocus="this.value = '';"></td>
                                                <td id="td_harga_sementara_{{ $no }}">
                                                    <input 
                                                        type="text" 
                                                        autocomplete="off" 
                                                        id="harga_sementara_{{ $no }}" 
                                                        name="harga_sementara[{{ $product->id }}]" 
                                                        required 
                                                        value="{{ $productHarga->harga_jual ?? $product->harga_jual }}" 
                                                        style="width: 100px;" 
                                                        data-nama="{{ $product->nama }}"
                                                        data-index="{{ $no }}"
                                                        onblur="handleBlurSementara({{ $no }}, '{{ $productHarga->harga_jual ?? $product->harga_jual }}')"
                                                        oninput="updateHargaSementara2({{ $no }})" 
                                                        onkeydown="handleEnterSementara(event, {{ $no }}, '{{ round((($product->harga_pokok * $product->profit) / 100) + $product->harga_pokok) }}')"
                                                    >
                                                </td>
                                                <td><input type="checkbox" style="pointer-events:none;" id="checkbox_select_{{ $no }}" name="selected_ids[{{ $product->id }}]" value="{{ $product->id }}" class="product-checkbox" data-nama="{{ $product->nama }}"></td>
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
    
    {{-- pass modal --}}
    <div id="passwordModal" class="modal-password" style="display:none;">
        <div class="modal-content-password">
            <h5>MASUKAN PASSWORD</h5>
            <input type="password" id="passwordInput" onkeydown="handleValidatePassword(event)" oninput="this.value = this.value.toUpperCase()"/>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     const inputs = document.querySelectorAll('input[data-nama]');

        //     inputs.forEach(input => {
        //         input.addEventListener('input', function () {
        //             const nama = this.dataset.nama;
        //             const hargaLamaAsal = parseFloat(this.dataset.hargaLama || 1);
        //             const inputBaru = parseFloat(this.value || 0);

        //             if (hargaLamaAsal === 0) return; // hindari divide by zero

        //             const rasio = inputBaru / hargaLamaAsal;

        //             // Update semua input lain dengan nama produk sama
        //             inputs.forEach(target => {
        //                 if (target.dataset.nama === nama && target !== this) {
        //                     const hargaLamaTarget = parseFloat(target.dataset.hargaLama || 1);
        //                     const hasilBaru = Math.ceil(hargaLamaTarget * rasio);
        //                     target.value = hasilBaru;
        //                 }
        //             });
        //         });
        //     });
        // });
        
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

        let tempInputData = {
            inputField: null,
            newValue: null,
            originalValue: null
        };

        let modalActive = false;

        function handleEnterPokok(event, no, originalValue) {
            if (event.key === 'Enter') {
                if (!modalActive) {
                    // Tahap 1: Cek nilai input dan tampilkan modal jika perlu
                    var inputField = document.getElementById('harga_pokok_' + no);
                    var hargaLama = parseFloat(document.getElementById('harga_lama_' + no).value) || 0;

                    // Jika input kosong, kembalikan nilai ke nilai asli
                    if (inputField.value === '') {
                        inputField.value = originalValue;
                    }

                    if (parseFloat(inputField.value) < hargaLama) {
                        tempInputData.inputField = inputField;
                        tempInputData.newValue = inputField.value;
                        tempInputData.originalValue = originalValue;

                        // Buka modal
                        showPasswordModal();
                        return;
                    }

                    // Fokuskan pada elemen berikutnya
                    document.getElementById('profit_' + no).focus();
                } else {
                    // Tahap 2: Validasi password setelah modal muncul
                    validatePassword();
                }
            }
        }

        function handleBlurPokok(no, originalValue) {
            if (!modalActive) {
                // Tahap 1: Cek nilai input dan tampilkan modal jika perlu
                var inputField = document.getElementById('harga_pokok_' + no);
                var hargaLama = parseFloat(document.getElementById('harga_lama_' + no).value) || 0;

                // Jika input kosong, kembalikan nilai ke nilai asli
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                if (parseFloat(inputField.value) < hargaLama) {
                    tempInputData.inputField = inputField;
                    tempInputData.newValue = inputField.value;
                    tempInputData.originalValue = originalValue;

                    // Buka modal
                    showPasswordModal();
                    return;
                }

                // Fokuskan pada elemen berikutnya
                document.getElementById('profit_' + no).focus();
            } else {
                // Tahap 2: Validasi password setelah modal muncul
                validatePassword();
            }
        }

        function handleValidatePassword() {
            if (event.key === 'Enter') {
                validatePassword();
            }
        }

        function showPasswordModal() {
            var modal = document.getElementById('passwordModal');
            modal.style.display = 'block';
            document.getElementById('passwordInput').focus();
            modalActive = true;
        }

        const ownerPassword = @json($owner);
        function validatePassword() {
            var password = document.getElementById('passwordInput').value;

            // Validasi password
            if (password === ownerPassword) {
                tempInputData.inputField.value = tempInputData.newValue;
            } else {
                tempInputData.inputField.value = tempInputData.originalValue;
                alert('Password salah!');
            }

            resetModal();
        }

        function resetModal() {
            var modal = document.getElementById('passwordModal');
            modal.style.display = 'none';
            document.getElementById('passwordInput').value = ''; // Reset input password
            tempInputData = { inputField: null, newValue: null, originalValue: null };
            modalActive = false;
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
                var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + no).value) || 0;
                var profit = parseFloat(document.getElementById('profit_' + no).value) || 0;
                var hargaSementara = (hargaPokok * profit) / 100 + hargaPokok;
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = hargaSementara.toFixed(0);
                }

                if (parseFloat(inputField.value) < hargaPokok) {
                    alert('HARGA SEMENTARA TIDAK BOLEH LEBIH KECIL');
                    inputField.value = originalValue;
                    document.getElementById('checkbox_select_' + no).checked = false;
                    document.getElementById('td_harga_sementara_' + no).style.backgroundColor = 'white';
                    return;
                }

                // Ambil nama produk dari input yang ditekan
                var namaProduk = inputField.dataset.nama;

                // Tandai semua td dengan nama produk sama
                const semuaInput = document.querySelectorAll('input[data-nama="' + namaProduk + '"]');

                semuaInput.forEach(function(input) {
                    const index = input.dataset.index;
                    const td = document.getElementById('td_harga_sementara_' + index);
                    const checkbox = document.getElementById('checkbox_select_' + index);
                    if (td) {
                        td.style.backgroundColor = 'red';
                    }
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        }

        function handleBlurSementara(no, originalValue) {
            var inputField = document.getElementById('harga_sementara_' + no);
            var profit = parseFloat(document.getElementById('profit_' + no).value) || 0;
            var hargaSementara = (hargaPokok * profit) / 100 + hargaPokok;

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = hargaSementara.toFixed(0);
            }
        }

        function updateHargaSementara2(no) {
            var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + no).value) || 0;
            var hargaSementara = parseFloat(document.getElementById('harga_sementara_' + no).value) || 0;

            // Menghitung harga_sementara
            var hitung = hargaSementara - hargaPokok;
            var hargaSementara = (hitung / hargaPokok) * 100;

            // Memperbarui nilai harga_sementara berdasarkan indeks
            document.getElementById('profit_' + no).value = hargaSementara.toFixed(2); // Menggunakan 2 desimal
        }

        // function updateProfitPokok(index, id) {
        //     var hargaPokok = parseFloat(document.getElementById('harga_pokok_' + index).value) || 0;
        //     var hargaLama = parseFloat(document.getElementById('harga_lama_' + index).value) || 0;

        //     // Menghindari pembagian dengan nol
        //     if (hargaLama === 0) {
        //         document.getElementById('profit_pokok_' + index).innerHTML = '0.00';
        //         return;
        //     }

        //     // Menghitung persentase profit
        //     var profitPersen = ((hargaPokok - hargaLama) / hargaLama) * 100;

        //     // Update tampilan profit
        //     document.getElementById('profit_pokok_' + index).innerHTML = profitPersen.toFixed(2);

        //     // Jika ingin juga menghitung harga_sementara, pastikan rumusnya sesuai
        //     var profit = parseFloat(document.getElementById('profit_' + index).value) || 0;
        //     var hargaSementara = hargaPokok + (hargaPokok * profit / 100);

        //     // Update input harga_sementara
        //     document.getElementById('harga_sementara_' + index).value = Math.ceil(hargaSementara);
        // }

        function updateProfitPokok(index, id) {
            var inputCurrent = document.getElementById('harga_pokok_' + index);
            var hargaPokok = parseFloat(inputCurrent.value) || 0;
            var hargaLama = parseFloat(document.getElementById('harga_lama_' + index).value) || 0;

            if (hargaLama === 0) {
                document.getElementById('profit_pokok_' + index).innerHTML = '0.00';
                return;
            }

            // Hitung profit persen untuk current index
            var profitPersen = ((hargaPokok - hargaLama) / hargaLama) * 100;
            document.getElementById('profit_pokok_' + index).innerHTML = profitPersen.toFixed(2);

            // Hitung harga sementara
            var profit = parseFloat(document.getElementById('profit_' + index).value) || 0;
            var hargaSementara = hargaPokok + (hargaPokok * profit / 100);
            document.getElementById('harga_sementara_' + index).value = Math.ceil(hargaSementara);

            // Ambil nama produk dari atribut data-nama pada input harga_pokok
            var namaProduk = inputCurrent.dataset.nama;
            var hargaLamaAsal = parseFloat(inputCurrent.dataset.hargaLama || 1);
            var rasio = hargaPokok / hargaLamaAsal;

            // Update semua input harga_pokok lain dengan nama produk sama (kecuali yang sedang diubah)
            var inputs = document.querySelectorAll('input[id^="harga_pokok_"][data-nama="' + namaProduk + '"]');
            inputs.forEach(function(targetInput) {
                if (targetInput !== inputCurrent) {
                    var hargaLamaTarget = parseFloat(targetInput.dataset.hargaLama || 1);
                    var hasilBaru = Math.ceil(hargaLamaTarget * rasio);
                    targetInput.value = hasilBaru;

                    // Index target input diambil dari id, misal "harga_pokok_3" → 3
                    var targetIndex = targetInput.id.split('_')[2];

                    // Update profit dan harga sementara untuk input target
                    if (hargaLamaTarget === 0) {
                        document.getElementById('profit_pokok_' + targetIndex).innerHTML = '0.00';
                        return;
                    }
                    var profitPersenTarget = ((hasilBaru - hargaLamaTarget) / hargaLamaTarget) * 100;
                    document.getElementById('profit_pokok_' + targetIndex).innerHTML = profitPersenTarget.toFixed(2);

                    var profitTarget = parseFloat(document.getElementById('profit_' + targetIndex).value) || 0;
                    var hargaSementaraTarget = hasilBaru + (hasilBaru * profitTarget / 100);
                    document.getElementById('harga_sementara_' + targetIndex).value = Math.ceil(hargaSementaraTarget);
                }
            });
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

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const productName = this.dataset.nama;
                    const isChecked = this.checked;
                    const tdId = this.dataset.tdId;
                    const tdElement = document.getElementById(tdId);

                    if (tdElement) {
                        if (isChecked) {
                            tdElement.style.backgroundColor = 'red';
                        } else {
                            tdElement.style.backgroundColor = 'white';
                        }
                    }

                    // Loop through all checkboxes and match by data-nama
                    checkboxes.forEach(cb => {
                        if (cb.dataset.nama === productName) {
                            cb.checked = isChecked;

                            const matchingTdId = cb.dataset.tdId;
                            const matchingTdElement = document.getElementById(matchingTdId);

                            if (matchingTdElement) {
                                if (isChecked) {
                                    matchingTdElement.style.backgroundColor = 'red';
                                } else {
                                    matchingTdElement.style.backgroundColor = 'white';
                                }
                            }
                        }
                    });
                });
            });
        });
    </script>
@endsection