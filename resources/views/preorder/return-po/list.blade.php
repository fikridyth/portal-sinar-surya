@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">DAFTAR KEMBALI BARANG</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body">
                    <div class="row w-100">
                        <div class="form-group col-7">
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SUPPLIER</th>
                                            <th class="text-center">KASIR</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">JAM</th>
                                            <th class="text-center">PILIH</th>
                                            <th class="text-center">HAPUS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preorderTableBody">
                                        @foreach ($returs as $retur)
                                            <tr data-id="{{ $retur->supplier->nama }}">
                                                <td class="text-center">{{ $retur->supplier->nama }}</td>
                                                <td class="text-center">{{ $retur->created_by }}</td>
                                                <td class="text-center">{{ $retur->date }}</td>
                                                <td class="text-center">{{ $retur->time }}</td>
                                                <td class="text-center"><input type="checkbox" class="preorder-checkbox" data-detail="{{ json_encode($retur->detail) }}"></td>
                                                <td class="text-center"><input type="checkbox" class="delete-checkbox"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-5">
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NAMA BARANG</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">HARGA</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderDetailTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-4 mb-7">KEMBALI</button>
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
                
                if (this.checked) {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                        }
                    });

                    tbody.innerHTML = ''; // Clear existing rows before adding new ones
                    JSON.parse(detail).forEach(item => {
                        console.log(item);
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${item.nama}</td>
                            <td class="text-center">${item.order}</td>
                            <td class="text-end">${number_format(item.price)}</td>
                        `;
                        tbody.appendChild(newRow);
                    });
                } else {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        otherCheckbox.disabled = false;
                    });

                    tbody.innerHTML = ''; // Clear the table if checkbox is unchecked
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
    </script>
@endsection
