@extends('main')

@include('master.product.add-on.styles')

@php
    use App\Models\Product;
    $totalPrice = 0;
    $totalOrder = 0;
@endphp
<style>
    .container-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Optional: Adds space between columns */
        }
    .column {
        flex: 1 1 10%; /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
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
        flex: 1 1 20%; /* Adjust width for column with different size */
    }
    .fs-need {
        font-size: 14px;
    }

    /* Modal Style */
    .modal {
        display: none; /* Sembunyikan modal secara default */
        position: fixed;
        z-index: 1; /* Letakkan modal di atas konten lainnya */
        left: 0;
        top: 0;
        width: 100%; /* Lebar penuh */
        height: 100%; /* Tinggi penuh */
        background-color: rgba(0, 0, 0, 0.4); /* Latar belakang gelap */
        overflow: auto; /* Konten bisa di-scroll jika terlalu besar */
        padding-top: 60px; /* Jarak atas untuk konten */
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 30px;
        border: 1px solid #888;
        width: 80%; /* Lebar modal */
        max-width: 1000px;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* backdrop */
    .modal-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .modal-content-custom {
        width: 95vw;
        height: 90vh;
        margin: 5vh auto;
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        flex: 1;
        padding: 15px;
        overflow: hidden;
    }

    .table-wrapper {
        max-height: 650px;
        overflow-y: auto;
    }

    /* sticky header */
    #product-table thead th {
        position: sticky;
        top: 0;
        background: #a0f7dc; /* warna header hijau muda */
        z-index: 10;
        border-bottom: 2px solid #000; /* agar lebih jelas */
    }

    /* Beri padding dan border */
    #product-table th, #product-table td {
        padding: 8px 12px;
        border: 1px solid #000;
        white-space: nowrap; /* agar kolom tidak terlalu melebar */
    }
</style>

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-2" style="width: 95%;">
            <div class="card">
                <div class="card-body mt-n4">
                    {{-- <form action="{{ route('preorder.process-barang') }}" method="POST">
                        @csrf --}}
                        <div class="card-body">
                            <div class="row mb-1">
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
                                                <input type="text" value="{{ $preorder->nomor_receive }}" disabled class="form-control" id="nomorSupplier2" value="">
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
                                                <input type="text" value="{{ $preorder->created_at->format('d/m/Y') }}" disabled class="form-control" id="inputPassword3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="mx-4">KODE SUPPLIER</label>
                                    <select class="supplier-select" id="supplierSelect">
                                        <option value="">{{ $preorder->supplier->nomor }} - {{ $preorder->supplier->nama }}</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mt-3 mb-1">
                                <div class="row w-100">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="rata2" class="col-sm-5 col-form-label" style="font-size: 14px;">PENJ. RATA2</label>
                                                <div class="col-sm-3">
                                                    <input type="text" value="" class="form-control" id="rata2" disabled>
                                                </div>
                                                <label for="rata2" class="col-sm-2 col-form-label" style="font-size: 14px;">HR</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="maximum" class="col-sm-5 col-form-label" style="font-size: 14px;">MAKSIMUM</label>
                                                <div class="col-sm-3">
                                                    <input type="text" value="" class="form-control" id="maximum" disabled>
                                                </div>
                                                <label for="maximum" class="col-sm-2 col-form-label" style="font-size: 14px;">HR</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="stok" class="col-sm-4 col-form-label" style="font-size: 14px;">STOK</label>
                                                <div class="col-sm-6">
                                                    <input type="text" value="" disabled class="form-control" id="stok">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <form action="{{ route('daftar-po.set-diskon', $preorder->id) }}" method="POST" class="form">
                                                    @csrf
                                                    <div class="row align-items-center">
                                                        <div class="col-5">
                                                            <label for="totalPrice" class="mb-0">DISKON REGULER</label>
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="hidden" name="total_harga" value="{{ $preorder->total_harga }}">
                                                            @if ($preorder->diskon_global == 0)
                                                                <input type="number" required name="diskon_global" class="form-control" style="width: 120px;">
                                                            @else
                                                                <input type="number" id="diskon_global" name="diskon_global" value="{{ $preorder->diskon_global }}" class="form-control" style="width: 120px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-3">
                                                            <button type="submit" class="btn btn-sm btn-primary">SET</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container-box mt-n3">
                                <div class="column"><div class="form-group"><label class="col-form-label">H. TERAKHIR</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 1</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 2</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 3</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">PPN(%)</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">HARGA RATA2</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 1</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 2</label></div></div>
                                <div class="column"><div class="form-group"><label class="col-form-label">DISKON 3</label></div></div>
                            </div>
                            <div class="container-box mb-1">
                                <div class="column"><div class="form-group"><input type="text" id="price-input" disabled size="9"></div></div>
                                <div class="column"><div class="form-group"><input type="text" id="diskon1-input" disabled size="9"></div></div>
                                <div class="column"><div class="form-group"><input type="text" id="diskon2-input" disabled size="9"></div></div>
                                <div class="column"><div class="form-group"><input type="text" id="diskon3-input" disabled size="9"></div></div>
                                <div class="column"><div class="form-group"><input type="text" id="ppn-input" disabled size="9"></div></div>
                                <div class="column"><div class="form-group"><input type="text" id="price-ppn-input" disabled size="9"></div></div>
                                <div class="column">
                                    <div class="form-group">
                                        <input type="number" style="width: 100px;" autocomplete="off" id="diskon4-input" size="9" 
                                            onblur="handleBlurDiskon4()" onkeydown="handleDiskon4Price(event)" onfocus="this.value = '';">
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="form-group">
                                        <input type="number" style="width: 100px;" autocomplete="off" id="diskon5-input" size="9" 
                                            onblur="handleBlurDiskon5()" onkeydown="handleDiskon5Price(event)" onfocus="this.value = '';">
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="form-group">
                                        <input type="number" style="width: 100px;" autocomplete="off" id="diskon6-input" size="9" 
                                            onblur="handleBlurDiskon6()" onkeydown="handleDiskon6Price(event)" onfocus="this.value = '';">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <div class="row w-100">
                                    <div class="form-group col-12">
                                        <div style="overflow-x: auto; height: 400px; border: 1px solid #ccc;">
                                            <table id="details-table" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                                <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                                    <tr class="fs-need">
                                                        <th class="text-center">KODE</th>
                                                        <th class="text-center">NAMA BARANG</th>
                                                        <th class="text-center">ISI</th>
                                                        <th class="text-center">HARGA POKOK</th>
                                                        <th class="text-center">ORDER</th>
                                                        <th class="text-center">TERIMA</th>
                                                        <th class="text-center">SATUAN</th>
                                                        <th class="text-center">NETTO</th>
                                                        <th class="text-center">JUMLAH</th>
                                                        <th class="text-center">BNS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($preorder->detail !== null)
                                                        @php
                                                            $totalItems = count(json_decode($preorder->detail, true));
                                                        @endphp
                                                        @foreach (json_decode($preorder->detail, true) as $index => $detail)
                                                        @php
                                                            $currentIndex = $totalItems - $index;
                                                            $no = $index + 1;
                                                            $totalPrice += $detail['field_total'];
                                                            $totalOrder += $detail['order'];
                                                            if ($detail['is_ppn'] !== 0) { $priceWithPpn = ($detail['price'] * $detail['is_ppn'] / 100) + $detail['price']; }
                                                            else { $priceWithPpn = $detail['price']; }

                                                            $dataProduct = Product::where('kode', $detail['kode'])->first();
                                                        @endphp
                                                            <tr class="fs-need">
                                                                <td hidden class="text-center" style="width: 100px;">
                                                                    <div hidden class="select-container">
                                                                        <input class="form-check-input select-checkbox" type="checkbox" id="checkbox-{{ $no }}" onchange="handleCheckboxChange(this)"
                                                                        data-stok-value="{{ $detail['stok'] }}" data-rata2-value="{{ $detail['penjualan_rata'] }}" data-maximum-value="{{ $detail['stok_maksimum'] }}"
                                                                        data-ppn-value="{{ $detail['is_ppn'] }}" data-price-value="{{ $detail['old_price'] ?? $detail['price'] }}" data-price-ppn-value="{{ $priceWithPpn }}"
                                                                        data-diskon1-value="{{ $detail['diskon1'] }}" data-diskon2-value="{{ $detail['diskon2'] }}" data-diskon3-value="{{ $detail['diskon3'] }}"
                                                                        data-diskon4-value="{{ $detail['diskon1'] }}" data-diskon5-value="{{ $detail['diskon2'] }}" data-diskon6-value="{{ $detail['diskon3'] }}"
                                                                        data-noindex="{{ $no }}" data-nonama="{{ $detail['nama'] }}"
                                                                        >
                                                                    </div>
                                                                    <button hidden class="btn btn-sm btn-primary mb-2" type="button" id="edit-save-{{ $no }}" style="display:none;" onclick="handleSaveClick(this)">Save</button>
                                                                    <div style="display: none"><button class="btn btn-sm btn-danger" type="button" id="delete-save-{{ $no }}" style="display:none;" onclick="handleDestroyClick(this)">Delete</button></div>
                                                                </td>

                                                                <td class="text-center" id="kode-text-{{ $no }}">{{ $detail['kode'] }}</td>
                                                                <td>{{ $detail['nama'] . '/' . $detail['unit_jual'] }}</td>
                                                                <td class="text-end">{{ str_replace('P', '', $detail['unit_jual']) }}</td>
                                                                <td class="text-end" id="old-price-{{ $no }}">{{ number_format($dataProduct->harga_pokok ?? 0) }}</td>
                                                                <td class="text-end" id="order-view-text-{{ $no }}">{{ $detail['order'] }}</td>
                                                                <td class="text-end">
                                                                    <div class="order-container">
                                                                        <span class="order-text" id="order-text-{{ $no }}">{{ $detail['order'] }}</span>
                                                                        <input type="number" style="width: 50px;" class="order-input" hidden disabled id="order-input-{{ $no }}" value="{{ $detail['order'] }}" size="3"
                                                                            onblur="handleBlurOrder({{ $no }}, '{{ $detail['order'] }}')" onkeydown="handleEnterOrder(event, {{ $no }}, '{{ $detail['order'] }}')" onfocus="this.value = '';">
                                                                    </div>
                                                                </td>
                                                                <td class="text-end">
                                                                    <div class="price-container">
                                                                        <span class="price-text" id="price-text-{{ $no }}">{{ number_format($dataProduct->harga_pokok ?? 0) }}</span>
                                                                        <input type="number" style="width: 100px;" class="price-input" id="price-input-{{ $no }}" hidden disabled value="{{ $dataProduct->harga_pokok ?? 0 }}" size="10"
                                                                            onblur="handleBlurPrice({{ $no }}, '{{ $dataProduct->harga_pokok }}')" onkeydown="handleEnterPrice(event, {{ $no }}, '{{ $dataProduct->harga_pokok }}')" onfocus="this.value = '';">
                                                                    </div>
                                                                </td>
                                                                <td class="text-end netto" id="netto-{{ $no }}">{{ number_format($detail['price']) }}</td>
                                                                <td class="text-end field-total" id="field-total-{{ $no }}">{{ number_format($detail['field_total']) }}</td>
                                                                <td>
                                                                    <form action="{{ route('daftar-po.set-bonus', $preorder->id) }}" method="POST" class="form" id="bonusForm-{{ $no }}" style="margin:0; padding:0;">
                                                                        @csrf
                                                                        <div class="row align-items-center" style="margin:0; padding:0;">
                                                                            <input type="hidden" name="receive_type" value="{{ $preorder->receive_type }}">
                                                                            <input type="hidden" name="nomor_receive" value="{{ $preorder->nomor_receive }}">
                                                                            <input type="hidden" name="no" value="{{ $no - 1 }}">
                                                                            <button type="submit" style="display:none; margin:0; padding:2px 6px; font-size:10px;" id="bonus-save-{{ $no }}" onclick="confirmAlertBonus(event, 'Apakah barang ini bonus sebesar {{ number_format($detail['field_total']) }}?', 'bonusForm-{{ $no }}')" class="btn btn-sm btn-primary">BONUS</button>
                                                                        </div>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="fs-need"></tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @if ($preorder->detail !== null)
                                            <input type="hidden" id="current-index" value="{{ $totalItems }}">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex">
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-primary" id="tambah-button">TAMBAH</button>
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-primary" disabled id="simpan-button">SIMPAN</button>
                                    </div>
                                    <div class="mx-2">
                                        <a href="{{ route('receive-po.add-product', enkrip($preorder->id)) }}" id="tambah-list-button" class="btn btn-primary">INVENTORY</a>
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-warning" disabled id="ubah-button" onclick="handleChangeClick(this)">GANTI</button>
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-danger" disabled id="hapus-button" onclick="handleDestroyClick(this)">HAPUS</button>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mx-2">
                                        <label for="totalPrice" class="mt-1">Jumlah</label>
                                    </div>
                                    <div class="mx-2">
                                        <input type="text" id="totalPrice22" value="{{ number_format($preorder->total_harga) }}" disabled size="10" class="form-control">
                                    </div>
                                    <div class="mx-4">
                                    </div>
                                    <div class="mx-2">
                                        <a class="btn btn-danger" href="{{ route('index') }}">KEMBALI</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex">
                                    <div class="mx-2">
                                        <a href="{{ route('receive-po.create') }}" class="btn btn-success">TAMBAH PREORDER</a>
                                    </div>
                                    <div class="mx-2">
                                        @if ($preorder->is_cancel !== null && $preorder->detail !== '[]')
                                            <a href="{{ route('receive-po.preview-data', enkrip($preorder->id)) }}" id="proses-button" class="btn btn-warning">PENERIMAAN</a>
                                        @else
                                            <button disabled class="btn btn-warning">PENERIMAAN</button>
                                        @endif
                                    </div>
                                    <div class="mx-2">
                                        <a href="{{ route('daftar-receive-po') }}" class="btn btn-info">DAFTAR PREORDER</a>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <form action="{{ route('create-receive.set-ppn-receive', $preorder->id) }}" id="ppnForm" method="POST" class="form">
                                        @csrf
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <label for="totalPrice" class="mb-0">PPN</label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="hidden" name="total_harga" value="{{ $preorder->total_harga }}">
                                                <div class="slider-container">
                                                    <input type="checkbox" name="ppn" id="ppn" {{ $preorder->ppn_global ? 'checked' : '' }} class="slider-checkbox">
                                                    <label for="ppn" class="slider-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mx-5">
                                    </div>
                                    <div class="mx-5">
                                    </div>
                                    <div class="mx-4">
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
                                        <input type="text" id="totalPrice33" value="{{ number_format($preorder->grand_total) ?? number_format($preorder->total_harga) }}" disabled size="10" class="form-control">
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

    <!-- Modal Barcode -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h5>List Products</h5>
            <table id="productTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>NAMA BARANG</th>
                        <th>STOK</th>
                        <th>HARGA BELI</th>
                        <th>HARGA JUAL</th>
                        <th>PILIH</th>
                    </tr>
                </thead>
                <tbody id="productDetails">
                    <!-- Product data will be inserted here -->
                </tbody>
            </table>
            <!-- Button Save to trigger the store process -->
            <button class="btn btn-primary mt-2" id="saveBtn">SIMPAN</button>
        </div>
    </div>
    
    {{-- Modal Change Product --}}     
    <div id="productChangeModal" class="modal-custom">
        <div class="modal-content-custom">
            <div class="modal-header">
                <h5 class="modal-title">GANTI PRODUK</h5>
                <button type="button" class="btn-close" onclick="closeProductModal()"></button>
            </div>
            <div class="modal-body">
                <div class="table-wrapper">
                    <table class="table table-bordered table-striped" id="product-table">
                        <thead>
                            <tr>
                                <th hidden>ID</th>
                                <th>NAMA BARANG</th>
                                <th>UNIT BELI</th>
                                <th>UNIT JUAL</th>
                                <th>STOK</th>
                                <th>HARGA BELI</th>
                                <th>HARGA JUAL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>   
@endsection

@section('scripts')
    @include('preorder.detail-po.js.netto')
    @include('preorder.detail-po.js.new-row-receive')
    <script>
        $(document).on('click', 'tr.fs-need', function (e) {
            // Abaikan klik langsung di input / button
            if (
                $(e.target).is('input') ||
                $(e.target).is('button') ||
                $(e.target).closest('button').length
            ) return;

            const $currentCheckbox = $(this).find('.select-checkbox');
            if (!$currentCheckbox.length) return;

            // Jika checkbox ini mau dicentang
            const willCheck = !$currentCheckbox.prop('checked');

            // Uncheck checkbox lain
            $('.select-checkbox').not($currentCheckbox).each(function () {
                if ($(this).prop('checked')) {
                    $(this)
                        .prop('checked', false)
                        .trigger('change');
                }
            });

            // Set checkbox yang diklik
            $currentCheckbox
                .prop('checked', willCheck)
                .trigger('change');
        });
        
        var preorderData = <?php echo json_encode($preorder->id); ?>; 
        $(document).ready(function() {
            $('#supplierSelect').change(function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    // URL for the POST route
                    var url = "{{ route('update-supplier-receive-data') }}";
                    
                    // Send a POST request with both supplierId and preorderData
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF protection
                            supplier_id: supplierId, // Send the supplierId
                            preorder_data: preorderData // Send preorderData
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Request failed: ' + error);
                        }
                    });
                }
            });
        });

        $(`.supplier-select`).select2({
            allowClear: true
        });

        $(`.supplier-select`).on('select2:open', function(e) {
            // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
            const searchBox = $(this).data('select2').dropdown.$search[0];
            if (searchBox) {
                searchBox.focus();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('tambah-button').click();
            }
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
            if (event.key === 'Delete') {
                document.getElementById('delete-save-' + no).click();
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

                document.getElementById('diskon4-input').focus();
            }
            if (event.key === 'Delete') {
                document.getElementById('delete-save-' + no).click();
            }
        }

        function handleBlurPrice(no, originalValue) {
            var inputField = document.getElementById('price-input-' + no);

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = originalValue;
            }
        }

        function confirmAlertBonus(event, text, formId) {
            event.preventDefault();
            Swal.fire({
                title: 'Notifikasi',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form directly
                    document.getElementById(formId).submit();
                }
            });
        }

        let nomorIndex = 1;
        let diskon1Index = '0';
        let diskon2Index = '0';
        let diskon3Index = '0';
        let selectedRowIndex = null;
        let selectedRowNama = null;
        function handleCheckboxChange(selectedCheckbox) {
            // input diskon when enter
            nomorIndex = selectedCheckbox.getAttribute('data-noindex')
            namaIndex = selectedCheckbox.getAttribute('data-nonama')
            diskon1Index = selectedCheckbox.getAttribute('data-diskon1-value');
            diskon2Index = selectedCheckbox.getAttribute('data-diskon2-value');
            diskon3Index = selectedCheckbox.getAttribute('data-diskon3-value');
            selectedRowIndex = nomorIndex;
            selectedRowNama = namaIndex;

            const index = selectedCheckbox.id.split('-')[1];
            
            // Get the discount value from the checkbox's data attribute
            const diskonValue = parseFloat(selectedCheckbox.getAttribute('data-diskon4-value'));
            
            // Get the netto element by its ID
            const priceInput = document.querySelector('.price-input');
            const nettoElement = document.getElementById(`netto-${index}`);
            const tambahButton = document.getElementById('tambah-button');
            const prosesButton = document.getElementById('proses-button');
            const ubahButton = document.getElementById('ubah-button');
            const hapusButton = document.getElementById('hapus-button');
            
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
                selectedCheckbox.style.display = 'none';
                button.style.display = 'inline-block';
                buttonD.style.display = 'inline-block';
                buttonB.style.display = 'inline-block';
                tambahButton.disabled = true;
                ubahButton.disabled = false;
                hapusButton.disabled = false;
                prosesButton.classList.add('disabled-link');
            } else {
                // Remove the discount if the checkbox is unchecked
                currentNetto += diskonValue;
                selectedCheckbox.style.display = 'inline-block';
                button.style.display = 'none';
                buttonD.style.display = 'none';
                buttonB.style.display = 'none';
                tambahButton.disabled = false;
                ubahButton.disabled = true;
                hapusButton.disabled = true;
                prosesButton.classList.remove('disabled-link');
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

        function handleDiskon4Price(event) {
            if (event.key === 'Enter') {
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                var inputField = document.getElementById('diskon4-input');
                if (inputField.value === '') {
                    inputField.value = diskon1Index;
                }

                document.getElementById('diskon5-input').focus();
            }
            if (event.key === 'Delete') {
                document.getElementById('delete-save-' + nomorIndex).click();
            }
        }

        function handleBlurDiskon4() {
            var inputField = document.getElementById('diskon4-input');

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = diskon1Index;
            }
        }
        
        function handleDiskon5Price(event) {
            if (event.key === 'Enter') {
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                var inputField = document.getElementById('diskon5-input');
                if (inputField.value === '') {
                    inputField.value = diskon2Index;
                }

                document.getElementById('diskon6-input').focus();
            }
            if (event.key === 'Delete') {
                document.getElementById('delete-save-' + nomorIndex).click();
            }
        }

        function handleBlurDiskon5() {
            var inputField = document.getElementById('diskon5-input');

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = diskon2Index;
            }
        }
        
        function handleDiskon6Price(event) {
            if (event.key === 'Enter') {
                var inputField = document.getElementById('diskon6-input');
                // Jika input kosong, kembalikan nilai ke nilai asli (originalValue)
                if (inputField.value === '') {
                    inputField.value = diskon3Index;
                }

                document.getElementById('edit-save-' + nomorIndex).click();
                document.getElementById('edit-save-' + nomorIndex).disabled = true;
            }
            if (event.key === 'Delete') {
                document.getElementById('delete-save-' + nomorIndex).click();
            }
        }

        function handleBlurDiskon6() {
            var inputField = document.getElementById('diskon6-input');

            // Kembalikan nilai ke originalValue jika kosong saat kehilangan fokus
            if (inputField.value === '') {
                inputField.value = diskon3Index;
            }
        }
        
        let productTableInitialized = false;
        let yajraTable;
        let rowName = '';
        function handleChangeClick() {
            rowName = selectedRowNama;  
            $('#productChangeModal').fadeIn(200, function () {
                if (!productTableInitialized) {
                    yajraTable = $('#product-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route('master.product.datatable') }}',
                        pageLength: 500,
                        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
                        columns: [
                            { data: 'id', name: 'id', visible: false },
                            { data: 'nama', name: 'nama' },
                            { data: 'unit_beli', name: 'unit_beli' },
                            { data: 'unit_jual', name: 'unit_jual' },
                            { data: 'stok', name: 'stok' },
                            { data: 'harga_pokok', name: 'harga_pokok' },
                            { data: 'harga_jual', name: 'harga_jual' }
                        ],
                        initComplete: function () {
                            if (rowName) {
                                yajraTable.search(rowName, true, false).draw(false);
                            }
                        }
                    });

                    $('#product-table tbody').on('click', 'tr', function () {
                        const data = yajraTable.row(this).data();
                        if (data) {
                            selectProduct(data.id, data.nama);
                        }
                    });

                    productTableInitialized = true;
                } else {
                    yajraTable.search('').draw(false);
                    if (rowName) {
                        yajraTable.search(rowName, true, false).draw(false);
                    }
                }
            });
        }

        function closeProductModal() {
            $('#productChangeModal').fadeOut(200, function() {
                // Reset search dan rowName saat modal ditutup
                if (productTableInitialized) {
                    yajraTable.search('').draw(false);
                }
                rowName = '';
            });
        }

        $(window).click(function(event) {
            if ($(event.target).is('#productChangeModal')) {
                closeProductModal();
            }
        });

        // dipanggil dari tombol PILIH di DataTable
        function selectProduct(productId, productName) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Ganti dengan produk ' + productName + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('daftar-po.change') }}',
                        type: 'PUT',
                        data: {
                            id: {{ $preorder->id }},
                            array: selectedRowIndex - 1,
                            product_id: productId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            if (res.success) {
                                $('#productChangeModal').fadeOut(200);
                                location.reload();
                            }
                        }
                    });

                }
            });
        }

        function handleDestroyClick() {
            // Prepare data to be sent
            var data = {
                id: {{ $preorder->id }},
                array: selectedRowIndex - 1,
            };

            Swal.fire({
                title: 'Notifikasi?',
                text: 'Apakah kamu yakin ingin menghapus item ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform AJAX request
                    $.ajax({
                        url: '{{ route('daftar-po.destroy') }}', // Use the named route to generate URL
                        type: 'DELETE',
                        data: data,
                        headers: {
                            // 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Handle success response
                            if (response.success) {
                                var redirectUrl = @json(route('receive-po.create-detail', enkrip($preorder->id)));
                                window.location.href = redirectUrl;
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
            });
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
            const oldPriceInput = document.getElementById(`old-price-${index}`);
            const orderInput = document.getElementById(`order-input-${index}`);
            const orderViewText = document.getElementById(`order-view-text-${index}`);
            orderViewText.textContent = orderInput.value;
            const orderText = document.getElementById(`order-text-${index}`);
            orderText.textContent = orderInput.value;

            // diskon
            const diskon1Input = document.getElementById(`diskon4-input`);
            const diskon2Input = document.getElementById(`diskon5-input`);
            const diskon3Input = document.getElementById(`diskon6-input`);
            
            // total
            const nettoElement = document.getElementById(`netto-${index}`);
            let nettoElementValue = nettoElement.textContent;
            nettoElementValue = nettoElementValue.replace(/\./g, '');
            const fieldTotalElement = document.getElementById(`field-total-${index}`);
            let fieldTotalElementValue = fieldTotalElement.textContent;
            fieldTotalElementValue = fieldTotalElementValue.replace(/\./g, '');

            const priceText = document.getElementById(`price-text-${index}`);
            priceText.textContent = nettoElement.textContent;
            const kodeText = document.getElementById(`kode-text-${index}`);
            
            // Prepare data to be sent
            var data = {
                id: {{ $preorder->id }},
                array: index - 1,
                kode: kodeText.textContent,
                price: priceInput.value,
                oldPrice: oldPriceInput.innerHTML,
                order: orderInput.value,
                netto: nettoElementValue,
                total: fieldTotalElementValue,
                diskon1: diskon1Input.value,
                diskon2: diskon2Input.value,
                diskon3: diskon3Input.value
            };

            // Perform AJAX request
            $.ajax({
                url: '{{ route('create-receive.update') }}', // Use the named route to generate URL
                type: 'POST',
                data: data,
                headers: {
                    // 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle success response
                    if (response.success) {
                        var redirectUrl = @json(route('receive-po.create-detail', enkrip($preorder->id)));
                        window.location.href = redirectUrl;
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
            var priceDiskon5Value = checkbox.getAttribute('data-diskon5-value');
            var priceDiskon6Value = checkbox.getAttribute('data-diskon6-value');
            var priceDiskon4Value = checkbox.getAttribute('data-diskon4-value');
            var diskonGlobalElement = document.getElementById('diskon_global');

            if (checkbox.checked) {
                if (!diskonGlobalElement) {
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
                    document.getElementById('diskon4-input').value = priceDiskon1Value || 0;
                    document.getElementById('diskon5-input').value = priceDiskon2Value || 0;
                    document.getElementById('diskon6-input').value = priceDiskon3Value || 0;

                    document.querySelectorAll('.order-container').forEach(container => {
                        const orderText = container.querySelector('.order-text');
                        const orderInput = container.querySelector('.order-input');
                        orderText.hidden = true;
                        orderInput.hidden = false;
                        orderInput.focus();
                    });

                    document.querySelectorAll('.price-container').forEach(container => {
                        container.querySelector('.price-text').hidden = true;
                        container.querySelector('.price-input').hidden = false;
                    });
                } else {
                    var priceDiskon4ValueSet = parseFloat(diskonGlobalElement.value);
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
                    document.getElementById('diskon4-input').value = priceDiskon4ValueSet;
                    document.getElementById('diskon5-input').value = 0;
                    document.getElementById('diskon6-input').value = 0;

                    document.querySelectorAll('.order-container').forEach(container => {
                        const orderText = container.querySelector('.order-text');
                        const orderInput = container.querySelector('.order-input');
                        orderText.hidden = true;
                        orderInput.hidden = false;
                        orderInput.focus();
                    });

                    document.querySelectorAll('.price-container').forEach(container => {
                        container.querySelector('.price-text').hidden = true;
                        container.querySelector('.price-input').hidden = false;
                    });
                }
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

        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen form dan checkbox
            const form = document.getElementById('ppnForm');
            const checkbox = document.getElementById('ppn');

            // Tambahkan event listener pada checkbox
            checkbox.addEventListener('change', function() {
                // Kirimkan form saat checkbox diubah
                form.submit();
            });
        });
    </script>
@endsection
