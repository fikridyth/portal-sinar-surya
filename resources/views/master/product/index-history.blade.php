@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body mt-n2">
                <div class="card-body mt-n4">
                    <div class="row w-100">
                        <div class="form-group col-6">
                            <h5>{{ $product->nama }}</h5>
                            <div style="overflow-x: auto; height: 610px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">DOKUMEN</th>
                                            <th class="text-center">NAMA SUPPLIER</th>
                                            <th class="text-center">PILIH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderProduct as $data)
                                            <tr>
                                                <td class="text-center">{{ $data['nomor_receive'] ?? '-' }}</td>
                                                <td>{{ $data['supplier']['nama'] }}</td>
                                                <td class="text-center"><input type="checkbox" class="preorder-checkbox"
                                                        data-detail="{{ json_encode($data['detail']) }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc; margin-top: 32px;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NAMA BARANG</th>
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
        document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const detail = JSON.parse(this.getAttribute('data-detail'));
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                let total = 0;

                if (this.checked) {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                        }
                    });

                    tbody.innerHTML = ''; // Clear existing rows before adding new ones
                    JSON.parse(detail).forEach(item => {
                        const amount = item.order * item.price; // Menghitung jumlah per item
                        total += amount;

                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${item.nama}/${item.unit_jual}</td>
                            <td class="text-center">${item.order}</td>
                            <td class="text-end">${number_format(item.price)}</td>
                            <td class="text-end">${number_format(amount)}</td>
                        `;
                        tbody.appendChild(newRow);
                    });

                    totalCell.textContent = number_format(total);
                } else {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        otherCheckbox.disabled = false;
                    });

                    tbody.innerHTML = ''; // Clear the table if checkbox is unchecked
                    totalCell.textContent = '0';
                }
            });
        });

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
@endsection
