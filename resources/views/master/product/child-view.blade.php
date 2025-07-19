@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER PERSEDIAAN</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="product-table">
                    <thead>
                        <tr class="text-center">
                            <th>NAMA BARANG</th>
                            <th>NO BARANG</th>
                            <th>SUMBER</th>
                            <th>TINGKAT</th>
                            <th>U. BELI</th>
                            <th>U. JUAL</th>
                            <th>KONVERSI</th>
                            <th>HARGA BELI</th>
                            <th>HARGA JUAL</th>
                            <th>ALTERNATIF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $product->nama . '/' . $product->unit_jual }}</td>
                            <td>{{ $product->kode }}</td>
                            <td>{{ $product->kode_sumber }}</td>
                            <td>{{ $product->kode_sumber === null ? 'SUMBER' : 'ANAK' }}</td>
                            <td>{{ $product->unit_beli }}</td>
                            <td>{{ $product->unit_jual }}</td>
                            <td>{{ $product->konversi . '.00' }}</td>
                            <td class="harga_beli_parent">{{ number_format($product->harga_pokok) }}</td>
                            <td>{{ number_format($product->harga_jual) }}</td>
                            <td>{{ $product->kode_alternatif }}</td>
                        </tr>
                        @foreach ($childProduct as $child)
                            <tr>
                                <td>{{ $child->nama . '/' . $child->unit_jual }}</td>
                                <td>{{ $child->kode }}</td>
                                <td>{{ $child->kode_sumber }}</td>
                                <td>{{ $child->kode_sumber === null ? 'SUMBER' : 'ANAK' }}</td>
                                <td>{{ $child->unit_beli }}</td>
                                <td>{{ $child->unit_jual }}</td>
                                <td>{{ $child->konversi . '.00' }}</td>
                                <td>{{ number_format($child->harga_pokok) }}</td>
                                <td>{{ number_format($child->harga_jual) }}</td>
                                <td>{{ $child->kode_alternatif }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-primary mx-3" id="data-anak-btn" disabled>DATA ANAK</button>
                    <button class="btn btn-primary mx-3" id="data-sumber-btn" disabled>DATA SUMBER</button>
                    <button class="btn btn-success mx-3" id="simpan-btn" disabled>SIMPAN</button>
                    <a href="{{ route('master.product.child', enkrip($product->id)) }}" class="btn btn-danger mx-3 disabled-link">BATAL</a>
                    <a href="{{ route('master.product.show', enkrip($product->id)) }}" class="btn btn-success mx-3">SELESAI</a>
                </div>
            </div>
        </div>
    </div>
@endsection