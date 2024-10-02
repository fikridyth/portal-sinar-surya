@extends('main')

@section('styles')
    <style>
        .table thead th {
            position: sticky;
            top: 0;
            background-color: white; /* Optional: set background color for better visibility */
            z-index: 10; /* Ensure the header is above other content */
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 95%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                KONFIRMASI CEK / GIRO / TUNAI
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">  
                    <form action="{{ route('master.giro.update', '') }}" class="form" method="POST" enctype="multipart/form-data" id="giroForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="giro_id" id="giro_id" value="">
                        <div class="row w-100">
                            <div class="form-group col-8">
                                <div class="d-flex justify-content-center mt-2">
                                    <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <h6 class="d-flex justify-content-center mt-2">DATA PEMBAYARAN</h6>
                                                <tr style="border: 1px solid black; font-size: 12px;">
                                                    <th class="text-center">NO</th>
                                                    <th class="text-center">V</th>
                                                    <th class="text-center">NAMA SUPPLIER</th>
                                                    <th class="text-center">TANGGAL BAYAR</th>
                                                    <th class="text-center">JUMLAH RP</th>
                                                    <th class="text-center">NOMOR GIRO</th>
                                                    <th class="text-center">JATUH TEMPO</th>
                                                    <th class="text-center">V</th>
                                                    <th class="text-center">STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                    $previousIdParent = null;
                                                @endphp
                                                @foreach ($pembayarans as $index => $pmb)
                                                    <tr>
                                                        <td class="text-center" style="color: <?= $pmb->tipe_giro == 'CABANG' ? 'red' : 'black'; ?>">
                                                            @if ($pmb->nomor_bukti !== $previousIdParent)
                                                                {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                                @php $counter++; @endphp
                                                            @else
                                                                {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center"><input type="checkbox" class="input-konfirmasi" id="input-konfirmasi-{{ $index }}" data-id="{{ $pmb->id }}" data-bukti="{{ $pmb->data_bukti }}" data-nomor="{{ $pmb->nomor_bukti }}"></td>
                                                        @if ($pmb->tipe_giro == 'CABANG')
                                                            <td style="color: red;">{{ $pmb->supplier->nama }}/LOGO</td>
                                                        @else
                                                            <td>{{ $pmb->supplier->nama }}</td>
                                                        @endif
                                                        <td class="text-center">{{ $pmb->date }}</td>
                                                        <td class="text-end">{{ number_format($pmb->total_with_materai) }}</td>
                                                        <td class="text-center">{{ $pmb->nomor_giro }}</td>
                                                        <td class="text-center">{{ $pmb->tanggal_akhir ?? '-' }}</td>
                                                        <td class="text-center"><input type="checkbox" class="giro-checkbox" data-id="{{ $pmb->id }}"></td>
                                                        <td>KONFORM</td>
                                                    </tr>
                                                    @if ($pmb->nomor_bukti)
                                                        @php $previousIdParent = $pmb->nomor_bukti; @endphp
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    <a href="{{ route('pembayaran-konfirmasi.index') }}" class="btn btn-danger">SELESAI</a>
                                </div>
                            </div>
                            
                            <div class="form-group col-4">
                                <div class="d-flex justify-content-center mt-2">
                                    <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <h6 class="d-flex justify-content-center mt-2">DATA DOKUMEN</h6>
                                                <tr style="border: 1px solid black; font-size: 12px;">
                                                    <th>DOKUMEN</th>
                                                    <th>TANGGAL BAYAR</th>
                                                    <th>JUMLAH</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-tbody-dok">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let selectedIdk = [];
        $('.input-konfirmasi').change(function () {
            const id = $(this).data('id');
            const nomorBukti = $(this).data('nomor');
            const dataBukti = $(this).data('bukti');
            const tableBody = $('#data-tbody-dok');
            if ($(this).is(':checked')) {
                // other table
                selectedIdk.push(id);

                // update table document
                tableBody.empty();
                dataBukti.forEach(item => {
                    const newRow = `<tr>
                        <td>${item.nomor_bukti}</td>
                        <td>${item.date}</td>
                        <td>${number_format(item.total)}</td>
                    </tr>`;
                    tableBody.append(newRow);
                });

                $('.input-konfirmasi[data-nomor="' + nomorBukti + '"]').each(function () {
                    if (!$(this).is(':checked')) {
                        $(this).prop('checked', true).change(); // Trigger change event
                    }
                });
            } else {
                tableBody.empty();

                $('.input-konfirmasi[data-nomor="' + nomorBukti + '"]').each(function () {
                    if ($(this).is(':checked')) {
                        $(this).prop('checked', false).change(); // Trigger change event
                    }
                });
            }
        });

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        document.querySelectorAll('.giro-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const giroId = this.getAttribute('data-id');
                    const confirmation = confirm("Kembalikan pembayaran? giro akan rusak setelah ini!");
                    
                    if (confirmation) {
                        document.getElementById('giro_id').value = giroId; // Set the ID in the hidden input
                        document.getElementById('giroForm').action = `{{ url('master/giro/update') }}/${giroId}`; // Update the form action
                        document.getElementById('giroForm').submit(); // Submit the form
                    } else {
                        this.checked = false; // Uncheck the checkbox if user selects "No"
                    }
                }
            });
        });
    </script>
@endsection