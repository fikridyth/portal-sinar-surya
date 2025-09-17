@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body mt-n2">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body mt-n4">
                    <div class="row w-100">
                        <div class="form-group col-6">
                            {{-- <select name="supplier_id" required class="supplier-select" style="width: 300px;">
                                <option value="{{ $supplier->id }}" selected>{{ $supplier->nama }}</option>
                                @foreach ($suppliers as $sup)
                                    @if ($sup->id !== $supplier->id)
                                        <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                    @endif
                                @endforeach
                            </select> --}}
                            <h5>{{ $nama }}</h5>
                            {{-- <a href="{{ route('master.supplier.index') }}" class="btn btn-primary" title="CARI DATA"><i class="fas fa-search"></i></a> --}}
                            <div class="d-flex align-items-center mt-2 mb-2">
                                <div class="center">{{ $dari }} - {{ $sampai }}</div>
                            </div>
                            <div style="overflow-x: auto; height: 610px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NAMA BARANG</th>
                                            {{-- <th class="text-center">QTY</th> --}}
                                            <th class="text-center">HARGA</th>
                                            <th class="text-center">PILIH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product->nama }}</td>
                                                {{-- <td class="text-end">{{ number_format($product->stok, 0) }}</td> --}}
                                                <td class="text-end">{{ number_format($product->harga_pokok, 0) }}</td>
                                                <td class="text-center"><input type="checkbox" class="preorder-checkbox" data-id="{{ $product->id }}" data-detail="{{ json_encode($product->data_order_return) }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-6 mt-3">
                            <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc; margin-top: 50px;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">DOKUMEN</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">HARGA</th>
                                            <th class="text-center">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderDetailTableBody">
                                    </tbody>
                                </table>
                            </div>
                            <div style="height: 60px; border: 1px solid #ccc; margin-top: 0;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-end" style="width: 75%;">TOTAL</th>
                                            <th class="text-end value-total" style="width: 25%;">0</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-n2">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateOrderDetail(this);
                });
            });

            function updateOrderDetail(checkbox) {
                const detail = JSON.parse(checkbox.getAttribute('data-detail'));
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                let total = 0;

                if (checkbox.checked) {
                    tbody.innerHTML = ''; // Bersihkan isi tabel

                    JSON.parse(detail).forEach(item => {
                        total += item.total_dokumen;

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.nomor_dokumen}</td>
                            <td>${item.tanggal_dokumen}</td>
                            <td class="text-end">${number_format(item.order_dokumen)}</td>
                            <td class="text-end">${number_format(item.price_dokumen)}</td>
                            <td class="text-end">${number_format(item.total_dokumen)}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    totalCell.textContent = number_format(total);
                } else {
                    tbody.innerHTML = '';
                    totalCell.textContent = '0';
                }
            }

            // Fungsi format angka (basic)
            function number_format(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endsection
