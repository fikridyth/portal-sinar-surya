@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card mt-n3">
            <div class="card-body mt-n3">
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

        <form action="{{ route('persetujuan-harga-jual-update', enkrip($preorder->id)) }}" method="POST" class="form" id="filter-form">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
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
                                        $productHarga = App\Models\HargaSementara::where('id_product', $product->id)->orderBy('created_at', 'desc')->first();
                                        $changeTextColor = (($product->harga_jual - $dtl['price']) / $dtl['price']) * 100;
                                    @endphp
                                    <tr>
                                        <input type="text" name="preorder" value="{{ $preorder->id }}" hidden>
                                        <input type="text" name="no_preorder" value="{{ $preorder->nomor_po }}" hidden>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{ $dtl['nama'] . '/' . $dtl['unit_jual'] }}</td>
                                        <td class="text-center">{{ $dtl['order'] }}</td>
                                        {{-- <input type="text" hidden name="kode[{{ $index }}]" value="{{ $dtl['kode'] }}">
                                        <input type="text" hidden name="nama[{{ $index }}]" value="{{ $dtl['nama'] . '/' . $dtl['unit_jual'] }}"> --}}
                                        <input type="text" hidden name="harga_pokok[{{ $index }}]" id="persetujuan_harga_pokok_{{ $index }}" value="{{ $productHarga->harga_pokok ?? $product->harga_pokok }}">
                                        <input type="text" hidden name="nama[{{ $index }}]" value="{{ $dtl['nama'] . '/' . $dtl['unit_jual'] . '/' . $dtl['kode'] . '/' . $dtl['price'] }}">
                                        <td class="text-center">{{ number_format($product->harga_lama) }}</td>
                                        {{-- <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($dtl['price']) }}</td> --}}
                                        <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($productHarga->harga_pokok ?? $product->harga_pokok) }}</td>
                                        {{-- <td class="text-center">{{ number_format((($dtl['price'] - ($productHarga->harga_pokok ?? $product->harga_lama)) / ($productHarga->harga_pokok ?? $product->harga_lama)) * 100, 2) }}</td> --}}
                                        <td class="text-center">{{ number_format(((($productHarga->harga_pokok ?? $product->harga_pokok) - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                        <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_jual) }}</td>
                                        
                                        @php
                                        $roundedPrice = ((($productHarga->harga_pokok ?? $product->harga_pokok) * $product->profit) / 100) + $product->harga_jual;
                                            if (strlen(($productHarga->harga_pokok ?? $product->harga_pokok)) >= 6) {
                                                $roundedValue = round($roundedPrice, -3);
                                            } elseif (strlen(($productHarga->harga_pokok ?? $product->harga_pokok)) >= 4) {
                                                $roundedValue = round($roundedPrice, -2);
                                            } elseif (strlen(($productHarga->harga_pokok ?? $product->harga_pokok)) >= 2) {
                                                $roundedValue = round($roundedPrice, -1);
                                            } else {
                                                $roundedValue = $roundedPrice;
                                            }
                                        @endphp
                                        <td class="text-center"><input type="text" name="harga_jual[{{ $index }}]" id="persetujuan_harga_jual_{{ $index }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $roundedValue }}" size="10"></td>

                                        <td class="text-center"><input type="text" name="mark_up[{{ $index }}]" id="persetujuan_mark_up_{{ $index }}" onkeypress='return validateNumberInput(event)' value="{{ $product->profit }}" size="5"></td>
                                        {{-- <td class="text-center"><input type="text" name="mark_up[{{ $index }}]" id="persetujuan_mark_up_{{ $index }}" onkeypress='return validateNumberInput(event)' value="{{ number_format((($product->harga_jual - $dtl['price']) / $dtl['price']) * 100, 2) }}" size="5"></td> --}}
                                        <td class="text-center"><input type="checkbox" data-kode="{{ $dtl['kode'] }}" value="{{ $index }}" class="select-product"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                            <button type="submit" disabled id="ganti-harga-btn" class="btn btn-primary">GANTI HARGA</button>
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
                        // event.target.disabled = true;
                        document.getElementById('ganti-harga-btn').disabled = false;

                        // Fetch product data based on kode
                        fetch(`/get-products-by-kode/${kode}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.products) {
                                    const tbody = document.querySelector('table tbody');
                                    const parentRow = event.target.closest('tr');
                                    data.products.forEach(product => {
                                        const hargaJual = Math.round(((product.harga_pokok * product.profit) / 100) + product.harga_pokok);
                                        // Create a new row
                                        const newRow = document.createElement('tr');
                                        newRow.innerHTML = `
                                            <td class="text-center"></td>
                                            <td class="text-center">${product.nama}/${product.unit_jual}</td>
                                            <td class="text-center">0</td>
                                            <input type="text" hidden name="nama[${indexCounter}]" value="${product.nama}/${product.unit_jual}/${product.kode}/${product.harga_pokok}">
                                            <input type="text" hidden name="harga_pokok[${indexCounter}]" id="persetujuan_harga_pokok_${indexCounter}" value="${product.harga_pokok}">
                                            <td class="text-center">${number_format(product.harga_pokok)}</td>
                                            <td class="text-center">${number_format(product.harga_pokok)}</td>
                                            <td class="text-center">0.00</td>
                                            <td class="text-center">${number_format(product.harga_jual)}</td>
                                            <td class="text-center">
                                                <input type="text" name="harga_jual[${indexCounter}]" id="persetujuan_harga_jual_${indexCounter}" value="${hargaJual}" size="10">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" name="mark_up[${indexCounter}]" id="persetujuan_mark_up_${indexCounter}" value="${product.profit}" size="5">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="select-product" value="${indexCounter}" checked></td>
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
                        // document.getElementById('ganti-harga-btn').disabled = true;
                        tbody.querySelectorAll(`input[name="kode[]"][value="${kode}"]`).forEach(input => input.closest('tr').remove());
                        // updateTotalFaktur();
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
                    document.getElementById(`persetujuan_harga_jual_${index}`).value = number_format(hargaJualValue);
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

        function number_format_mark_up(number) {
            return Number(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        const form = document.getElementById('filter-form');
        form.addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.select-product');
            console.log(checkboxes);
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    // Hapus input harga_sementara yang tidak dicentang
                    const hiddenInput = document.querySelector(`input[name="harga_pokok[${checkbox.value}]"]`);
                    const hiddenInput2 = document.querySelector(`input[name="nama[${checkbox.value}]"]`);
                    const hiddenInput3 = document.querySelector(`input[name="harga_jual[${checkbox.value}]"]`);
                    const hiddenInput4 = document.querySelector(`input[name="mark_up[${checkbox.value}]"]`);
                    if (hiddenInput) {
                        hiddenInput.remove();
                        hiddenInput2.remove();
                        hiddenInput3.remove();
                        hiddenInput4.remove();
                    }
                }
            });
        });
    </script>
@endsection
