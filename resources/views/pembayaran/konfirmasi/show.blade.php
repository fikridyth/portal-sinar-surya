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
        <div class="mb-3 mt-n3" style="width: 95%">
            <div class="card">
                <div class="card-body">  
                    <form action="{{ route('master.giro.update', '') }}" class="form" method="POST" enctype="multipart/form-data" id="giroForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="giro_id" id="giro_id" value="">
                        <div class="row w-100 mt-n3">
                            <div class="form-group col-9">
                                <div class="d-flex justify-content-center mt-2">
                                    <div style="overflow-x: auto; height: 590px; border: 1px solid #ccc;">
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
                                                        <td class="text-center" style="color: <?= ($pmb->tipe_giro == 'CABANG' || $pmb->is_cabang == 1) ? 'red' : 'black'; ?>">
                                                            @if ($pmb->nomor_bukti !== $previousIdParent)
                                                                {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                                @php $counter++; @endphp
                                                            @else
                                                                {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center"><input type="checkbox" class="input-konfirmasi" id="input-konfirmasi-{{ $index }}" data-id="{{ $pmb->id }}" data-bukti="{{ $pmb->data_bukti }}" data-nomor="{{ $pmb->nomor_bukti }}"></td>
                                                        @if ($pmb->tipe_giro == 'CABANG' || $pmb->is_cabang == 1)
                                                            <td style="color: red;">{{ $pmb->supplier->nama }}/LOGO</td>
                                                        @else
                                                            <td>{{ $pmb->supplier->nama }}</td>
                                                        @endif
                                                        <td class="text-center">{{ $pmb->date }}</td>
                                                        <td class="text-end">{{ number_format($pmb->total_with_materai) }}</td>
                                                        <td class="text-center">{{ $pmb->nomor_giro }}</td>
                                                        <td class="text-center">{{ $pmb->date_last ?? '-' }}</td>
                                                        <td class="text-center"><input type="checkbox" class="giro-checkbox" data-id="{{ $pmb->id }}"></td>
                                                        <td>KONFORM</td>
                                                    </tr>
                                                    @if ($pmb->nomor_bukti)
                                                        @php $previousIdParent = $pmb->nomor_bukti; @endphp
                                                    @endif
                                                @endforeach
                                                @foreach ($historypmb as $index => $pmb)
                                                    <tr>
                                                        <td class="text-center" style="color: red;">
                                                            @if ($pmb->nomor_bukti !== $previousIdParent)
                                                                {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}
                                                                @php $counter++; @endphp
                                                            @else
                                                                {{ str_pad($counter - 1, 3, 0, STR_PAD_LEFT) }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center"><input type="checkbox" class="input-history" id="input-history-{{ $index }}" data-id="{{ $pmb->id }}" data-date="{{ $pmb->date_first }}" data-nomor="{{ $pmb->nomor_bukti }}" data-jumlah="{{ $pmb->jumlah }}"></td>
                                                        <td style="color: red;">{{ $pmb->supplier->nama }}/LOGO</td>
                                                        <td class="text-center">{{ $pmb->date }}</td>
                                                        <td class="text-end">{{ number_format($pmb->jumlah) }}</td>
                                                        <td class="text-center">{{ $pmb->nomor_giro }}</td>
                                                        <td class="text-center">{{ $pmb->date_last ?? '-' }}</td>
                                                        <td class="text-center"><input type="checkbox" class="giro-checkbox" disabled data-id="{{ $pmb->id }}"></td>
                                                        <td>KONFORM</td>
                                                    </tr>
                                                    {{-- @if ($pmb->nomor_bukti)
                                                        @php $previousIdParent = $pmb->nomor_bukti; @endphp
                                                    @endif --}}
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    <a href="{{ route('pembayaran-konfirmasi.index') }}" class="btn btn-danger">SELESAI</a>
                                </div>
                            </div>
                            
                            <div class="form-group col-3">
                                <div class="d-flex justify-content-center mt-2">
                                    <div style="overflow-x: auto; height: 590px; border: 1px solid #ccc;">
                                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <h6 class="d-flex justify-content-center mt-2">DATA DOKUMEN</h6>
                                                <tr style="border: 1px solid black; font-size: 12px;">
                                                    <th>DOKUMEN</th>
                                                    <th>TANGGAL</th>
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
                selectedIdk.push(id);

                // update table document
                tableBody.empty();
                dataBukti.forEach(item => {
                    const newRow = `<tr>
                        <td style="font-size: 12px;">${item.nomor_bukti}</td>
                        <td style="font-size: 12px;">${item.date}</td>
                        <td style="font-size: 12px;">${number_format(item.total)}</td>
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

        $('.input-history').change(function () {
            const id = $(this).data('id');
            const dateBukti = $(this).data('date');
            const nomorBukti = $(this).data('nomor');
            const jumlahBukti = $(this).data('jumlah');
            const tableBody = $('#data-tbody-dok');
            if ($(this).is(':checked')) {

                // update table document
                tableBody.empty();
                const newRow = `<tr>
                    <td style="font-size: 12px;">${dateBukti}</td>
                    <td style="font-size: 12px;">${nomorBukti}</td>
                    <td style="font-size: 12px;">${number_format(jumlahBukti)}</td>
                </tr>`;
                tableBody.append(newRow);
            } else {
                tableBody.empty();
            }
        });

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        document.querySelectorAll('.giro-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const giroId = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Kembalikan pembayaran?',
                        text: 'Giro akan rusak setelah ini!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('giro_id').value = giroId; // Set the ID in the hidden input
                            document.getElementById('giroForm').action = `{{ url('master/giro/update') }}/${giroId}`; // Update the form action
                            document.getElementById('giroForm').submit(); // Submit the form
                        } else {
                            this.checked = false; // Uncheck the checkbox if user selects "No"
                        }
                    });
                }
            });
        });
    </script>
@endsection