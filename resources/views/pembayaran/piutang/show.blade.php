@extends('main')

@section('styles')
    <style>
        .disabled-label {
            opacity: 0.7;
            color: gray; /* Mengubah warna teks menjadi abu-abu */
        }
    </style>
@endsection

@section('content')
    <div class="container mb-3 mt-n3">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex align-items-center justify-content-center">
                    <a href="{{ route('daftar-tagihan.index') }}" class="btn btn-sm btn-dark mx-2" style="color: blueviolet; font-size: 14px;">DAFTAR TAGIHAN</a>
                    <a href="{{ route('history-tagihan.index') }}" class="btn btn-sm btn-dark mx-2" style="color: blueviolet; font-size: 14px;">HISTORY TAGIHAN</a>
                </div>
                {{-- <div class="mb-4 d-flex align-items-end">
                    <div class="mx-5">
                        <div class="text-center mt-2 mb-2 h6" style="color: purple; font-size: 18px;">WILAYAH</div>
                        <label><input type="checkbox" name="wilayah" value="cibinong" onchange="toggleJenisLangganan()"> CIBINONG</label><br>
                        <label><input type="checkbox" name="wilayah" value="bogor" onchange="toggleJenisLangganan()"> BOGOR</label><br>
                        <label><input type="checkbox" name="wilayah" value="cicurug" onchange="toggleJenisLangganan()"> CICURUG</label>
                    </div>
                
                    <div class="mx-5">
                        <div class="text-center mt-2 mb-2 h6" style="color: purple; font-size: 18px;">JENIS LANGGANAN</div>
                        <label class="disabled-label"><input type="checkbox" name="jenis-langganan" value="pembelian_kredit" onchange="toggleJenisPiutang()" disabled> PEMBELIAN KREDIT</label><br>
                        <label class="disabled-label"><input type="checkbox" name="jenis-langganan" value="kanvasing" onchange="toggleJenisPiutang()" disabled> KANVASING</label><br>
                        <label class="disabled-label"><input type="checkbox" name="jenis-langganan" value="cabang" onchange="toggleJenisPiutang()" disabled> CABANG</label>
                    </div>
                
                    <div class="mx-5">
                        <div class="text-center mt-2 mb-2 h6" style="color: purple; font-size: 18px;">JENIS PIUTANG</div>
                        <label class="disabled-label"><input type="checkbox" name="jenis-piutang" value="penjualan_kredit" onchange="toggleButtonProses()" disabled> PENJUALAN KREDIT</label><br>
                        <label class="disabled-label"><input type="checkbox" name="jenis-piutang" value="penjualan_kanvasing" onchange="toggleButtonProses()" disabled> PENJUALAN KANVASING</label><br>
                        <label class="disabled-label"><input type="checkbox" name="jenis-piutang" value="bayar_faktur" onchange="toggleButtonProses()" disabled> BAYAR FAKTUR</label>
                    </div>

                    <div class="mx-5">
                        <button class="btn btn-primary mx-3" id="prosesButton" onclick="processQuery()" disabled>PROSES</button>
                        <button class="btn btn-danger mx-3" onclick="location.reload()">BATAL</button>
                    </div>
                </div> --}}

                <div class="d-flex justify-content-center">
                    <label class="mx-4">NAMA LANGGANAN</label>
                    <select class="langganan-select" id="langgananSelect" style="width: 500px;">
                        <option value="{{ $pelanggan->id }}" selected>{{ $pelanggan->nomor }} - {{ $pelanggan->nama }}</option>
                        @foreach ($langganans as $langganan)
                            <option value="{{ enkrip($langganan->id) }}">{{ $langganan->nomor }} - {{ $langganan->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <hr>
                <form action="{{ route('pembayaran-piutang.store') }}" method="POST" class="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3 d-flex align-items-center">
                        <div class="col-1">
                            <label for="">COLLECTOR</label>
                        </div>
                        <div class="col-2">
                            {{-- <select id="user-select" class="user-select btn-block" name="created_by">
                                @foreach ($listUser as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select> --}}
                            <input type="text" class="readonly-input" readonly name="created_by" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="col-6"></div>
                        <div class="col-1">
                            {{-- <label for="">NO BUKTI</label> --}}
                        </div>
                        <div class="col-2">
                            <input type="text" hidden class="readonly-input" readonly name="nomor_piutang" value="{{ $getNomor }}">
                        </div>
                    </div>
                    <table id="pembayaranTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NAMA LANGGANAN</th>
                                <th class="text-center">NO DOKUMEN</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">JUMLAH RP</th>
                                <th class="text-center">MATERAI</th>
                                <th class="text-center">V</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dataKredit->isEmpty())
                                <tr>
                                    <td class="text-center" colspan="6"><b>TIDAK ADA DATA</b></td>
                                </tr>
                            @else
                                @foreach ($dataKredit as $data)
                                    <tr>
                                        <td>{{ $pelanggan->nama }}
                                        <td class="text-center">{{ $data['nomor_bukti'] ?? $data['nomor'] }}
                                        <td class="text-center">{{ $data['date'] }}
                                        <td class="text-end">{{ number_format($data['total_with_materai'] ?? $data['total']) }}
                                        <td class="text-end">{{ number_format($data['beban_materai'] ?? 10000) }}
                                        <td class="text-center">
                                            <input type="checkbox" class="payment-checkbox" name="check[bayar][{{ $loop->index }}][nomor_bukti]" value="{{ $data['nomor_bukti'] ?? $data['nomor'] }}">
                                            <input type="hidden" name="check[bayar][{{ $loop->index }}][beban_materai]" value="{{ $data['beban_materai'] ?? 10000 }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" id="submit-button" disabled class="btn btn-primary">PROSES</button>
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
            $('#langgananSelect').change(function() {
                var langgananId = $(this).val();
                if (langgananId) {
                    var url = "{{ route('pembayaran-piutang.show', ':id') }}".replace(':id', langgananId);
                    window.location.href = url;
                }
            });
        });

        $(`.langganan-select`).select2({
            placeholder: '---Select Langganan---',
            allowClear: true
        });

        $(`.langganan-select`).on('select2:open', function(e) {
            // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
            const searchBox = $(this).data('select2').dropdown.$search[0];
            if (searchBox) {
                searchBox.focus();
            }
        });

        const checkboxes = document.querySelectorAll('.payment-checkbox');
        const submitButton = document.getElementById('submit-button');

        // Fungsi untuk memeriksa apakah ada checkbox yang dicentang
        function updateSubmitButtonState() {
            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            submitButton.disabled = !isChecked; // Aktifkan jika ada checkbox yang dicentang
        }

        // Tambahkan event listener pada semua checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSubmitButtonState);
        });

        $(document).ready(function() {
            $('#user-select').select2();
        });

        function toggleJenisLangganan() {
            const wilayahCheckboxes = document.querySelectorAll('input[name="wilayah"]');
            const jenisLanggananCheckboxes = document.querySelectorAll('input[name="jenis-langganan"]');
            
            const isChecked = Array.from(wilayahCheckboxes).some(checkbox => checkbox.checked);
            
            jenisLanggananCheckboxes.forEach(checkbox => {
                checkbox.disabled = !isChecked;
                
                // Menghapus class 'disabled-label' pada label terkait
                const label = checkbox.parentElement;
                if (!isChecked) {
                    label.classList.add('disabled-label');
                } else {
                    label.classList.remove('disabled-label');
                }
            });
        }

        function toggleJenisPiutang() {
            const jenisLanggananCheckboxes = document.querySelectorAll('input[name="jenis-langganan"]');
            const jenisPiutangCheckboxes = document.querySelectorAll('input[name="jenis-piutang"]');
            
            const isChecked = Array.from(jenisLanggananCheckboxes).some(checkbox => checkbox.checked);
            
            jenisPiutangCheckboxes.forEach(checkbox => {
                checkbox.disabled = !isChecked;
                
                // Menghapus class 'disabled-label' pada label terkait
                const label = checkbox.parentElement;
                if (!isChecked) {
                    label.classList.add('disabled-label');
                } else {
                    label.classList.remove('disabled-label');
                }
            });
        }

        function toggleButtonProses() {
            const checkboxes = document.querySelectorAll('input[name="jenis-piutang"]');
            const button = document.getElementById('prosesButton');
            
            // Enable the button if at least one checkbox is checked
            button.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
        }

        function processQuery() {
            const wilayah = Array.from(document.querySelectorAll('input[name="wilayah"]:checked')).map(el => el.value);
            // Get selected jenis langganan
            const jenisLangganan = Array.from(document.querySelectorAll('input[name="jenis-langganan"]:checked')).map(el => el.value);
            // Get selected jenis piutang
            const jenisPiutang = Array.from(document.querySelectorAll('input[name="jenis-piutang"]:checked')).map(el => el.value);

            // Check conditions
            const isWilayahBogor = wilayah.includes('bogor');
            const isJenisLanggananCabang = jenisLangganan.includes('cabang');
            const isJenisPiutangBayarFaktur = jenisPiutang.includes('bayar_faktur');
            const tableBody = document.querySelector('#pembayaranTable tbody');
            // Get the table body element
            tableBody.innerHTML = ''; // Clear previous data

            if (isWilayahBogor && isJenisLanggananCabang && isJenisPiutangBayarFaktur) {
                fetch('/api/getPembayaranData')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const listTunai = data.listTunai;
                        const listBayar = data.listBayar;

                        if (listBayar.length === 0 && listTunai.length === 0) {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center"><b>TIDAK ADA DATA</b></td>
                                </tr>
                            `;
                        } else {
                            // Process listBayar
                            listBayar.forEach((bayar, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${bayar.supplier_name}</td>
                                    <td>${bayar.nomor_bukti}</td>
                                    <td class="text-center">${bayar.date}</td>
                                    <td class="text-end">${new Intl.NumberFormat().format(bayar.total_with_materai)}</td>
                                    <td class="text-end">${new Intl.NumberFormat().format(bayar.beban_materai ?? 0)}</td>
                                    <td class="text-center"><input type="checkbox" name="check[bayar][${index}]" value="${bayar.nomor_bukti}"></td>
                                `;
                                tableBody.appendChild(row);
                            });

                            // Process listTunai
                            listTunai.forEach((tunai, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${tunai.supplier_name}</td>
                                    <td>${tunai.nomor_bukti}</td>
                                    <td class="text-center">${tunai.date}</td>
                                    <td class="text-end">${new Intl.NumberFormat().format(tunai.total_with_materai)}</td>
                                    <td class="text-end">0</td>
                                    <td class="text-center"><input type="checkbox" name="check[tunai][${index}]" value="${tunai.nomor_bukti}"></td>
                                `;
                                tableBody.appendChild(row);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center"><b>TIDAK ADA DATA</b></td>
                    </tr>
                `;
            }
        }
    </script>
@endsection
