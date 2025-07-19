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
        <hr class="dashed-line-2 mb-2">
        <table class="table table-bordered" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="font-size: 10px;">NAMA BARANG</th>
                    <th style="font-size: 10px;">STOK</th>
                    <th style="font-size: 10px;">BAL</th>
                    <th style="font-size: 10px;">SLOP</th>
                    <th style="font-size: 10px;">BKS</th>
                    <th style="font-size: 10px;">TOTAL</th>
                    <th style="font-size: 10px;">BAL</th>
                    <th style="font-size: 10px;">SLOP</th>
                    <th style="font-size: 10px;">BKS</th>
                    <th style="font-size: 10px;">TOTAL</th>
                    <th style="font-size: 10px;">BAL</th>
                    <th style="font-size: 10px;">SLOP</th>
                    <th style="font-size: 10px;">BKS</th>
                    <th style="font-size: 10px;">TOTAL</th>
                    <th style="font-size: 10px;">BAL</th>
                    <th style="font-size: 10px;">SLOP</th>
                    <th style="font-size: 10px;">BKS</th>
                    <th style="font-size: 10px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td style="font-size: 8px;"><b>{{ $product['nama'] }}/{{ $product['unit_jual'] }}</b></td>
                    <td style="text-align: right; font-size: 10px;"><b>{{ number_format($product['stok'], 0) }}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="dashed-line">
        <hr class="dashed-line-2">
        
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
