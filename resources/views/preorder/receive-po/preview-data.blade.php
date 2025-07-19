@extends('main')

@section('content')
    <div class="container mb-7 mt-n3">
        {{-- <form action="{{ route('persetujuan-harga-jual-update', $preorder->id) }}" method="POST" class="form"> --}}
        <form action="{{ route('receive-po.update', enkrip($preorder->id)) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div style="overflow-x: auto; height: 620px; border: 1px solid #ccc;">
                        <table id="table-product" class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center"></th>
                                    <th colspan="2" class="text-center">HARGA BELI</th>
                                    <th class="text-center"></th>
                                    <th colspan="2" class="text-center">HARGA JUAL</th>
                                    <th colspan="2" class="text-center"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">NAMA BARANG</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center">HARGA LAMA</th>
                                    <th class="text-center">HARGA BARU</th>
                                    <th class="text-center">NAIK(%)</th>
                                    <th class="text-center">HARGA LAMA</th>
                                    <th class="text-center">HARGA BARU</th>
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
                                        <td class="text-end">{{ number_format($product->harga_lama ? $product->harga_lama : $product->harga_pokok) }}</td>
                                        @if ($product->harga_lama !== 0)
                                            @if ($product->harga_pokok > $product->harga_lama)
                                                <td class="text-end" style="background-color: red; color: white;">{{ number_format($product->harga_pokok) }}</td>
                                            @else
                                                <td class="text-end" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_pokok) }}</td>
                                            @endif 
                                        @else
                                            <td class="text-end">{{ number_format($product->harga_pokok) }}</td>
                                        @endif
                                        <td class="text-end">{{ number_format((($product->harga_pokok - ($product->harga_lama ? $product->harga_lama : $product->harga_pokok)) / ($product->harga_lama ? $product->harga_lama : $product->harga_pokok)) * 100, 2) }}</td>
                                        <td class="text-end" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_jual) }}</td>
                                        <td class="text-end">{{ number_format((($product->harga_pokok * $product->profit) / 100) + $product->harga_pokok) }}</td>
                                        <td class="text-end">{{ $product->profit }}</td>
                                        <td class="text-center"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a class="btn btn-danger" href="{{ route('receive-po.create-detail', enkrip($preorder->id)) }}">KEMBALI</a>
                        </div>
                        {{-- <div class="mx-2">
                            <a href="{{ route('receive-po.cetak', enkrip($preorder->id)) }}" class="btn btn-warning">CETAK</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div> --}}
                        <div class="mx-2">
                            <a href="{{ route('receive-po.cetak', enkrip($preorder->id)) }}" class="btn btn-primary">PROSES</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection