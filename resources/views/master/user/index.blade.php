@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="tambahButton" class="btn btn-primary btn-tambah" onclick="addPromosi()">TAMBAH</button>
                    <a href="{{ route('index') }}" class="btn btn-danger mx-2">SELESAI</a>
                </div>

                <form id="promoForm" action="{{ route('master.user.store') }}" method="POST" class="form">
                    @csrf
                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc;">
                            <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-center">DATA-DATA PROMOSI</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">USE ID</th>
                                        <th class="text-center">NAMA USER</th>
                                        <th class="text-center">CABANG</th>
                                        <th class="text-center">PASSWORD</th>
                                        <th class="text-center">V</th>
                                        <th class="text-center">JABATAN</th>
                                        <th class="text-center">STANDAR</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="promoTableBody">
                                    @foreach ($users as $user)
                                        <tr data-id="{{ $user->id }}">
                                            <td class="user_username">{{ $user->username }}</td>
                                            <td class="user_name">{{ $user->name }}</td>
                                            <td class="user_cabang">{{ $user->cabang->nama }}</td>
                                            <td class="user_password text-center">*****</td>
                                            <td class="user_show_password text-center"><input type="checkbox" class="showPasswordCheckbox"
                                                onclick="togglePasswordVisibility(this, '{{ $user->show_password }}')"></td>
                                            <td class="user_jabatan">{{ $user->jabatan }}</td>
                                            <td class="user_role">{{ $user->role->nama }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning mx-1 btn-correct" data-id="{{ $user->id }}">KOREKSI</button>
                                                <button type="button" class="btn btn-sm btn-danger mx-1" onclick="confirmDelete({{ $user->id }})">HAPUS</button>
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
                @foreach ($users as $user)
                    <form id="delete-form-{{ $user->id }}" action="{{ route('master.user.destroy', $user->id) }}" method="POST" style="display: none;">
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
        function togglePasswordVisibility(checkbox, password) {
            const passwordCell = checkbox.closest('tr').querySelector('.user_password');

            if (checkbox.checked) {
                // Show the actual password
                passwordCell.textContent = password; // Use the password passed as an argument
            } else {
                // Revert back to *****
                passwordCell.textContent = "*****";
            }
        }

        function addPromosi() {
            // var actionButtons = document.querySelectorAll('#tambahButton, #promoTable .btn-warning, #promoTable .btn-danger');
            // actionButtons.forEach(function(button) {
            //     button.disabled = true;
            // });

            var addrow = `
            <tr>
                <td class="text-center"><input type="text" required name="username" style="width: 100px;" autocomplete="off"></td>
                <td class="text-center"><input type="text" required name="name" style="width: 100px;" autocomplete="off"></td>
                <td class="text-center">
                    <select name="cabang_id" required class="cabang-select" style="width: 150px;">
                        <option value="">---Pilih Cabang---</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center" colspan="2"><input type="password" required name="password" style="width: 100px;" autocomplete="off"></td>
                <td class="text-center"><input type="text" required name="jabatan" style="width: 100px;" autocomplete="off"></td>
                <td class="text-center">
                    <select name="role_id" required class="role-select" style="width: 150px;">
                        <option value="">---Pilih Role---</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary">TAMBAH</button>
                    <button type="button" class="btn btn-sm btn-secondary mx-1 btn-cancel">BATAL</button>
                </td>
            </tr>
            `;

            document.getElementById('promoTableBody').insertAdjacentHTML('beforeend', addrow);
        }

        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const promoTableBody = document.getElementById('promoTableBody');

            promoTableBody.addEventListener('click', (event) => {
                // var actionButtons = document.querySelectorAll('#tambahButton, #promoTable .btn-warning, #promoTable .btn-danger');
                // actionButtons.forEach(function(button) {
                //     button.disabled = true;
                // });

                if (event.target.classList.contains('btn-correct')) {
                    const row = event.target.closest('tr');
                    const id = row.getAttribute('data-id');
                    const name = row.querySelector('.user_name').innerText;
                    const username = row.querySelector('.user_username').innerText;
                    const cabang = row.querySelector('.user_cabang').innerText;
                    const jabatan = row.querySelector('.user_jabatan').innerText;
                    const password = row.querySelector('.user_password').innerText;
                    const role = row.querySelector('.user_role').innerText;

                    // Replace <td> with input fields
                    row.innerHTML = `
                        <td class="text-center"><input type="text" required value="${username}" name="username" style="width: 100px;" autocomplete="off"></td>
                        <td class="text-center"><input type="text" required value="${name}" name="name" style="width: 100px;" autocomplete="off"></td>
                        <td class="text-center">
                            <select name="cabang_id" required class="cabang-select" style="width: 150px;">
                                <option value="">---Pilih Cabang---</option>
                                @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}" ${cabang === '{{ $cabang->nama }}' ? 'selected' : ''}>{{ $cabang->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center" colspan="2"><input type="password" required name="password" style="width: 100px;" autocomplete="off"></td>
                        <td class="text-center"><input type="text" required value="${jabatan}" name="jabatan" style="width: 100px;" autocomplete="off"></td>
                        <td class="text-center">
                            <select name="role_id" required class="role-select" style="width: 150px;">
                                <option value="">---Select Role---</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" ${role === '{{ $role->nama }}' ? 'selected' : ''}>{{ $role->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="id" value="${id}">
                            <button type="button" class="btn btn-sm btn-success mx-1 btn-save">SIMPAN</button>
                            <button type="button" class="btn btn-sm btn-secondary mx-1 btn-cancel">BATAL</button>
                        </td>
                    `;
                }
            });

            promoTableBody.addEventListener('click', (event) => {
                if (event.target.classList.contains('btn-save')) {
                    const row = event.target.closest('tr');
                    const id = row.querySelector('input[name="id"]').value;
                    const rowData = {
                        name: row.querySelector('input[name="name"]').value,
                        username: row.querySelector('input[name="username"]').value,
                        jabatan: row.querySelector('input[name="jabatan"]').value,
                        password: row.querySelector('input[name="password"]').value,
                        role: row.querySelector('select[name="role_id"]').value,
                        cabang: row.querySelector('select[name="cabang_id"]').value,
                    };

                    fetch(`/master/user/${id}/update`, {
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
