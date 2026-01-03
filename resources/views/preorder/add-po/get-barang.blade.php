@extends('main')

@section('styles')
<style>
    tr.selected td {
        background-color: #e9f5ff !important;
    }

    .custom-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.5);
        z-index: 9999;
    }

    .custom-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-modal-content {
        background: #fff;
        width: 100%;
        height: 100%;
        overflow: auto;
        border-radius: 6px;
        padding: 20px;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="card mt-n3">
            <div class="card-body">
                <div class="card-body mt-n5" style="font-size: 14px;">
                    <div class="d-flex justify-content-between">
                        <div class="row w-100 mt-1">
                            <div class="form-group col-9">
                                <div class="row">
                                    <label for="nomorSupplier1" class="col-sm-2 col-form-label">Supplier Header</label>
                                    <div class="col-sm-2">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier1->nomor }}" placeholder="Email">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier1->nama }}" placeholder="Email">
                                        <input type="hidden" name="supplierId" value="{{ $supplier1->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <div class="row">
                                    <label for="inputPassword3" class="col-sm-6 col-form-label">Penjualan Rata2</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="{{ $penjualanRata }}" name="penjualan_rata" class="form-control" id="inputPassword3">
                                    </div>
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="row w-100">
                            <div class="form-group col-9">
                                <div class="row">
                                    <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 2</label>
                                    <div class="col-sm-2">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier2->nomor ?? '' }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier2->nama ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <div class="row">
                                    <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="{{ $waktuKunjungan }}" name="waktu_kunjungan" class="form-control" id="inputPassword3">
                                    </div>
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="row w-100">
                            <div class="form-group col-9">
                                <div class="row">
                                    <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 3</label>
                                    <div class="col-sm-2">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier3->nomor ?? '' }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" disabled class="form-control form-control-sm" id="inputEmail3"
                                            value="{{ $supplier3->nama ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <div class="row">
                                    <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Minimum</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="{{ $stokMinimum }}" name="stok_minimum" oninput="updateStokMin(this)" class="form-control" id="inputStokMinimum">
                                    </div>
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="row w-100">
                            <div class="form-group col-9">
                            </div>
                            <div class="form-group col-3">
                                <div class="row">
                                    <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Maksimum</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="{{ $stokMaksimum }}" name="stok_maksimum" oninput="updateStokMax(this)" class="form-control" id="inputStokMaximum">
                                    </div>
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <div class="row w-100">
                            <div class="form-group col-12">
                                <div class="mb-3">
                                    <h6 class="mb-2">DAFTAR BARANG TERPILIH</h6>
                                    <div style="overflow-x:auto; max-height:200px; border:1px solid #ccc;">
                                        <table class="table table-bordered table-sm" id="selected-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">NAMA BARANG</th>
                                                    <th class="text-center">ISI</th>
                                                    <th class="text-center">PENJUALAN</th>
                                                    <th class="text-center">MINIMUM</th>
                                                    <th class="text-center">STOK</th>
                                                    <th class="text-center">MAKSIMUM</th>
                                                    <th class="text-center">ORDER</th>
                                                    <th class="text-center">V</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="empty-selected">
                                                    <td colspan="8" class="text-center text-muted">
                                                        Belum ada barang dipilih
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div style="overflow-x: auto; height: 350px; border: 1px solid #ccc;">
                                    <table class="table table-bordered table-sm" style="width: 100%; table-layout: auto;">
                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                            <tr>
                                                <th class="text-center">NAMA BARANG</th>
                                                <th class="text-center">ISI</th>
                                                <th class="text-center">PENJUALAN</th>
                                                <th class="text-center">MINIMUM</th>
                                                <th class="text-center">STOK</th>
                                                <th class="text-center">MAKSIMUM</th>
                                                <th class="text-center">ORDER</th>
                                                <th class="text-center">HARGA</th>
                                                <th class="text-center">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allProducts  as $index => $product)
                                                <tr
                                                    data-kode="{{ $product['kode'] }}"
                                                    data-kode-sumber="{{ $product['kode_sumber'] ?? '' }}"
                                                    data-nama="{{ $product['nama'] }}"
                                                    data-stok="{{ $product['stok'] }}"
                                                    data-isi="{{ $product['isi'] }}"
                                                    data-harga="{{ $product['harga_pokok'] }}"
                                                    data-jual="{{ $product['unit_jual'] }}"
                                                    data-diskon1="{{ $product['diskon1'] }}"
                                                    data-diskon2="{{ $product['diskon2'] }}"
                                                    data-diskon3="{{ $product['diskon3'] }}"
                                                    data-id-supplier="{{ $product['id_supplier'] }}"
                                                    data-harga-lama="{{ $product['harga_lama'] }}"
                                                >
                                                    <td>{{ $product['nama'] . '/' . $product['unit_jual'] }}</td>
                                                    <input type="text" hidden name="name[]" value="{{ $product['nama'] . '/' . $product['unit_jual'] . '/' . number_format($product['stok'], 2) . '/' . $product['harga_pokok']}}">
                                                    <input type="text" hidden name="stock[]" value="{{ number_format($product['stok'], 2)}}">
                                                    <input type="text" hidden name="harga[]" value="{{ $product['harga_pokok'] }}">
                                                    <input type="text" hidden name="previous_url" value="{{ $previousUrl }}">
                                                    <td class="text-end">{{ str_replace('P', '', $product['unit_jual']) }}</td>
                                                    <td class="text-end average" id="average-{{ $index }}">{{ $product['average'] ?? 0 }}</td>
                                                    <td class="text-end minimum" id="minimum-{{ $index }}">{{ $product['minimum'] ?? 0 }}</td>
                                                    <td class="text-end">{{ number_format($product['stok'], 2)}}</td>
                                                    <td class="text-end maximum" id="maximum-{{ $index }}">{{ $product['maximum'] ?? 0 }}</td>
                                                    <td class="text-center">
                                                        <input type="text" name="orderPo[]" size="3" data-index="{{ $index }}" oninput="updateTotal(this)" id="orderPoInput-{{ $index }}"
                                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $product['maximum'] <= 0 || $product['stok'] >= $product['maximum'] ? 0 : number_format(round($product['stok'] - $product['maximum']),0) }}">
                                                    </td>
                                                    <td class="text-end" id="price-{{ $index }}">{{ number_format($product['harga_pokok']) }}</td>
                                                    <td class="text-end" id="total-{{ $index }}">0</td>
                                                    <td class="text-end totally" hidden id="total-hidden-{{ $index }}">0</td>
                                                    <td hidden class="text-center"><input type="checkbox" hidden data-kode="{{ $product['kode'] }}" class="select-product-modal"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <!-- Content for the main part of the row goes here -->
                        </div>
                        <div class="col-3 d-flex align-items-center mx-5 mt-2">
                            <label for="total-price" class="col-5">TOTAL RP</label>
                            <input id="total-price" type="text" value="0" disabled class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2 mb-n4">
                        <button type="button" onclick="window.history.back()" class="btn btn-danger mx-5">BATAL</button>
                        <button type="button"
                                class="btn btn-primary"
                                id="openPoModal">
                            BUAT PO
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @include('preorder.add-po.get-barang-modal')
    </div> 
@endsection

@section('scripts')
@include('preorder.add-po.js.get-child-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let index = document.querySelectorAll('table tbody tr').length;

            // Handle changes to product selection checkboxes
            document.querySelectorAll('.select-product').forEach(function(checkbox) {
                checkbox.addEventListener('change', function(event) {
                    const kode = event.target.getAttribute('data-kode');
                    if (event.target.checked) {
                        event.target.disabled = true;
                        
                        // Fetch product data based on kode
                        fetch(`/get-products-by-kode-po/${kode}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.products) {
                                const tbody = document.querySelector('table tbody');
                                const parentRow = event.target.closest('tr');
                                data.products.forEach(product => {
                                    // Create a new row
                                        const newRow = document.createElement('tr');
                                        const stokValue = parseFloat(product.stok);
                                        const formattedStok = isNaN(stokValue) ? '' : stokValue.toFixed(2);

                                        newRow.innerHTML = `
                                            <input type="text" hidden name="name[]" value="${product.nama}/${product.unit_jual}/${formattedStok}/${product.harga_pokok}">
                                            <input type="text" hidden name="stock[]" value="${formattedStok}">
                                            <input type="text" hidden name="harga[]" value="${product.harga_pokok}">
                                            <td>${product.nama}/${product.unit_jual}</td>
                                            <td class="text-end">${product.unit_jual.replace('P', '')}</td>
                                            <td class="text-end">-</td>
                                            <td class="text-end">-</td>
                                            <td class="text-end">-</td>
                                            <td class="text-end">-</td>
                                            <td class="text-center">
                                                <input type="text" name="orderPo[]" size="3" data-index="${index}" 
                                                oninput="updateTotal(this)" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                            </td>
                                            <td class="text-end" id="price-${index}">${number_format(product.harga_pokok)}</td>
                                            <td class="text-end" id="total-${index}">0</td>
                                            <td class="text-end totally" hidden id="total-hidden-${index}">0</td>
                                            <td class="text-center"></td>
                                        `;

                                    // Insert the new row immediately after the parent row
                                    parentRow.insertAdjacentElement('afterend', newRow);

                                    // Increment index for next row
                                    index++;
                                });
                                // Update overall total price after adding new rows
                                updateTotalPrice();
                            }
                        });
                    }
                });
            });
        });

        // Function to update the total price for a specific product
        function updateTotal(input) {
            // Get the index of the product from the input field's data attribute
            const index = input.getAttribute('data-index');

            // Get the quantity from the input field
            let quantity = parseFloat(input.value) || 0;

            if (quantity < 0) {
                quantity = -quantity; // Convert negative to positive
            }
            input.value = quantity; // Update input field to reflect the change

            // Get the product price from the element with a specific id
            const priceElement = document.getElementById('price-' + index);
            if (!priceElement) {
                console.error(`Price element with id 'price-${index}' not found.`);
                return;
            }
            const price = parseFloat(priceElement.textContent.replace(/,/g, '')) || 0;

            // Calculate the total
            const total = quantity * price;

            // Update the total element with the calculated value
            const totalElement = document.getElementById('total-' + index);
            const totalHiddenElement = document.getElementById('total-hidden-' + index);
            if (totalElement) {
                totalElement.textContent = total.toLocaleString();
                if (totalHiddenElement) {
                    totalHiddenElement.textContent = total;
                }
            } else {
                console.error(`Total element with id 'total-${index}' not found.`);
            }

            // Recalculate and update the overall total price
            updateTotalPrice();
        }

        // Function to update the overall total price
        function updateTotalPrice() {
            let totalPrice = 0;
            document.querySelectorAll('.totally').forEach(element => {
                let value = element.textContent.replace(/,/g, '');
                totalPrice += parseInt(value, 10) || 0;
            });

            // Update the total-price input field
            document.getElementById('total-price').value = totalPrice.toLocaleString();
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        $(document).ready(function() {
            var selectedProducts = [];
            $('input[name="products[]"]:checked').each(function() {
                selectedProducts.push($(this)
            .val());
            });
        })

        document.addEventListener('DOMContentLoaded', () => {
            // Update stock maximum values when the page loads
            document.querySelectorAll('input.stok-max').forEach(input => {
                updateStokMax(input);
            });

            // Ensure the updateTotal function is called on quantity input change
            document.querySelectorAll('input[data-index]').forEach(input => {
                input.addEventListener('input', () => updateTotal(input));
            });

            const inputs = document.querySelectorAll('input[name="orderPo[]"]');
            // console.log(inputs)
            inputs.forEach(input => {
                updateTotal(input);
            });

            // Update total price initially
            updateTotalPrice();
        });

        // Function to update the stock maximum values
        function updateStokMax(inputElement) {
            // Get the value from the input field
            const stokMaksimum = parseFloat(inputElement.value);

            // Check if the input value is a valid number
            if (isNaN(stokMaksimum)) {
                console.error('Invalid stok_maksimum value');
                return;
            }

            // Iterate over each row to update maximum values
            document.querySelectorAll('tr').forEach(row => {
                const averageElement = row.querySelector('.average');
                const maximumElement = row.querySelector('.maximum');

                if (averageElement && maximumElement) {
                    const averageValue = parseFloat(averageElement.textContent.trim());

                    if (!isNaN(averageValue)) {
                        // Calculate the new maximum value
                        const newMaximum = stokMaksimum * averageValue;

                        // Update the maximum value in the table
                        maximumElement.textContent = newMaximum.toFixed(2); // Display with 2 decimal places
                    }
                }
            });
        }

        // Function to update the stock minimum values
        function updateStokMin(inputElement) {
            // Get the value from the input field
            const stokMinimum = parseFloat(inputElement.value);

            // Check if the input value is a valid number
            if (isNaN(stokMinimum)) {
                console.error('Invalid stok_maksimum value');
                return;
            }

            // Iterate over each row to update minimum values
            document.querySelectorAll('tr').forEach(row => {
                const averageElement = row.querySelector('.average');
                const minimumElement = row.querySelector('.minimum');

                if (averageElement && minimumElement) {
                    const averageValue = parseFloat(averageElement.textContent.trim());

                    if (!isNaN(averageValue)) {
                        // Calculate the new minimum value
                        const newMinimum = stokMinimum * averageValue;

                        // Update the minimum value in the table
                        minimumElement.textContent = newMinimum.toFixed(2); // Display with 2 decimal places
                    }
                }
            });
        }

        const poModal = document.getElementById('poModal');
        document.getElementById('openPoModal').addEventListener('click', () => {
            poModal.classList.add('show');
            fillPoModal();
        });
        document.getElementById('closePoModal').addEventListener('click', () => {
            poModal.classList.remove('show');
        });
        document.getElementById('cancelPoModal').addEventListener('click', () => {
            poModal.classList.remove('show');
        });
        poModal.addEventListener('click', e => {
            if (e.target === poModal) {
                poModal.classList.remove('show');
            }
        });

        function fillPoModal() {
            const tbody = document.querySelector('#modal-po-table tbody');
            const buatPoBtn = document.querySelectorAll('#poModal button[type="submit"]');
            tbody.innerHTML = '';
            let hasItem = false;

            document.querySelectorAll('.order-child, input[name="orderPo[]"]').forEach(input => {
                const order = parseFloat(input.value) || 0;
                if (order > 0) {
                    hasItem = true;
                    const row = input.closest('tr');

                    // Stok 2 angka di belakang koma
                    const stokFormatted = parseFloat(row.dataset.stok).toFixed(2);

                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td hidden><input type="text" name="stok[]" hidden value="${stokFormatted}"></td>
                            <td hidden><input type="text" name="nama[]" hidden value="${row.dataset.nama}"></td>
                            <td hidden><input type="text" name="unit_jual[]" hidden value="${row.dataset.jual}"></td>
                            <td hidden><input type="text" name="kode[]" hidden value="${row.dataset.kode}"></td>
                            <td hidden><input type="text" name="kode_sumber[]" hidden value="${row.dataset.kodeSumber}"></td>
                            <td hidden><input type="text" name="diskon1[]" hidden value="${row.dataset.diskon1}"></td>
                            <td hidden><input type="text" name="diskon2[]" hidden value="${row.dataset.diskon2}"></td>
                            <td hidden><input type="text" name="diskon3[]" hidden value="${row.dataset.diskon3}"></td>
                            <td hidden><input type="text" name="id_supplier[]" hidden value="${row.dataset.idSupplier}"></td>
                            <td hidden><input type="text" name="old_price[]" hidden value="${row.dataset.hargaLama}"></td>
                            <td>
                                <a href="#" class="link-to-table" data-kode="${row.dataset.kode}">
                                    ${row.dataset.nama}/${row.dataset.jual}
                                </a>
                            </td>
                            <td class="text-end">${stokFormatted}</td>
                            <td class="text-end" style="margin:0; height:10px;">
                                <input type="number" name="order[]" size="1" class="form-control order mb-n1" value="${order}" min="0" step="1" style="height: 25px;">
                                <input type="hidden" name="price[]" class="price" value="${row.dataset.harga}">
                                <input type="hidden" name="fieldtotal[]" class="fieldtotal" value="0">
                            </td>
                            <td class="text-end">${formatNumber(row.dataset.harga)}</td>
                            <td class="text-end" style="margin:0; height:10px;">
                                <input type="text" name="total[]" class="form-control total mb-n1" value="0.00" readonly style="height: 25px;">
                            </td>
                        </tr>
                    `);
                }
            });

            // Jika tidak ada item, tampilkan pesan modal-empty
            if (!hasItem) {
                tbody.innerHTML = `
                    <tr id="modal-empty">
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada barang yang di order
                        </td>
                    </tr>
                `;
                buatPoBtn.forEach(btn => btn.disabled = true); // Disable tombol BUAT PO
            } else {
                buatPoBtn.forEach(btn => btn.disabled = false); // Disable tombol BUAT PO
            }

            // Bind input event untuk menghitung total
            tbody.querySelectorAll('.order').forEach(input => {
                input.addEventListener('input', function() {
                    const row = input.closest('tr');
                    calculateTotal(row);
                });
            });

            // Hitung total awal
            tbody.querySelectorAll('tr').forEach(row => {
                if (!row.id || row.id !== 'modal-empty') calculateTotal(row);
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('link-to-table')) {
                e.preventDefault();
                const kode = e.target.dataset.kode;

                // Tutup modal
                const modal = document.getElementById('poModal');
                if (modal) {
                    modal.classList.remove('show'); 
                    document.body.classList.remove('modal-open'); 
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }

                // Cari row di table utama
                const targetRow = document.querySelector(`tr[data-kode="${kode}"]`);
                if (targetRow) {
                    // Scroll ke row
                    targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Highlight row sementara
                    targetRow.classList.add('highlight');
                    setTimeout(() => targetRow.classList.remove('highlight'), 2000);

                    // Fokus ke input order jika ada
                    const inputOrder = targetRow.querySelector('input[name="orderPo[]"]');
                    if (inputOrder) inputOrder.focus();
                }
            }
        });

        // Format number dengan 2 decimal dan comma
        function formatNumber(num) {
            return parseFloat(num).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Hitung total per row
        function calculateTotal(row) {
            const order = parseFloat(row.querySelector('.order').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const total = order * price;

            row.querySelector('.total').value = formatNumber(total);
            row.querySelector('.fieldtotal').value = total;
        }
    </script>
@endsection
