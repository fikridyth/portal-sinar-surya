@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 82%">
            <form action="{{ route('receive-po.store') }}" method="POST" class="form">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label">Nomor</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" value="{{ $getNomorPo }}" readonly name="nomor_po"
                                                    class="form-control readonly-input" id="nomorSupplier2" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="inputPassword3"
                                                class="col col-form-label d-flex justify-content-end">TANGGAL PO</label>
                                            <div class="col">
                                                <input type="text" value="{{ now()->format('Y-m-d') }}" name="tanggal_po"
                                                    readonly class="form-control readonly-input" id="inputPassword3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2"
                                                class="col col-form-label d-flex justify-content-end">KODE SUPPLIER</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="col">
                                        <div class="custom-select-supplier">
                                            <input type="text" size="9" autofocus class="search-input-supplier_1" name="supplier" value="{{ old('supplier') }}" autocomplete="off" required placeholder="Search..." onkeyup="filterFunction()">
                                            <div class="select-items-supplier" id="select-items-supplier_1">
                                                <!-- Options will be added here dynamically -->
                                            </div>
                                        </div>
                        
                                        <select id="supplier_1" style="width: 150px;" hidden>
                                            <option value="">---Select Supplier---</option>
                                            <!-- Example options; replace with server-side data as needed -->
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="col">
                                        <input type="text" id="nama_supplier_1" name='dataSupplier1' value="" readonly class="form-control readonly-input" autocomplete="off" />
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <div class="row w-100">
                                    <div class="form-group col-12">
                                        <h6 class="text-center" style="color: red;">DAFTAR PESANAN BARANG YANG BELUM DITERIMA</h6>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">NAMA BARANG</th>
                                                    <th class="text-center">ORDER</th>
                                                    <th class="text-center">HARGA</th>
                                                    <th class="text-center">JUMLAH RP</th>
                                                    <th class="text-center">NOMOR P.O</th>
                                                    <th class="text-center">TANGGAL</th>
                                                    <th class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody id="po_details">
                                                <tr>
                                                    <td id="targetRow4" colspan="7" class="text-center">Tidak ada data yang dipilih</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" id="button-baru" hidden class="btn btn-primary mx-3">BUAT PENERIMAAN</button>
                                            <button type="button" id="button-dihapus" hidden class="btn btn-danger mx-3">HAPUS PO</button>
                                            {{-- <button type="button" id="button-tetap" hidden class="btn btn-primary mx-3">TETAP</button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Supplier 1 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const supplier = document.getElementById('supplier_1');
            const namaSupplier = document.getElementById('nama_supplier_1');
            const selectItemsSupplier = document.getElementById('select-items-supplier_1');
            const searchInputSupplier = document.querySelector('.search-input-supplier_1');

            // Populate options from the original select element
            Array.from(supplier.options).forEach(option => {
                if (option.value) {
                    const div = document.createElement('div');
                    div.textContent = option.textContent;
                    div.dataset.value = option.value;
                    div.dataset.kode = option.getAttribute('data-kode');
                    div.dataset.nama = option.getAttribute('data-nama');
                    div.onclick = function() {
                        searchInputSupplier.value = this.dataset.kode;
                        namaSupplier.value = this.dataset.nama;
                        closeAllSelect();

                        $.ajax({
                            url: '/receive-get-preorder-data',
                            method: 'GET',
                            data: {
                                kode: searchInputSupplier.value
                            },
                            success: function(response) {
                                console.log(response)
                                if (Array.isArray(response)) {
                                    // Clear previous data
                                    $('#po_details').empty();
                                    const groupedPreorders = {};
                                    
                                    // Loop through each preorder
                                    response.forEach(function(preorder) {
                                        // console.log(preorder);

                                        // Parse the details JSON
                                        try {
                                            const details = JSON.parse(preorder.detail);

                                            // Group items by nomor_po
                                            if (!groupedPreorders[preorder.nomor_po]) {
                                                groupedPreorders[preorder.nomor_po] = {
                                                    items: [],
                                                    date_first: preorder.date_first // Store date_first for this nomor_po
                                                };
                                            }

                                            details.forEach(function(item) {
                                                groupedPreorders[preorder.nomor_po].items.push(item);
                                            });
                                        } catch (e) {
                                            console.error('Failed to parse detail JSON:', e);
                                        }
                                    });

                                    Object.keys(groupedPreorders).forEach(nomor_po => {
                                        const group = groupedPreorders[nomor_po];
                                        group.items.forEach(function(item, index) {
                                            $('#po_details').append(
                                                `<tr>
                                                    <td hidden>${item.kode}</td>
                                                    <td hidden>${item.nama}</td>
                                                    <td hidden>${item.unit_jual}</td>
                                                    <td hidden>${item.stok}</td>
                                                    <td hidden>${item.order}</td>
                                                    <td hidden>${item.price}</td>
                                                    <td hidden>${item.field_total}</td>
                                                    <td hidden>${item.kode_sumber}</td>
                                                    <td hidden>${item.diskon1}</td>
                                                    <td hidden>${item.diskon2}</td>
                                                    <td hidden>${item.diskon3}</td>
                                                    <td hidden>${item.penjualan_rata}</td>
                                                    <td hidden>${item.waktu_kunjungan}</td>
                                                    <td hidden>${item.stok_minimum}</td>
                                                    <td hidden>${item.stok_maksimum}</td>
                                                    <td hidden>${item.is_ppn}</td>
                                                    <td hidden>${nomor_po}</td>
                                                    <td>${item.nama}</td>
                                                    <td class="text-end">${item.order}</td>
                                                    <td class="text-end">${number_format(item.price)}</td>
                                                    <td class="text-end">${number_format(item.field_total)}</td>
                                                    <td class="text-center">${nomor_po}</td>
                                                    <td class="text-center">${group.date_first}</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="checkbox-group" data-nomor-po="${nomor_po}" id="checkbox-${nomor_po}-${index}">
                                                    </td>
                                                </tr>`
                                            );
                                        });
                                    });

                                    // Attach event listener for checkboxes
                                    $('.checkbox-group').on('change', function() {
                                        const nomorPo = $(this).data('nomor-po');
                                        const isChecked = this.checked;

                                        // Check or uncheck all checkboxes with the same nomor_po
                                        $(`.checkbox-group[data-nomor-po="${nomorPo}"]`).prop('checked', isChecked);
                                    });

                                    // Hide or show specific rows as needed
                                    $('#targetRow4').attr('hidden', 'hidden');
                                    $('#button-baru').removeAttr('hidden');
                                    // $('#button-tetap').removeAttr('hidden');
                                    $('#button-dihapus').removeAttr('hidden');
                                } else {
                                    console.warn('Expected an array but received:', response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', status, error);
                            }
                        });
                    }
                    selectItemsSupplier.appendChild(div);
                }
            });

            function number_format(number) {
                return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function filterFunction() {
                const input = searchInputSupplier.value.toUpperCase();
                const items = selectItemsSupplier.getElementsByTagName('div');
                Array.from(items).forEach(item => {
                    const text = item.textContent || item.innerText;
                    item.style.display = text.toUpperCase().includes(input) ? 'block' : 'none';
                });
            }

            function closeAllSelect() {
                selectItemsSupplier.style.display = 'none';
            }

            // Filter options when typing in the search input
            searchInputSupplier.addEventListener('keyup', filterFunction);

            // Toggle dropdown visibility on input click
            searchInputSupplier.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent the click from propagating to the document
                const isVisible = selectItemsSupplier.style.display === 'block';
                closeAllSelect(); // Close any other open dropdowns
                selectItemsSupplier.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                closeAllSelect();
            });

            searchInputSupplier.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    event.stopPropagation();

                    const isVisible = selectItemsSupplier.style.display === 'block';
                    if (isVisible) {
                        const visibleItems = Array.from(selectItemsSupplier.getElementsByTagName('div'))
                            .filter(item => item.style.display !== 'none');

                        if (visibleItems.length > 0) {
                            const firstItem = visibleItems[0];

                            // Simulate click on the first visible item
                            searchInputSupplier.value = firstItem.dataset.kode;
                            namaSupplier.value = firstItem.dataset.nama;
                            closeAllSelect();

                            $.ajax({
                                url: '/receive-get-preorder-data',
                                method: 'GET',
                                data: {
                                    kode: searchInputSupplier.value
                                },
                                success: function(response) {
                                    console.log(response)
                                    if (Array.isArray(response)) {
                                        // Clear previous data
                                        $('#po_details').empty();
                                        const groupedPreorders = {};
                                        
                                        // Loop through each preorder
                                        response.forEach(function(preorder) {
                                            // console.log(preorder);

                                            // Parse the details JSON
                                            try {
                                                const details = JSON.parse(preorder.detail);

                                                // Group items by nomor_po
                                                if (!groupedPreorders[preorder.nomor_po]) {
                                                    groupedPreorders[preorder.nomor_po] = {
                                                        items: [],
                                                        date_first: preorder.date_first // Store date_first for this nomor_po
                                                    };
                                                }

                                                details.forEach(function(item) {
                                                    groupedPreorders[preorder.nomor_po].items.push(item);
                                                });
                                            } catch (e) {
                                                console.error('Failed to parse detail JSON:', e);
                                            }
                                        });

                                        Object.keys(groupedPreorders).forEach(nomor_po => {
                                            const group = groupedPreorders[nomor_po];
                                            group.items.forEach(function(item, index) {
                                                $('#po_details').append(
                                                    `<tr>
                                                        <td hidden>${item.kode}</td>
                                                        <td hidden>${item.nama}</td>
                                                        <td hidden>${item.unit_jual}</td>
                                                        <td hidden>${item.stok}</td>
                                                        <td hidden>${item.order}</td>
                                                        <td hidden>${item.price}</td>
                                                        <td hidden>${item.field_total}</td>
                                                        <td hidden>${item.kode_sumber}</td>
                                                        <td hidden>${item.diskon1}</td>
                                                        <td hidden>${item.diskon2}</td>
                                                        <td hidden>${item.diskon3}</td>
                                                        <td hidden>${item.penjualan_rata}</td>
                                                        <td hidden>${item.waktu_kunjungan}</td>
                                                        <td hidden>${item.stok_minimum}</td>
                                                        <td hidden>${item.stok_maksimum}</td>
                                                        <td hidden>${item.is_ppn}</td>
                                                        <td hidden>${nomor_po}</td>
                                                        <td>${item.nama}</td>
                                                        <td class="text-end">${item.order}</td>
                                                        <td class="text-end">${number_format(item.price)}</td>
                                                        <td class="text-end">${number_format(item.field_total)}</td>
                                                        <td class="text-center">${nomor_po}</td>
                                                        <td class="text-center">${group.date_first}</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="checkbox-group" data-nomor-po="${nomor_po}" id="checkbox-${nomor_po}-${index}">
                                                        </td>
                                                    </tr>`
                                                );
                                            });
                                        });

                                        // Attach event listener for checkboxes
                                        $('.checkbox-group').on('change', function() {
                                            const nomorPo = $(this).data('nomor-po');
                                            const isChecked = this.checked;

                                            // Check or uncheck all checkboxes with the same nomor_po
                                            $(`.checkbox-group[data-nomor-po="${nomorPo}"]`).prop('checked', isChecked);
                                        });

                                        // Hide or show specific rows as needed
                                        $('#targetRow4').attr('hidden', 'hidden');
                                        $('#button-baru').removeAttr('hidden');
                                        // $('#button-tetap').removeAttr('hidden');
                                        $('#button-dihapus').removeAttr('hidden');
                                    } else {
                                        console.warn('Expected an array but received:', response);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX Error:', status, error);
                                }
                            });
                        }
                        selectItemsSupplier.appendChild(div);
                    } else {
                        filterFunction();
                        selectItemsSupplier.style.display = 'block';
                    }
                }
            });

            closeAllSelect();
        });

        document.getElementById('button-baru').addEventListener('click', function() {
            // Collect selected data
            const selectedItems = [];
            const nomorPos = new Set();

            $('#po_details tr').each(function() {
                const checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.is(':checked')) {
                    const rowData = {
                        kode: $(this).find('td:nth-child(1)').text(),
                        nama: $(this).find('td:nth-child(2)').text(),
                        unit_jual: $(this).find('td:nth-child(3)').text(),
                        stok: $(this).find('td:nth-child(4)').text(),
                        order: $(this).find('td:nth-child(5)').text(),
                        price: $(this).find('td:nth-child(6)').text(),
                        field_total: $(this).find('td:nth-child(7)').text(),
                        kode_sumber: $(this).find('td:nth-child(8)').text(),
                        diskon1: $(this).find('td:nth-child(9)').text(),
                        diskon2: $(this).find('td:nth-child(10)').text(),
                        diskon3: $(this).find('td:nth-child(11)').text(),
                        penjualan_rata: $(this).find('td:nth-child(12)').text(),
                        waktu_kunjungan: $(this).find('td:nth-child(13)').text(),
                        stok_minimum: $(this).find('td:nth-child(14)').text(),
                        stok_maksimum: $(this).find('td:nth-child(15)').text(),
                        is_ppn: $(this).find('td:nth-child(16)').text(),
                    };
                    selectedItems.push(rowData);

                    // Collect nomor_po
                    const nomorPo = $(this).find('td:nth-child(17)').text(); // Adjust the index for nomor_po
                    nomorPos.add(nomorPo); // Add to Set
                }
            });

            // Create a hidden input field to store the selected items as JSON
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'details';
            input.value = JSON.stringify(selectedItems);
            document.querySelector('form').appendChild(input);

            const nomorPoInput = document.createElement('input');
            nomorPoInput.type = 'hidden';
            nomorPoInput.name = 'old_nomor_po'; // Set the name as needed
            nomorPoInput.value = Array.from(nomorPos).join(','); // Join with a comma for submission
            document.querySelector('form').appendChild(nomorPoInput);

            // Now submit the form
            document.querySelector('form').submit();
        });

        document.getElementById('button-dihapus').addEventListener('click', function() {
            const nomorPos = new Set();

            // Collect selected nomor_po
            $('#po_details tr').each(function() {
                const checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.is(':checked')) {
                    const nomorPo = $(this).find('td:nth-child(17)').text(); // Adjust index for nomor_po
                    nomorPos.add(nomorPo);
                }
            });

            const uniqueNomorPos = Array.from(nomorPos);

            if (uniqueNomorPos.length === 0) {
                alert('Silakan pilih PO yang ingin dihapus.');
                return;
            }

            // Send delete request
            $.ajax({
                url: '/destroy-receive-data',
                method: 'DELETE',
                data: {
                    nomor_po: uniqueNomorPos,
                    _token: $('input[name="_token"]').val() // Include CSRF token if using Laravel
                },
                success: function(response) {
                    // Handle success (e.g., refresh the table or show a message)
                    alert('PO berhasil dihapus.');
                    location.reload(); // Reload the page or update UI
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting PO:', error);
                    alert('Gagal menghapus PO. Silakan coba lagi.');
                }
            });
        });
    </script>
@endsection
