<!DOCTYPE html>
<html lang="id">
@php
    // $date_last = Carbon\Carbon::parse($preorder->date_last);
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
    </style>
</head>

<body>
    @if ($parameter == 0)<div>@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 1)<div style="margin-top: 150px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 2)<div style="margin-top: 280px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 3)<div style="margin-top: 410px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 4)<div style="margin-top: 540px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 5)<div style="margin-top: 670px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 6)<div style="margin-top: 800px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 7)<div style="margin-top: 930px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 8)<div style="margin-top: 1060px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 9)<div style="margin-top: 1190px;">@include('pembayaran.template-cetak')</div>@endif
    @if ($parameter == 10)<div style="margin-top: 1320px;">@include('pembayaran.template-cetak')</div>@endif

    <script>
        // Fungsi ini akan dipanggil saat halaman selesai dimuat
        function autoPrint() {
            window.print();
        }

        // Menjalankan fungsi autoPrint setelah halaman dimuat sepenuhnya
        window.onload = autoPrint;

        const pembayaranIndexUrl = "{{ route('pembayaran.index') }}";
    
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' || event.key === 'Backspace') {
                window.location.href = pembayaranIndexUrl;
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
