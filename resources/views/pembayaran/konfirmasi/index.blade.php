@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 55%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                KONFIRMASI CEK / GIRO
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <form action="{{ route('pembayaran-konfirmasi.show') }}" method="GET" class="form">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <label class="mx-4">KODE SUPPLIER</label>
                            <select class="supplier-select" required id="supplierSelect" name="id_supplier">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ enkrip($supplier->id) }}">{{ $supplier->nomor }} - {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-center align-items-center" style="margin-top: 30px;">
                            <label for="tanggal" class="mx-3" style="margin-right: 10px; font-size: 18px;">TANGGAL</label>
                        
                            <div class="d-flex justify-content-between" style="width: 85%; background-color: #1cd2d2; padding: 10px; border-radius: 4px;">
                                <div class="mx-4">
                                    <input type="radio" name="range" value="1">
                                    <label style="color: red;">PERIODE</label>
                                </div>
                                <div class="mx-4">
                                    <input type="radio" name="range" value="2">
                                    <label style="color: red;">HARI INI</label>
                                </div>
                                <div class="mx-4">
                                    <input type="radio" name="range" value="3">
                                    <label style="color: red;">1 MINGGU</label>
                                </div>
                                <div class="mx-4">
                                    <input type="radio" name="range" value="4">
                                    <label style="color: red;">1 BULAN</label>
                                </div>
                                <div class="mx-4">
                                    <input type="radio" name="range" value="5">
                                    <label style="color: red;">SELURUHNYA</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <div class="d-flex justify-content-center" style="width: 60%; padding: 10px; border-radius: 4px;">
                                <div class="row align-items-center mx-5">
                                    <div class="col-3">
                                        <label>PERIODE</label>
                                    </div>
                                    <div class="col-9">
                                        <input class="form-control form-control-solid" placeholder="Pilih Periode" autocomplete="off"
                                        id="periode" name="periode" style="flex: 1; border: 1px solid black;" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <div class="d-flex justify-content-between" style="width: 100%; padding: 10px; border-radius: 4px;">
                                <a href="{{ route('master.giro.index') }}" class="btn btn-dark" style="color: red;">PENDAFTARAN GIRO</a>
                                <button type="submit" class="btn btn-primary">PROSES</button>
                                <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                                <a href="{{ route('master.cek-giro.index') }}" class="btn btn-dark" style="color: red;">INFORMASI CEK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(`.supplier-select`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });

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

        document.getElementById("periode").value = "{{ now()->format('Y-m-d') }} - {{ now()->format('Y-m-d') }}";

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
    </script>
@endsection