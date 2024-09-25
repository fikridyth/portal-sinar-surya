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
                                        <input type="text" readonly class="btn-block readonly-input" id="no-rekening" value="{{ $bank->no_rekening }}">
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
                                    <a href="{{ route('index') }}" class="btn btn-primary mx-4">SELESAI</a>
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
                                    <div class="col-1"></div>
                                    <div class="col-0-7"></div>
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
                                    <div class="col-1"></div>
                                    <div class="col-8">
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
                                                    <td class="text-end"><input type="number" id="value1" name="tunai_payment" max="{{ $pembayaran->grand_total }}" style="width: 100px;" value="{{ $pembayaran->grand_total }}" oninput="updateValue2()"></td>
                                                    <td>TUNAI</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><input type="number" class="readonly-input" id="value2" name="tunai_other_income" readonly style="width: 100px;" value="0"></td>
                                                    <td>OTHER INCOME</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                            </tbody>
                                            <tbody id="giro-table" style="display: none;">
                                                <tr>
                                                    <td class="text-end"><input type="number" id="value3" name="giro_payment" max="{{ $pembayaran->grand_total }}" style="width: 100px;" value="{{ $pembayaran->grand_total }}" oninput="updateValue4()"></td>
                                                    <td>
                                                        <select name="nomor_giro" style="width: 100px;">
                                                            <option value="{{ $giros[0]->nomor }}" selected>{{ $giros[0]->nomor }}</option>
                                                            @foreach ($giros->slice(1) as $giro)
                                                                <option value="{{ $giro->nomor }}" >{{ $giro->nomor }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="date" name="date_last" style="width: 100px;"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><input type="number" id="value4" name="giro_tunai_payment" style="width: 100px;" value="0" oninput="updateValue5()"></td>
                                                    <td>TUNAI</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><input type="number" class="readonly-input" id="value5" name="giro_other_income" readonly style="width: 100px;" value="0"></td>
                                                    <td>OTHER INCOME</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-2">
                                        <label for="" style="font-size: 12px;">MATERAI</label>
                                        <input type="text" class="text-end btn-block readonly-input" readonly value="{{ number_format($pembayaran->supplier->materai, 0) ?? 0 }}" style="font-size: 12px;">
                                        <label for="" style="font-size: 12px;">BEBAN MATERAI</label>
                                        <input type="text" class="text-end btn-block" style="font-size: 12px;" name="beban_materai" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-1"></div>
                                    <div class="col-3">
                                        <input type="text" class="btn-block readonly-input text-end" value="{{ number_format($pembayaran->grand_total) }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-3"></div>
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
            let value1, value2, value3, value4;

            switch(value) {
                case '0':
                    // Extract last zero digits
                    let lastZeroDigits = totalAmount % 1;
                    value1 = totalAmount - lastZeroDigits;
                    value2 = lastZeroDigits;
                    value3 = totalAmount - lastZeroDigits;
                    value4 = lastZeroDigits;
                    break;
                case '1':
                    // Extract last one digits
                    let lastOneDigits = totalAmount % 10;
                    value1 = totalAmount - lastOneDigits;
                    value2 = lastOneDigits;
                    value3 = totalAmount - lastOneDigits;
                    value4 = lastOneDigits;
                    break;
                case '2':
                    // Extract last two digits
                    let lastTwoDigits = totalAmount % 100;
                    value1 = totalAmount - lastTwoDigits;
                    value2 = lastTwoDigits;
                    value3 = totalAmount - lastTwoDigits;
                    value4 = lastTwoDigits;
                    break;
                case '3':
                    // Extract last three digits
                    let lastThreeDigits = totalAmount % 1000;
                    value1 = totalAmount - lastThreeDigits;
                    value2 = lastThreeDigits;
                    value3 = totalAmount - lastThreeDigits;
                    value4 = lastThreeDigits;
                    break;
                case '4':
                    // Extract last four digits
                    let lastFourDigits = totalAmount % 10000;
                    value1 = totalAmount - lastFourDigits;
                    value2 = lastFourDigits;
                    value3 = totalAmount - lastFourDigits;
                    value4 = lastFourDigits;
                    break;
                case '5':
                    // Extract last five digits
                    let lastFiveDigits = totalAmount % 100000;
                    value1 = totalAmount - lastFiveDigits;
                    value2 = lastFiveDigits;
                    value3 = totalAmount - lastFiveDigits;
                    value4 = lastFiveDigits;
                    break;
                case '6':
                    // Extract last six digits
                    let lastSixDigits = totalAmount % 1000000;
                    value1 = totalAmount - lastSixDigits;
                    value2 = lastSixDigits;
                    value3 = totalAmount - lastSixDigits;
                    value4 = lastSixDigits;
                    break;
            }

            document.getElementById('value1').value = value1;
            document.getElementById('value2').value = value2;
            document.getElementById('value3').value = value3;
            document.getElementById('value4').value = value4;
            document.getElementById('value5').value = 0;
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

        function updateValue2() {
            const value1 = document.getElementById('value1').value;
            const grandTotal = {{ $pembayaran->grand_total }};
            const value2 = grandTotal - value1;

            document.getElementById('value2').value = value2 >= 0 ? value2 : 0;
        }

        function updateValue4() {
            const value3 = document.getElementById('value3').value;
            const grandTotal = {{ $pembayaran->grand_total }};
            const value4 = grandTotal - value3;

            document.getElementById('value4').value = value4 >= 0 ? value4 : 0;
            document.getElementById('value5').value = 0;
            updateValue5();
        }

        function updateValue5() {
            const value3 = document.getElementById('value3').value;
            const value4 = document.getElementById('value4').value;
            const grandTotal = {{ $pembayaran->grand_total }};
            const value5 = grandTotal - value3 - value4;

            document.getElementById('value5').value = value5 >= 0 ? value5 : 0;
        }
    </script>
@endsection
