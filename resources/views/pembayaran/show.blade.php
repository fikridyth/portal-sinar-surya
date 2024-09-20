@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN CEK/GIRO/TUNAI
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" class="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <h6>SISTEM ADMINISTRATOR</h6>
                    <hr>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="row w-100">
                            <div class="form-group col-5">
                                <div class="row mb-1">
                                    <div class="col-4">
                                        <label for="date-input">TANGGAL</label>
                                    </div>
                                    <div class="col-4">
                                        <input type="text" readonly id="date-input" class="form-control readonly-input" value="{{ now()->format('d/m/Y') }}">
                                    </div>
                                    <div class="col-4 text-right">
                                        {{-- <form action="{{ route('pembayaran.default-bank') }}" method="POST" class="form">
                                            @csrf
                                            <input type="number" hidden name="id" value="1">
                                            <button type="submit" class="btn btn-sm btn-danger">DEFAULT BANK</button>
                                        </form> --}}
                                        {{-- <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-danger">DEFAULT BANK</a> --}}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">
                                        <label for="">BANK</label>
                                    </div>
                                    <div class="col-8">
                                        <select id="bank-select" class="bank-select btn-block" disabled>
                                            <option value="{{ $bank->id }}" data-no-rekening="{{ $bank->no_rekening }}">{{ $bank->nama }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">
                                        <label for="">NO REKENING</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" readonly class="btn-block readonly-input" id="no-rekening" value="23289">
                                    </div>
                                </div>
                                <div class="row mb-1 mt-3">
                                    <div class="col-2"></div>
                                    <div class="col-2" style="background-color: brown;">
                                        <input type="radio" id="cek" name="rekening" value="CK">
                                        <label for="cek" style="color: white;">CEK</label>
                                    </div>
                                    <div class="col-2" style="background-color: brown;">
                                        <input type="radio" id="giro" name="rekening" value="GR">
                                        <label for="giro" style="color: white;">GIRO</label>
                                    </div>
                                    <div class="col-3" style="background-color: brown;">
                                        <input type="radio" id="transfer" name="rekening" value="TR">
                                        <label for="transfer" style="color: white;">TRANSFER</label>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">JUMLAH RP</th>
                                            <th class="text-center">SALDO RP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <h6 class="text-center">BUKU CEK/GIRO</h6>
                                <table id="data-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">DARI NOMOR</th>
                                            <th class="text-center">SAMPAI NOMOR</th>
                                            <th class="text-center">SISA</th>
                                            <th class="text-center">RUSAK</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-tbody">
                                    </tbody>
                                </table>
                                <hr>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                                </div>
                            </div>
                            <div class="form-group col-7">
                                <div class="row">
                                    <div class="col-2"></div>
                                    <div class="col-8">
                                        <div class="row mb-1">
                                            <div class="col-4">
                                                <label for="" style="font-size: 15px">SUPPLIER</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" readonly class="readonly-input btn-block" name="supplier" value="{{ $pembayaran->supplier->nama }}">
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4">
                                                <label for="" style="font-size: 15px">JUMLAH CEK/GIRO</label>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" class="btn-block" required min="0" max="1" autocomplete="off" id="jumlah-check" name="type_payment">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-3"></div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio0" name="comma" value="0" onclick="updateTable('0')">
                                        <label for="cek" style="color: white;">0</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio1" name="comma" value="1" onclick="updateTable('1')">
                                        <label for="giro" style="color: white;">1</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio2" name="comma" value="2" onclick="updateTable('2')">
                                        <label for="transfer" style="color: white;">2</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio3" name="comma" value="3" onclick="updateTable('3')">
                                        <label for="transfer" style="color: white;">3</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio4" name="comma" value="4" onclick="updateTable('4')">
                                        <label for="transfer" style="color: white;">4</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio5" name="comma" value="5" onclick="updateTable('5')">
                                        <label for="transfer" style="color: white;">5</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio6" name="comma" value="6" onclick="updateTable('6')">
                                        <label for="transfer" style="color: white;">6</label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-3"></div>
                                    <div class="col-6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">JUMLAH</th>
                                                    <th class="text-center">NOMOR GIRO</th>
                                                    <th class="text-center">V</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tunai-table" style="display: none;">
                                                <tr>
                                                    <input type="text" id="amount-total" value="{{ $pembayaran->grand_total }}" hidden>
                                                    <input type="text" name="nomor_bukti" value="{{ $pembayaran->nomor_bukti }}" hidden>
                                                    <input type="text" hidden id="amount1-1" name="tunai_payment" value="{{ $pembayaran->grand_total }}">
                                                    <td class="text-end" id="amount1">{{ number_format($pembayaran->grand_total) }}</td>
                                                    <td>TUNAI</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <input type="text" hidden id="amount2-1" name="tunai_other_income">
                                                    <td class="text-end" id="amount2">0</td>
                                                    <td>OTHER INCOME</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                            </tbody>
                                            <tbody id="giro-table" style="display: none;">
                                                <tr>
                                                    <input type="text" hidden name="nomor_giro" value="{{ $giro->nomor }}">
                                                    <input type="text" hidden id="amount3-1" name="giro_payment" value="{{ $pembayaran->grand_total }}">
                                                    <td class="text-end" id="amount3">{{ number_format($pembayaran->grand_total) }}</td>
                                                    <td>{{ $giro->nomor }}</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <input type="text" hidden id="amount4-1" name="giro_tunai_payment">
                                                    <td class="text-end" id="amount4">0</td>
                                                    <td>TUNAI</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <input type="text" hidden name="giro_other_income" value="0">
                                                    <td class="text-end">0</td>
                                                    <td>OTHER INCOME</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <label for="" style="font-size: 12px;">MATERAI</label>
                                        <input type="text" class="text-end btn-block" style="font-size: 12px;" value="0">
                                        <label for="" style="font-size: 12px;">BEBAN MATERAI</label>
                                        <input type="text" class="text-end btn-block" style="font-size: 12px;" value="0">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-3"></div>
                                    <div class="col-3">
                                        <input type="text" class="btn-block readonly-input text-end" value="{{ number_format($pembayaran->grand_total) }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-4"></div>
                                    <div class="col-2">
                                        <a href="{{ route('pembayaran.index') }}" class="btn btn-danger">BATAL</a>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary">PROSES</button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NOMOR GIRO</th>
                                            <th class="text-center">JATUH TEMPO</th>
                                            <th class="text-center">JUMLAH</th>
                                            <th class="text-center">KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-tbody-2">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#select-banks').select2();

            $('#select-banks').on('select2:select', function(e) {
                var selectedOption = e.params.data.element;
                var noRekening = $(selectedOption).data('no-rekening');
                $('#no-rekening').val(noRekening);
            });
        });

        function updateTable(value) {
            let totalAmount = document.getElementById('amount-total').value;
            let amount1, amount2, amount3, amount4;

            switch(value) {
                case '0':
                    // Extract last zero digits
                    let lastZeroDigits = totalAmount % 1;
                    amount1 = totalAmount - lastZeroDigits;
                    amount2 = lastZeroDigits;
                    amount3 = totalAmount - lastZeroDigits;
                    amount4 = lastZeroDigits;
                    break;
                case '1':
                    // Extract last one digits
                    let lastOneDigits = totalAmount % 10;
                    amount1 = totalAmount - lastOneDigits;
                    amount2 = lastOneDigits;
                    amount3 = totalAmount - lastOneDigits;
                    amount4 = lastOneDigits;
                    break;
                case '2':
                    // Extract last two digits
                    let lastTwoDigits = totalAmount % 100;
                    amount1 = totalAmount - lastTwoDigits;
                    amount2 = lastTwoDigits;
                    amount3 = totalAmount - lastTwoDigits;
                    amount4 = lastTwoDigits;
                    break;
                case '3':
                    // Extract last three digits
                    let lastThreeDigits = totalAmount % 1000;
                    amount1 = totalAmount - lastThreeDigits;
                    amount2 = lastThreeDigits;
                    amount3 = totalAmount - lastThreeDigits;
                    amount4 = lastThreeDigits;
                    break;
                case '4':
                    // Extract last four digits
                    let lastFourDigits = totalAmount % 10000;
                    amount1 = totalAmount - lastFourDigits;
                    amount2 = lastFourDigits;
                    amount3 = totalAmount - lastFourDigits;
                    amount4 = lastFourDigits;
                    break;
                case '5':
                    // Extract last five digits
                    let lastFiveDigits = totalAmount % 100000;
                    amount1 = totalAmount - lastFiveDigits;
                    amount2 = lastFiveDigits;
                    amount3 = totalAmount - lastFiveDigits;
                    amount4 = lastFiveDigits;
                    break;
                case '6':
                    // Extract last six digits
                    let lastSixDigits = totalAmount % 1000000;
                    amount1 = totalAmount - lastSixDigits;
                    amount2 = lastSixDigits;
                    amount3 = totalAmount - lastSixDigits;
                    amount4 = lastSixDigits;
                    break;
            }

            document.getElementById('amount1').textContent = new Intl.NumberFormat('en-US').format(amount1);
            document.getElementById('amount1-1').value = amount1;
            document.getElementById('amount2').textContent = new Intl.NumberFormat('en-US').format(amount2);
            document.getElementById('amount2-1').value = amount2;
            document.getElementById('amount3').textContent = new Intl.NumberFormat('en-US').format(amount3);
            document.getElementById('amount3-1').value = amount3;
            document.getElementById('amount4').textContent = new Intl.NumberFormat('en-US').format(amount4);
            document.getElementById('amount4-1').value = amount4;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const bankSelect = document.querySelector('.bank-select');
            const rekeningRadios = document.querySelectorAll('input[name="rekening"]');

            function updateTable() {
                const idBank = bankSelect.value;
                const rekening = Array.from(rekeningRadios).find(radio => radio.checked)?.value;
                console.log(idBank, rekening)

                if (idBank && rekening) {
                    fetch(`/master/get-bayar-giro?id_bank=${idBank}&rekening=${rekening}`)
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.getElementById('data-tbody');
                            tbody.innerHTML = ''; // Clear existing data

                            data.dataHeader.forEach(item => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="text-center">${item.kode} ${item.dari}</td>
                                    <td class="text-center">${item.kode} ${item.sampai}</td>
                                    <td class="text-center">${item.remainingAmount}</td>
                                    <td class="text-center">0</td>
                                `;
                                tbody.appendChild(row);
                            });

                            
                            const tbody2 = document.getElementById('data-tbody-2');
                            tbody2.innerHTML = ''; // Clear existing data

                            data.dataDetail.forEach(item => {
                                const row2 = document.createElement('tr');
                                row2.innerHTML = `
                                    <td class="text-center">${item.nomor}</td>
                                    <td class="text-center">${item.tanggal_akhir}</td>
                                    <td class="text-end">${number_format(item.jumlah) ?? 0}</td>
                                    <td class="text-center"></td>
                                `;
                                tbody2.appendChild(row2);
                            });

                            function number_format(number) {
                                return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            }
                        })
                    .catch(error => console.error('Error fetching data:', error));
                }
            }

            bankSelect.addEventListener('change', updateTable);
            rekeningRadios.forEach(radio => radio.addEventListener('change', updateTable));

            // Munculkan Tabel Jumlah
            const jumlahCheck = document.getElementById('jumlah-check');
            const tunaiTable = document.getElementById('tunai-table');
            const giroTable = document.getElementById('giro-table');
            
            jumlahCheck.addEventListener('input', function() {
                const amount = parseFloat(this.value);
                
                if (amount === 0) {
                    tunaiTable.style.display = 'table-row-group';
                    giroTable.style.display = 'none';
                } else if (amount === 1) {
                    tunaiTable.style.display = 'none';
                    giroTable.style.display = 'table-row-group';
                } else {
                    tunaiTable.style.display = 'none';
                    giroTable.style.display = 'none';
                }
            });
        });
    </script>
@endsection
