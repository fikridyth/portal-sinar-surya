@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('master.promosi.index-all') }}" method="GET" style="width: 100%;">
                    <div class="d-flex mt-n2 mb-2 mx-auto" style="width: 30%;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <label class="form-label fw-semibold mb-0" style="font-size: 16px">PERIODE :</label>
                            </div>
                            <div class="flex-grow-1">
                                <input class="form-control form-control-solid" placeholder="Pilih Periode"
                                    autocomplete="off" id="periode" style="margin-left: 9px;" name="periode" value="{{ request('periode') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-n2 mb-2 mx-auto" style="width: 30%;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <label class="form-label fw-semibold mb-0" style="font-size: 16px">SUPPLIER :</label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="hidden" name="supplier" id="supplierInput">
                                <button type="button" id="checkAll" class="btn btn-success">SEMUA</button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                            <table id="promoTable" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr>
                                        <th class="text-center">NAMA SUPPLIER</th>
                                        <th class="text-center">PILIH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td class="text-center">{{ $supplier->nama }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" 
                                                    name="supplier[]" 
                                                    value="{{ $supplier->id }}" 
                                                    class="supplier-checkbox">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary mx-3">PROSES</button>
                        <a href="{{ route('master.supplier.show', enkrip(1)) }}" class="btn btn-danger mx-3">KEMBALI</a>
                    </div>
                </form>
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

        document.getElementById('checkAll').addEventListener('click', function(e) {
            e.preventDefault();

            const checkboxes = document.querySelectorAll('.supplier-checkbox');
            const checked = document.querySelectorAll('.supplier-checkbox:checked');

            if (checked.length > 0) {
                // kalau sudah ada yang dicentang → clear semua
                checkboxes.forEach(cb => cb.checked = false);
                this.textContent = "SEMUA"; // ubah teks tombol
                this.classList.remove("btn-danger");
                this.classList.add("btn-success");
            } else {
                // kalau belum ada yang dicentang → centang semua
                checkboxes.forEach(cb => cb.checked = true);
                this.textContent = "CLEAR"; // ubah teks tombol
                this.classList.remove("btn-success");
                this.classList.add("btn-danger");
            }
        });

        document.querySelector("form").addEventListener("submit", function (e) {
            const checked = document.querySelectorAll(".supplier-checkbox:checked");
            const supplierInput = document.getElementById("supplierInput");

            if (checked.length > 10) {
                // lebih dari 10 → anggap ALL
                supplierInput.value = "all";
                // hapus semua name="supplier[]" agar tidak terkirim
                document.querySelectorAll(".supplier-checkbox").forEach(cb => cb.disabled = true);
            } else {
                // kirim array id supplier yang dipilih
                const ids = Array.from(checked).map(cb => cb.value);
                supplierInput.value = JSON.stringify(ids); 
                // hapus atribut name pada checkbox agar tidak double kirim
                document.querySelectorAll(".supplier-checkbox").forEach(cb => cb.disabled = true);
            }
        });
    </script>
@endsection
