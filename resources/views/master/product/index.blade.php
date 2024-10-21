@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER PERSEDIAAN</li>
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
                    <input type="text" id="barcodeInput" style="width: 100px; opacity: 0" autofocus>
                    {{ $dataTable->table() }}
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
                    if (data.error) {
                        alert(data.error);
                        barcodeValues.length = 0;
                    } else {
                        window.location.href = '/master/product/' + data.product;
                        barcodeValues.length = 0;
                    }
                });
        }

        document.addEventListener('keydown', function(event) {
            // focus barcode
            if (event.key === 'Enter' || event.key === 'Escape') {
                document.getElementById('barcodeInput').focus();
            }
        });
    </script>
@endsection
