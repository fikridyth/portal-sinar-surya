@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body mt-n2">
                {{-- <div class="card-header" style="display: flex; justify-content: space-between;">
                    Master Product
                    <div>
                        <a href="{{ route('master.product.create') }}" type="button" class="btn btn-md btn-primary">Add
                            Product</a>
                    </div>
                </div> --}}

                <div class="card-body mt-n4">
                    <div class="row w-100">
                        <div class="form-group col-6">
                            {{-- <select name="supplier_id" required class="supplier-select" style="width: 300px;">
                                <option value="{{ $supplier->id }}" selected>{{ $supplier->nama }}</option>
                                @foreach ($suppliers as $sup)
                                    @if ($sup->id !== $supplier->id)
                                        <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                    @endif
                                @endforeach
                            </select> --}}
                            <h5>{{ $supplier->nama }}</h5>
                            {{-- <a href="{{ route('master.supplier.index') }}" class="btn btn-primary" title="CARI DATA"><i class="fas fa-search"></i></a> --}}
                            <div class="d-flex align-items-center mt-2 mb-2">
                                <form action="{{ route('master.history-preorder.index', enkrip($supplier->id)) }}" method="GET" class="d-flex align-items-center">
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
                            <div style="overflow-x: auto; height: 610px; border: 1px solid #ccc;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">DOKUMEN</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">KETERANGAN</th>
                                            <!-- <th class="text-center">PILIH</th> -->
                                            <th class="text-center">DARI</th>
                                            <th class="text-center">SAMPAI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                <!-- <td class="text-center"><input type="checkbox" class="preorder-checkbox" data-id="{{ $data['nomor'] }}" data-date="{{ $data['date'] }}" data-nama="{{ $supplier->nama }}" data-detail="{{ json_encode($data['detail']) }}"></td> -->
                                                <td class="text-center">
                                                    <input type="checkbox" class="preorder-checkbox checkbox-dari" data-id="{{ $data['nomor'] }}" data-supid="{{ $supplier->id }}" data-date="{{ $data['date'] }}" data-nama="{{ $supplier->nama }}" data-detail="{{ json_encode($data['detail']) }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="preorder-checkbox checkbox-sampai" data-id="{{ $data['nomor'] }}" data-supid="{{ $supplier->id }}" data-date="{{ $data['date'] }}" data-nama="{{ $supplier->nama }}" data-detail="{{ json_encode($data['detail']) }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($getHistory as $data)
                                            @php
                                                // dd(json_encode($data['detail']));
                                                $getTipe = explode('-', $data->nomor_receive);
                                                if ($getTipe[0] == 'RP') $getName = 'PEMBELIAN';
                                                else $getName = 'RETUR';
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $data->nomor_receive }}</td>
                                                <td class="text-center">{{ $data->date }}</td>
                                                <td>{{ $getName }}</td>
                                                <!-- <td class="text-center"><input type="checkbox" class="history-checkbox" data-nomor="{{ $data->nomor_receive }}"></td> -->
                                                <td class="text-center"><input type="checkbox" disabled></td>
                                                <td class="text-center"><input type="checkbox" disabled></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group col-6 mt-3">
                            <div style="overflow-x: auto; height: 550px; border: 1px solid #ccc; margin-top: 70px;">
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
                            <div style="height: 60px; border: 1px solid #ccc; margin-top: 0;">
                                <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-end" style="width: 75%;">TOTAL</th>
                                            <th class="text-end value-total" style="width: 25%;">0</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="#" class="btn btn-primary" style="margin-right: 15px;">CETAK BARANG</a>
                    <a href="#" id="cetakBtn" class="btn btn-success" style="margin-right: 15px;">CETAK FAKTUR</a>
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-n2">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(`.supplier-select`).select2({
            placeholder: '---Select Supplier---',
            allowClear: true
        });

        // document.getElementById('supplier-select').addEventListener('change', function() {
        //     const selectedId = this.value;
        //     if (selectedId) {
        //         // Redirect to the history-preorder route with the selected ID
        //         window.location.href = `/history-preorder/${selectedId}`;
        //     }
        // });

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

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.preorder-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const isDari = this.classList.contains('checkbox-dari');
                    const isSampai = this.classList.contains('checkbox-sampai');

                    // Bila dicentang
                    if (this.checked) {
                        if (isDari) {
                            document.querySelectorAll('.checkbox-dari').forEach(cb => {
                                if (cb !== this) cb.disabled = true;
                            });
                        }

                        if (isSampai) {
                            document.querySelectorAll('.checkbox-sampai').forEach(cb => {
                                if (cb !== this) cb.disabled = true;
                            });
                        }
                    } else {
                        if (isDari) {
                            document.querySelectorAll('.checkbox-dari').forEach(cb => cb.disabled = false);
                        }

                        if (isSampai) {
                            document.querySelectorAll('.checkbox-sampai').forEach(cb => cb.disabled = false);
                        }
                    }

                    updateCetakUrl();
                    updateOrderDetail(this);
                });
            });

            function updateCetakUrl() {
                const dariCheckbox = document.querySelector('.checkbox-dari:checked');
                const sampaiCheckbox = document.querySelector('.checkbox-sampai:checked');
                const cetakBtn = document.getElementById('cetakBtn');

                if (dariCheckbox && sampaiCheckbox) {
                    let dariDate = dariCheckbox.getAttribute('data-date');
                    let sampaiDate = sampaiCheckbox.getAttribute('data-date');

                    // Tukar jika dariDate > sampaiDate
                    if (new Date(dariDate) > new Date(sampaiDate)) {
                        [dariDate, sampaiDate] = [sampaiDate, dariDate];
                    }

                    const supplierId = dariCheckbox.getAttribute('data-supid');
                    const supplierName = dariCheckbox.getAttribute('data-nama');

                    cetakBtn.href = `/master/cetak-faktur-supplier/${supplierId}/${supplierName}/${dariDate}/${sampaiDate}`;
                    cetakBtn.style.display = 'inline-block';
                } else {
                    cetakBtn.disabled = true;
                    cetakBtn.href = '#';
                }
            }

            function updateOrderDetail(checkbox) {
                const detail = JSON.parse(checkbox.getAttribute('data-detail'));
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                let total = 0;

                if (checkbox.checked) {
                    tbody.innerHTML = ''; // Bersihkan isi tabel

                    JSON.parse(detail).forEach(item => {
                        const amount = item.order * item.price;
                        total += amount;

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.nama}/${item.unit_jual}</td>
                            <td class="text-center">${item.order}</td>
                            <td class="text-end">${number_format(item.price)}</td>
                            <td class="text-end">${number_format(amount)}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    totalCell.textContent = number_format(total);
                } else {
                    tbody.innerHTML = '';
                    totalCell.textContent = '0';
                }
            }

            // Fungsi format angka (basic)
            function number_format(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });

        document.querySelectorAll('.history-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const nomor = this.getAttribute('data-nomor');
                const tbody = document.getElementById('orderDetailTableBody');
                const totalCell = document.querySelector('.table tbody tr th.value-total');
                let total = 0;
                
                if (this.checked) {
                    document.querySelectorAll('.history-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.disabled = true;
                            document.querySelectorAll('.preorder-checkbox').forEach(preorderCheckbox => {
                                preorderCheckbox.disabled = true;
                            });
                        }
                    });

                    fetch(`/master/get-history-po?nomor=${nomor}`)
                        .then(response => response.json())
                        .then(data => {
                            tbody.innerHTML = ''; // Clear existing data

                            data.dataDetail.forEach(item => {
                                const amount = item.order * item.price; // Menghitung jumlah per item
                                total += amount;

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${item.product_nama}/${item.product_unit_jual}</td>
                                    <td class="text-center">${item.order}</td>
                                    <td class="text-end">${number_format(item.price)}</td>
                                    <td class="text-end">${number_format(amount)}</td>
                                `;
                                tbody.appendChild(row);
                            });

                            totalCell.textContent = number_format(total);
                        })
                        .catch(error => console.error('Error fetching data:', error));
                } else {
                    document.querySelectorAll('.history-checkbox').forEach(otherCheckbox => {
                        otherCheckbox.disabled = false;
                            document.querySelectorAll('.preorder-checkbox').forEach(preorderCheckbox => {
                                preorderCheckbox.disabled = false;
                            });
                    });

                    tbody.innerHTML = ''; // Clear the table if checkbox is unchecked
                    totalCell.textContent = '0';
                }
            });
        });

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
@endsection
