@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">STOCK OPNAME</li>
                    </ol>
                </nav>
            </div>

            {{-- <div class="mt-2">
                <a href="{{ route('master.product.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div> --}}
        </div>

        <div class="container mb-4" style="border: 1px solid #000000; padding: 15px;">
            
            <form action="{{ route('master.opname') }}" method="GET">
                <h5 class="text-center mb-3">FILTER</h5>
                <div class="row mb-4">
                    <div class="col-3">
                        {{-- <label class="form-label fs-6 fw-semibold">UNIT :</label> --}}
                        <select class="unit-select" id="unit" name="unit" style="width: 250px;">
                            <option value="">---SELECT UNIT---</option>
                            @foreach ($units as $unit)
                                @if (request('unit') == $unit->id)
                                    <option value="{{ $unit->id }}" selected>
                                        {{ $unit->nama }}
                                    </option>
                                @else
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        {{-- <label class="form-label fs-6 fw-semibold">DEPARTEMEN :</label> --}}
                        <select class="departemen-select" id="departemen" name="departemen" style="width: 250px;">
                            <option value="">---SELECT DEPARTEMEN---</option>
                            @foreach ($departemens as $departemen)
                                @if (request('departemen') == $departemen->id)
                                    <option value="{{ $departemen->id }}" selected>
                                        {{ $departemen->nama }}
                                    </option>
                                @else
                                    <option value="{{ $departemen->id }}">{{ $departemen->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        {{-- <label class="form-label fs-6 fw-semibold">SUPPLIER :</label> --}}
                        <select class="supplier-select" id="supplier" name="supplier" style="width: 250px;">
                            <option value="">---SELECT SUPPLIER---</option>
                            @foreach ($suppliers as $supplier)
                                @if (request('supplier') == $supplier->id)
                                    <option value="{{ $supplier->id }}" selected>
                                        {{ $supplier->nama }}
                                    </option>
                                @else
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-secondary mx-3"
                                id="clear">Clear</button>
                            <button type="submit" class="btn btn-sm btn-primary fw-semibold px-6"
                                data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                id="apply">Apply</button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="{{ route('master.opname.update') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
                @if ($products->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">KODE</th>
                                <th>NAMA</th>
                                <th>STOK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $product->kode }}</td>
                                    <td>{{ $product->nama . '/' . $product->unit_jual }}</td>
                                    <td><input type="number" name="order[{{ $product->id }}]" value="{{ number_format($product->stok, 0) }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning text-center">
                        Pilih Data Filter
                    </div>
                @endif
            

                <div class="row d-flex justify-content-start mb-7">
                    <div class="col-0-5">
                        <button type="submit" class="btn btn-primary" title="SIMPAN DATA"><i class="fas fa-save"></i></button>
                    </div>
                    <div class="col-10"></div>
                    <div class="col-0-5">
                        <button type="button" onclick="window.history.back()" class="btn btn-warning" title="KEMBALI"><i class="fas fa-arrow-left"></i></button>
                    </div>
                    <div class="col-0-5">
                        <a href="{{ route('index') }}" class="btn btn-danger" title="KELUAR"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(`.unit-select`).select2({
            placeholder: '---SELECT UNIT---',
            allowClear: true
        });
        $(`.departemen-select`).select2({
            placeholder: '---SELECT DEPARTEMEN---',
            allowClear: true
        });
        $(`.supplier-select`).select2({
            placeholder: '---SELECT SUPPLIER---',
            allowClear: true
        });

        var unit = document.getElementById("unit");
        var departemen = document.getElementById("departemen");
        var supplier = document.getElementById("supplier");
        document.getElementById("clear").addEventListener("click", function() {
            unit.value = '';
            unit.dispatchEvent(new Event('change'));
            departemen.value = '';
            departemen.dispatchEvent(new Event('change'));
            supplier.value = '';
            supplier.dispatchEvent(new Event('change'));
        });
    </script>
@endsection