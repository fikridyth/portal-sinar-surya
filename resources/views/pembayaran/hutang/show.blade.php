@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN HUTANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">NO SUPPLIER</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->nomor }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">TANGGAL</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ now()->format('d/m/Y') }}"></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">NAMA</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->nama }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">NOMOR BUKTI</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $getNomorBukti }}"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-2"></div>
                            <div class="col-3"><label for="">ALAMAT</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="{{ $supplier->alamat1 }}"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row form-group">
                            <div class="col-3"><label for="">KETERANGAN</label></div>
                            <div class="col-6"><input type="text" class="mx-2" size="30" disabled value="PEMBAYARAN HUTANG"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('pembayaran-hutang.process', enkrip($supplier->id)) }}" id="dataForm" method="POST" class="form">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3 w-50 mx-auto" style="background-color: red; color: white;">'Klik' Kolom Kode Untuk Dokumen Yang Dibayar</div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NO DOKUMEN</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">CURRENCY</th>
                                <th class="text-center">RATE</th>
                                <th class="text-center">JUMLAH</th>
                                <th class="text-center">KODE</th>
                                <th class="text-center">DETAIL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getAllData as $data)
                                <tr>
                                    <input type="text" hidden name="nomor[]" value="{{ $data['nomor'] }}">
                                    <input type="text" hidden name="date[]" value="{{ $data['date'] }}">
                                    <input type="text" hidden name="total[]" value="{{ $data['total'] }}">
                                    <td class="text-center">{{ $data['nomor'] }}</td>
                                    <td class="text-center date-cell">{{ $data['date'] }}</td>
                                    <td class="text-center">IND</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">{{ number_format($data['total'], 0) }}</td>
                                    <td class="text-center"><input type="checkbox" class="data-checkbox" data-index="{{ $loop->index }}"></td>
                                    <td class="text-center"><input type="checkbox" class="goto-detail" data-nomor="{{ $data['nomor'] }}"></td>
                               </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <button type="button" onclick="window.history.back()" class="btn btn-danger">KEMBALI</button>
                        </div>
                        <div class="mx-2">
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan semua checkbox
            const checkboxes = document.querySelectorAll('.data-checkbox');
            const form = document.getElementById('dataForm');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Mendapatkan tanggal terkait dengan checkbox yang dicentang
                    const checkedDate = this.closest('tr').querySelector('.date-cell').textContent;

                    // Menentukan checkbox dengan tanggal yang sama
                    if (this.checked) {
                        checkboxes.forEach(function(cb) {
                            const rowDate = cb.closest('tr').querySelector('.date-cell').textContent;
                            if (rowDate === checkedDate) {
                                cb.checked = checkbox.checked;
                            }
                        });
                    }
                });
            });

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Filter selected checkboxes
                const selectedData = [];
                checkboxes.forEach((checkbox, index) => {
                    if (checkbox.checked) {
                        selectedData.push(index);
                    }
                });

                // Create a hidden input to store selected indices
                let selectedIndicesInput = document.getElementById('selectedIndices');
                if (!selectedIndicesInput) {
                    selectedIndicesInput = document.createElement('input');
                    selectedIndicesInput.type = 'hidden';
                    selectedIndicesInput.name = 'selectedIndices[]';
                    selectedIndicesInput.id = 'selectedIndices';
                    form.appendChild(selectedIndicesInput);
                }
                selectedIndicesInput.value = JSON.stringify(selectedData);

                // Submit the form
                form.submit();
            });
        });

        document.querySelectorAll('.goto-detail').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Mengecek apakah checkbox dicentang
                if (this.checked) {
                    // Mengambil nomor dari data-nomor
                    const nomor = this.getAttribute('data-nomor');
                    
                    // Melakukan AJAX request untuk mendapatkan ID berdasarkan nomor
                    fetch(`/api/receive-po/detail/${nomor}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.encryptedId) {
                                // Jika data ditemukan, arahkan ke halaman detail berdasarkan ID
                                window.location.href = `/receive-po/done-detail/${data.encryptedId}`;
                            } else {
                                alert('Data tidak ditemukan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan, coba lagi.');
                        });
                }
            });
        });
    </script>
@endsection
