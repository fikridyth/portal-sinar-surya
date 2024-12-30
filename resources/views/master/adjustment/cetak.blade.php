<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Cetak</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .dashed-line {
            border: 0;
            border-top: 1px dashed black; /* Ganti 'black' dengan warna yang diinginkan */
            margin: 10px 0; /* Sesuaikan margin sesuai kebutuhan */
        }
        .dashed-line-2 {
            border: 0;
            border-top: 1px dashed black; /* Ganti 'black' dengan warna yang diinginkan */
            margin: -5px 0; /* Sesuaikan margin sesuai kebutuhan */
        }
    </style>
</head>

<body>
    {{-- @dd($results) --}}
    <div class="container mt-4">
        <div class="row">
            <div class="col-3">SINAR SURYA</div>
            <div class="col-5"></div>
            <div class="col-3">{{ now()->format('d/m/Y') }}</div>
        </div>
        <div class="row">
            <div class="col-3">JL. PASAR BARU - BOGOR</div>
            <div class="col-5"></div>
            <div class="col-3">GAWIH JAYA</div>
        </div>
        <div class="row">
            <div class="col-3">Telp : (0251)-324647</div>
            <div class="col-5"></div>
            <div class="col-3">Phone : 021 790875</div>
        </div>
        <div class="row">
            <div class="col-3">Fax &nbsp;: (0251)-357029</div>
            <div class="col-5"></div>
            <div class="col-3">Fax &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 7964355</div>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-5"></div>
            <div class="col-3">CP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: EDI 087870000717</div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-2">PENYESUAIAN HARGA</div>
            <div class="col-4"></div>
            <div class="col-2"></div>
        </div>
        {{-- <hr class="dashed-line">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-5"></div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-3">Type Of Payment : Credit</div>
            <div class="col-5"></div>
            <div class="col-3">Cancellation Date : {{ now()->format('d/m/Y') }}</div>
        </div>
        <div class="row">
            <div class="col-3">Term Of Payment : 0</div>
        </div> --}}
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 10px;">
            <div class="col-1">No</div>
            <div class="col-4">Nama Barang</div>
            <div class="col-1">Konv</div>
            <div class="col-1">Isi</div>
            <div class="col-2">Harga</div>
            <div class="col-1">Stok</div>
            {{-- <div class="col-1">Fisik</div> --}}
            <div class="col-2">Rupiah</div>
            {{-- <div class="col-1">Selisih Qty</div> --}}
            {{-- <div class="col-1">Selisih Rp</div> --}}
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        @foreach ($products as $product)
            {{-- @dd($result['product']['nama']) --}}
            <div class="row" style="margin-top: 10px;">
                <div class="col-1">{{ $loop->iteration }}</div>
                <div class="col-4">{{ $product->nama }}/{{ $product->unit_jual }}</div>
                <div class="col-1">{{ $product->konversi }}</div>
                <div class="col-1">{{ str_replace('P', '', $product->unit_jual) }}</div>
                <div class="col-2">{{ number_format($product->harga_jual) }}</div>
                <div class="col-1">{{ $product->stok }}</div>
                {{-- <div class="col-1">{{ $product->stok }}</div> --}}
                <div class="col-2">{{ number_format($product->harga_jual * $product->stok) }}</div>
                {{-- <div class="col-1">{{ number_format(0, 2) }}</div>
                <div class="col-1">{{  }}</div> --}}
            </div>
        @endforeach
        <hr class="dashed-line">
        <hr class="dashed-line">
    </div>

    <script>
        // Fungsi ini akan dipanggil saat halaman selesai dimuat
        function autoPrint() {
            window.print();
        }

        // Menjalankan fungsi autoPrint setelah halaman dimuat sepenuhnya
        window.onload = autoPrint;

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' || event.key === 'Backspace') {
                window.history.back();
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
