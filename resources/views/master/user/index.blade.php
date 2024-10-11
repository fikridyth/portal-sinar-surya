@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER USER</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="tambahButton" class="btn btn-primary btn-tambah" onclick="addPromosi()">TAMBAH</button>
                    <a href="{{ route('index') }}" class="btn btn-danger mx-2">KEMBALI</a>
                </div>
                
                <form id="promoForm" action="{{ route('master.user.store') }}" method="POST" class="form">
                    @csrf
                    <div class="d-flex justify-content-between mt-2">
                        <table id="promoTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center">DATA-DATA PROMOSI</th>
                                </tr>
                                <tr>
                                    <th class="text-center">NAMA</th>
                                    <th class="text-center">USERNAME</th>
                                    {{-- <th class="text-center">EMAIL</th> --}}
                                    <th class="text-center">PASSWORD</th>
                                    <th class="text-center">ROLE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="promoTableBody">
                                @foreach ($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td class="user_name">{{ $user->name }}</td>
                                        <td class="user_username">{{ $user->username }}</td>
                                        {{-- <td class="user_email">{{ $user->email }}</td> --}}
                                        <td class="user_password text-center">*****</td>
                                        <td class="user_role">{{ $user->role->nama }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning mx-1 btn-correct" data-id="{{ $user->id }}">KOREKSI</button>
                                            <button type="button" class="btn btn-sm btn-danger mx-1" onclick="confirmDelete({{ $user->id }})">HAPUS</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        function addPromosi() {
            var actionButtons = document.querySelectorAll('#tambahButton, #promoTable .btn-warning, #promoTable .btn-danger');
            actionButtons.forEach(function(button) {
                button.disabled = true;
            });

            var addrow = `
            <tr>
                <td class="text-center"><input type="text" required name="name" style="width: 150px;"></td>
                <td class="text-center"><input type="text" required name="username" style="width: 150px;"></td>
                <td class="text-center"><input type="password" required name="password" style="width: 150px;"></td>
                <td class="text-center">
                    <select name="role_id" required class="role-select" style="width: 300px;">
                        <option value="">---Select Role---</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <button type="submit" class="btn btn-sm btn-primary">SAVE</button>
                </td>
            </tr>
            `;

            document.getElementById('promoTableBody').insertAdjacentHTML('beforeend', addrow);

            $(`.role-select`).select2({
                placeholder: '---Select Role---',
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
                    const name = row.querySelector('.user_name').innerText;
                    const username = row.querySelector('.user_username').innerText;
                    const password = row.querySelector('.user_password').innerText;
                    const role = row.querySelector('.user_role').innerText;

                    // Replace <td> with input fields
                    row.innerHTML = `
                        <td class="text-center"><input type="text" required value="${name}" name="name" style="width: 150px;"></td>
                        <td class="text-center"><input type="text" required value="${username}" name="username" style="width: 150px;"></td>
                        <td class="text-center"><input type="password" required name="password" style="width: 150px;"></td>
                        <td class="text-center">
                            <select name="role_id" required class="role-select" style="width: 300px;">
                                <option value="">---Select Role---</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" ${role === '{{ $role->nama }}' ? 'selected' : ''}>{{ $role->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="id" value="${id}">
                            <button type="button" class="btn btn-sm btn-success mx-1 btn-save">Simpan</button>
                            <button type="button" class="btn btn-sm btn-secondary mx-1 btn-cancel">Batal</button>
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
                        password: row.querySelector('input[name="password"]').value,
                        role: row.querySelector('select[name="role_id"]').value,
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
