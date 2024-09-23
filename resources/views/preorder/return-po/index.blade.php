@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">RETUR PEMBELIAN BARANG
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('return-po.store') }}" method="POST" class="form">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-7">
                                    <div class="row align-items-center">
                                        <label class="col-3 col-form-label">NOMOR RETUR</label>
                                        <div class="col-3">
                                            <input type="text" class="readonly-input form-control" value="Auto Generate" autocomplete="off" readonly>
                                        </div>
                                        <label class="col-2 col-form-label text-end">TANGGAL</label>
                                        <div class="col-3">
                                            <input type="text" name="date" class="readonly-input form-control" value="{{ now()->format('Y-m-d') }}" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-5">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-form-label text-center">KETERANGAN</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-7">
                                    <div class="row align-items-center mt-1">
                                        <label class="col-3 col-form-label">BUKTI PENERIMAAN</label>
                                        <div class="col-7">
                                            <select id="preorder-select" name="nomor_receive" required class="preorder-select btn-block">
                                                <option value=""></option>
                                                @foreach ($preorders as $preorder)
                                                    <option value="{{ $preorder->nomor_receive }}">{{ $preorder->nomor_receive }} - {{ $preorder->supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                        
                                    <div class="row align-items-center mt-1">
                                        <label class="col-3 col-form-label">NAMA SUPPLIER</label>
                                        <div class="col-7">
                                            <select id="supplier-select" name="id_supplier" required class="supplier-select btn-block">
                                                <option value=""></option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-5 mb-4">
                                    <div class="row">
                                        <textarea name="" id="" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">NO BARANG</th>
                                                <th class="text-center">NAMA BARANG</th>
                                                <th class="text-center">KETERANGAN</th>
                                                <th class="text-center">QTY</th>
                                                <th class="text-center">NOMOR PO</th>
                                                <th class="text-center">HARGA</th>
                                                <th class="text-center">TOTAL RP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <button type="button" id="button-tambah" class="btn btn-success">TAMBAH</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" id="button-simpan" disabled class="btn btn-primary">SIMPAN</button>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">TOTAL</label>
                                </div>
                                <div class="col-auto mx-4">
                                    <input type="text" size="15" class="readonly-input form-control text-end" value="0" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('index') }}" class="btn btn-danger mx-5">BATAL</a>
                            <button type="submit" class="btn btn-primary">PROSES</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(`.supplier-select`).select2({
                placeholder: '---Select Supplier---',
                allowClear: true
            });
            
            $(`.preorder-select`).select2({
                placeholder: '---Select Receive---',
                allowClear: true
            });
        });
    </script>
@endsection
