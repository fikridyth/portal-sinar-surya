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
    .table thead th {
        position: sticky; /* Enable sticky positioning */
        top: 0; /* Stick to the top */
        background-color: white; /* Background color for header */
        z-index: 10; /* Ensure the header is above other content */
    }
</style>

@section('content')
    <div class="container mb-2 mt-n3">
        <div class="card">
            <div class="card-body mt-n4">
                <form action="{{ route('master.kartu-stok.show', enkrip($product->id)) }}" method="GET" class="d-flex align-items-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="row w-100">
                                <div class="col-2"></div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">NOMOR BARANG</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="col">
                                        <input type="text" value="{{ $product->kode }}" disabled class="form-control" id="nomorSupplier2" value="">
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
                                            <label for="nomorSupplier2" class="col col-form-label d-flex justify-content-end">NAMA BARANG</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="col">
                                        <input type="text" value="{{ $product->nama }}" disabled class="form-control" id="nomorSupplier2" value="">
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
                                            <label class="col col-form-label d-flex justify-content-end">PERIODE</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="col">
                                        <input class="form-control form-control-solid" placeholder="Pilih Periode" autocomplete="off" id="periode" name="periode" style="flex: 1; border: 1px solid black;" />
                                    </div>
                                </div>
                                <div class="col-1 mx-n4">
                                    <button type="button" class="btn btn-secondary mx-3"id="clear">Clear</button>
                                </div>
                                <div class="col-1 mx-5">
                                    <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" id="apply">Apply</button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">NO</th>
                                                    <th class="text-center">TANGGAL/SUPPLIER / LANGGANAN</th>
                                                    <th class="text-center">TIPE</th>
                                                    <th class="text-center"></th>
                                                    @foreach ($allProducts as $prd)
                                                        <th class="text-center">{{ $prd['unit_jual'] }}</th>
                                                    @endforeach
                                                    <th class="text-center">MASUK</th>
                                                    <th class="text-center">KELUAR</th>
                                                    <th class="text-center">STOK AKHIR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productFlow as $flow)
                                                    @php
                                                        $getTipe = explode('-', $flow['tipe']);
                                                        if ($getTipe[0] == 'RP') $flow['tipe'] = 'RECEIVE';
                                                        else if ($getTipe[0] == 'RR') $flow['tipe'] = 'RETUR';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($flow['tanggal'])->format('d/m/Y') . " ' " . $product->supplier->nama }}</td>
                                                        <td>{{ $flow['tipe'] }}</td>
                                                        <td class="text-center"><input type="checkbox" name="" id=""></td>
                                                        @foreach ($allProducts as $prd)
                                                            {{-- @dd($prd, $flow) --}}
                                                            @if ($prd['kode'] == $flow['kode'])
                                                                <td class="text-end">{{ $flow['total'] }}</td>
                                                            @else
                                                                <td class="text-end"></td>
                                                            @endif
                                                        @endforeach
                                                        <td class="text-end">@if ($flow['total'] > 0) {{ $flow['total'] * ($flow['unit_jual'] ?? 1) }} @endif</td>
                                                        <td class="text-end">@if ($flow['total'] <= 0) {{ str_replace('-', '', $flow['total']) * ($flow['unit_jual'] ?? 1) }} @endif</td>
                                                        <td class="text-end"></td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td>{{ now()->format('d/m/Y') . " ' " . $product->supplier->nama }}</td>
                                                    <td style="color: red;">STOK</td>
                                                    <td class="text-center"><input type="checkbox" name="" id=""></td>
                                                    @foreach ($allProducts as $prd)
                                                        <td class="text-end" style="color: red;">{{ $prd['stok'] }}</td>
                                                    @endforeach
                                                    <td class="text-end" style="color: red;"></td>
                                                    <td class="text-end" style="color: red;"></td>
                                                    <td class="text-end" style="color: red;">{{ $totalMasuk }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-3 mb-n5">
                            <div class="mx-2">
                                <a class="btn btn-primary" href="{{ route('master.kartu-stok.index') }}">BARANG LAIN</a>
                            </div>
                            <div class="mx-2">
                                <a class="btn btn-danger" href="{{ route('index') }}">KELUAR</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let today = moment();
        let startOfMonth = moment().startOf('month');

        $("#periode").daterangepicker({
            startDate: startOfMonth,
            endDate: today,
            locale: {
                cancelLabel: "Clear",
                format: "YYYY-MM-DD",
                monthNames: [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember",
                ],
            },
            dateLimit: {
                days: 375
            },
            autoApply: true
        });

        // set default value di input
        $("#periode").val(
            startOfMonth.format("YYYY-MM-DD") + ' - ' + today.format("YYYY-MM-DD")
        );

        // jika ada request dari backend, override default
        @if(request('periode'))
            $("#periode").val("{{ request('periode') }}");
        @endif

        $("#periode").on("apply.daterangepicker", function(ev, picker) {
            $(this).val(
                picker.startDate.format("YYYY-MM-DD") +
                ' - ' +
                picker.endDate.format("YYYY-MM-DD")
            );
        });

        $("#periode").on("cancel.daterangepicker", function() {
            $(this).val('');
        });

        document.getElementById("clear").addEventListener("click", function() {
            $("#periode").val('');
        });
    </script>
@endsection

