<!DOCTYPE html>
<html lang="id">
@php
    $date_last = Carbon\Carbon::parse($preorder->date_last);
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
            border-top: 1px dashed black; /* Ganti 'black' dengan warna yang diinginkan */
            margin: 10px 0; /* Sesuaikan margin sesuai kebutuhan */
        }
        .dashed-line-2 {
            border: 0;
            border-top: 1px dashed black; /* Ganti 'black' dengan warna yang diinginkan */
            margin: -5px 0; /* Sesuaikan margin sesuai kebutuhan */
        }

        .col-1-5 {
            flex: 0 0 12.33%; /* Lebar 1/12 dari lebar kontainer (12.33% x 12 = 100%) */
            max-width: 12.33%;
        }
    </style>
</head>

<body>
    <form action="{{ route('receive-po.update', enkrip($preorder->id)) }}" method="POST" class="form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="container mt-4">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-3">KONFIRMASI HARGA</div>
                <div class="col-3"></div>
                <div class="col-2">Halaman : 1</div>
            </div>
            <hr class="dashed-line">
            <div class="row">
                <div class="col-3">{{ $preorder->supplier->nama }}</div>
                <div class="col-5"></div>
                <div class="col-3">P/O Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ now()->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="col-3"></div>
                <div class="col-5"></div>
                <div class="col-3">Receipt Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ now()->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="col-3">Phone &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                <div class="col-5"></div>
                <div class="col-3">Due Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ now()->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="col-3">Fax &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                <div class="col-5"></div>
                <div class="col-3">Type Of Payment : Kredit</div>
            </div>
            <div class="row">
                <div class="col-3">P/O Number : {{ $preorder->nomor_po }}</div>
                <div class="col-5"></div>
                <div class="col-4">Nomor Bukti &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $preorder->nomor_receive }}</div>
            </div>
            <hr class="dashed-line">
            <hr class="dashed-line-2">
            <div class="row" style="margin-top: 10px; margin-bottom: -10px;">
                <div class="col-4"></div>
                <div class="col-3 text-center">---- HARGA BELI ----</div>
                <div class="col-1"></div>
                <div class="col-3 text-center">---- HARGA JUAL ----</div>
                <div class="col-1"></div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-3">NAMA BARANG</div>
                <div class="col-1">QTY</div>
                <div class="col-1-5">HARGA LAMA</div>
                <div class="col-1-5">HARGA BARU</div>
                <div class="col-1">(%)</div>
                <div class="col-1-5">HARGA LAMA</div>
                <div class="col-1-5">HARGA BARU</div>
                <div class="col-1">MRKUP</div>
            </div>
            <hr class="dashed-line">
            <hr class="dashed-line-2">
            @foreach (json_decode($preorder->detail, true) as $detail)
                @if (!empty($detail['field_total']) && $detail['field_total'] > 0)
                    @php
                        $product = App\Models\Product::where('kode', $detail['kode'])->first();
                    @endphp
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-3">{{ $detail['nama'] }}/{{ $product->unit_jual }}</div>
                        <div class="col-1">{{ $detail['order'] }}</div>
                        <div class="col-1-5 text-center">{{ number_format($product->harga_lama) }}</div>
                        <div class="col-1-5 text-center">{{ number_format($detail['price']) }}</div>
                        <div class="col-1">{{ number_format((($detail['price'] - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</div>
                        <div class="col-1-5 text-center">{{ number_format($product->harga_jual) }}</div>
                        <div class="col-1-5 text-center">{{ number_format($product->harga_jual) }}</div>
                        <div class="col-1">{{ number_format((($product->harga_jual - $detail['price']) / $detail['price']) * 100, 2) }}</div>
                    </div>
                @endif
            @endforeach
            <hr class="dashed-line">
            <hr class="dashed-line-2">
            <div class="row mt-3">
                <div class="col-6"></div>
                <div class="col-3">Total Faktur Rp :</div>
                <div class="col-3" style="text-align: right;">{{ number_format($preorder->grand_total) }}</div>
            </div>
            @foreach (json_decode($preorder->detail, true) as $detail)
                @if ($detail['field_total'] == 0)
                    @php
                        $product = App\Models\Product::where('kode', $detail['kode'])->first();
                    @endphp
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-4">BONUS {{ $detail['nama'] }}/{{ $product->unit_jual }}</div>
                        <div class="col-1">{{ $detail['order'] }}</div>
                        <div class="col-1-5"></div>
                        <div class="col-1-5"></div>
                        <div class="col-1-5"></div>
                        <div class="col-1-5"></div>
                    </div>
                @endif
            @endforeach
            {{-- <hr class="dashed-line">
            <hr class="dashed-line-2"> --}}
            <div class="row" style="margin-top: 20px;">
                <div class="col-3"></div>
                <div class="col-2">Dibuat Oleh</div>
                <div class="col-2"></div>
                <div class="col-3">Disetujui Oleh</div>
                <div class="col-2"></div>
            </div>
            <br><br><br>
            <div class="row" style="margin-top: 25px;">
                <div class="col-3"></div>
                <div class="col-2">( {{ auth()->user()->name }} )</div>
                <div class="col-2"></div>
                <div class="col-3">( LO HARYANTO )</div>
                <div class="col-2"></div>
            </div>
            <hr class="dashed-line">
        </div>
    </form>

    <script>
        // Fungsi ini akan dipanggil saat halaman selesai dimuat
        function autoPrint() {
            window.print();
        }

        // Menjalankan fungsi autoPrint setelah halaman dimuat sepenuhnya
        window.onload = autoPrint;

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' || event.key === 'Backspace') {
                event.preventDefault();
                document.querySelector('form').submit();
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
