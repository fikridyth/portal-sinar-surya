@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mt-n2 mb-2 mx-auto" style="width: 40%;">
                    <form action="{{ route('master.promosi.index-all') }}" method="GET"
                        style="width: 100%; max-width: 800px;">

                        {{-- Row 1: Label & Periode --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <label class="form-label fw-semibold mb-0" style="font-size: 18px">Periode :</label>
                            </div>
                            <div class="flex-grow-1">
                                <input class="form-control form-control-solid" placeholder="Pilih Periode"
                                    autocomplete="off" id="periode" name="periode" value="{{ request('periode') }}" />
                            </div>
                        </div>

                        {{-- Row 2: Supplier Select --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <label class="form-label fw-semibold">Supplier :</label>
                            </div>
                            <div class="flex-grow-1">
                                <select name="supplier" id="supplier" class="form-select form-select-solid supplier"
                                    data-control="select2" data-placeholder="PILIH SUPPLIER">
                                    <option value="">-- Semua Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                            {{ strtoupper($supplier->nama) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Tombol Aksi --}}
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" class="btn btn-secondary me-2" id="clear">Clear</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true"
                                data-kt-user-table-filter="filter" id="apply">Apply</button>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-center mt-2">
                    <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                        <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center">PROMOSI YANG SUDAH SELESAI</th>
                                </tr>
                                <tr>
                                    <th class="text-center">NAMA SUPPLIER</th>
                                    <th class="text-center">JENIS</th>
                                    <th class="text-center">DOKUMEN</th>
                                    <th class="text-center">TANGGAL BAYAR</th>
                                    <th class="text-center">JUMLAH RP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($promosi->count())
                                    @foreach ($promosi as $promo)
                                        <tr>
                                            <td>{{ $promo->supplier->nama }}</td>
                                            <td>{{ $promo->tipe }}</td>
                                            <td class="text-center">{{ $promo->nomor_bukti }}</td>
                                            <td class="text-center">{{ $promo->updated_at->format('d/m/Y') }}</td>
                                            <td class="text-end">{{ number_format($promo->total, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center fw-bold" colspan="5">TIDAK ADA DATA</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <a href="{{ route('master.supplier.show', enkrip(1)) }}" class="btn btn-danger mx-2">KEMBALI</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(`.supplier`).select2({
            placeholder: '---PILIH SUPPLIER---',
            allowClear: true
        });

        $(`.supplier`).on('select2:open', function(e) {
            // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
            const searchBox = $(this).data('select2').dropdown.$search[0];
            if (searchBox) {
                searchBox.focus();
            }
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
        var supplier = $('#supplier'); // pakai jQuery karena Select2 pakai jQuery
        document.getElementById("clear").addEventListener("click", function() {
            periode.value = '';
            supplier.val('').trigger('change'); // reset Select2
        });
    </script>
@endsection
