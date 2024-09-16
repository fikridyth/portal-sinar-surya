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
                                    <select id="bank-select" class="bank-select btn-block">
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
                            <div class="d-flex mb-2 align-items-center">
                                <!-- Bagian Link -->
                                <div class="d-flex flex-column" style="width: 20%;">
                                    <a href="{{ route('index') }}" id="button-gabung" class="btn btn-sm btn-danger mb-2 disabled-link">PROSES GABUNG</a>
                                    <a href="#" id="button-bayar" class="btn btn-sm btn-danger mb-2 disabled-link">BAYAR CABANG</a>
                                    <a href="#" id="button-cetak" class="btn btn-sm btn-danger mb-2 disabled-link">CETAK GIRO</a>
                                    <a href="#" id="button-hapus" class="btn btn-sm btn-danger mb-2 disabled-link">HAPUS BAYAR</a>
                                    <form id="delete-form" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" id="delete-id">
                                    </form>
                                </div>
                                
                                <!-- Bagian Tabel -->
                                <div class="flex-grow-1">
                                    <table class="table table-bordered mx-5" style="width: 90%">
                                        <thead>
                                            <tr>
                                                <th>DOKUMEN</th>
                                                <th>TANGGAL BAYAR</th>
                                                <th>JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="nomor-bukti">
                                                <td id="tanggal-bukti"></td>
                                                <td id="jumlah-bukti"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    {{-- <tr>
                                        <th colspan="5" class="text-center">DAFTAR SUPPLIER YANG SUDAH DIBUATKAN P.O</th>
                                    </tr> --}}
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">JUMLAH RP</th>
                                        <th class="text-center">NOMOR GIRO</th>
                                        <th class="text-center">V</th>
                                        <th class="text-center">GBG</th>
                                        <th class="text-center">KFM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $previousIdParent = null;
                                    @endphp
                                    @foreach ($pembayarans as $index => $pmb)
                                        <tr>
                                            {{-- <td class="text-center">{{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}</td> --}}
                                            <td class="text-center" style="color: <?= $pmb->nomor_giro !== null ? 'red' : 'black'; ?>">
                                                @if ($pmb->id_supplier !== $previousIdParent)
                                                    {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                    @php $counter++; @endphp
                                                @else
                                                    {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                @endif
                                            </td>
                                            <td>{{ $pmb->supplier->nama }}</td>
                                            <td class="text-end">{{ number_format($pmb->grand_total) }}</td>
                                            <td class="keterangan_bayar">{{ $pmb->nomor_giro }}</td>
                                            <td class="text-center"><input type="checkbox" class="input-check" id="input-check-{{ $index }}" data-id="{{ $pmb->id }}" data-nomor="{{ $pmb->nomor_bukti }}" data-tanggal="{{ $pmb->date }}" data-jumlah="{{ number_format($pmb->grand_total) }}"></td>
                                            <td class="text-center"><input type="checkbox" class="input-gabung" id="input-gabung-{{ $index }}"></td>
                                            <td class="text-center"><input type="checkbox" class="input-konfirmasi" id="input-konfirmasi-{{ $index }}"></td>
                                        </tr>
                                        @if ($pmb->id_supplier)
                                            @php $previousIdParent = $pmb->id_supplier; @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
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
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#bank-select').select2();

            $('#bank-select').on('select2:select', function(e) {
                var selectedOption = e.params.data.element;
                var noRekening = $(selectedOption).data('no-rekening');
                $('#no-rekening').val(noRekening);
            });

            $('.input-check').change(function () {
                const id = $(this).data('id');
                const nomorBukti = $(this).data('nomor');
                const tanggalBukti = $(this).data('tanggal');
                const jumlahBukti = $(this).data('jumlah');
                const keteranganBayar = $(this).closest('tr').find('.keterangan_bayar').text().trim(); // Ambil nilai keterangan bayar
                if ($(this).is(':checked')) {
                    // other table
                    $('#nomor-bukti').text(nomorBukti);
                    $('#tanggal-bukti').text(tanggalBukti);
                    $('#jumlah-bukti').text(jumlahBukti);

                    // link
                    if (keteranganBayar === '') {
                        // keterangan bayar null, aktifkan button-bayar dan nonaktifkan button-hapus
                        $('#button-bayar').removeClass('disabled-link');
                        $('#button-bayar').attr('href', `{{ route('pembayaran.show', '') }}/${id}`);
                        
                        $('#button-cetak').addClass('disabled-link');
                        $('#button-cetak').attr('href', '#');

                        $('#button-hapus').addClass('disabled-link');
                        $('#button-hapus').attr('href', '#');
                    } else {
                        // keterangan bayar tidak null, aktifkan button-hapus dan nonaktifkan button-bayar
                        $('#button-bayar').addClass('disabled-link');
                        $('#button-bayar').attr('href', '#');
                        
                        $('#button-cetak').removeClass('disabled-link');
                        $('#button-cetak').attr('href', `{{ route('pembayaran.cetak-payment', '') }}/${id}`);
                        
                        $('#button-hapus').removeClass('disabled-link');
                        $('#button-hapus').attr('href', '#'); // Jangan gunakan href pada tombol hapus, gunakan JavaScript untuk meng-handle klik
                        $('#button-hapus').data('id', id); // Simpan ID untuk penghapusan
                    }

                    // other checkbox
                    $('input[type="checkbox"]').not(this).prop('disabled', true);
                } else {
                    $('#nomor-bukti').text('');
                    $('#tanggal-bukti').text('');
                    $('#jumlah-bukti').text('');

                    $('#button-bayar').addClass('disabled-link');
                    $('#button-bayar').attr('href', '#');
                    $('#button-cetak').addClass('disabled-link');
                    $('#button-cetak').attr('href', '#');
                    $('#button-hapus').addClass('disabled-link');
                    $('#button-hapus').attr('href', '#');

                    $('input[type="checkbox"]').prop('disabled', false);
                }
            });

            $('#button-hapus').click(function (e) {
                e.preventDefault(); // Mencegah navigasi default

                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    // Set ID ke formulir dan kirim formulir
                    var id = $(this).data('id');
                    // Set the ID to the hidden input field
                    $('#delete-id').val(id);
                    // Set the action URL of the form
                    $('#delete-form').attr('action', `{{ route('pembayaran.destroy-payment', '') }}/${id}`);
                    // Submit the form
                    $('#delete-form').submit();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const bankSelect = document.querySelector('.bank-select');
            const rekeningRadios = document.querySelectorAll('input[name="rekening"]');

            function updateTable() {
                const idBank = bankSelect.value;
                const rekening = Array.from(rekeningRadios).find(radio => radio.checked)?.value;

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
        });
    </script>
@endsection
