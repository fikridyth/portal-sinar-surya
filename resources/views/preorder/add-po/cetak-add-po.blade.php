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
            <div class="col-2">PURCHASE ORDER</div>
            <div class="col-4"></div>
            <div class="col-2">Halaman : 1</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 10px;">
            <div class="col-4 text-center">DESCRIPTION</div>
            <div class="col-2 text-center">STOCK</div>
            <div class="col-2 text-center">QTY</div>
            <div class="col-2 text-center">PRICE</div>
            <div class="col-2 text-center">TOTAL</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        @php
            $totalOrder = collect($results)->sum(function ($r) {
                return $r['order'] * $r['price'];
            });
        @endphp
        @foreach ($results as $result)
            <div class="row" style="margin-top: 10px;">
                <div class="col-4">{{ $result['nama'] . '/' . $result['unit_jual'] }}</div>
                <div class="col-2 text-center">{{ $result['stok'] }}</div>
                <div class="col-2 text-center">{{ $result['order'] }}</div>
                <div class="col-2 text-right">{{ number_format($result['price'], 2) }}</div>
                <div class="col-2 text-right">{{ number_format(($result['order'] * $result['price']), 2) }}</div>
            </div>
        @endforeach
        <hr class="dashed-line">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-3">TOTAL ORDER &nbsp;====></div>
            <div class="col-3" style="text-align: right;">{{ number_format($totalOrder, 2) }}</div>
        </div>
        <div class="row">
            <div class="col-6">JENIS BARANG : {{ count($results) }}</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 20px;">
            <div class="col-1"></div>
            <div class="col-2">Tanda Terima</div>
            <div class="col-2"></div>
            <div class="col-2">Mengetahui</div>
            <div class="col-2"></div>
            <div class="col-2">Hormat Kami</div>
            <div class="col-1"></div>
        </div>
        <br><br><br>
        <div class="row" style="margin-top: 25px;">
            <div class="col-1">(</div>
            <div class="col-2"></div>
            <div class="col-1">)</div>
            <div class="col-1">(</div>
            <div class="col-2"></div>
            <div class="col-1">)</div>
            <div class="col-1" style="text-align: right">(</div>
            <div class="col-2">LO HARYANTO &emsp14;&emsp14;&emsp14;&emsp14;&emsp14;)</div>
            <div class="col-1"></div>
        </div>
        <hr class="dashed-line">
    </div>

    <script>
        // Fungsi ini akan dipanggil saat halaman selesai dimuat
        function autoPrint() {
            window.print();
        }

        // Menjalankan fungsi autoPrint setelah halaman dimuat sepenuhnya
        window.onload = autoPrint;
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
