@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PERSETUJUAN HARGA JUAL - PILIH BARANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">NOMOR BUKTI</label>
                            <input type="text" class="mx-2" disabled value="{{ $preorder->nomor_po }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">SUPPLIER</label>
                            <input type="text" class="mx-2" size="30" disabled value="{{ $preorder->supplier->nama }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">ALAMAT</label>
                            <input type="text" class="mx-2" size="35" disabled value="{{ $preorder->supplier->alamat1 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('persetujuan-harga-jual-update', $preorder->id) }}" method="POST" class="form">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">HARGA SEMENTARA</th>
                                <th class="text-center"></th>
                                <th colspan="2" class="text-center">HARGA SATUAN</th>
                                <th class="text-center"></th>
                                <th colspan="2" class="text-center">HARGA SATUAN</th>
                                <th colspan="2" class="text-center"></th>
                            </tr>
                            <tr>
                                <th class="text-center">&#9989;</th>
                                <th class="text-center">NAMA BARANG</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">BELI LAMA</th>
                                <th class="text-center">BELI BARU</th>
                                <th class="text-center">NAIK(%)</th>
                                <th class="text-center">JUAL LAMA</th>
                                <th class="text-center">JUAL BARU</th>
                                <th class="text-center">MK UP</th>
                                <th class="text-center">GANTI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $index => $dtl)
                                @php
                                    $product = App\Models\Product::where('kode', $dtl['kode'])->first();
                                    $changeTextColor = (($product->harga_jual - $dtl['price']) / $dtl['price']) * 100;
                                @endphp
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{ $dtl['nama'] . '/' . $dtl['unit_jual'] }}</td>
                                    <td class="text-center">{{ $dtl['order'] }}</td>
                                    <input type="text" hidden name="kode[{{ $index }}]" value="{{ $dtl['kode'] }}">
                                    <input type="text" hidden name="harga_pokok[{{ $index }}]" id="persetujuan_harga_pokok_{{ $index }}" value="{{ $dtl['price'] }}">
                                    <td class="text-center">{{ number_format($product->harga_pokok) }}</td>
                                    <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($dtl['price']) }}</td>
                                    <td class="text-center">{{ number_format((($dtl['price'] - $product->harga_pokok) / $product->harga_pokok) * 100, 2) }}</td>
                                    <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_jual) }}</td>
                                    <td class="text-center"><input type="text" name="harga_jual[{{ $index }}]" id="persetujuan_harga_jual_{{ $index }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $product->harga_jual }}" size="10"></td>
                                    <td class="text-center"><input type="text" name="mark_up[{{ $index }}]" id="persetujuan_mark_up_{{ $index }}" onkeypress='return validateNumberInput(event)' value="{{ number_format((($product->harga_jual - $dtl['price']) / $dtl['price']) * 100, 2) }}" size="5"></td>
                                    <td class="text-center"><input type="checkbox" data-kode="{{ $dtl['kode'] }}" class="select-product"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="row mt-4" style="margin-left: 25px;">
                        <div class="col-7"></div>
                        <div class="col-5">
                            <div class="form-group mx-2">
                                <label for="">TOTAL FAKTUR</label>
                                <input type="text" class="text-end mx-2 readonly-input" id="total_faktur" size="12" readonly>
                            </div>
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a class="btn btn-danger" href="{{ route('persetujuan-harga-jual') }}">KEMBALI</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" class="btn btn-primary">GANTI HARGA</button>
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
            // Initialize index counter
            let indexCounter = document.querySelectorAll('table tbody tr').length;

            // Handle changes to product selection checkboxes
            document.querySelectorAll('.select-product').forEach(function(checkbox) {
                checkbox.addEventListener('change', function(event) {
                    const kode = event.target.getAttribute('data-kode');
                    if (event.target.checked) {
                        event.target.disabled = true;

                        // Fetch product data based on kode
                        fetch(`/get-products-by-kode/${kode}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.products) {
                                    const tbody = document.querySelector('table tbody');
                                    const parentRow = event.target.closest('tr');
                                    data.products.forEach(product => {
                                        // Create a new row
                                        const newRow = document.createElement('tr');
                                        newRow.innerHTML = `
                                            <td class="text-center"></td>
                                            <td class="text-center">${product.nama}/${product.unit_jual}</td>
                                            <td class="text-center">0</td>
                                            <input type="text" hidden name="kode[${indexCounter}]" value="${product.kode}">
                                            <input type="text" hidden name="harga_pokok[${indexCounter}]" id="persetujuan_harga_pokok_${indexCounter}" value="${product.harga_pokok}">
                                            <td class="text-center">${number_format(product.harga_pokok)}</td>
                                            <td class="text-center">${number_format(product.harga_pokok)}</td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">${number_format(product.harga_jual)}</td>
                                            <td class="text-center">
                                                <input type="text" name="harga_jual[${indexCounter}]" id="persetujuan_harga_jual_${indexCounter}" value="${product.harga_jual}" size="10">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" name="mark_up[${indexCounter}]" id="persetujuan_mark_up_${indexCounter}" value="${number_format((product.harga_jual - product.harga_pokok) / product.harga_pokok * 100, 2)}" size="5">
                                            </td>
                                            <td class="text-center"></td>
                                        `;
                                        // tbody.appendChild(newRow);
                                        parentRow.insertAdjacentElement('afterend', newRow);
                                        
                                        // Attach event listeners for newly added rows
                                        attachEventListeners(indexCounter);
                                        indexCounter++;
                                    });
                                }
                            });
                    } else {
                        // Optional: Remove rows based on the unselected checkbox
                        const tbody = document.querySelector('table tbody');
                        tbody.querySelectorAll(`input[name="kode[]"][value="${kode}"]`).forEach(input => input.closest('tr').remove());
                        updateTotalFaktur();
                    }
                });
            });

            // Attach event listeners for pre-existing rows
            function attachEventListeners(index) {
                document.getElementById(`persetujuan_harga_jual_${index}`).addEventListener('input', function() {
                    updateMarkUp(index);
                });
                document.getElementById(`persetujuan_mark_up_${index}`).addEventListener('input', function() {
                    updateHargaJual(index);
                });
            }

            // Update markup percentage
            function updateMarkUp(index) {
                const hargaPokok = parseFloat(document.getElementById(`persetujuan_harga_pokok_${index}`).value) || 0;
                const hargaJual = parseFloat(document.getElementById(`persetujuan_harga_jual_${index}`).value) || 0;

                if (!isNaN(hargaPokok) && hargaPokok > 0) {
                    const markUpValue = ((hargaJual - hargaPokok) / hargaPokok) * 100;
                    document.getElementById(`persetujuan_mark_up_${index}`).value = markUpValue.toFixed(2);
                }
            }

            // Update selling price based on markup
            function updateHargaJual(index) {
                const hargaPokok = parseFloat(document.getElementById(`persetujuan_harga_pokok_${index}`).value) || 0;
                const markUp = parseFloat(document.getElementById(`persetujuan_mark_up_${index}`).value) || 0;

                if (!isNaN(hargaPokok) && hargaPokok > 0) {
                    const hargaJualValue = ((hargaPokok * markUp) / 100) + hargaPokok;
                    document.getElementById(`persetujuan_harga_jual_${index}`).value = hargaJualValue;
                }
            }

            @foreach ($detail as $index => $dtl)
                document.getElementById('persetujuan_harga_jual_{{ $index }}').addEventListener('input', function() {
                    updateMarkUp({{ $index }});
                });
                document.getElementById('persetujuan_mark_up_{{ $index }}').addEventListener('input', function() {
                    updateHargaJual({{ $index }});
                });
            @endforeach

            // Function to calculate the total of persetujuan_harga_jual fields
            function updateTotalFaktur() {
                let total = 0;
                document.querySelectorAll('[id^=persetujuan_harga_jual_]').forEach(element => {
                    const value = parseFloat(element.value.replace(/,/g, ''));
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                // Update the total_faktur input
                document.getElementById('total_faktur').value = total.toLocaleString();
            }

            // Attach event listeners to input fields
            document.querySelectorAll('[id^=persetujuan_harga_jual_]').forEach(input => {
                input.addEventListener('input', updateTotalFaktur);
            });

            // Initial calculation in case there are pre-filled values
            updateTotalFaktur();
        });

        function validateNumberInput(event) {
            var charCode = event.charCode || event.keyCode;
            var char = String.fromCharCode(charCode);

            // Allow digits (0-9) and dot (.)
            if ((charCode >= 48 && charCode <= 57) || char === '.' || char === '-') {
                return true;
            }
            return false;
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
@endsection
