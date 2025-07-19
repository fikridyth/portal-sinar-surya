@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER PERSEDIAAN</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="product-table">
                    <thead>
                        <tr class="text-center">
                            <th>NAMA BARANG</th>
                            <th>NO BARANG</th>
                            <th>SUMBER</th>
                            <th>TINGKAT</th>
                            <th>U. BELI</th>
                            <th>U. JUAL</th>
                            <th>KONVERSI</th>
                            <th>HARGA BELI</th>
                            <th>HARGA JUAL</th>
                            <th>ALTERNATIF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $product->nama . '/' . $product->unit_jual }}</td>
                            <td>{{ $product->kode }}</td>
                            <td>{{ $product->kode_sumber }}</td>
                            <td>{{ $product->kode_sumber === null ? 'SUMBER' : 'ANAK' }}</td>
                            <td>{{ $product->unit_beli }}</td>
                            <td>{{ $product->unit_jual }}</td>
                            <td>{{ $product->konversi . '.00' }}</td>
                            <td class="harga_beli_parent">{{ number_format($product->harga_pokok) }}</td>
                            <td>{{ number_format($product->harga_jual) }}</td>
                            <td>{{ $product->kode_alternatif }}</td>
                        </tr>
                        @foreach ($childProduct as $child)
                            <tr>
                                <td>{{ $child->nama . '/' . $child->unit_jual }}</td>
                                <td>{{ $child->kode }}</td>
                                <td>{{ $child->kode_sumber }}</td>
                                <td>{{ $child->kode_sumber === null ? 'SUMBER' : 'ANAK' }}</td>
                                <td>{{ $child->unit_beli }}</td>
                                <td>{{ $child->unit_jual }}</td>
                                <td>{{ $child->konversi . '.00' }}</td>
                                <td>{{ number_format($child->harga_pokok) }}</td>
                                <td>{{ number_format($child->harga_jual) }}</td>
                                <td>{{ $child->kode_alternatif }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-primary mx-3" id="data-anak-btn">DATA ANAK</button>
                    <button class="btn btn-primary mx-3" id="data-sumber-btn" disabled>DATA SUMBER</button>
                    <button class="btn btn-success mx-3" id="simpan-btn" disabled>SIMPAN</button>
                    <a href="{{ route('master.product.child', enkrip($product->id)) }}" class="btn btn-danger mx-3 batal disabled-link">BATAL</a>
                    <a href="{{ route('master.product.show', enkrip($product->id)) }}" class="btn btn-success mx-3 selesai">SELESAI</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
        document.getElementById('data-anak-btn').click();
        }
    });

    // Pass PHP data to JavaScript as a single object
    var product = @json($product); // Convert PHP object to JSON
    var nextKode = @json($nextKode); // Convert PHP object to JSON

    function formatCurrency(value) {
        // Remove any non-numeric characters (e.g., 'P') and convert to number
        var numericValue = parseFloat(value.replace(/[^\d.-]/g, ''));
        // Return as a string with two decimal places
        return numericValue;
    }

    function addChildRowToTable(product, nextKode) {
        // Define the new row's content
        // <td><input type="text" size="7" name="harga_jual" class="harga_jual_value" value="${new Intl.NumberFormat().format(product.harga_jual / formatCurrency(product.unit_beli))}" /></td>
        var newRow = `
            <tr>
                <td class='nama_value' data-nama-value='${product.nama}'>${product.nama + '/P1'}</td>
                <td class='kode_value'>${nextKode}</td>
                <td class='kode_sumber_value'>${product.kode}</td>
                <td class='tipe_data' id='tipe_data'>ANAK</td>
                <td class='unit_beli_value' data-unit-beli='${formatCurrency(product.unit_beli)}'>P${formatCurrency(product.unit_beli)}</td>
                <td>
                    P&nbsp;<input type="text" class='unit_jual_value' id='unit_jual_value' autocomplete="off" size="3" name="unit_jual" value="" autofocus
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                        onkeydown="if(event.key === 'Enter') document.getElementById('harga_jual_value').focus();" />
                </td>
                <td class='konversi_value'>${formatCurrency(product.unit_beli)}.00</td>
                <td class='harga_beli_value' name='harga_beli'>${new Intl.NumberFormat().format(Math.floor(product.harga_pokok / formatCurrency(product.unit_beli)))}</td>
                <td>
                    <input type="text" size="7" name="harga_jual" class="harga_jual_value" id="harga_jual_value" value=""
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                        onkeydown="if(event.key === 'Enter') document.getElementById('kode_alternatif_value').focus();" />
                </td>
                <td>
                    <input type="text" size="7" name="kode_alternatif" class="kode_alternatif_value" id="kode_alternatif_value" value=""
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                        onkeydown="if(event.key === 'Enter') document.getElementById('simpan-btn').click();" />
                </td>
            </tr>
        `;

        // Get the table body element
        var tableBody = document.querySelector('#product-table tbody');

        // Insert the new row at the end of the table body
        tableBody.insertAdjacentHTML('beforeend', newRow);

        // Reattach event listeners after the new row is added
        attachEventListeners();
    }

    function addParentRowToTable(product, nextKode) {
        // Define the new row's content
        var newRow = `
            <tr>
                <td class='nama_value' data-nama-value='${product.nama}'>${product.nama}</td>
                <td class='kode_value'>${nextKode}</td>
                <td class='kode_sumber_value' data-kode-sumber-parent='${product.kode}'>${nextKode}</td>
                <td class='tipe_data' id='tipe_data'>SUMBER</td>
                <td><input type="text" class='unit_beli_value' size="5" name="unit_beli" value="1" /></td>
                <td class='unit_jual_value' data-unit-jual-parent='${formatCurrency(product.unit_beli)}'></td>
                <td class='konversi_value' data-profit-parent='${product.profit}'>0.00</td>
                <td class='harga_beli_value' data-harga-beli-parent='${new Intl.NumberFormat().format(product.harga_pokok)}'>0</td>
                <td class='harga_jual_value' data-harga-jual-parent='${new Intl.NumberFormat().format(product.harga_jual)}'>0</td>
                <td></td>
            </tr>
        `;

        // Get the table body element
        var tableBody = document.querySelector('#product-table tbody');

        // Insert the new row at the end of the table body
        tableBody.insertAdjacentHTML('beforeend', newRow);

        // Reattach event listeners after the new row is added
        attachEventListeners();
    }

    function attachEventListeners() {
        // Get all unit_jual input elements
        var unitJualElements = document.querySelectorAll('.unit_jual_value');
        unitJualElements.forEach(input => {
            input.addEventListener('input', updateKonversi);
        });
    }

    function updateKonversi(event) {
        const tableBody = document.querySelector('#product-table tbody');
        const firstRow = tableBody.querySelector('tr');

        const inputField = event.target;
        const row = inputField.closest('tr');
        const unitBeli = parseFloat(row.querySelector('.unit_beli_value').getAttribute('data-unit-beli'));
        const unitJual = parseFloat(inputField.value.replace(/[^\d.-]/g, '')); // Clean input value

        const namaValueElement = row.querySelector('.nama_value');
        const konversiElement = row.querySelector('.konversi_value');
        const hargaBeliValueElement = row.querySelector('.harga_beli_value');
        const hargaJualValueElement = row.querySelector('.harga_jual_value');
        
        const hargaBeliParent = firstRow.querySelector('.harga_beli_parent');
        // Extract the text content from the cell
        const hargaBeliText = hargaBeliParent.textContent.trim();
        // Remove commas (and other non-numeric characters if necessary)
        const hargaBeliParentElement = parseFloat(hargaBeliText.replace(/,/g, ''));

        if (unitJual !== 0) {
            // Calculate conversion rate
            const konversi = Math.floor(unitBeli / unitJual);
            // Calculate updated harga beli
            const hargaBeliUpdate = Math.floor(hargaBeliParentElement / konversi);
        // console.log(konversiElement, hargaBeliParentElement, konversi, hargaBeliUpdate)

            // Update HTML elements
            namaValueElement.textContent = `${product.nama + '/P' + unitJual}`;
            konversiElement.textContent = konversi.toFixed(2); // Keep 2 decimal places
            hargaBeliValueElement.textContent = parseFloat(hargaBeliUpdate); // Update input value
            hargaJualValueElement.value = parseFloat(hargaBeliUpdate); // Update input value
        } else {
            // Handle division by zero
            konversiElement.textContent = 0;
            hargaBeliValueElement.textContent = 0;
        }
    }

    // Attach the click event to the button
    document.getElementById('data-anak-btn').addEventListener('click', function() {
        const button = event.target;
        const buttonSumber = document.getElementById('data-sumber-btn');
        const buttonSimpan = document.getElementById('simpan-btn');
        buttonSimpan.disabled = false;
        button.disabled = true;
        buttonSumber.disabled = true;

        const link = document.querySelector('a.batal.disabled-link');
        const links = document.querySelector('a.selesai');
        if (link) {
            link.classList.remove('disabled-link');
            links.classList.add('disabled-link');
        }
        addChildRowToTable(product, nextKode); // Add the single product to the table
    });

    // Attach the click event to the button
    document.getElementById('data-sumber-btn').addEventListener('click', function() {
        const button = event.target;
        const buttonAnak = document.getElementById('data-anak-btn');
        const buttonSimpan = document.getElementById('simpan-btn');
        buttonSimpan.disabled = false;
        button.disabled = true;
        buttonAnak.disabled = true;

        const link = document.querySelector('a.batal.disabled-link');
        if (link) {
            link.classList.remove('disabled-link');
        }
        addParentRowToTable(product, nextKode); // Add the single product to the table
    });

    document.getElementById('simpan-btn').addEventListener('click', function() {
        const inputField = event.target;
        const tipeData = document.getElementById('tipe_data');
        if (tipeData.textContent === 'SUMBER') {
            saveToDatabaseParent();
        } else {
            saveToDatabaseChild();
        }
    });

    function saveToDatabaseParent(event) {
        // Get the table body
        const tableBody = document.querySelector('#product-table tbody');
        
        // Find all rows in the table body
        const rows = tableBody.querySelectorAll('tr');
        
        // Get the last row
        const lastRow = rows[rows.length - 1]; // Gets the last row in the NodeList
        
        // Extract data from the last row
        const unitBeliCell = lastRow.querySelector('.unit_beli_value');
        const hargaBeliInput = lastRow.querySelector('.harga_beli_value');
        const hargaJualInput = lastRow.querySelector('.harga_jual_value');
        const kodeAlternatifInput = lastRow.querySelector('.kode_alternatif_value');
        const tipeDataInput = lastRow.querySelector('.tipe_data');
        const namaValueInput = lastRow.querySelector('.nama_value').getAttribute('data-nama-value');
        const parentKodeSumberInput = lastRow.querySelector('.kode_sumber_value').getAttribute('data-kode-sumber-parent');
        const parentHargaBeliInput = lastRow.querySelector('.harga_beli_value').getAttribute('data-harga-beli-parent');
        const parentHargaJualInput = lastRow.querySelector('.harga_jual_value').getAttribute('data-harga-jual-parent');
        const parentUnitJualInput = lastRow.querySelector('.unit_jual_value').getAttribute('data-unit-jual-parent');
        const parentProfitInput = lastRow.querySelector('.konversi_value').getAttribute('data-profit-parent');
        const kodeValueInput = lastRow.querySelector('.kode_value');
        const kodeSumberValueInput = lastRow.querySelector('.kode_sumber_value');

        const rowData = {
            nama: namaValueInput ? namaValueInput : '',
            kode: kodeValueInput ? kodeValueInput.textContent : '',
            tipe: tipeDataInput ? tipeDataInput.textContent : '',
            unit_beli: unitBeliCell ? unitBeliCell.value : '',
            unit_jual: parentUnitJualInput,
            harga_beli: parentHargaBeliInput.replace(/[^0-9]/g, ''),
            harga_jual: parentHargaJualInput.replace(/[^0-9]/g, ''),
            profit: parentProfitInput,
            konversi: 1,
            kode_sumber: parentKodeSumberInput,
        };

        // Send data to the server via an AJAX request
        fetch('/master/store-product-parent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(rowData)
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 400) {
                    return response.json().then(result => {
                        throw new Error(result.message || 'Bad request.');
                    });
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                const newProductId = result.data.id;
                const newUrl = `/master/product/parent/${newProductId}`;
                window.location.href = newUrl;
            } else {
                alert(result.message || 'Failed to store data.');
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    }

    function saveToDatabaseChild(event) {
        // Get the table body
        const tableBody = document.querySelector('#product-table tbody');
        
        // Find all rows in the table body
        const rows = tableBody.querySelectorAll('tr');
        
        // Get the last row
        const lastRow = rows[rows.length - 1]; // Gets the last row in the NodeList
        
        // Extract data from the last row
        const unitBeliCell = lastRow.querySelector('.unit_beli_value');
        const unitJualInput = lastRow.querySelector('.unit_jual_value');
        const hargaBeliInput = lastRow.querySelector('.harga_beli_value');
        const hargaJualInput = lastRow.querySelector('.harga_jual_value');
        const kodeAlternatifInput = lastRow.querySelector('.kode_alternatif_value');
        const tipeDataInput = lastRow.querySelector('.tipe_data');
        const namaValueInput = lastRow.querySelector('.nama_value').getAttribute('data-nama-value');
        const kodeValueInput = lastRow.querySelector('.kode_value');
        const kodeSumberValueInput = lastRow.querySelector('.kode_sumber_value');
        const profitValueInput = hargaBeliInput.textContent !== 0 ? ((hargaJualInput.value.replace(/[^0-9]/g, '') - hargaBeliInput.textContent.replace(/[^0-9]/g, '')) / hargaBeliInput.textContent.replace(/[^0-9]/g, '')) * 100 : 0;
        const konversiValueInput = lastRow.querySelector('.konversi_value');
        function cleanKonversiValue(value) {
            // Check if the value ends with '.00'
            if (value.endsWith('.00')) {
                // Remove '.00'
                return value.slice(0, -3).trim() || 'null';
            }
            return value.trim();
        }

        const rowData = {
            unit_beli: unitBeliCell ? unitBeliCell.getAttribute('data-unit-beli') : '',
            unit_jual: unitJualInput ? unitJualInput.value : '',
            harga_pokok: hargaBeliInput ? hargaBeliInput.textContent.replace(/[^0-9]/g, '') : '',
            harga_jual: hargaJualInput ? hargaJualInput.value.replace(/[^0-9]/g, '') : '',
            kode_alternatif: kodeAlternatifInput.value ? kodeAlternatifInput.value : '',
            nama: namaValueInput ? namaValueInput : '',
            kode: kodeValueInput ? kodeValueInput.textContent : '',
            tipe: tipeDataInput ? tipeDataInput.textContent : '',
            konversi: konversiValueInput ? cleanKonversiValue(konversiValueInput.textContent) : '',
            kode_sumber: kodeSumberValueInput ? kodeSumberValueInput.textContent : '',
            profit: profitValueInput,
        };

        // Send data to the server via an AJAX request
        fetch('/master/store-product-child', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(rowData)
        })
        .then(response => {
            if (!response.ok) {
                document.getElementById('unit_jual_value').focus();
                throw new Error(`Isi data unit jual!`);
            }
            return response.json();
        })
        .then(result => {
            // console.log('Success:', result);

            if (result.success) {
                const url = window.location.href;
                const urlParts = url.split('/');
                const id = urlParts[urlParts.length - 1]; // Get the ID

                // Build the new URL with the ID
                const newUrl = `/master/product/child/${id}`;
                window.location.href = newUrl;
            } else {
                // Handle any specific success cases or messages
                alert('Failed to store data.');
            }
        })
        .catch(error => {
            // console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        });
    }

    // Initial setup
    attachEventListeners();
</script>

@endsection
