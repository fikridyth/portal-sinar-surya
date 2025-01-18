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
        body {
            font-size: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
        .dashed-line {
            border: 0;
            border-top: 1px dashed black; /* Ganti 'black' dengan warna yang diinginkan */
            margin: 10px 0; /* Sesuaikan margin sesuai kebutuhan */
            width: 25%;
            float: right;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-between">
        <div class="p-3 border-right" style="width: 50%;">
            <p class="text-right">{{ auth()->user()->name }}</p>
            <div class="d-flex justify-content-between" style="margin-top: -18px;">
                <p>No : {{ $getNomorBukti }}</p>
                <p class="text-right">Tanggal : {{ now()->format('d/m/Y') }}</p>
            </div>
            <p style="margin-top: -10px;">Sudah Dibayarkan Kepada :</p>
            <p style="margin-top: -18px;">{{ $supplier->nama }}</p>
            <p style="margin-top: -10px;">Uang Sejumlah :</p>
            <p class="text-left mx-3" style="margin-top: -18px;"><i>{{ $formatTotal }} rupiah</i></p>
            @foreach ($getHutang as $hutang)
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0" style="min-width: 200px;">{{ $hutang['nomor'] }}</p>
                    <p class="mb-0" style="min-width: 50px;">{{ \Carbon\Carbon::parse($hutang['date'])->format('d/m/Y') }}</p>
                    <p class="mb-0" style="margin-left: 100px;">Rp </p>
                    <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($hutang['total']) }}</p>
                </div>
            @endforeach
            <hr class="dashed-line">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Jumlah</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($totalHutang) }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Materai</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">-{{ number_format($supplier->materai) }}</p>
            </div>
            <hr class="dashed-line">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Total</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($totalHutang - $supplier->materai) }}</p>
            </div>
            <hr class="dashed-line">

        </div>
        <div class="p-3 border-left" style="width: 50%;">
            <div class="d-flex justify-content-between">
                <p>COPY</p>
                <p class="text-right">{{ auth()->user()->name }}</p>
            </div>
            <div class="d-flex justify-content-between" style="margin-top: -18px;">
                <p>No : {{ $getNomorBukti }}</p>
                <p class="text-right">Tanggal : {{ now()->format('d/m/Y') }}</p>
            </div>
            <p style="margin-top: -10px;">Sudah Dibayarkan Kepada :</p>
            <p style="margin-top: -18px;">{{ $supplier->nama }}</p>
            <p style="margin-top: -10px;">Uang Sejumlah :</p>
            <p class="text-left mx-3" style="margin-top: -18px;"><i>{{ $formatTotal }} rupiah</i></p>
            @foreach ($getHutang as $hutang)
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0" style="min-width: 200px;">{{ $hutang['nomor'] }}</p>
                    <p class="mb-0" style="min-width: 50px;">{{ \Carbon\Carbon::parse($hutang['date'])->format('d/m/Y') }}</p>
                    <p class="mb-0" style="margin-left: 100px;">Rp </p>
                    <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($hutang['total']) }}</p>
                </div>
            @endforeach
            <hr class="dashed-line">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Jumlah</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($totalHutang) }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Materai</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">-{{ number_format($supplier->materai) }}</p>
            </div>
            <hr class="dashed-line">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="mb-0" style="min-width: 230px;"></p>
                <p class="mb-0" style="min-width: 20px;">Total</p>
                <p class="mb-0" style="margin-left: 100px;">Rp </p>
                <p class="mb-0" style="min-width: 100px; text-align: right;">{{ number_format($totalHutang - $supplier->materai) }}</p>
            </div>
            <hr class="dashed-line">
        </div>
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
