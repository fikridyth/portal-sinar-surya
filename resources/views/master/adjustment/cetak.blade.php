<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Cetak</title>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    {{-- <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
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
        <div class="text-center">
            *** LAPORAN PERSEDIAAN ***
        </div>
        <div>
            TANGGAL : {{ now()->format('d/m/Y') }}
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 10px;">
            <div class="col-4">NAMA BARANG</div>
            <div class="col-2" style="display: flex; justify-content: flex-end;">HARGA</div>
            <div class="col-1" style="display: flex; justify-content: flex-end;">STOK</div>
            <div class="col-1" style="display: flex; justify-content: flex-end;">FISIK</div>
            <div class="col-2" style="display: flex; justify-content: flex-end;">SELISIH QTY</div>
            <div class="col-2" style="display: flex; justify-content: flex-end;">SELISIH RUPIAH</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        @foreach ($products as $product)
            {{-- @dd($product) --}}
            <div class="row" style="margin-top: 10px;">
                <div class="col-4">{{ $product->nama }}/{{ $product->unit_jual }}</div>
                <div class="col-2" style="display: flex; justify-content: flex-end;">{{ number_format($product->harga_jual) }}</div>
                <div class="col-1" style="display: flex; justify-content: flex-end;">{{ number_format($product->stok,0) }}</div>
                <div class="col-1" style="display: flex; justify-content: flex-end;">{{ number_format($product->stok,0) }}</div>
                <div class="col-2" style="display: flex; justify-content: flex-end;">0</div>
                <div class="col-2" style="display: flex; justify-content: flex-end;">0</div>
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
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    {{-- <script src="/assets/js/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="{{ asset('assets/js/jquery-3.5.1.slim.min.js') }}"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script> --}}
    {{-- <script src="/assets/js/popper.min.js"></script> --}}
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>

    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    {{-- <script src="/assets/js/bootstrap.min.js"></script> --}}
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>

</html>
