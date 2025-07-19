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
                    <button class="btn btn-primary mx-3" id="data-anak-btn" disabled>DATA ANAK</button>
                    <button class="btn btn-primary mx-3" id="data-sumber-btn">DATA SUMBER</button>
                    <button class="btn btn-success mx-3" id="simpan-btn" disabled>SIMPAN</button>
                    <a href="{{ route('master.product.parent', enkrip($product->id)) }}" class="btn btn-danger mx-3 batal disabled-link">BATAL</a>
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
        document.getElementById('data-sumber-btn').click();
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

    function addParentRowToTable(product, nextKode) {
        const newRow = `
            <tr>
                <td class='nama_value' data-nama-value='${product.nama}'>${product.nama}</td>
                <td class='kode_value'>${nextKode}</td>
                <td class='kode_sumber_value' data-kode-sumber-parent='${product.kode}'>${nextKode}</td>
                <td class='tipe_data'>SUMBER</td>
                <td>
                    P&nbsp;<input type="text" class="unit_beli_value" size="4" name="unit_beli" value="" autocomplete="off" autofocus
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                </td>
                <td class="unit_jual_value" data-unit-jual-parent="${formatCurrency(product.unit_beli)}"></td>
                <td class="konversi_value" data-profit-parent="${product.profit}">0.00</td>
                <td class="harga_beli_value" data-harga-beli-parent="${new Intl.NumberFormat().format(product.harga_pokok)}">
                    <input type="text" size="7" name="parent_harga_beli" class="parent_harga_beli" value=""
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                </td>
                <td class="harga_jual_value" data-harga-jual-parent="${new Intl.NumberFormat().format(product.harga_jual)}">
                    <input type="text" size="7" name="parent_harga_jual" class="parent_harga_jual" value=""
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                </td>
                <td></td>
            </tr>
        `;

        const tableBody = document.querySelector('#product-table tbody');
        tableBody.insertAdjacentHTML('beforeend', newRow);

        // Dapatkan elemen baris terakhir (baru saja ditambahkan)
        const lastRow = tableBody.querySelector('tr:last-child');

        // Tambahkan event listener pada input unit beli
        const unitBeliInput = lastRow.querySelector('.unit_beli_value');
        if (unitBeliInput) {
            unitBeliInput.addEventListener('input', function () {
                updateParentHargaValues(lastRow);
            });
        }

        // Tambahkan event listener lainnya jika ada
        attachEventListeners();
    }

    function updateParentHargaValues(rowElement) {
        const unitBeliInput = rowElement.querySelector('.unit_beli_value');
        const unitJualValueElem = rowElement.querySelector('.unit_jual_value');
        const parentHargaBeliInput = rowElement.querySelector('.parent_harga_beli');
        const parentHargaJualInput = rowElement.querySelector('.parent_harga_jual');

        const unitBeli = parseFloat(unitBeliInput.value) || 0;
        const unitJualRaw = unitJualValueElem.getAttribute('data-unit-jual-parent') || '0';
        const unitJual = parseFloat(unitJualRaw.replace(/[^\d.]/g, '')) || 0;

        const hargaPokokRaw = rowElement.querySelector('.harga_beli_value').getAttribute('data-harga-beli-parent') || '0';
        const hargaJualRaw = rowElement.querySelector('.harga_jual_value').getAttribute('data-harga-jual-parent') || '0';
        const hargaPokok = parseFloat(hargaPokokRaw.replace(/[^\d.]/g, '')) || 0;
        const hargaJual = parseFloat(hargaJualRaw.replace(/[^\d.]/g, '')) || 0;

        if (unitBeli > 0 && unitJual > 0) {
            const newHargaBeli = ((hargaPokok / unitJual) * unitBeli).toFixed(0);
            const newHargaJual = ((hargaJual / unitJual) * unitBeli).toFixed(0);

            parentHargaBeliInput.value = newHargaBeli;
            parentHargaJualInput.value = newHargaJual;
        }
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
            const konversi = unitBeli / unitJual;
            // Calculate updated harga beli
            const hargaBeliUpdate = hargaBeliParentElement / konversi;
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

        const link = document.querySelector('a.disabled-link');
        if (link) {
            link.classList.remove('disabled-link');
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
        const links = document.querySelector('a.selesai');
        if (link) {
            link.classList.remove('disabled-link');
            links.classList.add('disabled-link');
        }
        addParentRowToTable(product, nextKode); // Add the single product to the table
    });

    document.getElementById('simpan-btn').addEventListener('click', function() {
        const inputField = event.target;
        saveToDatabaseParent();
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
        const tipeDataInput = lastRow.querySelector('.tipe_data');
        const namaValueInput = lastRow.querySelector('.nama_value').getAttribute('data-nama-value');
        const parentKodeSumberInput = lastRow.querySelector('.kode_sumber_value').getAttribute('data-kode-sumber-parent');
        const parentHargaBeliInputValue = lastRow.querySelector('.parent_harga_beli');
        const parentHargaBeliInput = lastRow.querySelector('.harga_beli_value').getAttribute('data-harga-beli-parent');
        const parentHargaJualInputValue = lastRow.querySelector('.parent_harga_jual');
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
            harga_beli_input: parentHargaBeliInputValue.value,
            harga_jual_input: parentHargaJualInputValue.value,
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
                document.getElementById('unit_beli_value').focus();
                throw new Error(`Isi data unit beli!`);
            }
            return response.json();
        })
        .then(result => {
            // console.log('Success:', result);

            if (result.success) {
                const newProductId = result.id;
                const newUrl = `/master/product/parent/${newProductId}`;
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
