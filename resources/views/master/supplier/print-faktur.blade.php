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

    <div class="center report-title">DAFTAR PEMBELIAN SUPPLIER</div>
    <div class="center">{{ $name }}</div>
    <div class="center">{{ $date }}</div>
    <div class="page-info">Halaman : 1</div>

    <table class="table-report">
        <thead>
            <tr>
                <th style="text-align: center;">NO DOKUMEN</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @php
                $details = json_decode($data['detail'] ?? '[]', true);
                $totalField = 0;
            @endphp
            @foreach($details as $dt)
                @php
                    $totalField += $dt['field_total'];
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $id }}</td>
                    <td style="text-align: center;">{{ $date }}</td>
                    <td style="text-align: right;">{{ number_format($dt['field_total'], 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">TOTAL</td>
                <td style="text-align: right;">{{ number_format($totalField, 0) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
