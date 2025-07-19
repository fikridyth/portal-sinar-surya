@extends('main')

<style>
    .container-box {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        /* Optional: Adds space between columns */
    }

    .column {
        flex: 1 1 10%;
        /* Adjust width to fit 9 columns in a row, 100% / 9 = ~11% */
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
        flex: 1 1 20%;
        /* Adjust width for column with different size */
    }

    .fs-need {
        font-size: 14px;
    }
</style>

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-2" style="width: 95%">
            <div class="card mt-n1">
                <div class="card-body mt-n4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mt-2">
                            <div class="row w-100">
                                <div class="form-group col-12">
                                    <div class="d-flex align-items-center mt-2 mb-2">
                                        <form action="{{ route('master.adjustment.history.index') }}" method="GET" class="d-flex align-items-center">
                                            <div class="mt-2 me-3">
                                                <label class="form-label fw-semibold" style="font-size: 18px">Periode :</label>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <input class="form-control form-control-solid" placeholder="Pilih Periode" autocomplete="off"
                                                    id="periode" name="periode" value="{{ request('periode') }}" />
                                                <button type="button" class="btn btn-secondary mx-3"
                                                    id="clear">Clear</button>
                                                <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                    data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                    id="apply">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                                        <table id="details-table" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr class="fs-need">
                                                    <th class="text-center">NOMOR</th>
                                                    <th class="text-center">SUPPLIER</th>
                                                    <th class="text-center">NAMA BARANG</th>
                                                    <th class="text-center">STOK</th>
                                                    <th class="text-center">FISIK</th>
                                                    <th class="text-center">SELISIH QTY</th>
                                                    <th class="text-center">SELISIH RUPIAH</th>
                                                    <th class="text-center">TANGGAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($adjustments->isEmpty())
                                                    <tr>
                                                        <td colspan="9" class="text-center"><b>TIDAK ADA HISTORY</b></td>
                                                    </tr>
                                                @else
                                                    @foreach ($adjustments as $adjust)
                                                        <tr class="fs-need">
                                                            <td class="text-center">{{ str_pad($adjust->nomor, 6, '0', STR_PAD_LEFT) }}</td>
                                                            <td>{{ $adjust->product->supplier->nama }}</td>
                                                            <td>{{ $adjust->nama }}</td>
                                                            <td class="text-end">{{ $adjust->stok_lama }}</td>
                                                            <td class="text-end">{{ $adjust->stok_baru }}</td>
                                                            <td class="text-end">{{ $adjust->selisih_qty }}</td>
                                                            <td class="text-end">{{ number_format($adjust->selisih_rupiah) }}</td>
                                                            <td class="text-center">{{ Carbon\Carbon::parse($adjust->tanggal)->format('d/m/Y') }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("#periode").daterangepicker({
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

        document.getElementById("periode").value = "{{ request('periode') }}";

        $("#periode").on(
            "apply.daterangepicker",
            function(ev, picker) {
                $(this).val(picker.startDate.format("YYYY-MM-DD") + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            }
        );

        $("#periode").on(
            "cancel.daterangepicker",
            function() {
                $(this).val('');
            }
        );

        var periode = document.getElementById("periode");
        document.getElementById("clear").addEventListener("click", function() {
            periode.value = '';
        });
    </script>
@endsection
