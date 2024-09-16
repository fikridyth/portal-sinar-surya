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
                                        <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-danger">DEFAULT BANK</a>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">
                                        <label for="">BANK</label>
                                    </div>
                                    <div class="col-8">
                                        <select id="select-banks" class="product-select btn-block">
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}" data-no-rekening="{{ $bank->no_rekening }}">{{ $bank->nama }}</option>
                                            @endforeach
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
                                        <input type="radio" id="cek" name="rekening" value="cek">
                                        <label for="cek" style="color: white;">CEK</label>
                                    </div>
                                    <div class="col-2" style="background-color: brown;">
                                        <input type="radio" id="giro" name="rekening" value="giro">
                                        <label for="giro" style="color: white;">GIRO</label>
                                    </div>
                                    <div class="col-3" style="background-color: brown;">
                                        <input type="radio" id="transfer" name="rekening" value="transfer">
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
                                                <input type="text" class="btn-block" name="type_payment">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-3"></div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio0" name="rekening" value="0" onclick="updateTable('0')">
                                        <label for="cek" style="color: white;">0</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio1" name="rekening" value="1" onclick="updateTable('1')">
                                        <label for="giro" style="color: white;">1</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio2" name="rekening" value="2" onclick="updateTable('2')">
                                        <label for="transfer" style="color: white;">2</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio3" name="rekening" value="3" onclick="updateTable('3')">
                                        <label for="transfer" style="color: white;">3</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio4" name="rekening" value="4" onclick="updateTable('4')">
                                        <label for="transfer" style="color: white;">4</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio5" name="rekening" value="5" onclick="updateTable('5')">
                                        <label for="transfer" style="color: white;">5</label>
                                    </div>
                                    <div class="col-0-7 text-center" style="background-color: darkblue;">
                                        <input type="radio" id="radio6" name="rekening" value="6" onclick="updateTable('6')">
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
                                            <tbody>
                                                <tr>
                                                    <input type="text" id="amount-total" value="{{ $pembayaran->grand_total }}" hidden>
                                                    <input type="text" name="nomor_bukti" value="{{ $pembayaran->nomor_bukti }}" hidden>
                                                    <input type="text" hidden id="amount1-1" name="payment" value="{{ $pembayaran->grand_total }}">
                                                    <td class="text-end" id="amount1">{{ number_format($pembayaran->grand_total) }}</td>
                                                    <td>{{ $pembayaran->nomor_giro ?? 'TUNAI' }}</td>
                                                    <td><input class="form-check-input" style="opacity: 1;" type="checkbox" disabled checked></td>
                                                </tr>
                                                <tr>
                                                    <input type="text" hidden id="amount2-1" name="other_income">
                                                    <td class="text-end" id="amount2">0</td>
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
            let amount1, amount2;

            switch(value) {
                case '0':
                    // Extract last zero digits
                    let lastZeroDigits = totalAmount % 1;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastZeroDigits;
                    amount2 = lastZeroDigits;
                    break;
                case '1':
                    // Extract last one digits
                    let lastOneDigits = totalAmount % 10;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastOneDigits;
                    amount2 = lastOneDigits;
                    break;
                case '2':
                    // Extract last two digits
                    let lastTwoDigits = totalAmount % 100;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastTwoDigits;
                    amount2 = lastTwoDigits;
                    break;
                case '3':
                    // Extract last three digits
                    let lastThreeDigits = totalAmount % 1000;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastThreeDigits;
                    amount2 = lastThreeDigits;
                    break;
                case '4':
                    // Extract last four digits
                    let lastFourDigits = totalAmount % 10000;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastFourDigits;
                    amount2 = lastFourDigits;
                    break;
                case '5':
                    // Extract last five digits
                    let lastFiveDigits = totalAmount % 100000;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastFiveDigits;
                    amount2 = lastFiveDigits;
                    break;
                case '6':
                    // Extract last six digits
                    let lastSixDigits = totalAmount % 1000000;
                    // Calculate amount1 and amount2
                    amount1 = totalAmount - lastSixDigits;
                    amount2 = lastSixDigits;
                    break;
            }

            document.getElementById('amount1').textContent = new Intl.NumberFormat('en-US').format(amount1);
            document.getElementById('amount1-1').value = amount1;
            document.getElementById('amount2').textContent = new Intl.NumberFormat('en-US').format(amount2);
            document.getElementById('amount2-1').value = amount2;
        }
    </script>
@endsection
