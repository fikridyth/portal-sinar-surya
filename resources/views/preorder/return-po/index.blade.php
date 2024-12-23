@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">RETUR PEMBELIAN BARANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('return-po.store') }}" method="POST" class="form">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-7">
                                    <div class="row align-items-center">
                                        <label class="col-3 col-form-label">NOMOR RETUR</label>
                                        <div class="col-3">
                                            <input type="text" class="readonly-input form-control" value="Auto Generate" autocomplete="off" readonly>
                                        </div>
                                        <label class="col-2 col-form-label text-end">TANGGAL</label>
                                        <div class="col-3">
                                            <input type="text" name="date" class="readonly-input form-control" value="{{ now()->format('Y-m-d') }}" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-5">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-form-label text-center">KETERANGAN</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-7">
                                    <div class="row align-items-center mt-1">
                                        <label class="col-3 col-form-label">BUKTI PENERIMAAN</label>
                                        <div class="col-7">
                                            <select id="preorder-select" name="nomor_receive" required class="preorder-select btn-block">
                                                <option value=""></option>
                                                @foreach ($preorders as $preorder)
                                                    <option value="{{ $preorder->nomor_receive }}">{{ $preorder->nomor_receive }} - {{ $preorder->supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row align-items-center mt-1">
                                        <label class="col-3 col-form-label">NAMA SUPPLIER</label>
                                        <div class="col-7">
                                            <select id="supplier-select" name="id_supplier" required class="supplier-select btn-block">
                                                <option value=""></option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-5 mb-4">
                                    <div class="row">
                                        <textarea name="" id="" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table id="details-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">NO BARANG</th>
                                                <th class="text-center">NAMA BARANG</th>
                                                <th class="text-center">KETERANGAN</th>
                                                <th class="text-center">QTY</th>
                                                {{-- <th class="text-center">NOMOR PO</th> --}}
                                                <th class="text-center">HARGA</th>
                                                {{-- <th class="text-center">TOTAL RP</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="fs-need"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <button type="button" id="tambah-button" class="btn btn-success mx-2">TAMBAH</button>
                                    <a href="{{ route('receive-po.add-product', enkrip($preorder->id)) }}" id="tambah-list-button" class="btn btn-info">TAMBAH LIST</a>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">TOTAL</label>
                                </div>
                                <div class="col-auto mx-4">
                                    <input type="text" size="15" class="readonly-input form-control text-end" value="0" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('daftar-return-po') }}" class="btn btn-warning">DAFTAR RETUR BARANG</a>
                            <a href="{{ route('index') }}" class="btn btn-danger mx-4">BATAL</a>
                            <button type="submit" id="simpan-button" disabled class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(`.supplier-select`).select2({
                placeholder: '---Select Supplier---',
                allowClear: true
            });

            $(`.supplier-select`).on('select2:open', function(e) {
                // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
                const searchBox = $(this).data('select2').dropdown.$search[0];
                if (searchBox) {
                    searchBox.focus();
                }
            });

            $(`.preorder-select`).select2({
                placeholder: '---Select Receive---',
                allowClear: true
            });

            $(`.preorder-select`).on('select2:open', function(e) {
                // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
                const searchBox = $(this).data('select2').dropdown.$search[0];
                if (searchBox) {
                    searchBox.focus();
                }
            });
        });

        const tambahButton = document.getElementById('tambah-button');
        tambahButton.addEventListener('click', function() {
            tambahButton.disabled = true;
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('tambah-button').click();
                document.getElementById('tambah-button').disabled = true;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            // Tambahkan event listener untuk menangani keydown
            form.addEventListener('keydown', function (event) {
                // Jika tombol yang ditekan adalah Enter (kode 13)
                if (event.key === 'Enter') {
                    // Mencegah aksi default (submit form)
                    event.preventDefault();
                }
            });

            const tambahButton = document.getElementById('tambah-button');
            const tableBody = document.querySelector('#details-table tbody');

            let index = 0; // Start index from the current index

            tambahButton.addEventListener('click', function() {
                index++;

                const newRow = document.createElement('tr');
                newRow.classList.add('fs-need');

                newRow.innerHTML = `
                    <td class="text-center">${index}</td>
                    <td class="text-center data-kode" id="data-kode">
                        <input type="number" size="1" autofocus class="kode-input" min="1" step="1" style="width: 300px;"
                        onkeydown="handleEnterKode(event)">
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                `;

                tableBody.appendChild(newRow);
            });
        });

        function handleEnterKode(event) {
            if (event.key === 'Enter') {
                // Mengambil nilai dari input
                const inputValue = document.querySelector('.kode-input').value.trim();

                // Jika nilai kosong, klik tombol tambah list
                if (inputValue === '') {
                    alert('DATA TIDAK BOLEH KOSONG!');
                } else {
                    // Jika nilai terisi, lakukan AJAX untuk memproses input
                    ajaxProses(inputValue);
                }
            }
        }

        let items = []; 
        function ajaxProses(inputValue) {
            $.ajax({
                url: '/get-data-return-barcode', // Ganti dengan URL yang sesuai
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    kode: inputValue
                },
                success: function(response) {
                    if (response.error) {
                        document.querySelector('.kode-input').value = '';
                        alert(response.error);
                    } else {
                        const kodeInput = document.querySelector('.kode-input');
                        const rowToReplace = kodeInput.closest('tr'); // Find the row containing the input field

                        rowToReplace.innerHTML = `
                            <td class="text-center">${rowToReplace.querySelector('td:first-child').textContent}</td>
                            <td class="text-center">${response.data.kode_alternatif}</td>
                            <td class="text-center">${response.data.nama}</td>
                            <td class="text-center">${response.data.unit_jual}</td>
                            <td class="text-center">1</td>
                            <td class="text-center">${response.data.harga_pokok}</td>
                        `;

                        items.push({
                            kode: response.data.kode,
                            order: 1,
                            price: response.data.harga_pokok
                        });

                        document.getElementById('tambah-button').disabled = false;
                        document.getElementById('simpan-button').disabled = false;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                }
            });
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            if (items.length === 0) {
                event.preventDefault();
                alert('Please add items before submitting!');
                return;
            }

            const form = this; // Store the form reference

            // Dynamically add hidden input fields for each item
            items.forEach(function(item, index) {
                const inputKode = document.createElement('input');
                inputKode.type = 'hidden';
                inputKode.name = `items[${index}][kode]`; 
                inputKode.value = item.kode;
                form.appendChild(inputKode);
            });
            items.forEach(function(item, index) {
                const inputOrder = document.createElement('input');
                inputOrder.type = 'hidden';
                inputOrder.name = `items[${index}][order]`; 
                inputOrder.value = item.order;
                form.appendChild(inputOrder);
            });
            items.forEach(function(item, index) {
                const inputPrice = document.createElement('input');
                inputPrice.type = 'hidden';
                inputPrice.name = `items[${index}][price]`; 
                inputPrice.value = item.price;
                form.appendChild(inputPrice);
            });

            // After appending hidden inputs, submit the form manually
            form.submit();
        });

    </script>
@endsection
