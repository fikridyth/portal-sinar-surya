@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="tambahButton" class="btn btn-primary btn-tambah" onclick="addPromosi()">TAMBAH</button>
                    <a href="{{ route('index') }}" class="btn btn-danger mx-2">KEMBALI</a>
                </div>
                
                <form id="promoForm" action="{{ route('master.bank.store') }}" method="POST" class="form">
                    @csrf
                    <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc;">
                        <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto; font-size: 13px;">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center">DATA BANK</th>
                                </tr>
                                <tr>
                                    <th class="text-center">NAMA BANK</th>
                                    <th class="text-center">NO REKENING</th>
                                    <th class="text-center">MILIK</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="promoTableBody">
                                @foreach ($banks as $bank)
                                    <tr data-id="{{ $bank->id }}">
                                        <td class="bank_nama text-center">{{ $bank->nama }}</td>
                                        <td class="bank_id" hidden>{{ $bank->id }}</td>
                                        <td class="bank_no_rekening text-center">{{ $bank->no_rekening }}</td>
                                        <td class="bank_milik text-center">{{ $bank->milik }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning mx-1 btn-correct" data-id="{{ $bank->id }}">KOREKSI</button>
                                            <button type="button" class="btn btn-sm btn-danger mx-1" onclick="confirmDelete({{ $bank->id }})">HAPUS</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
                @foreach ($banks as $bank)
                    <form id="delete-form-{{ $bank->id }}" action="{{ route('master.bank.destroy', $bank->id) }}" method="POST" style="display: none;">
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
                <td class="text-center"><input type="text" required name="bank_nama" style="width: 200px;"></td>
                <td class="text-center"><input type="text" required name="bank_no_rekening" onkeypress='return event.charCode >= 48 && event.charCode <= 57' style="width: 200px;"></td>
                <td class="text-center">
                    <select required name="bank_milik" class="tipe-select" style="width: 200px;">
                        <option value=""></option>
                        <option value="SENDIRI">SENDIRI</option>
                        <option value="CABANG">CABANG</option>
                        <option value="LANGGANAN">LANGGANAN</option>
                    </select>    
                </td>
                <td class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary">SAVE</button>
                </td>
            </tr>
            `;

            document.getElementById('promoTableBody').insertAdjacentHTML('afterbegin', addrow);
            
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
                    const bankNama = row.querySelector('.bank_nama').innerText;
                    const bankNorek = row.querySelector('.bank_no_rekening').innerText;
                    const bankMilik = row.querySelector('.bank_milik').innerText;

                    // Replace <td> with input fields
                    row.innerHTML = `
                        <td class="text-center"><input type="text" required name="bank_nama" value="${bankNama}" style="width: 200px;"></td>
                        <td class="text-center"><input type="text" required name="bank_no_rekening" value="${bankNorek}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' style="width: 200px;"></td>
                        <td class="text-center">
                            <select required name="bank_milik" class="tipe-select" style="width: 200px;">
                                <option value=""></option>
                                <option value="SENDIRI" ${bankMilik === 'SENDIRI' ? 'selected' : ''}>SENDIRI</option>
                                <option value="CABANG" ${bankMilik === 'CABANG' ? 'selected' : ''}>CABANG</option>
                                <option value="LANGGANAN" ${bankMilik === 'LANGGANAN' ? 'selected' : ''}>LANGGANAN</option>
                            </select>    
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="id" value="${id}">
                            <button type="button" class="btn btn-sm btn-success mx-1 btn-save">Simpan</button>
                            <button type="button" class="btn btn-sm btn-secondary mx-1 btn-cancel">Batal</button>
                        </td>
                    `;
                    
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
                        bank_nama: row.querySelector('input[name="bank_nama"]').value,
                        bank_no_rekening: row.querySelector('input[name="bank_no_rekening"]').value,
                        bank_milik: row.querySelector('select[name="bank_milik"]').value,
                    };

                    fetch(`/master/bank/${id}/update`, {
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
