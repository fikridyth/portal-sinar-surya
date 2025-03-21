@extends('main')

@section('content')
    <div class="container-fluid mb-7 w-100" style="max-width: 90%; width: 90%;">
        <div class="card mt-n3">
            <div class="card-body mt-n4">
                <form action="{{ route('kredit.store', $kredit->id) }}" method="POST" class="form">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group">
                                    <div class="row align-items-center">
                                        <label class="col-2 col-form-label text-end">NOMOR KREDIT</label>
                                        <div class="col-2">
                                            <input type="text" value="AUTO-GENERATE" disabled>
                                        </div>
                                        <label class="col-2 col-form-label text-end">TANGGAL</label>
                                        <div class="col-2">
                                            <input type="text" name="date" class="readonly-input form-control" style="width: 180px;" value="{{ now()->format('Y-m-d') }}" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group">
                                    <div class="row align-items-center">
                                        <label class="col-2 col-form-label text-end">PELANGGAN</label>
                                        <div class="col-2">
                                            <input type="text" value="-" disabled>
                                        </div>
                                        
                                        <label class="col-2 col-form-label text-end">KODE PELANGGAN</label>
                                        <div class="col-2">
                                            <input type="text" disabled value="{{ $kredit->langganan->nomor }}">
                                        </div>
                                        
                                        <label class="col-2 col-form-label text-end">NAMA PELANGGAN</label>
                                        <div class="col-2">
                                            <input type="text" disabled value="{{ $kredit->langganan->nama }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <div style="overflow-x: auto; height: 530px; border: 1px solid #ccc;">
                                        <table id="details-table" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">NO</th>
                                                    <th class="text-center">NO BARANG</th>
                                                    <th class="text-center">NAMA BARANG</th>
                                                    {{-- <th class="text-center">KETERANGAN</th> --}}
                                                    <th class="text-center">QTY</th>
                                                    {{-- <th class="text-center">NOMOR PO</th> --}}
                                                    <th class="text-center">HARGA</th>
                                                    <th class="text-center">TOTAL</th>
                                                    <th class="text-center">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="fs-need"></tr>
                                                @if (isset($kredit->detail))
                                                    @foreach (json_decode($kredit->detail, true) as $index => $data)
                                                        @php
                                                            $no = $index + 1;
                                                        @endphp
                                                        <tr data-index="{{ $index }}" id="row-{{ $index }}">
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td class="text-center">{{ $data['kode'] }}</td>
                                                            <td>{{ $data['nama'] . '/' . $data['unit_jual'] }}</td>
                                                            <td class="text-center">
                                                                <input type="number" style="width: 70px" name="qty[]" value="{{ $data['order'] }}" class="order-input" id="order-input-{{ $no }}"
                                                                    onblur="handleBlurOrder({{ $no }}, '{{ $data['order'] }}')" onkeydown="handleEnterOrder(event, {{ $no }}, '{{ $data['order'] }}')" onfocus="this.value = '';">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" style="width: 120px" name="harga[]" value="{{ $data['price'] }}" class="price-input" id="price-input-{{ $no }}"
                                                                    onblur="handleBlurPrice({{ $no }}, '{{ $data['price'] }}')" onkeydown="handleEnterPrice(event, {{ $no }}, '{{ $data['price'] }}')" onfocus="this.value = '';">
                                                            </td>
                                                            <td class="text-end">{{ number_format($data['order'] * $data['price']) }}</td>
                                                            <td class="text-center">
                                                                <a href="#" class="btn btn-danger btn-sm delete-row" data-index="{{ $index }}">HAPUS</a>
                                                                <a href="#" class="btn btn-primary btn-sm save-row" id="save-row-{{ $no }}" hidden data-index="{{ $index }}"><i class="fa fa-save"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        {{-- <td class="text-center" colspan="6"><b>TIDAK ADA DATA</b></td> --}}
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <button type="button" id="tambah-button" class="btn btn-success mx-2">TAMBAH</button>
                                    <a href="{{ route('daftar-kredit-product', enkrip($kredit->id)) }}" id="tambah-list-button" class="btn btn-danger mx-2">INVENTORY</a>
                                    <a href="{{ route('kredit.list') }}" class="btn btn-info mx-2">LIST ORDER</a>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">TOTAL</label>
                                </div>
                                <div class="col-auto mx-4">
                                    <input type="text" size="15" class="readonly-input form-control text-end" value="{{ number_format($kredit->total ?? 0) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('kredit.index') }}" class="btn btn-danger mx-4">BATAL</a>
                            @if (isset($kredit->detail))
                                <button type="submit" id="simpan-button" class="btn btn-primary">PROSES</button>
                            @else
                                <button type="submit" id="simpan-button" disabled class="btn btn-primary">PROSES</button>
                            @endif
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
            // $(`.supplier-select`).select2({
            //     placeholder: '---Select Supplier---',
            //     allowClear: true
            // });

            // $(`.supplier-select`).on('select2:open', function(e) {
            //     // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
            //     const searchBox = $(this).data('select2').dropdown.$search[0];
            //     if (searchBox) {
            //         searchBox.focus();
            //     }
            // });

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

        function handleEnterOrder(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('order-input-' + no);
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                document.getElementById('price-input-' + no).focus();
            }
        }

        function handleBlurOrder(no, originalValue) {
            var inputField = document.getElementById('order-input-' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function handleEnterPrice(event, no, originalValue) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('price-input-' + no);
                
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = originalValue;
                }

                // document.getElementById('price-input-' + no).focus();
                document.getElementById('save-row-' + no).click();
            }
        }

        function handleBlurPrice(no, originalValue) {
            var inputField = document.getElementById('price-input-' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        const tambahButton = document.getElementById('tambah-button');
        tambahButton.addEventListener('click', function() {
            tambahButton.disabled = true;
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA')) {
                return;
            }
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
                    <td class="text-center"></td>
                    <td class="text-center data-kode" id="data-kode">
                        <input type="number" size="1" autofocus class="kode-input" min="1" step="1" style="width: 300px;"
                        onkeydown="handleEnterKode(event)">
                    </td>
                    <td class="text-center"></td>
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
                    document.getElementById('tambah-list-button').click();
                } else {
                    // Jika nilai terisi, lakukan AJAX untuk memproses input
                    ajaxProses(inputValue);
                }
            }
        }

        var kreditId = <?php echo json_encode($kredit->id); ?>; 
        let items = []; 
        function ajaxProses(inputValue) {
            $.ajax({
                url: '/get-data-kredit-barcode', // Ganti dengan URL yang sesuai
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    kode: inputValue,
                    id: kreditId
                },
                success: function(response) {
                    if (response.error) {
                        document.querySelector('.kode-input').value = '';
                        alert(response.error);
                    } else {
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                }
            });
        }

        $(document).on('click', '.save-row', function(e) {
            e.preventDefault();

            let button = $(this);
            let index = button.data('index');
            let order = $(`#order-input-${index + 1}`).val();
            let price = $(`#price-input-${index + 1}`).val();

            $.ajax({
                url: '/save-kredit-item', // URL endpoint Laravel
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
                    index: index,
                    order: order,
                    price: price,
                    kredit_id: kreditId
                },
                success: function(response) {
                    if (response.success) {
                        // Hapus baris tabel setelah berhasil
                        window.location.reload();
                    } else {
                        alert("Gagal save data: " + response.message);
                    }
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.delete-row', function(e) {
            e.preventDefault();

            let button = $(this);
            let index = button.data('index'); // Ambil index dari atribut data

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus item ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/destroy-kredit-item', // URL endpoint Laravel
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
                            index: index,
                            kredit_id: kreditId
                        },
                        success: function(response) {
                            if (response.success) {
                                // Hapus baris tabel setelah berhasil
                                window.location.reload();
                            } else {
                                alert("Gagal menghapus data: " + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert("Terjadi kesalahan. Silakan coba lagi.");
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
