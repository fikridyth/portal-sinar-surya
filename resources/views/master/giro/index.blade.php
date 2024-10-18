@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">BUKU CEK / GIRO
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-2">
                    <div class="form-group">
                        <div class="row align-items-center">
                            <label for="nomorSupplier2" class="col-6 col-form-label">Nama Bank</label>
                            <div class="col-6">
                                <select class="bank-select" name="id_bank" style="width: 200%;">
                                    <option value="">---Select Bank---</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}" data-id="{{ enkrip($bank->id) }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <label for="nomorSupplier2" class="col-6 col-form-label">CEK / GIRO</label>
                            <div class="col-6">
                                <div class="d-flex align-items-center" style="background-color: black; padding: 1px; width: 140px;">
                                    <div class="form-check me-3">
                                        <input type="radio" id="cek" name="rekening" value="CK" class="form-check-input">
                                        <label for="cek" class="form-check-label" style="color: white; font-size: 12px;">CEK</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="giro" name="rekening" value="GR" class="form-check-input">
                                        <label for="giro" class="form-check-label" style="color: white; font-size: 12px;">GIRO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <table id="data-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">KODE</th>
                            <th class="text-center">DARI NOMOR</th>
                            <th class="text-center">SAMPAI NOMOR</th>
                            <th class="text-center">KADALUWARSA</th>
                            <th class="text-center">C/G</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">DETAIL</th>
                            {{-- <th class="text-center">HAPUS</th> --}}
                        </tr>
                    </thead>
                    <tbody id="data-tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.bank-select').select2({
                placeholder: '---Select Bank---',
                allowClear: true
            });

            // Clear radio buttons when select box changes
            $('.bank-select').on('select2:select', function(e) {
                $('input[name="rekening"]').prop('checked', false);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const bankSelect = document.querySelector('.bank-select');
            const rekeningRadios = document.querySelectorAll('input[name="rekening"]');

            function updateTable() {
                const selectedOption = bankSelect.options[bankSelect.selectedIndex];
                const idBank = bankSelect.value;
                const dataId = selectedOption.getAttribute('data-id');
                const rekening = Array.from(rekeningRadios).find(radio => radio.checked)?.value;

                if (idBank && rekening) {
                    fetch(`/master/get-data-giro?id_bank=${idBank}&rekening=${rekening}`)
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.getElementById('data-tbody');
                            tbody.innerHTML = ''; // Clear existing data
                            
                            const row1 = document.createElement('tr');
                            row1.innerHTML = `
                                <td colspan="8" class="text-center"><a href="giro/create/${dataId}" class="btn btn-sm btn-primary">BUAT GIRO BARU</a></td>
                            `;
                            tbody.appendChild(row1);

                            data.forEach(item => {
                                const row2 = document.createElement('tr');
                                row2.innerHTML = `
                                    <td class="text-center">${item.kode}</td>
                                    <td class="text-center">${item.dari}</td>
                                    <td class="text-center">${item.sampai}</td>
                                    <td class="text-center">/ /</td>
                                    <td class="text-center">GIRO</td>
                                    <td class="text-center">${item.status}</td>
                                    <td class="text-center"><a href="giro/show/${item.id_enkrip}" class="btn btn-info btn-sm">Detail</a></td>
                                `;
                                tbody.appendChild(row2);
                            });
                        })
                        .catch(error => console.error('Error fetching data:', error));
                }
            }

            bankSelect.addEventListener('change', updateTable);
            rekeningRadios.forEach(radio => radio.addEventListener('change', updateTable));
        });
    </script>
@endsection
