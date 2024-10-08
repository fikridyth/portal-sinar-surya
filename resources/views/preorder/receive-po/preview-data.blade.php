@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PENERIMAAN BARANG - PURCHASE ORDER
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- <form action="{{ route('persetujuan-harga-jual-update', $preorder->id) }}" method="POST" class="form"> --}}
        <form action="#" method="POST" class="form">
            @csrf
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">HARGA SEMENTARA</th>
                                <th class="text-center"></th>
                                <th colspan="2" class="text-center">HARGA SATUAN</th>
                                <th class="text-center"></th>
                                <th colspan="2" class="text-center">HARGA SATUAN</th>
                                <th colspan="2" class="text-center"></th>
                            </tr>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">NAMA BARANG</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">BELI LAMA</th>
                                <th class="text-center">BELI BARU</th>
                                <th class="text-center">NAIK(%)</th>
                                <th class="text-center">JUAL LAMA</th>
                                <th class="text-center">JUAL BARU</th>
                                <th class="text-center">MK UP</th>
                                <th class="text-center">H/D</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $index => $dtl)
                                @php
                                    $product = App\Models\Product::where('kode', $dtl['kode'])->first();
                                    $changeTextColor = (($product->harga_jual - $dtl['price']) / $dtl['price']) * 100;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ str_pad($loop->iteration, 3, 0, STR_PAD_LEFT) }}</td>
                                    <td class="text-center">{{ $dtl['nama'] . '/' . $dtl['unit_jual'] }}</td>
                                    <td class="text-center">{{ $dtl['order'] }}</td>
                                    <input type="text" hidden name="harga_pokok[{{ $index }}]" id="persetujuan_harga_pokok_{{ $index }}" value="{{ $dtl['price'] }}">
                                    <input type="text" hidden name="nama[{{ $index }}]" value="{{ $dtl['nama'] . '/' . $dtl['unit_jual'] . '/' . $dtl['kode'] . '/' . $dtl['price'] }}">
                                    <td class="text-end">{{ number_format($product->harga_pokok) }}</td>
                                    <td class="text-end" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($dtl['price']) }}</td>
                                    <td class="text-end">{{ number_format((($dtl['price'] - $product->harga_pokok) / $product->harga_pokok) * 100, 2) }}</td>
                                    <td class="text-end" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_jual) }}</td>
                                    <td class="text-end">{{ number_format(round((($dtl['price'] * $product->profit) / 100) + $dtl['price'], -3)) }}</td>
                                    <td class="text-end">{{ $product->profit }}</td>
                                    <td class="text-center"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a class="btn btn-danger" href="{{ route('receive-po.create-detail', $preorder->id) }}">KEMBALI</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" class="btn btn-primary">CETAK</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection