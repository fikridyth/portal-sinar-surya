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
                                    <button type="button" id="tambah-button" class="btn btn-success">TAMBAH</button>
                                </div>
                                {{-- <div class="col-auto">
                                    <button type="button" id="simpan-button" disabled class="btn btn-primary">SIMPAN</button>
                                </div> --}}
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
                            <a href="{{ route('index') }}" class="btn btn-danger mx-5">BATAL</a>
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
            
            $(`.preorder-select`).select2({
                placeholder: '---Select Receive---',
                allowClear: true
            });
        });

        function handleSelectChange(event) {
            const selectElement = event.target;
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const kode = selectedOption.getAttribute('data-kode');
            const jual = selectedOption.getAttribute('data-jual');
            
            // Find the closest row and update the data-kode cell
            const row = selectElement.closest('tr');
            const kodeCell = row.querySelector('#data-kode');
            const jualCell = row.querySelector('#data-jual');
            kodeCell.textContent = kode;
            jualCell.value = jual;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tambahButton = document.getElementById('tambah-button');
            const tableBody = document.querySelector('#details-table tbody');
            // const element = document.getElementById('current-index');
            // const currentIndex = element && element.value ? parseInt(element.value, 10) || 0 : 0;

            let index = 0; // Start index from the current index

            tambahButton.addEventListener('click', function() {
                const simpanButton = document.getElementById('simpan-button');
                simpanButton.disabled = false;

                index++;
                
                const newRow = document.createElement('tr');
                newRow.classList.add('fs-need');
                
                newRow.innerHTML = `
                    <td>${index}</td>
                    <td class="text-center data-kode" hidden id="data-kode"></td>
                    <td colspan="3" class="text-center">
                        <select id="products-${index}" class="product-select" onchange="handleSelectChange(event)">
                            <option value="">---Select Product---</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-kode="{{ $product->kode }}" data-jual="{{ $product->harga_pokok }}">{{ $product->kode }} - {{ $product->nama }}/{{ $product->unit_jual }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center"><input type="number" size="1" class="order-input" min="1" step="1"></td>
                    <td class="text-center"><input type="number" id="data-jual" size="1" class="price-input" min="1" step="1"></td>
                `;
                
                tableBody.appendChild(newRow);

                // Initialize Select2 on the newly added select element
                $(`#products-${index}`).select2({
                    placeholder: '---Select Product---',
                    allowClear: true
                });
            });
        });

        // Store Data
        document.addEventListener('DOMContentLoaded', function() {
            const simpanButton = document.getElementById('simpan-button');

            simpanButton.addEventListener('click', function() {
                event.preventDefault();

                // Get the selected values from the dropdowns
                const preorderSelect = document.getElementById('preorder-select');
                const supplierSelect = document.getElementById('supplier-select');

                const selectedPreorder = preorderSelect.value; // This gets the selected preorder value
                const selectedSupplier = supplierSelect.value; // This gets the selected supplier value

                if (!selectedPreorder) {
                    alert('SILAHKAN PILIH BUKTI PENERIMAAN.');
                    return; // Exit the function if validation fails
                }

                if (!selectedSupplier) {
                    alert('SILAHKAN PILIH SUPPLIER.');
                    return; // Exit the function if validation fails
                }

                const rows = document.querySelectorAll('#details-table tbody tr');
                const data = [];

                rows.forEach(row => {
                    // Use class selectors for cells if ids are not unique
                    const kodeElement = row.querySelector('.data-kode');
                    const orderElement = row.querySelector('.order-input');
                    const priceElement = row.querySelector('.price-input');

                    const kode = kodeElement ? kodeElement.textContent.trim() : '';
                    const order = orderElement ? orderElement.value.trim() : '';
                    const price = priceElement ? priceElement.value.trim() : '';

                    if (kode && order) {
                        data.push({
                            kode: kode,
                            order: order,
                            price: price,
                        });
                    }
                });

                if (data.length === 0) {
                    alert('SILAHKAN ISI DATA PRODUCT.');
                    return; // Exit the function if validation fails
                }

                fetch('{{ route("return-po.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        data: data,
                        receive: selectedPreorder,
                        supplier: selectedSupplier
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        var redirectUrl = @json(route('daftar-return-po'));
                        window.location.href = redirectUrl;
                    } else {
                        alert('Proses Gagal');
                        // alert(`Validation Errors:\n${result.errors.join('\n')}`);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
