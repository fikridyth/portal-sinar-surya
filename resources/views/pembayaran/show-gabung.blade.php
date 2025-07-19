@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <form action="{{ route('pembayaran.update-gabung', $ids) }}" method="POST" class="form">
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
                                        <input type="text" readonly id="date-input" class="form-control readonly-input"
                                            value="{{ now()->format('d/m/Y') }}">
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
                                            <option value="{{ $bank->id }}" data-no-rekening="{{ $bank->no_rekening }}">
                                                {{ $bank->nama }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">
                                        <label for="">NO REKENING</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" readonly class="btn-block readonly-input" id="no-rekening"
                                            value="{{ $bank->no_rekening }}">
                                    </div>
                                </div>
                                <div class="row mb-1 mt-3">
                                    <div class="col-2"></div>
                                    <div class="col-2" style="background-color: brown;">
                                        <input type="radio" id="cek" name="rekening" value="CK">
                                        <label for="cek" style="color: white;">CEK</label>
                                    </div>
                                    <div class="col-2" style="background-color: brown;">
                                        <input type="radio" id="giro" checked name="rekening" value="GR">
                                        <label for="giro" style="color: white;">GIRO</label>
                                    </div>
                                    <div class="col-3" style="background-color: brown;">
                                        <input type="radio" id="transfer" name="rekening" value="TR">
                                        <label for="transfer" style="color: white;">TRANSFER</label>
                                    </div>
                                </div>
                                {{-- <hr>
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
                                </table> --}}
                                <hr>
                                <div class="d-flex justify-content-center" style="margin-top: 27%">
                                    <a href="{{ route('index') }}" class="btn btn-primary mx-4">SELESAI</a>
                                </div>
                                <hr>
                                <h6 class="text-center">BUKU CEK/GIRO</h6>
                                <div style="overflow-x: auto; height: 290px; border: 1px solid #ccc;">
                                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                        <thead>
                                            <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                <th class="text-center">DARI NOMOR</th>
                                                <th class="text-center">SAMPAI NOMOR</th>
                                                <th class="text-center">SISA</th>
                                                <th class="text-center">RUSAK</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data-tbody">
                                        </tbody>
                                    </table>
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
                                                <input type="text" readonly class="readonly-input btn-block"
                                                    name="supplier" value="{{ $pembayaran[0]->supplier->nama }}">
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4">
                                                <label for="" style="font-size: 15px">JUMLAH CEK/GIRO</label>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" autofocus class="btn-block" required min="0"
                                                    max="2" autocomplete="off" id="jumlah-check"
                                                    name="type_payment">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-1"></div>
                                    <div class="col-0-7"></div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio0" name="comma" value="0"
                                            onclick="updateTable('0')">
                                        <label for="cek" style="color: white;">0</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio1" name="comma" value="1"
                                            onclick="updateTable('1')">
                                        <label for="giro" style="color: white;">1</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio2" name="comma" value="2"
                                            onclick="updateTable('2')">
                                        <label for="transfer" style="color: white;">2</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio3" name="comma" value="3"
                                            onclick="updateTable('3')">
                                        <label for="transfer" style="color: white;">3</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio4" name="comma" value="4"
                                            onclick="updateTable('4')">
                                        <label for="transfer" style="color: white;">4</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio5" name="comma" value="5"
                                            onclick="updateTable('5')">
                                        <label for="transfer" style="color: white;">5</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio6" name="comma" value="6"
                                            onclick="updateTable('6')">
                                        <label for="transfer" style="color: white;">6</label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-1"></div>
                                    <div class="col-8">
                                        <div style="overflow-x: auto; height: 160px; border: 1px solid #ccc; margin-left: 20px;">
                                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                                <thead>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                        <th class="text-center">JUMLAH</th>
                                                        <th class="text-center">NOMOR GIRO</th>
                                                        <th class="text-center">V</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tunai-table" style="display: none;">
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <input type="text" id="amount-total" value="{{ $pembayaran->sum('grand_total') }}" hidden>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" id="value1" name="tunai_payment" style="width: 100px;" 
                                                            value="{{ number_format($pembayaran->sum('grand_total'), 0, ',', '.') }}" oninput="updateValue2()" onkeyup="formatInputNumber(this)">
                                                        </td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">TUNAI</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"type="checkbox" disabled checked></td>
                                                    </tr>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" class="readonly-input" id="value2" name="tunai_other_income" readonly style="width: 100px;" value="0" onkeyup="formatInputNumber(this)"></td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">OTHER INCOME</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"type="checkbox" disabled checked></td>
                                                    </tr>
                                                </tbody>
                                                <tbody id="giro-table" style="display: none;">
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" id="value3" name="giro_payment" style="width: 100px;" 
                                                            value="{{ number_format($pembayaran->sum('grand_total'), 0, ',', '.') }}" oninput="updateValue4()" onkeyup="formatInputNumber(this)">
                                                        </td>
                                                        <td class="text-center" style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                            <select name="nomor_giro" class="giro-select" style="width: 110px;">
                                                                <option value="{{ $giros[0]->nomor }}" selected>{{ $giros[0]->nomor }}</option>
                                                                @foreach ($giros->slice(1) as $giro)
                                                                    <option value="{{ $giro->nomor }}" >{{ $giro->nomor }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="date" name="date_last" style="width: 100px;"></td>
                                                    </tr>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" id="value4" name="giro_tunai_payment" style="width: 100px;" 
                                                            value="0" oninput="updateValue5()" onkeyup="formatInputNumber(this)">
                                                        </td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">TUNAI</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"
                                                                type="checkbox" disabled checked></td>
                                                    </tr>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" class="readonly-input" id="value5" name="giro_other_income" readonly style="width: 100px;" value="0"></td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">OTHER INCOME</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"
                                                                type="checkbox" disabled checked></td>
                                                    </tr>
                                                </tbody>
                                                <tbody id="transfer-table" style="display: none;">
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" id="value6" name="transfer_payment" style="width: 100px;" 
                                                            value="{{ number_format($pembayaran->sum('grand_total'), 0, ',', '.') }}" oninput="updateValue7()" onkeyup="formatInputNumber(this)">
                                                        </td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">TRANSFER</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"
                                                            type="checkbox" disabled checked></td>
                                                    </tr>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" id="value7" name="transfer_tunai_payment" style="width: 100px;" 
                                                            value="0" oninput="updateValue8()" onkeyup="formatInputNumber(this)">
                                                        </td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">TUNAI</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"
                                                                type="checkbox" disabled checked></td>
                                                    </tr>
                                                    <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input type="text" class="readonly-input" id="value8" name="transfer_other_income" readonly style="width: 100px;" value="0"></td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;">OTHER INCOME</td>
                                                        <td style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 2;" class="text-center"><input class="form-check-input" style="opacity: 1;"
                                                                type="checkbox" disabled checked></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label for="" style="font-size: 12px;">MATERAI</label>
                                        <input type="text" class="text-end btn-block readonly-input" readonly value="{{ number_format($pembayaran[0]->supplier->materai, 0) ?? 0 }}" style="font-size: 12px;">
                                        <label for="" style="font-size: 12px;">BEBAN MATERAI</label>
                                        <input type="text" class="text-end btn-block" style="font-size: 12px;" name="beban_materai" autocomplete="off" onkeyup="formatInputNumber(this)">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-1"></div>
                                    <div class="col-3">
                                        <input type="text" class="btn-block readonly-input text-end"
                                            value="{{ number_format($pembayaran->sum('grand_total')) }}" readonly>
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
                                <div style="overflow-x: auto; height: 290px; border: 1px solid #ccc;">
                                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                        <thead>
                                            <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
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

            $(`.giro-select`).select2({
                placeholder: '---Select Giro---'
            });
        });

        function updateTable(value) {
            let totalAmount = document.getElementById('amount-total').value;
            let value1, value2, value3, value4, value6, value7;

            switch(value) {
                case '0':
                    // Extract last zero digits
                    let lastZeroDigits = totalAmount % 1;
                    value1 = totalAmount - lastZeroDigits;
                    value2 = lastZeroDigits;
                    value3 = totalAmount - lastZeroDigits;
                    value4 = lastZeroDigits;
                    value6 = totalAmount - lastZeroDigits;
                    value7 = lastZeroDigits;
                    break;
                case '1':
                    // Extract last one digits
                    let lastOneDigits = totalAmount % 10;
                    value1 = totalAmount - lastOneDigits;
                    value2 = lastOneDigits;
                    value3 = totalAmount - lastOneDigits;
                    value4 = lastOneDigits;
                    value6 = totalAmount - lastOneDigits;
                    value7 = lastOneDigits;
                    break;
                case '2':
                    // Extract last two digits
                    let lastTwoDigits = totalAmount % 100;
                    value1 = totalAmount - lastTwoDigits;
                    value2 = lastTwoDigits;
                    value3 = totalAmount - lastTwoDigits;
                    value4 = lastTwoDigits;
                    value6 = totalAmount - lastTwoDigits;
                    value7 = lastTwoDigits;
                    break;
                case '3':
                    // Extract last three digits
                    let lastThreeDigits = totalAmount % 1000;
                    value1 = totalAmount - lastThreeDigits;
                    value2 = lastThreeDigits;
                    value3 = totalAmount - lastThreeDigits;
                    value4 = lastThreeDigits;
                    value6 = totalAmount - lastThreeDigits;
                    value7 = lastThreeDigits;
                    break;
                case '4':
                    // Extract last four digits
                    let lastFourDigits = totalAmount % 10000;
                    value1 = totalAmount - lastFourDigits;
                    value2 = lastFourDigits;
                    value3 = totalAmount - lastFourDigits;
                    value4 = lastFourDigits;
                    value6 = totalAmount - lastFourDigits;
                    value7 = lastFourDigits;
                    break;
                case '5':
                    // Extract last five digits
                    let lastFiveDigits = totalAmount % 100000;
                    value1 = totalAmount - lastFiveDigits;
                    value2 = lastFiveDigits;
                    value3 = totalAmount - lastFiveDigits;
                    value4 = lastFiveDigits;
                    value6 = totalAmount - lastFiveDigits;
                    value7 = lastFiveDigits;
                    break;
                case '6':
                    // Extract last six digits
                    let lastSixDigits = totalAmount % 1000000;
                    value1 = totalAmount - lastSixDigits;
                    value2 = lastSixDigits;
                    value3 = totalAmount - lastSixDigits;
                    value4 = lastSixDigits;
                    value6 = totalAmount - lastSixDigits;
                    value7 = lastSixDigits;
                    break;
            }

            document.getElementById('value1').value = value1 >= 0 ? value1.toLocaleString('id-ID') : 0;
            document.getElementById('value2').value = value2 >= 0 ? value2.toLocaleString('id-ID') : 0;
            document.getElementById('value3').value = value3 >= 0 ? value3.toLocaleString('id-ID') : 0;
            document.getElementById('value4').value = value4 >= 0 ? value4.toLocaleString('id-ID') : 0;
            document.getElementById('value6').value = value6 >= 0 ? value6.toLocaleString('id-ID') : 0;
            document.getElementById('value7').value = value7 >= 0 ? value7.toLocaleString('id-ID') : 0;
            document.getElementById('value5').value = 0;
            document.getElementById('value8').value = 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const bankSelect = document.querySelector('.bank-select');
            const rekeningRadios = document.querySelectorAll('input[name="rekening"]');

            function updateTable() {
                const idBank = bankSelect.value;
                const rekening = Array.from(rekeningRadios).find(radio => radio.checked)?.value;
                console.log(idBank, rekening)

                // if (idBank && rekening) {
                fetch(`/master/get-bayar-giro?id_bank=${idBank}&rekening=${rekening}`)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('data-tbody');
                        tbody.innerHTML = ''; // Clear existing data

                        data.dataHeader.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.kode} ${item.dari}</td>
                                <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.kode} ${item.sampai}</td>
                                <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.remainingAmount}</td>
                                <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.rusakGiro}</td>
                            `;
                            tbody.appendChild(row);
                        });

                        
                        const tbody2 = document.getElementById('data-tbody-2');
                        tbody2.innerHTML = ''; // Clear existing data

                        data.dataDetail.forEach(item => {
                            const row2 = document.createElement('tr');
                            if (item.increment !== undefined && item.increment !== null) {
                                row2.innerHTML = `
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.nomor}</td>
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.tanggal_akhir ?? 'Belum Terpakai'}</td>
                                    <td class="text-end" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${number_format(item.jumlah)}</td>
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${String(item.increment).padStart(3, '0')}</td>
                                `;
                            } else {
                                let flagText = '';
                                if (item.flag === 3) {
                                    flagText = 'RUSAK';
                                } else if (item.flag === 5) {
                                    flagText = 'RESERVE';
                                }
                                row2.innerHTML = `
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.nomor}</td>
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${item.tanggal_akhir ?? 'Belum Terpakai'}</td>
                                    <td class="text-end" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${number_format(item.jumlah)}</td>
                                    <td class="text-center" style="font-size: 14px; padding: 10px 20px; margin: 0; line-height: 0.5;">${flagText}</td>
                                `;
                            }
                            tbody2.appendChild(row2);
                        });

                        function number_format(number) {
                            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
                // }
            }

            updateTable();

            bankSelect.addEventListener('change', updateTable);
            rekeningRadios.forEach(radio => radio.addEventListener('change', updateTable));

            // Munculkan Tabel Jumlah
            const jumlahCheck = document.getElementById('jumlah-check');
            const tunaiTable = document.getElementById('tunai-table');
            const giroTable = document.getElementById('giro-table');
            const transferTable = document.getElementById('transfer-table');

            jumlahCheck.addEventListener('input', function() {
                const amount = parseFloat(this.value);

                if (amount === 0) {
                    tunaiTable.style.display = 'table-row-group';
                    tunaiTable.style.borderBottom = '1px solid black';
                    giroTable.style.display = 'none';
                    transferTable.style.display = 'none';
                } else if (amount === 1) {
                    tunaiTable.style.display = 'none';
                    giroTable.style.display = 'table-row-group';
                    giroTable.style.borderBottom = '1px solid black';
                    transferTable.style.display = 'none';
                } else if (amount === 2) {
                    tunaiTable.style.display = 'none';
                    giroTable.style.display = 'none';
                    transferTable.style.display = 'table-row-group';
                    transferTable.style.borderBottom = '1px solid black';
                } else {
                    tunaiTable.style.display = 'none';
                    giroTable.style.display = 'none';
                    transferTable.style.display = 'none';
                }
            });
        });
        
        function updateValue2() {
            const value1 = document.getElementById('value1').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const grandTotal = {{ $pembayaran->sum('grand_total') }};
            const parsedValue1 = parseInt(value1) || 0;
            const value2 = grandTotal - parsedValue1; // Konversi ke integer

            document.getElementById('value2').value = value2 >= 0 ? value2.toLocaleString('id-ID') : 0; // Format nilai
        }

        function updateValue4() {
            const value3 = document.getElementById('value3').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const grandTotal = {{ $pembayaran->sum('grand_total') }};
            const parsedValue3 = parseInt(value3) || 0;
            const value4 = grandTotal - parsedValue3; // Konversi ke integer

            document.getElementById('value4').value = value4 >= 0 ? value4.toLocaleString('id-ID') : 0; // Format nilai
            document.getElementById('value5').value = 0; // Reset value5
            updateValue5(); // Panggil updateValue5
        }

        function updateValue5() {
            const value3 = document.getElementById('value3').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const value4 = document.getElementById('value4').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const grandTotal = {{ $pembayaran->sum('grand_total') }};
            const parsedValue3 = parseInt(value3) || 0;
            const parsedValue4 = parseInt(value4) || 0;
            const value5 = grandTotal - parsedValue3 - parsedValue4; // Konversi ke integer

            document.getElementById('value5').value = value5 >= 0 ? value5.toLocaleString('id-ID') : 0; // Format nilai
        }

        function updateValue7() {
            const value6 = document.getElementById('value6').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const grandTotal = {{ $pembayaran->sum('grand_total') }};
            const parsedValue6 = parseInt(value6) || 0;
            const value7 = grandTotal - parsedValue6; // Konversi ke integer

            document.getElementById('value7').value = value7 >= 0 ? value7.toLocaleString('id-ID') : 0; // Format nilai
            document.getElementById('value8').value = 0; // Reset value5
            updateValue5(); // Panggil updateValue5
        }

        function updateValue8() {
            const value6 = document.getElementById('value6').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const value7 = document.getElementById('value7').value.replace(/\D/g, ''); // Hapus karakter non-digit
            const grandTotal = {{ $pembayaran->sum('grand_total') }};
            const parsedValue6 = parseInt(value6) || 0;
            const parsedValue7 = parseInt(value7) || 0;
            const value8 = grandTotal - parsedValue6 - parsedValue7; // Konversi ke integer

            document.getElementById('value8').value = value8 >= 0 ? value8.toLocaleString('id-ID') : 0; // Format nilai
        }
    </script>
@endsection
