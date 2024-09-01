@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 82%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                PEMESANAN BARANG - PURCHASE ORDER
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <form action="{{ route('receive-po.store') }}" method="POST" class="form">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label">Nomor PO</label>
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
                                <div class="col-2">
                                    <div class="col">
                                        <div class="custom-select-supplier">
                                            <input type="text" class="search-input-supplier_1" name="supplier" value="{{ old('supplier') }}" autocomplete="off" required placeholder="Search..." onkeyup="filterFunction()">
                                            <div class="select-items-supplier" id="select-items-supplier_1">
                                                <!-- Options will be added here dynamically -->
                                            </div>
                                        </div>
                        
                                        <select id="supplier_1" style="width: 200px;" hidden>
                                            <option value="">---Select Supplier---</option>
                                            <!-- Example options; replace with server-side data as needed -->
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" data-kode="{{ $supplier->nomor }}" data-nama="{{ $supplier->nama }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
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
                                                </tr>
                                            </thead>
                                            <tbody id="po_details">
                                                <tr>
                                                    <td id="targetRow4" colspan="6" class="text-center">Tidak ada data yang dipilih</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" id="button-baru" hidden class="btn btn-primary mx-3">PO BARU</button>
                                            <button type="button" id="button-tetap" hidden class="btn btn-primary mx-3">TETAP</button>
                                            <button type="button" id="button-dihapus" hidden class="btn btn-danger mx-3">DIHAPUS</button>
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
                                if (Array.isArray(response)) {
                                    // Clear previous data
                                    $('#po_details').empty();
                                    
                                    // Loop through each preorder
                                    response.forEach(function(preorder) {
                                        console.log(preorder);

                                        // Parse the details JSON
                                        try {
                                            const details = JSON.parse(preorder.detail);

                                            // Loop through each detail item
                                            details.forEach(function(item) {
                                                $('#po_details').append(
                                                    `<tr>
                                                        <td>${item.nama}</td>
                                                        <td>${item.order}</td>
                                                        <td>${item.price}</td>
                                                        <td>${item.field_total}</td>
                                                        <td>${preorder.nomor_po}</td>
                                                        <td>${preorder.date_first}</td>
                                                    </tr>`
                                                );
                                            });
                                        } catch (e) {
                                            console.error('Failed to parse detail JSON:', e);
                                        }
                                    });

                                    // Hide or show specific rows as needed
                                    $('#targetRow4').attr('hidden', 'hidden');
                                    $('#button-baru').removeAttr('hidden');
                                    $('#button-tetap').removeAttr('hidden');
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

            closeAllSelect();
        });
    </script>
@endsection
