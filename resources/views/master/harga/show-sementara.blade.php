@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 95%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                DATA HARGA SEMENTARA
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <label class="mx-4">KODE SUPPLIER</label>
                        <input type="text" class="readonly-input mx-3" readonly value="{{ $products[0]->supplier->nomor }}" style="width: 75px;">
                        <input type="text" class="readonly-input mx-3" readonly value="{{ $products[0]->supplier->nama }}">
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAMA BARANG</th>
                                        {{-- <th class="text-center">HARGA BELI LAMA</th>
                                        <th class="text-center">HARGA BELI BARU</th>
                                        <th class="text-center">%</th>
                                        <th class="text-center">MARK UP</th> --}}
                                        <th class="text-center">HARGA JUAL</th>
                                        <th class="text-center">HARGA SEMENTARA</th>
                                        <th class="text-center">TANGGAL AWAL</th>
                                        <th class="text-center">TANGGAL AKHIR</th>
                                        {{-- <th class="text-center">V</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($products as $index => $product)
                                        <form action="{{ route('master.harga.update', $product->id) }}" method="POST" class="form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <tr>
                                                <input type="hidden" name="id_supplier" value="{{ $product->id_supplier }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->nama }}/{{ $product->unit_jual }}</td>
                                                <td><input type="number" name="harga_lama" required value="{{ $product->harga_lama }}" style="width: 100px;"></td>
                                                <td><input type="number" name="harga_pokok" required value="{{ $product->harga_pokok }}" style="width: 100px;"></td>
                                                <td>{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                                <td><input type="number" name="harga_jual" required value="{{ $product->harga_jual }}" style="width: 100px;"></td>
                                                <td><input type="text" class="readonly-input" name="profit" readonly value="{{ $product->profit }}" style="width: 55px;"></td>
                                                <td><input type="number" name="harga_sementara" required value="{{ $product->harga_sementara ?? 0 }}" style="width: 100px;"></td>
                                                <td><input type="date" name="tanggal_awal" required value="{{ $product->tanggal_awal }}" style="width: 100px;"></td>
                                                <td><input type="date" name="tanggal_akhir" required value="{{ $product->tanggal_akhir }}" style="width: 100px;"></td>
                                                <td><button type="submit" class="btn btn-primary">UBAH</button></td>
                                            </tr>
                                        </form>
                                    @endforeach --}}
                                    @foreach ($products as $index => $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->nama }}/{{ $product->unit_jual }}</td>
                                            {{-- <td class="text-end">{{ number_format($product->harga_lama, 0) }}</td>
                                            <td class="text-end">{{ number_format($product->harga_pokok, 0) }}</td>
                                            <td class="text-end">{{ number_format((($product->harga_pokok - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                            <td class="text-end">{{ $product->profit }}</td> --}}
                                            <td class="text-end">{{ number_format($product->harga_jual, 0) }}</td>
                                            <td class="text-end">{{ number_format($product->harga_sementara, 0) ?? 0 }}</td>
                                            <td class="text-center">{{ $product->tanggal_awal }}</td>
                                            <td class="text-center">{{ $product->tanggal_akhir }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection