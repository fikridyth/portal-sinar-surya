@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">PEMBAYARAN CEK/GIRO/TUNAI
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <form action="{{ route('pembayaran.cabang.store') }}" method="POST" class="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex justify-content-between mt-2">
                        <div class="row w-100">
                            <div class="form-group col-8">
                                <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                        <thead>
                                            <tr>
                                                <th colspan="5" class="text-center">DAFTAR HUTANG LOGO</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">NAMA SUPPLIER</th>
                                                <th class="text-center">DOKUMEN</th>
                                                <th class="text-center">TANGGAL</th>
                                                <th class="text-center">JUMLAH RP</th>
                                                <th class="text-center">PILIH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalGrandTotal = 0;
                                            @endphp
                                            @foreach ($listBayar as $index => $list)
                                                <tr>
                                                    <td>{{ $list->supplier->nama }}</td>
                                                    <td class="text-center">{{ $list->nomor_bukti }}</td>
                                                    <td class="text-center">{{ \Carbon\Carbon::parse($list->date)->format('d-m-Y') }}</td>
                                                    <td class="text-end">{{ number_format($list->grand_total, 0) }}</td>
                                                    <td class="text-center"><input type="checkbox" class="bayar-checkbox" value="{{ $list->id }}" data-index="{{ $index }}" data-supplier-name="{{ $list->supplier->nama }}"></td>
                                                </tr>
                                                @php
                                                    $totalGrandTotal += $list->grand_total;
                                                    @endphp
                                            @endforeach
                                            <input type="hidden" name="nomor_bukti" id="nomor_bukti">
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="text-end">{{ number_format($totalGrandTotal) }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <div style="overflow-x: auto; height: 500px; border: 1px solid #ccc;">
                                    <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                        <thead>
                                            <tr>
                                                <th colspan="5" class="text-center">DAFTAR SUPPLIER</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">NAMA SUPPLIER</th>
                                                <th class="text-center">PILIH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suppliers as $index => $supplier)
                                                <tr>
                                                    <td>{{ $supplier->nama }}</td>
                                                    <td class="text-center"><input type="checkbox" name="data" value="{{ $supplier->nomor }}" class="supplier-checkbox" data-supplier-name="{{ $supplier->nama }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3 mx-3" disabled>PROSES</button>
                        <a href="{{ route('pembayaran.cabang-index') }}" class="btn btn-danger mx-3">BATAL</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.bayar-checkbox').change(function() {
                if ($(this).is(':checked')) {
                    $('.bayar-checkbox').not(this).prop('disabled', true);
                } else {
                    $('.bayar-checkbox').prop('disabled', false);
                }
            });

            $('.supplier-checkbox').change(function() {
                if ($(this).is(':checked')) {
                    $('.supplier-checkbox').not(this).prop('disabled', true);
                } else {
                    $('.supplier-checkbox').prop('disabled', false);
                }
            });

            document.querySelectorAll('.bayar-checkbox').forEach(function(bayarCheckbox) {
                bayarCheckbox.addEventListener('change', function() {
                    const supplierName = this.getAttribute('data-supplier-name');
                    const supplierCheckboxes = document.querySelectorAll('.supplier-checkbox[data-supplier-name="' + supplierName + '"]');

                    supplierCheckboxes.forEach(function(supplierCheckbox) {
                        supplierCheckbox.checked = this.checked;

                        // Set focus on the checkbox if it is checked
                        if (this.checked) {
                            supplierCheckbox.focus(); // Focus on the supplier checkbox
                        }
                    }, this); // Pass the context of the current checkbox
                    updateDataCabang();
                    updateSubmitButtonState();
                });
            });

            // Function to update the nomor_bukti hidden input
            function updateDataCabang() {
                const nomorBuktiInput = document.getElementById('nomor_bukti');
                const checkedCheckboxes = document.querySelectorAll('.bayar-checkbox:checked');
                
                // Collect the values from the checked checkboxes
                const selectedValues = Array.from(checkedCheckboxes).map(checkbox => {
                    return checkbox.value; // Get the value from the checkbox directly
                });

                // Check if selectedValues has content
                if (selectedValues.length > 0) {
                    nomorBuktiInput.value = selectedValues.join(','); // Set the hidden input's value as a comma-separated string
                } else {
                    nomorBuktiInput.value = ''; // Clear the input if no checkboxes are checked
                }
            }

            // Function to enable/disable the submit button
            function updateSubmitButtonState() {
                const button = document.querySelector('button[type="submit"]');
                const anyChecked = Array.from(document.querySelectorAll('.bayar-checkbox')).some(checkbox => checkbox.checked);
                button.disabled = !anyChecked; // Enable button if any checkbox is checked
            }
        });
    </script>
@endsection
