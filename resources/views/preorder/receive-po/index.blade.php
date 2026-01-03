@extends('main')

@php
    $totalPrice = 0;
    $totalOrder = 0;
@endphp
<style>
    .container-box {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        /* Optional: Adds space between columns */
    }

    .column {
        flex: 1 1 10%;
        /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
        box-sizing: border-box;
    }

    .form-group {
        margin: 0;
        text-align: center;
    }

    .col-form-label {
        font-size: 12px;
    }

    .col-2 {
        flex: 1 1 20%;
        /* Adjust width for column with different size */
    }

    .fs-need {
        font-size: 14px;
    }
</style>

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 82%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                PENERIMAAN BARANG - PURCHASE ORDER
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="nomorSupplier2"
                                            class="col col-form-label">Nomor PO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" value="{{ $preorder->nomor_po }}" disabled
                                                class="form-control" id="nomorSupplier2" value="">
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
                                            <input type="text" value="{{ $preorder->created_at->format('d/m/Y') }}"
                                                disabled class="form-control" id="inputPassword3">
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
                                    <input type="text" value="{{ $preorder->supplier->nomor }}" disabled
                                        class="form-control" id="nomorSupplier2" value="">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="col">
                                    <input type="text" value="{{ $preorder->supplier->nama }}" disabled
                                        class="form-control" id="nomorSupplier2" value="">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-3">
                            <div class="row w-100">
                                <div class="col-3">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="rata2" class="col-sm-5 col-form-label"
                                                style="font-size: 14px;">PENJ. RATA2</label>
                                            <div class="col-sm-3">
                                                <input type="text" value="" class="form-control" id="rata2"
                                                    disabled>
                                            </div>
                                            <label for="rata2" class="col-sm-2 col-form-label"
                                                style="font-size: 14px;">HR</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="maximum" class="col-sm-5 col-form-label"
                                                style="font-size: 14px;">MAKSIMUM</label>
                                            <div class="col-sm-3">
                                                <input type="text" value="" class="form-control" id="maximum"
                                                    disabled>
                                            </div>
                                            <label for="maximum" class="col-sm-2 col-form-label"
                                                style="font-size: 14px;">HR</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="stok" class="col-sm-4 col-form-label"
                                                style="font-size: 14px;">STOK</label>
                                            <div class="col-sm-6">
                                                <input type="text" value="" disabled class="form-control"
                                                    id="stok">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <form action="{{ route('daftar-po.set-diskon', $preorder->id) }}" method="POST"
                                                class="form">
                                                @csrf
                                                <div class="row align-items-center">
                                                    <div class="col-5">
                                                        <label for="totalPrice" class="mb-0">DISKON REGULER</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="hidden" name="total_harga"
                                                            value="{{ $preorder->total_harga }}">
                                                        {{-- @if ($preorder->diskon_global == 0) --}}
                                                            <input type="number" disabled required name="diskon_global"
                                                                class="form-control" style="width: 120px;">
                                                        {{-- @else
                                                            <input type="number" required name="diskon_global"
                                                                value="{{ $preorder->diskon_global }}"
                                                                class="form-control" style="width: 120px;">
                                                        @endif --}}
                                                    </div>
                                                    <div class="col-3">
                                                        <button type="submit" disabled class="btn btn-sm btn-primary">SET</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-box">
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">H. TERAKHIR</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 1</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 2</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 3</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">PPN(%)</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">HARGA RATA2</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 1</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 2</label></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><label class="col-form-label">DISKON 3</label></div>
                            </div>
                        </div>
                        <div class="container-box mb-3">
                            <div class="column">
                                <div class="form-group"><input type="text" id="price-input" disabled size="9">
                                </div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon1-input" disabled
                                        size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon2-input" disabled
                                        size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon3-input" disabled
                                        size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="ppn-input" disabled size="9">
                                </div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="price-ppn-input" disabled
                                        size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon4-input" disabled size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon5-input" disabled size="9"></div>
                            </div>
                            <div class="column">
                                <div class="form-group"><input type="text" id="diskon6-input" disabled size="9"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table id="details-table" class="table table-bordered">
                                        <thead>
                                            <tr class="fs-need">
                                                <th class="text-center">NO</th>
                                                <th class="text-center">&#9989;</th>
                                                <th class="text-center">KODE</th>
                                                <th class="text-center">NAMA BARANG</th>
                                                <th class="text-center">ISI</th>
                                                <th class="text-center">SAT</th>
                                                <th class="text-center">SATUAN</th>
                                                <th class="text-center">ORDER</th>
                                                <th class="text-center">TERIMA</th>
                                                <th class="text-center">HARGA</th>
                                                <th class="text-center">NETTO</th>
                                                <th class="text-center">JUMLAH</th>
                                                <th class="text-center">BNS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalItems = count(json_decode($preorder->detail, true));
                                            @endphp
                                            @foreach (json_decode($preorder->detail, true) as $index => $detail)
                                                @php
                                                    $currentIndex = $totalItems - $index;
                                                    $no = $index + 1;
                                                    $totalPrice += $detail['field_total'];
                                                    $totalOrder += $detail['order'];
                                                    if ($detail['is_ppn'] !== 0) {
                                                        $priceWithPpn =
                                                            ($detail['price'] * $detail['is_ppn']) / 100 +
                                                            $detail['price'];
                                                    } else {
                                                        $priceWithPpn = $detail['price'];
                                                    }
                                                @endphp
                                                <tr class="fs-need">
                                                    <td>{{ $no }}</td>
                                                    <td class="text-center">
                                                        <div class="select-container">
                                                            <input class="form-check-input select-checkbox"
                                                                type="checkbox" id="checkbox-{{ $no }}"
                                                                onchange="handleCheckboxChange(this)"
                                                                data-stok-value="{{ $detail['stok'] }}"
                                                                data-rata2-value="{{ $detail['penjualan_rata'] }}"
                                                                data-maximum-value="{{ $detail['stok_maksimum'] }}"
                                                                data-ppn-value="{{ $detail['is_ppn'] }}"
                                                                data-price-value="{{ $detail['price'] }}"
                                                                data-price-ppn-value="{{ $priceWithPpn }}"
                                                                data-diskon1-value="{{ $detail['diskon1'] }}"
                                                                data-diskon2-value="{{ $detail['diskon2'] }}"
                                                                data-diskon3-value="{{ $detail['diskon3'] }}"
                                                                data-diskon4-value="{{ $detail['diskon1'] }}"
                                                                data-diskon5-value="{{ $detail['diskon2'] }}"
                                                                data-diskon6-value="{{ $detail['diskon3'] }}" disabled>
                                                        </div>
                                                        <button class="btn btn-sm btn-primary mb-2" type="button"
                                                            id="edit-save-{{ $no }}" style="display:none;"
                                                            onclick="handleSaveClick(this)">Save</button>
                                                    </td>
                                                    <td class="text-center">{{ $detail['kode'] }}</td>
                                                    <td>{{ $detail['nama'] . '/' . $detail['unit_jual'] }}</td>
                                                    <td class="text-end">{{ str_replace('P', '', $detail['unit_jual']) }}
                                                    </td>
                                                    <td class="text-end">{{ str_replace('P', '', $detail['unit_jual']) }}
                                                    </td>
                                                    <td class="text-end">{{ number_format($detail['price']) }}</td>
                                                    <td class="text-end" id="order-view-text-{{ $no }}">
                                                        {{ $detail['order'] }}</td>
                                                    <td class="text-end">
                                                        <div class="order-container">
                                                            <span class="order-text"
                                                                id="order-text-{{ $no }}">{{ $detail['order'] }}</span>
                                                            <input type="text" class="order-input" hidden disabled
                                                                id="order-input-{{ $no }}"
                                                                value="{{ $detail['order'] }}" size="3">
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="price-container">
                                                            <span class="price-text"
                                                                id="price-text-{{ $no }}">{{ number_format($detail['price']) }}</span>
                                                            <input type="text" class="price-input"
                                                                id="price-input-{{ $no }}" hidden disabled
                                                                value="{{ $detail['price'] }}" size="10">
                                                        </div>
                                                    </td>
                                                    <td class="text-end netto" id="netto-{{ $no }}">
                                                        {{ number_format($detail['price']) }}</td>
                                                    <td class="text-end field-total"
                                                        id="field-total-{{ $no }}">
                                                        {{ number_format($detail['field_total']) }}</td>
                                                    <td>
                                                        <form action="{{ route('daftar-po.set-bonus', $preorder->id) }}"
                                                            method="POST" class="form">
                                                            @csrf
                                                            <div class="row align-items-center">
                                                                <input type="hidden" name="no"
                                                                    value="{{ $no - 1 }}">
                                                                <button type="submit" style="display:none;"
                                                                    id="bonus-save-{{ $no }}"
                                                                    class="btn btn-sm btn-primary">SET</button>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="current-index" value="{{ $totalItems }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex">
                                <div class="mx-2">
                                    <a href="{{ route('receive-po.create') }}" class="btn btn-success">TAMBAH DATA</a>
                                </div>
                                <div class="mx-2">
                                    <button type="button" class="btn btn-primary" disabled
                                        id="simpan-button">SIMPAN</button>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="mx-2">
                                    <label for="totalPrice" class="mt-1">Jumlah</label>
                                </div>
                                <div class="mx-2">
                                    <input type="text" id="totalPrice22"
                                        value="{{ number_format($preorder->total_harga) }}" disabled size="10"
                                        class="form-control">
                                </div>
                                <div class="mx-4">
                                </div>
                                {{-- <div class="mx-2">
                                        <label for="totalOrder" class="mt-1">Jumlah Koli</label>
                                    </div>
                                    <div class="mx-2">
                                        <input id="total-order" type="text" value="{{ number_format(1000000) }}" disabled size="5" class="form-control">
                                    </div> --}}
                                <div class="mx-2">
                                    <a class="btn btn-danger" href="{{ route('index') }}">KEMBALI</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex">
                            </div>
                            <div class="d-flex">
                                <form action="{{ route('daftar-po.set-ppn', $preorder->id) }}" method="POST"
                                    class="form">
                                    @csrf
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="totalPrice" class="mb-0">PPN</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="hidden" name="total_harga"
                                                value="{{ $preorder->total_harga }}">
                                            {{-- @if ($preorder->ppn_global == 0) --}}
                                                <input type="number" max="100" required name="ppn_global"
                                                    class="form-control" disabled style="width: 70px;">
                                            {{-- @else
                                                <input type="number" max="100" required name="ppn_global"
                                                    value="{{ $preorder->ppn_global }}" class="form-control"
                                                    style="width: 70px;">
                                            @endif --}}
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" disabled class="btn btn-sm btn-primary">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="mx-3">
                                </div>
                                <div class="mx-5">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                            </div>
                            <div class="d-flex">
                                <div class="mx-2">
                                    <label for="totalPrice" class="mt-1">Grand Total</label>
                                </div>
                                <div class="mx-2">
                                    <input type="text" id="totalPrice33"
                                        value="{{ number_format($preorder->grand_total) ?? number_format($preorder->total_harga) }}"
                                        disabled size="10" class="form-control">
                                </div>
                                <div class="mx-5">
                                </div>
                                <div class="mx-4">
                                </div>
                                <div class="mx-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('preorder.detail-po.js.netto')
    @include('preorder.detail-po.js.new-row')
    <script>
        function handleCheckboxChange(selectedCheckbox) {
            const index = selectedCheckbox.id.split('-')[1];

            // Get the discount value from the checkbox's data attribute
            const diskonValue = parseFloat(selectedCheckbox.getAttribute('data-diskon4-value'));

            // Get the netto element by its ID
            const priceInput = document.querySelector('.price-input');
            const nettoElement = document.getElementById(`netto-${index}`);
            const tambahButton = document.getElementById('tambah-button');
            // const hapusButton = document.getElementById('hapus-button');

            // Get the current price and parse it as a float
            let currentPrice = parseFloat(priceInput.value.replace(/[^0-9.-]+/g, ""));

            // Get the current netto value and parse it as a float
            let currentNetto = parseFloat(nettoElement.textContent.replace(/[^0-9.-]+/g, ""));

            // handle button
            var buttonId = "edit-save-" + selectedCheckbox.id.split('-')[1];
            var button = document.getElementById(buttonId);
            var buttonDId = "delete-save-" + selectedCheckbox.id.split('-')[1];
            var buttonD = document.getElementById(buttonDId);
            var buttonBId = "bonus-save-" + selectedCheckbox.id.split('-')[1];
            var buttonB = document.getElementById(buttonBId);

            // Apply or remove the discount based on the checkbox status
            if (selectedCheckbox.checked) {
                // Apply the discount if the checkbox is checked
                currentNetto -= diskonValue;
                // Hide the checkbox
                selectedCheckbox.style.display = 'none';
                // Show the corresponding button
                button.style.display = 'inline-block';
                buttonD.style.display = 'inline-block';
                buttonB.style.display = 'inline-block';
                tambahButton.disabled = true;
                // hapusButton.disabled = false;
            } else {
                // Remove the discount if the checkbox is unchecked
                currentNetto += diskonValue;
                // Show the checkbox
                selectedCheckbox.style.display = 'inline-block';
                // Hide the corresponding button
                button.style.display = 'none';
                buttonD.style.display = 'none';
                buttonB.style.display = 'none';
                tambahButton.disabled = false;
                // hapusButton.disabled = true;
            }

            // Update the netto value in the DOM
            nettoElement.textContent = new Intl.NumberFormat().format(currentNetto);

            // Disable or enable all checkboxes except the one that was selected
            const allCheckboxes = document.querySelectorAll('.select-checkbox');
            allCheckboxes.forEach(checkbox => {
                if (checkbox !== selectedCheckbox) {
                    checkbox.disabled = selectedCheckbox.checked;
                }
            });

            // Call function to handle other inputs if necessary
            toggleInputs(selectedCheckbox);
        }

        function handleSaveClick(button) {
            // Extract the index from the button's ID
            const index = button.id.split('-')[2];
            const tambahButton = document.getElementById('tambah-button');
            const buttonId = `delete-save-${index}`;
            const deleteButton = document.getElementById(buttonId);
            const buttonBId = `bonus-save-${index}`;
            const bonusButton = document.getElementById(buttonBId);

            // Get the price input and netto elements by their IDs
            const priceInput = document.getElementById(`price-input-${index}`);
            const orderInput = document.getElementById(`order-input-${index}`);
            const orderViewText = document.getElementById(`order-view-text-${index}`);
            orderViewText.textContent = orderInput.value;
            const orderText = document.getElementById(`order-text-${index}`);
            orderText.textContent = orderInput.value;

            const nettoElement = document.getElementById(`netto-${index}`);
            let nettoElementValue = nettoElement.textContent;
            nettoElementValue = nettoElementValue.replace(/\./g, '');
            const fieldTotalElement = document.getElementById(`field-total-${index}`);
            let fieldTotalElementValue = fieldTotalElement.textContent;
            fieldTotalElementValue = fieldTotalElementValue.replace(/\./g, '');

            const priceText = document.getElementById(`price-text-${index}`);
            priceText.textContent = nettoElement.textContent;

            // Prepare data to be sent
            var data = {
                id: {{ $preorder->id }},
                array: index - 1,
                price: priceInput.value,
                order: orderInput.value,
                netto: nettoElementValue,
                total: fieldTotalElementValue
            };

            // Perform AJAX request
            $.ajax({
                url: '{{ route('daftar-po.update') }}', // Use the named route to generate URL
                type: 'POST',
                data: data,
                headers: {
                    // 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle success response
                    if (response.success) {
                        document.getElementById(`totalPrice22`).value = response.newTotalHarga;
                        document.getElementById(`totalPrice33`).value = response.newGrandTotal;
                        // var redirectUrl = @json(route('daftar-po.edit', $preorder->id));
                        // window.location.href = redirectUrl;
                        tambahButton.disabled = false;
                        document.getElementById(`checkbox-${index}`).checked = false;
                        document.getElementById(`checkbox-${index}`).style.display = 'inline-block';
                        button.style.display = 'none';
                        deleteButton.style.display = 'none';
                        bonusButton.style.display = 'none';

                        document.querySelectorAll('.select-checkbox').forEach(checkbox => {
                            checkbox.disabled = false;
                            checkbox.style.display = 'inline-block';
                        });

                        document.querySelectorAll('.edit-save-button').forEach(btn => {
                            btn.style.display = 'none';
                        });

                        document.querySelectorAll('.order-container').forEach(container => {
                            container.querySelector('.order-text').hidden = false;
                            container.querySelector('.order-input').hidden = true;
                        });

                        document.querySelectorAll('.price-container').forEach(container => {
                            container.querySelector('.price-text').hidden = false;
                            container.querySelector('.price-input').hidden = true;
                        });

                        // Update total price after successful response
                        updateTotalPrice();
                        updateTotalOrder();
                    } else {
                        // Handle error response if needed
                        alert('Failed to save data.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.error('AJAX error:', status, error);
                    alert('An error occurred while saving data.');
                }
            });
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            document.querySelectorAll('.field-total').forEach(element => {
                let value = element.textContent.replace(/,/g, '');
                totalPrice += parseFloat(value) || 0;
            });

            // Update the total-price input field
            document.getElementById('total-price').value = totalPrice.toLocaleString();
        }

        function updateTotalOrder() {
            let totalOrder = 0;
            document.querySelectorAll('.order-text').forEach(element => {
                let value = element.textContent.replace(/,/g, '');
                totalOrder += parseFloat(value) || 0;
            });

            // Update the total-Order input field
            document.getElementById('total-order').value = totalOrder.toLocaleString();
        }

        function toggleInputs(checkbox) {
            const row = checkbox.closest('tr');
            const terimaInput = row.querySelector('.order-input');
            const hargaInput = row.querySelector('.price-input');
            var stokValue = checkbox.getAttribute('data-stok-value');
            var rata2Value = checkbox.getAttribute('data-rata2-value');
            var maximumValue = checkbox.getAttribute('data-maximum-value');
            var ppnValue = checkbox.getAttribute('data-ppn-value');
            var priceValue = checkbox.getAttribute('data-price-value');
            var pricePpnValue = checkbox.getAttribute('data-price-ppn-value');
            var priceDiskon1Value = checkbox.getAttribute('data-diskon1-value');
            var priceDiskon2Value = checkbox.getAttribute('data-diskon2-value');
            var priceDiskon3Value = checkbox.getAttribute('data-diskon3-value');
            var priceDiskon4Value = checkbox.getAttribute('data-diskon4-value');
            var priceDiskon5Value = checkbox.getAttribute('data-diskon5-value');
            var priceDiskon6Value = checkbox.getAttribute('data-diskon6-value');

            if (checkbox.checked) {
                terimaInput.removeAttribute('disabled');
                hargaInput.removeAttribute('disabled');
                document.getElementById('stok').value = stokValue;
                document.getElementById('rata2').value = rata2Value;
                document.getElementById('maximum').value = maximumValue;
                document.getElementById('ppn-input').value = ppnValue;
                document.getElementById('price-input').value = priceValue;
                document.getElementById('price-ppn-input').value = pricePpnValue;
                document.getElementById('diskon1-input').value = priceDiskon1Value;
                document.getElementById('diskon2-input').value = priceDiskon2Value;
                document.getElementById('diskon3-input').value = priceDiskon3Value;
                document.getElementById('diskon4-input').value = priceDiskon4Value;
                document.getElementById('diskon5-input').value = priceDiskon5Value;
                document.getElementById('diskon6-input').value = priceDiskon6Value;

                document.querySelectorAll('.order-container').forEach(container => {
                    container.querySelector('.order-text').hidden = true;
                    container.querySelector('.order-input').hidden = false;
                });

                document.querySelectorAll('.price-container').forEach(container => {
                    container.querySelector('.price-text').hidden = true;
                    container.querySelector('.price-input').hidden = false;
                });
            } else {
                terimaInput.setAttribute('disabled', 'disabled');
                hargaInput.setAttribute('disabled', 'disabled');
                document.getElementById('stok').value = '';
                document.getElementById('rata2').value = '';
                document.getElementById('maximum').value = '';
                document.getElementById('ppn-input').value = '';
                document.getElementById('price-input').value = '';
                document.getElementById('price-ppn-input').value = '';
                document.getElementById('diskon1-input').value = '';
                document.getElementById('diskon2-input').value = '';
                document.getElementById('diskon3-input').value = '';
                document.getElementById('diskon4-input').value = '';
                document.getElementById('diskon5-input').value = '';
                document.getElementById('diskon6-input').value = '';

                document.querySelectorAll('.order-container').forEach(container => {
                    container.querySelector('.order-text').hidden = false;
                    container.querySelector('.order-input').hidden = true;
                });

                document.querySelectorAll('.price-container').forEach(container => {
                    container.querySelector('.price-text').hidden = false;
                    container.querySelector('.price-input').hidden = true;
                });
            }
        }

        // Initialize event listeners
        function initializeButtons() {
            document.getElementById('diskon4-input').addEventListener('input', updateNettoValues);
        }

        // Add event listeners once the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', initializeButtons);
    </script>
@endsection
