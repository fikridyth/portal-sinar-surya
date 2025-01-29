<!DOCTYPE html>
<html lang="id">
@php
    $date = Carbon\Carbon::parse($kredit->date);
@endphp

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
            border-top: 1px dashed black;
            /* Ganti 'black' dengan warna yang diinginkan */
            margin: 10px 0;
            /* Sesuaikan margin sesuai kebutuhan */
        }

        .dashed-line-2 {
            border: 0;
            border-top: 1px dashed black;
            /* Ganti 'black' dengan warna yang diinginkan */
            margin: -5px 0;
            /* Sesuaikan margin sesuai kebutuhan */
        }

        .col-1-5 {
            flex: 0 0 12.33%; /* Lebar 1/12 dari lebar kontainer (12.33% x 12 = 100%) */
            max-width: 12.33%;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-3">SINAR SURYA</div>
            <div class="col-5"></div>
            <div class="col-4"></div>
        </div>
        <div class="row mb-3">
            <div class="col-5">JL. PASAR BARU - BOGOR</div>
            <div class="col-7"></div>
        </div>
        <div class="row mt-1">
            <div class="col-4"></div>
            <div class="col-4" style="text-align: center">FAKTUR PENJUALAN</div>
            <div class="col-4"></div>
        </div>
        <div class="row">
            <div class="col-3">KEPADA YTH :</div>
            <div class="col-5"></div>
            <div class="col-4"></div>
        </div>
        <div class="row mt-4">
            <div class="col-3">{{ $kredit->langganan->nama }}</div>
            <div class="col-5"></div>
            <div class="col-4">NOMOR FAKTUR : {{ $kredit->nomor }}</div>
        </div>
        <div class="row">
            <div class="col-3">ALAMAT &emsp13;: {{ $kredit->langganan->alamat1 }}</div>
            <div class="col-5"></div>
            <div class="col-4">TANGGAL &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&nbsp;&nbsp;: {{ $date->format('d/m/Y') }}</div>
        </div>
        <div class="row mb-4">
            <div class="col-3">KOTA &emsp13;&emsp13;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $kredit->langganan->alamat2 }}</div>
            <div class="col-5"></div>
            <div class="col-4">TELEPON &emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp13;&emsp14;&emsp14;&nbsp;: {{ $kredit->langganan->telepon }}</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 10px;">
            <div class="col-1-5" style="text-align: center;"></div>
            <div class="col-1-5" style="text-align: center;">NOMOR</div>
            <div class="col-3">NAMA SUPPLIER</div>
            <div class="col-1-5" style="text-align: right;">QUANTITY</div>
            <div class="col-1-5" style="text-align: right;">HARGA</div>
            <div class="col-1-5" style="text-align: right;">DISKON</div>
            <div class="col-1-5" style="text-align: right;">JUMLAH</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        @foreach (json_decode($kredit->detail, true) as $detail)
            <div class="row" style="margin-top: 10px;">
                <div class="col-1-5" style="text-align: center;">{{ $loop->iteration }}</div>
                <div class="col-1-5" style="text-align: center;">{{ $detail['kode'] }}</div>
                <div class="col-3">{{ $detail['nama'] }}</div>
                <div class="col-1-5" style="text-align: right;">{{ $detail['order'] }}</div>
                <div class="col-1-5" style="text-align: right;">{{ number_format($detail['price']) }}</div>
                <div class="col-1-5" style="text-align: right;">0</div>
                <div class="col-1-5" style="text-align: right;">{{ number_format($detail['field_total']) }}</div>
            </div>
        @endforeach
        {{-- <hr class="dashed-line">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-3">TOTAL ORDER &nbsp;====></div>
            <div class="col-3" style="text-align: right;">{{ number_format($kredit->grand_total) }}</div>
        </div>
        <div class="row">
            <div class="col-6">JENIS BARANG : {{ count(json_decode($kredit->detail, true)) }}</div>
        </div> --}}
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 20px;">
            <div class="col-8"></div>
            <div class="col-2" style="text-align: right">JUMLAH &emsp13;&emsp13;RP</div>
            <div class="col-2" style="text-align: right"><u>{{ number_format($kredit->total) }}</u></div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-1"></div>
            <div class="col-2">Tanda Terima</div>
            <div class="col-2"></div>
            <div class="col-2">Mengetahui</div>
        </div>
        <br><br><br>
        <div class="row" style="margin-top: 25px;">
            <div class="col-1">(</div>
            <div class="col-2"></div>
            <div class="col-1">)</div>
            <div class="col-1" style="text-align: right">(</div>
            <div class="col-2">LO HARYANTO &emsp14;&emsp14;&emsp14;&emsp14;&emsp14;)</div>
            <div class="col-1"></div>
            {{-- <div class="col-1">(</div>
            <div class="col-2"></div>
            <div class="col-1">)</div> --}}
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
