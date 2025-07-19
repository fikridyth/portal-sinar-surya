@extends('main')

@section('content')
    <div class="container">
        {{-- <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER PERSEDIAAN</li>
                    </ol>
                </nav>
            </div>
        </div> --}}

        <div class="card">
            <div class="card-body">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body mt-n5">
                    <input type="text" id="barcodeInput" style="width: 100px; opacity: 0" autofocus>
                    <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                        {{ $dataTable->table() }}
                    </div>
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}

    <script>
        const barcodeValues = [];
        let inputTimeout;
        document.getElementById('barcodeInput').addEventListener('input', function(event) {
            const barcodeValueInit = event.target.value;
            const barcodeValue = barcodeValueInit.replace(/\D/g, '');

            // Pastikan input bukan kosong dan sudah memiliki cukup panjang
            if (barcodeValue.length > 0) {
                barcodeValues.push(barcodeValue);
                event.target.value = '';
                clearTimeout(inputTimeout);
                inputTimeout = setTimeout(scanBarcode, 200);
            }
        });

        function scanBarcode(barcodeValue) {
            const topTbody = document.querySelector('tbody.top');
            const kode = barcodeValues.join('');

            fetch(`/master/get-detail-products/${kode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.product) {
                        window.location.href = '/master/product/' + data.product;
                        barcodeValues.length = 0;
                    } else {
                        alert(data.error);
                        const firstRow = document.querySelector('#product-table tbody tr');

                        // If a first row exists, extract the product ID from the data-id attribute
                        if (firstRow) {
                            const productId = firstRow.getAttribute('data-id'); // Retrieve the product ID from data-id

                            // Navigate to the route with the product ID
                            if (productId) {
                                // Redirect to the product page using the product ID
                                window.location.href = `/master/product/create/${productId}`;
                            }
                        }
                        // window.location.href = '/master/product/create';
                        barcodeValues.length = 0;
                    }
                });
        }

        document.addEventListener('keydown', function(event) {
            // focus barcode
            if (event.key === 'Escape' || event.key === 'Enter') {
                document.getElementById('barcodeInput').focus();
            }

            // focus input
            if (event.key === 'Tab') {
                event.preventDefault(); // Prevent default tab behavior

                // Focus on the DataTable search input
                const searchInput = document.querySelector('#product-table_filter input');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // go to menu
            // if (event.key === 'Enter') {
            //     // Find the first row in the product table
            //     const firstRow = document.querySelector('#product-table tbody tr');

            //     // If a first row exists, extract the product ID from the data-id attribute
            //     if (firstRow) {
            //         const productId = firstRow.getAttribute('data-id'); // Retrieve the product ID from data-id

            //         // Navigate to the route with the product ID
            //         if (productId) {
            //             // Redirect to the product page using the product ID
            //             window.location.href = `/master/product/${productId}`;
            //         }
            //     }
            // }
        });
    </script>
@endsection
