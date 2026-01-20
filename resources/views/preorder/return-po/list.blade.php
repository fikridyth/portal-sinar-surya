@extends('main')

@section('content')
    <div class="container-fluid w-100" style="max-width: 90%; width: 90%;">
        <div class="card mt-n3">
            <div class="card-body mt-n4">
                <div class="card-body">
                    <div class="row w-100">
                        <div class="form-group col-7">
                            <div style="overflow-x: auto; height: 670px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SUPPLIER</th>
                                            <th class="text-center">KASIR</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">JAM</th>
                                            <th class="text-center">DETAIL</th>
                                            <th class="text-center">PILIH</th>
                                            <th class="text-center">HAPUS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preorderTableBody">
                                        @foreach ($returs as $retur)
                                            <tr data-id="{{ $retur->id }}">
                                                <td class="text-center">{{ $retur->supplier->nama }}</td>
                                                <td class="text-center">{{ $retur->created_by }}</td>
                                                <td class="text-center">{{ $retur->date }}</td>
                                                <td class="text-center">{{ $retur->jam }}</td>
                                                <td class="text-center"><input type="checkbox" class="preorder-checkbox" data-detail="{{ json_encode($retur->detail) }}" data-total="{{ $retur->total }}"></td>
                                                <td class="text-center"><input type="checkbox" class="edit-checkbox" data-detail="{{ enkrip($retur->id) }}"></td>
                                                <td class="text-center"><input type="checkbox" class="delete-checkbox"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-5">
                            <div style="overflow-x: auto; height: 610px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NAMA BARANG</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">HARGA</th>
                                            <th class="text-center">TOTAL</th>
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
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mb-2">KEMBALI</button>
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
                const total = this.getAttribute('data-total');
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                
                if (this.checked) {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                        }
                    });

                    tbody.innerHTML = ''; // Clear existing rows before adding new ones
                    JSON.parse(detail).forEach(item => {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${item.nama}</td>
                            <td class="text-center">${item.order}</td>
                            <td class="text-end">${number_format(item.price)}</td>
                            <td class="text-end">${number_format(item.field_total)}</td>
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
            function number_format(number) {
                return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        });

        $(document).on('change', '.delete-checkbox', function() {
            if (this.checked) {
                const row = $(this).closest('tr');
                const idToDelete = row.data('id');

                const confirmation = confirm(`Apakah Anda yakin ingin menghapus data ${idToDelete}?`);
                if (confirmation) {
                    // AJAX request to delete the item
                    $.ajax({
                        url: '/destroy-return-data/' + idToDelete, // Ganti dengan URL endpoint penghapusan yang sesuai
                        type: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        success: function(response) {
                            // Tindakan setelah berhasil menghapus
                            alert('Data berhasil dihapus.');
                            var redirectUrl = @json(route('daftar-return-po'));
                            window.location.href = redirectUrl;
                        },
                        error: function(xhr) {
                            // Tindakan jika terjadi kesalahan
                            alert('Terjadi kesalahan saat menghapus item. Silakan coba lagi.');
                        }
                    });
                } else {
                    // Jika tidak dikonfirmasi, uncheck checkbox
                    this.checked = false;
                }
            }
        });

        $(document).on('change', '.show-checkbox', function () {
            if ($(this).is(':checked')) {
                let detailId = $(this).data('detail'); // Ambil ID dari data-detail
                let route = `/return-po/${detailId}`; // Sesuaikan dengan format route Anda
                
                // Redirect ke route
                window.location.href = route;
            }
        });

        $(document).on('change', '.edit-checkbox', function () {
            if ($(this).is(':checked')) {
                let detailId = $(this).data('detail'); // Ambil ID dari data-detail
                let route = `/return-po/edit/${detailId}`; // Sesuaikan dengan format route Anda
                
                // Redirect ke route
                window.location.href = route;
            }
        });
    </script>
@endsection
