<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembelian Supplier</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        .center {
            text-align: center;
        }

        .report-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .table-report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-report th, .table-report td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: right;
        }

        .table-report th {
            background-color: #eee;
        }

        .table-report td.left {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
        }

        .page-info {
            text-align: right;
            margin-top: -10px;
        }

        th, td {
            text-align: center;
        }

        @media print {
            @page {
                margin: 20mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center report-title">*** DAFTAR PROMOSI YANG SUDAH DIBAYAR ***</div>
    <table class="table-report">
        <thead>
            <tr>
                <th style="text-align: center;">NAMA SUPPLIER</th>
                <th style="text-align: center;">JENIS</th>
                <th style="text-align: center;">DOKUMEN</th>
                <th style="text-align: center;">TANGGAL BAYAR</th>
                <th style="text-align: center;">JUMLAH RP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promosi as $promo)
                <tr>
                    <td style="text-align: left;">{{ $promo->supplier->nama }}</td>
                    <td style="text-align: center;">{{ $promo->tipe }}</td>
                    <td style="text-align: center;">{{ $promo->nomor_bukti }}</td>
                    <td style="text-align: center;">{{ $promo->updated_at->format('d/m/Y') }}</td>
                    <td style="text-align: right;">{{ number_format($promo->total, 0) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right;">TOTAL</td>
                <td style="text-align: right;">{{ number_format($promosi->sum('total'), 0) }}</td>
            </tr>
        </tbody>
    </table>

    <script>
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
</body>
</html>
