<!DOCTYPE html>
<html lang="id">
@php
    $date = Carbon\Carbon::parse($piutang->date);
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
            <div class="col-3">
                {{ $piutang->date = Carbon\Carbon::parse($piutang->date)->locale('id')->isoFormat('D MMMM YYYY') }}
            </div>
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
            <div class="col-3">P/0 &nbsp;: {{ $piutang->nomor_piutang }}</div>
            <div class="col-5"></div>
            <div class="col-3">CP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: EDI 087870000717</div>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">DAFTAR TAGIHAN LANGGANAN</div>
            <div class="col-2"></div>
            <div class="col-2">Halaman : 1</div>
        </div>
        {{-- <hr class="dashed-line">
        <div class="row">
            <div class="col-3">Due Date
                &emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;:
                {{ $date->format('d/m/Y') }}</div>
            <div class="col-5"></div>
            <div class="col-4">Arrival Date
                &emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;&emsp14;:
                {{ $date->format('d/m/Y') }}</div>
        </div>
        <div class="row">
            <div class="col-3">Type Of Payment : Credit</div>
            <div class="col-5"></div>
            <div class="col-3">Cancellation Date : {{ $date->format('d/m/Y') }}</div>
        </div>
        <div class="row">
            <div class="col-3">Term Of Payment : 0</div>
        </div> --}}
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        <div class="row" style="margin-top: 10px;">
            <div class="col-3" style="text-align: center;">NAMA SUPPLIER</div>
            <div class="col-1-5" style="text-align: center;">NO DOKUMEN</div>
            <div class="col-1-5" style="text-align: center;">TANGGAL</div>
            <div class="col-1-5" style="text-align: right;">JUMLAH</div>
            <div class="col-1-5" style="text-align: right;">BONUS</div>
            <div class="col-1-5" style="text-align: right;">MATERAI</div>
            <div class="col-1-5" style="text-align: right;">TOTAL</div>
        </div>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        @foreach (json_decode($piutang->detail, true) as $detail)
            <div class="row" style="margin-top: 10px;">
                <div class="col-3">{{ $detail['nama'] }}</div>
                <div class="col-1-5" style="text-align: center;">{{ $detail['nomor_bukti'] }}</div>
                <div class="col-1-5" style="text-align: center;">{{ $detail['date'] }}</div>
                <div class="col-1-5" style="text-align: right;">{{ number_format($detail['grand_total']) }}</div>
                <div class="col-1-5" style="text-align: right;">0</div>
                <div class="col-1-5" style="text-align: right;">{{ number_format($detail['beban_materai']) }}</div>
                <div class="col-1-5" style="text-align: right;">{{ number_format($detail['total_with_materai']) }}</div>
            </div>
        @endforeach
        {{-- <hr class="dashed-line">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-3">TOTAL ORDER &nbsp;====></div>
            <div class="col-3" style="text-align: right;">{{ number_format($piutang->grand_total) }}</div>
        </div>
        <div class="row">
            <div class="col-6">JENIS BARANG : {{ count(json_decode($piutang->detail, true)) }}</div>
        </div> --}}
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
