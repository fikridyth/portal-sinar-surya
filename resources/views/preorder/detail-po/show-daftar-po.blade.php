@extends('main')

@php
    $totalPrice = 0;
    $totalOrder = 0;
@endphp
<style>
    .container-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Optional: Adds space between columns */
        }
    .column {
        flex: 1 1 10%; /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
        box-sizing: border-box;
    }
    .form-group {
        margin: 0;
        text-align: center;
    }
    .col-form-label {
        font-size: 12px;
    }
    .col-2 {
        flex: 1 1 20%; /* Adjust width for column with different size */
    }
</style>

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">DAFTAR BARANG YANG HARUS DIPESAN
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('preorder.process-barang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">Nomor PO</label>
                                            {{-- <div class="col">
                                                <input type="text" value="{{ $preorder->nomor_po }}" disabled class="form-control" id="nomorSupplier2" value="">
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            {{-- <label for="nomorSupplier2" class="col col-form-label">Nomor PO</label> --}}
                                            <div class="col">
                                                <input type="text" value="{{ $preorder->nomor_po }}" disabled class="form-control" id="nomorSupplier2" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="inputPassword3" class="col col-form-label d-flex justify-content-end">TANGGAL PO</label>
                                            <div class="col">
                                                <input type="text" value="{{ $preorder->created_at->format('d/m/Y') }}" disabled class="form-control" id="inputPassword3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">NAMA SUPPLIER</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="col">
                                        <input type="text" value="{{ $preorder->supplier->nama }}" disabled class="form-control" id="nomorSupplier2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="d-flex justify-content-center">
                            <div class="row w-100">
                                <div class="col-3"></div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col-sm-4 col-form-label">DIORDER OLEH</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $preorder->supplier->nama }}" disabled class="form-control" id="nomorSupplier2" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="container-box">
                            <div class="column"><div class="form-group"><label class="col-form-label">H. TERAKHIR</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 1</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 2</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 3</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">PPN(%)</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">HARGA RATA2</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 1</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 2</label></div></div>
                            <div class="column"><div class="form-group"><label class="col-form-label">DISKON 3</label></div></div>
                        </div>
                        <div class="container-box mb-3">
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                            <div class="column"><div class="form-group"><input type="text" disabled size="9"></div></div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">KODE</th>
                                                <th class="text-center">NAMA BARANG</th>
                                                <th class="text-center">ISI</th>
                                                <th class="text-center">SAT</th>
                                                <th class="text-center">H.SATUAN</th>
                                                <th class="text-center">ORDER</th>
                                                <th class="text-center">TERIMA</th>
                                                <th class="text-center">HARGA</th>
                                                <th class="text-center">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (json_decode($preorder->detail, true) as $detail)
                                            @php
                                                $totalPrice += $detail['field_total'];
                                                $totalOrder += $detail['order'];
                                            @endphp
                                                {{-- @dd($totalOrder) --}}
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $detail['kode'] }}</td>
                                                    <td>{{ $detail['nama'] . '/' . $detail['unit_jual'] }}</td>
                                                    <td class="text-end">{{ str_replace('P', '', $detail['unit_jual']) }}</td>
                                                    <td class="text-end">{{ str_replace('P', '', $detail['unit_jual']) }}</td>
                                                    <td class="text-end">{{ number_format($detail['price']) }}</td>
                                                    <td class="text-end">{{ number_format($detail['order'], 2) }}</td>
                                                    <td class="text-end">{{ number_format($detail['order'], 2) }}</td>
                                                    <td class="text-end">{{ number_format($detail['price']) }}</td>
                                                    <td class="text-end">{{ number_format($detail['field_total']) }}</td> 
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <div class="mx-2">
                                <label for="totalPrice">Jumlah</label>
                            </div>
                            <div class="mx-2">
                                <input id="totalPrice" type="text" value="{{ number_format($totalPrice) }}" disabled size="10" class="form-control">
                            </div>
                            <div class="mx-2">
                                <label for="totalOrder">Jumlah Koli</label>
                            </div>
                            <div class="mx-2">
                                <input id="totalOrder" type="text" value="{{ number_format($totalOrder, 2) }}" disabled size="5" class="form-control">
                            </div>
                            <div class="mx-2">
                                <a href="{{ route('daftar-po.cetak', enkrip($preorder->id)) }}" class="btn btn-primary">CETAK</a>
                            </div>
                            <div class="mx-2">
                                <a class="btn btn-danger" href="{{ route('daftar-po') }}">KEMBALI</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
