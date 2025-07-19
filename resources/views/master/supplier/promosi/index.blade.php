@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="tambahButton" class="btn btn-primary btn-tambah" onclick="addPromosi()">TAMBAH</button>
                    <a href="{{ route('master.supplier.show', enkrip(1)) }}" class="btn btn-danger mx-2">KEMBALI</a>
                </div>

                <form id="promoForm" action="{{ route('master.promosi.store') }}" method="POST" class="form">
                    @csrf
                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                            <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center">DATA-DATA PROMOSI</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">DARI TGL</th>
                                        <th class="text-center">SAMPAI TGL</th>
                                        <th class="text-center">JUMLAH</th>
                                        <th class="text-center">JENIS</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="promoTableBody">
                                    @foreach ($promosi as $promo)
                                        <tr data-id="{{ $promo->id }}">
                                            <td>{{ $promo->supplier->nama }}</td>
                                            <td class="supplier" hidden>{{ $promo->supplier->id }}</td>
                                            <td class="date_first text-center">{{ $promo->date_first }}</td>
                                            <td class="date_last text-center">{{ $promo->date_last }}</td>
                                            <td class="total text-end">{{ number_format($promo->total, 0) }}</td>
                                            <td class="tipe">{{ $promo->tipe }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning mx-1 btn-correct" data-id="{{ $promo->id }}">KOREKSI</button>
                                                <button type="button" class="btn btn-sm btn-danger mx-1" onclick="confirmDelete({{ $promo->id }})">HAPUS</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success">SUBMIT</button>
                        </div> --}}
                    </div>
                </form>
                @foreach ($promosi as $promo)
                    <form id="delete-form-{{ $promo->id }}" action="{{ route('master.promosi.destroy', $promo->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addPromosi() {
            var actionButtons = document.querySelectorAll('#tambahButton, #promoTable .btn-warning, #promoTable .btn-danger');
            actionButtons.forEach(function(button) {
                button.disabled = true;
            });

            var now = new Date().toISOString().split('T')[0]; // Gets current date in YYYY-MM-DD format

            var addrow = `
            <tr>
                <td>
                    <select name="supplier_id" required class="supplier-select" style="width: 300px;">
                        <option value="">---Select Supplier---</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="date" name="date_first" value="${now}" style="width: 100px;"></td>
                <td><input type="date" name="date_last" value="${now}" style="width: 100px;"></td>
                <td><input type="text" required name="total" onkeypress='return event.charCode >= 48 && event.charCode <= 57' style="width: 100px;"></td>
                <td>
                    <select name="tipe" required class="tipe-select" style="width: 100px;">
                        <option value=""></option>
                        <option value="TARGET">TARGET</option>
                        <option value="GONDOLA">GONDOLA</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary">SAVE</button>
                </td>
            </tr>
            `;

            document.getElementById('promoTableBody').insertAdjacentHTML('beforeend', addrow);

            $(`.supplier-select`).select2({
                placeholder: '---Select Supplier---',
                allowClear: true
            });
            $(`.tipe-select`).select2({
                allowClear: true
            });
        }

        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const promoTableBody = document.getElementById('promoTableBody');

            promoTableBody.addEventListener('click', (event) => {
                var actionButtons = document.querySelectorAll('#tambahButton, #promoTable .btn-warning, #promoTable .btn-danger');
                actionButtons.forEach(function(button) {
                    button.disabled = true;
                });

                if (event.target.classList.contains('btn-correct')) {
                    const row = event.target.closest('tr');
                    const id = row.getAttribute('data-id');
                    const supplier = row.querySelector('.supplier').innerText;
                    const dateFirst = row.querySelector('.date_first').innerText;
                    const dateLast = row.querySelector('.date_last').innerText;
                    const total = row.querySelector('.total').innerText.replace(/,/g, '');
                    const tipe = row.querySelector('.tipe').innerText;

                    // Replace <td> with input fields
                    row.innerHTML = `
                        <td>
                            <select name="supplier_id" required class="supplier-select" style="width: 300px;">
                                <option value="">---Select Supplier---</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" ${supplier === '{{ $supplier->id }}' ? 'selected' : ''}>{{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="date" name="date_first" value="${dateFirst}" style="width: 100px;"></td>
                        <td><input type="date" name="date_last" value="${dateLast}" style="width: 100px;"></td>
                        <td><input type="text" required name="total" value="${total}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' style="width: 100px;"></td>
                        <td>
                            <select name="tipe" required class="tipe-select" style="width: 100px;">
                                <option value=""></option>
                                <option value="TARGET" ${tipe === 'TARGET' ? 'selected' : ''}>TARGET</option>
                                <option value="GONDOLA" ${tipe === 'GONDOLA' ? 'selected' : ''}>GONDOLA</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="id" value="${id}">
                            <button type="button" class="btn btn-sm btn-success mx-1 btn-save">Simpan</button>
                            <button type="button" class="btn btn-sm btn-secondary mx-1 btn-cancel">Batal</button>
                        </td>
                    `;

                    $(`.supplier-select`).select2({
                        placeholder: '---Select Supplier---',
                        allowClear: true
                    });
                    $(`.tipe-select`).select2({
                        allowClear: true
                    });
                }
            });

            promoTableBody.addEventListener('click', (event) => {
                if (event.target.classList.contains('btn-save')) {
                    const row = event.target.closest('tr');
                    const id = row.querySelector('input[name="id"]').value;
                    const rowData = {
                        id_supplier: row.querySelector('select[name="supplier_id"]').value,
                        date_first: row.querySelector('input[name="date_first"]').value,
                        date_last: row.querySelector('input[name="date_last"]').value,
                        total: row.querySelector('input[name="total"]').value,
                        tipe: row.querySelector('select[name="tipe"]').value,
                    };

                    fetch(`/master/promosi/${id}/update`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(rowData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Data berhasil disimpan.');
                            location.reload();
                        } else {
                            alert('Terjadi kesalahan saat menyimpan data.');
                        }
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan saat menyimpan data.');
                        console.error('Error:', error);
                    });
                } else if (event.target.classList.contains('btn-cancel')) {
                    location.reload(); // Or use a more sophisticated approach to restore the original state
                }
            });
        });
    </script>
@endsection
