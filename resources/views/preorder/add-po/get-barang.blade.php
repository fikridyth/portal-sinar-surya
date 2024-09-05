@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">DAFTAR BARANG YANG HARUS DIPESAN
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('preorder.process-barang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-2 col-form-label">Supplier Header</label>
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nomor }}" placeholder="Email">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nama }}" placeholder="Email">
                                            <input type="hidden" name="supplierName" value="{{ $supplier1->nama }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Penjualan Rata2</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->penjualan_rata }}" disabled class="form-control" id="inputPassword3">
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
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->waktu_kunjungan }}" disabled class="form-control" id="inputPassword3">
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
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Minimum</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->stok_minimum }}" disabled class="form-control" id="inputPassword3">
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
                                            <input type="text" value="{{ $penjualan->stok_maksimum }}" oninput="updateStokMax(this)" class="form-control" id="inputStokMaximum">
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
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
                                                <th class="text-center">CHILD</th>
                                                {{-- <th class="text-center">Harga Jual</th> --}}
                                                {{-- <th class="text-center">Pilih</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allProducts as $index => $product)
                                                <tr>
                                                    <td>{{ $product['nama'] . '/' . $product['unit_jual'] }}</td>
                                                    <input type="text" hidden name="name[]" value="{{ $product['nama'] . '/' . $product['unit_jual'] . '/' . number_format($product['stok'], 2) . '/' . $product['harga_pokok']}}">
                                                    <input type="text" hidden name="stock[]" value="{{ number_format($product['stok'], 2)}}">
                                                    <input type="text" hidden name="harga[]" value="{{ $product['harga_pokok'] }}">
                                                    <input type="text" hidden name="previous_url" value="{{ $previousUrl }}">
                                                    <td class="text-end">{{ str_replace('P', '', $product['unit_jual']) }}</td>
                                                    <td class="text-end average" id="average-{{ $index }}">{{ $product['average'] ?? '' }}</td>
                                                    <td class="text-end">{{ $product['minimum'] ?? '' }}</td>
                                                    <td class="text-end">{{ number_format($product['stok'], 2)}}</td>
                                                    <td class="text-end maximum" id="maximum-{{ $index }}">{{ $product['maximum'] ?? '' }}</td>
                                                    <td class="text-center">
                                                        <input type="text" name="orderPo[]" size="3" data-index="{{ $index }}" oninput="updateTotal(this)"
                                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                    </td>
                                                    <td class="text-end" id="price-{{ $index }}">{{ number_format($product['harga_pokok']) }}</td>
                                                    <td class="text-end" id="total-{{ $index }}">0</td>
                                                    <td class="text-end totally" hidden id="total-hidden-{{ $index }}">0</td>
                                                    <td class="text-center"><input type="checkbox" data-kode="{{ $product['kode'] }}" class="select-product"></td>
                                                    {{-- <td class="text-end">{{ number_format($product->harga_jual) }}</td> --}}
                                                    {{-- <td class="text-center"><input type="checkbox" id="products[]"
                                                            name="products[]" value="{{ $product->id }}"></td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <!-- Content for the main part of the row goes here -->
                            </div>
                            <div class="col-3 d-flex align-items-center mx-5">
                                <label for="total-price" class="col-5">TOTAL RP</label>
                                <input id="total-price" type="text" value="0" disabled class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" onclick="window.history.back()" class="btn btn-danger mx-5">BATAL</button>
                            <button type="submit" class="btn btn-primary">BUAT PO</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
                                            <td class="text-end">${formattedStok}</td>
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
            const quantity = parseFloat(input.value) || 0;

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
    </script>
@endsection
