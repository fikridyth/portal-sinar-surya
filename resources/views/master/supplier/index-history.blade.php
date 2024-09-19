@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">MASTER HISTORY PREORDER</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body">
                    <div class="row w-100">
                        <div class="form-group col-6">
                            {{ $supplier->nama }}
                            <div class="d-flex align-items-center">
                                <form action="{{ route('master.history-preorder.index', $supplier->id) }}" method="GET" class="d-flex align-items-center">
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
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">DOKUMEN</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">KETERANGAN</th>
                                            <th class="text-center">PILIH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preorderTableBody">
                                        @foreach ($getData as $data)
                                            @php
                                                // dd(json_encode($data['detail']));
                                                $getTipe = explode('-', $data['nomor']);
                                                if ($getTipe[0] == 'RP') $getName = 'PEMBELIAN';
                                                else $getName = 'RETUR';
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $data['nomor'] }}</td>
                                                <td class="text-center">{{ $data['date'] }}</td>
                                                <td>{{ $getName }}</td>
                                                <td class="text-center"><input type="checkbox" class="preorder-checkbox" data-detail="{{ json_encode($data['detail']) }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div style="overflow-x: auto; height: 700px; border: 1px solid #ccc; margin-top: 70px;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NAMA BARANG</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">HARGA</th>
                                            <th class="text-center">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderDetailTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-4 mb-7">KEMBALI</button>
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
                days: 30
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

        document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const detail = JSON.parse(this.getAttribute('data-detail'));
                const tbody = document.getElementById('orderDetailTableBody');
                
                if (this.checked) {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                        }
                    });

                    tbody.innerHTML = ''; // Clear existing rows before adding new ones
                    JSON.parse(detail).forEach(item => {
                        console.log(item);
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${item.nama}</td>
                            <td class="text-center">${item.order}</td>
                            <td class="text-end">${number_format(item.price)}</td>
                            <td class="text-end">${number_format(item.order * item.price)}</td>
                        `;
                        tbody.appendChild(newRow);
                    });
                } else {
                    document.querySelectorAll('.preorder-checkbox').forEach(otherCheckbox => {
                        otherCheckbox.disabled = false;
                    });

                    tbody.innerHTML = ''; // Clear the table if checkbox is unchecked
                }
            });
            function number_format(number) {
                return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        });
    </script>
@endsection
