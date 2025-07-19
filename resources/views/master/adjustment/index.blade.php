@extends('main')

@include('preorder.add-on.styles')

@section('content')
    <div class="mb-7 mx-5">
        <form action="{{ route('master.adjustment.show') }}" method="POST" id="adjustment-form">
            @csrf
            <div class="card mt-n3">
                <div class="card-body mt-n3">
                    <div class="d-flex justify-content-between">
                        <div class="row w-100">
                            <div class="form-group col-6">
                                <input type="text" autocomplete="off" id="searchInputSupplier" class="mb-2" size="45" placeholder="Cari Supplier" onkeyup="searchTableSupplier()">
                                <div class="row">
                                    <div class="col-6" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-supplier" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">SUPPLIER</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suppliers as $supplier)
                                                <tr>
                                                    <td class="supplier-cell">
                                                        {{ $supplier->nama }} 
                                                        <input type="checkbox" hidden class="checkbox-supplier" data-id="{{ $supplier->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-1 d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <button type="button" id="supplier-move-right" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;">></button>
                                        </div>
                                        <div>
                                            <button type="button" id="supplier-move-all-right" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;">>></button>
                                        </div>
                                        <div>
                                            <button type="button" id="supplier-move-left" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;"><</button>
                                        </div>
                                        <div>
                                            <button type="button" id="supplier-move-all-left" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;"><<</button>
                                        </div>                                    
                                    </div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-supplier-pilih" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="selected-suppliers" style="display: none;">
                                <!-- Dynamically added hidden inputs will appear here -->
                            </div>
                            <div class="form-group col-6">
                                <input type="text" autocomplete="off" id="searchInputGrup" class="mb-2 mx-5" size="35" placeholder="Cari Grup" onkeyup="searchTableGrup()">
                                <div class="row">
                                    <div class="col-0-5"></div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-grup" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">GRUP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($grups as $grup)
                                                <tr>
                                                    <td class="grup-cell">
                                                        {{ $grup->nama }}
                                                        <input type="checkbox" hidden class="checkbox-grup" data-id="{{ $grup->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-1 d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <button type="button" id="grup-move-right" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;">></button>
                                        </div>
                                        <div>
                                            <button type="button" id="grup-move-all-right" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;">>></button>
                                        </div>
                                        <div>
                                            <button type="button" id="grup-move-left" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;"><</button>
                                        </div>
                                        <div>
                                            <button type="button" id="grup-move-all-left" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;"><<</button>
                                        </div>                                    
                                    </div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-grup-pilih" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="selected-grups" style="display: none;">
                                <!-- Dynamically added hidden inputs will appear here -->
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="row w-100">
                            <div class="form-group col-6">
                                <input type="text" autocomplete="off" id="searchInputProduct" class="mb-2" size="45" placeholder="Cari Product" onkeyup="searchTableProduct()">
                                <div class="row">
                                    <div class="col-6" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-product" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">PRODUCT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                <tr>
                                                    <td class="product-cell">
                                                        {{ $product->nama }}/{{ $product->unit_jual }}
                                                        <input type="checkbox" hidden class="checkbox-product" data-id="{{ $product->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-1 d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <button type="button" id="product-move-right" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;">></button>
                                        </div>
                                        <div>
                                            {{-- <button type="button" id="product-move-all-right" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;">>></button> --}}
                                            <button type="button" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;">>></button>
                                        </div>
                                        <div>
                                            <button type="button" id="product-move-left" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;"><</button>
                                        </div>
                                        <div>
                                            {{-- <button type="button" id="product-move-all-left" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;"><<</button> --}}
                                            <button type="button" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;"><<</button>
                                        </div>                                    
                                    </div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-product-pilih" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="selected-products" style="display: none;">
                                <!-- Dynamically added hidden inputs will appear here -->
                            </div>
                            <div class="form-group col-6">
                                <input type="text" autocomplete="off" id="searchInputDepartemen" class="mb-2 mx-5" size="35" placeholder="Cari Departemen" onkeyup="searchTableDepartemen()">
                                <div class="row">
                                    <div class="col-0-5"></div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-departemen" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">DEPARTEMEN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($departemens as $departemen)
                                                <tr>
                                                    <td class="departemen-cell">
                                                        {{ $departemen->nama }}
                                                        <input type="checkbox" hidden class="checkbox-departemen" data-id="{{ $departemen->id }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-1 d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <button type="button" id="departemen-move-right" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;">></button>
                                        </div>
                                        <div>
                                            <button type="button" id="departemen-move-all-right" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;">>></button>
                                        </div>
                                        <div>
                                            <button type="button" id="departemen-move-left" class="btn btn-info mt-2 text-center" style="padding: 12px 20px; font-size: 12px;"><</button>
                                        </div>
                                        <div>
                                            <button type="button" id="departemen-move-all-left" class="btn btn-info mt-2 text-center" style="padding: 12px 17px; font-size: 12px;"><<</button>
                                        </div>                                    
                                    </div>
                                    <div class="col-5" style="overflow-x: auto; height: 300px; border: 1px solid #ccc;">
                                        <table id="table-departemen-pilih" class="table table-bordered" style="width: 100%; table-layout: auto;">
                                            <thead>
                                                <tr style="font-size: 12px; padding: 2px 4px; margin: 0; line-height: 0.5;">
                                                    <th colspan="5" class="text-center">PILIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="selected-departemens" style="display: none;">
                                <!-- Dynamically added hidden inputs will appear here -->
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div style="margin-right: 2%;">
                            <button type="submit" disabled id="button-proses" class="btn btn-primary mx-3">PROSES</button>
                            <a href="{{ route('index') }}" class="btn btn-danger">KEMBALI</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // tabel supplier
            $('td.supplier-cell').click(function() {
                var checkbox = $(this).find('.checkbox-supplier');
                checkbox.prop('checked', !checkbox.prop('checked'));
                if ($(this).css('background-color') === 'rgb(255, 250, 160)') {
                    // Jika sudah ada warna, hapus
                    $(this).css('background-color', '');
                } else {
                    // Jika tidak ada warna, tambahkan warna latar belakang
                    $(this).css('background-color', '#FFFAA0');
                }
            });
            $('#supplier-move-all-right').click(function() {
                // Ambil semua baris dari tabel supplier
                $('#table-supplier tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var supplierId = $(this).data('id');
                    row.find('.checkbox-supplier').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.supplier-cell setelah dipindahkan
                    row.find('td.supplier-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris ke tabel pilih dan taruh di atas
                    $('#table-supplier-pilih tbody').append(row); // Pindahkan ke tabel pilih di bagian atas
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#supplier-move-right').click(function() {
                // Ambil semua baris yang dipilih dari tabel supplier
                $('#table-supplier .checkbox-supplier:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var supplierId = $(this).data('id');
                    row.find('.checkbox-supplier').prop('checked', false); // Hapus status checkbox
                    row.find('td.supplier-cell').css('background-color', '');
                    $('#table-supplier-pilih tbody').append(row); // Pindahkan ke tabel pilih
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#supplier-move-all-left').click(function() {
                // Ambil semua baris dari tabel pilih
                $('#table-supplier-pilih tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var supplierId = $(this).data('id');
                    row.find('.checkbox-supplier').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.supplier-cell setelah dipindahkan
                    row.find('td.supplier-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris kembali ke tabel supplier dan taruh di atas
                    $('#table-supplier tbody').append(row); // Pindahkan ke tabel supplier di bagian atas
                });
                $('#button-proses').prop('disabled', true);
            });
            $('#supplier-move-left').click(function() {
                // Ambil semua baris yang dipilih dari tabel pilih
                $('#table-supplier-pilih .checkbox-supplier:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var supplierId = $(this).data('id');
                    row.find('.checkbox-supplier').prop('checked', false); // Hapus status checkbox
                    row.find('td.supplier-cell').css('background-color', '');
                    $('#table-supplier tbody').prepend(row); // Pindahkan kembali ke tabel supplier
                });
                $('#button-proses').prop('disabled', false);
            });

            // tabel grup
            $('td.grup-cell').click(function() {
                var checkbox = $(this).find('.checkbox-grup');
                checkbox.prop('checked', !checkbox.prop('checked'));
                if ($(this).css('background-color') === 'rgb(255, 250, 160)') {
                    // Jika sudah ada warna, hapus
                    $(this).css('background-color', '');
                } else {
                    // Jika tidak ada warna, tambahkan warna latar belakang
                    $(this).css('background-color', '#FFFAA0');
                }
            });
            $('#grup-move-all-right').click(function() {
                // Ambil semua baris dari tabel grup
                $('#table-grup tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var grupId = $(this).data('id');
                    row.find('.checkbox-grup').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.grup-cell setelah dipindahkan
                    row.find('td.grup-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris ke tabel pilih dan taruh di atas
                    $('#table-grup-pilih tbody').append(row); // Pindahkan ke tabel pilih di bagian atas
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#grup-move-right').click(function() {
                // Ambil semua baris yang dipilih dari tabel grup
                $('#table-grup .checkbox-grup:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var grupId = $(this).data('id');
                    row.find('.checkbox-grup').prop('checked', false); // Hapus status checkbox
                    row.find('td.grup-cell').css('background-color', '');
                    $('#table-grup-pilih tbody').append(row); // Pindahkan ke tabel pilih
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#grup-move-all-left').click(function() {
                // Ambil semua baris dari tabel pilih
                $('#table-grup-pilih tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var grupId = $(this).data('id');
                    row.find('.checkbox-grup').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.grup-cell setelah dipindahkan
                    row.find('td.grup-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris kembali ke tabel grup dan taruh di atas
                    $('#table-grup tbody').append(row); // Pindahkan ke tabel grup di bagian atas
                });
                $('#button-proses').prop('disabled', true);
            });
            $('#grup-move-left').click(function() {
                // Ambil semua baris yang dipilih dari tabel pilih
                $('#table-grup-pilih .checkbox-grup:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var grupId = $(this).data('id');
                    row.find('.checkbox-grup').prop('checked', false); // Hapus status checkbox
                    row.find('td.grup-cell').css('background-color', '');
                    $('#table-grup tbody').prepend(row); // Pindahkan kembali ke tabel supplier
                });
                $('#button-proses').prop('disabled', false);
            });
            
            // tabel product
            $('td.product-cell').click(function() {
                var checkbox = $(this).find('.checkbox-product');
                checkbox.prop('checked', !checkbox.prop('checked'));
                if ($(this).css('background-color') === 'rgb(255, 250, 160)') {
                    // Jika sudah ada warna, hapus
                    $(this).css('background-color', '');
                } else {
                    // Jika tidak ada warna, tambahkan warna latar belakang
                    $(this).css('background-color', '#FFFAA0');
                }
            });
            $('#product-move-all-right').click(function() {
                // Ambil semua baris dari tabel product
                $('#table-product tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var productId = $(this).data('id');
                    row.find('.checkbox-product').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.product-cell setelah dipindahkan
                    row.find('td.product-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris ke tabel pilih dan taruh di atas
                    $('#table-product-pilih tbody').append(row); // Pindahkan ke tabel pilih di bagian atas
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#product-move-right').click(function() {
                // Ambil semua baris yang dipilih dari tabel product
                $('#table-product .checkbox-product:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var productId = $(this).data('id');
                    row.find('.checkbox-product').prop('checked', false); // Hapus status checkbox
                    row.find('td.product-cell').css('background-color', '');
                    $('#table-product-pilih tbody').append(row); // Pindahkan ke tabel pilih
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#product-move-all-left').click(function() {
                // Ambil semua baris dari tabel pilih
                $('#table-product-pilih tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var productId = $(this).data('id');
                    row.find('.checkbox-product').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.product-cell setelah dipindahkan
                    row.find('td.product-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris kembali ke tabel product dan taruh di atas
                    $('#table-product tbody').append(row); // Pindahkan ke tabel product di bagian atas
                });
                $('#button-proses').prop('disabled', true);
            });
            $('#product-move-left').click(function() {
                // Ambil semua baris yang dipilih dari tabel pilih
                $('#table-product-pilih .checkbox-product:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var productId = $(this).data('id');
                    row.find('.checkbox-product').prop('checked', false); // Hapus status checkbox
                    row.find('td.product-cell').css('background-color', '');
                    $('#table-product tbody').prepend(row); // Pindahkan kembali ke tabel supplier
                });
                $('#button-proses').prop('disabled', false);
            });
            
            // tabel departemen
            $('td.departemen-cell').click(function() {
                var checkbox = $(this).find('.checkbox-departemen');
                checkbox.prop('checked', !checkbox.prop('checked'));
                if ($(this).css('background-color') === 'rgb(255, 250, 160)') {
                    // Jika sudah ada warna, hapus
                    $(this).css('background-color', '');
                } else {
                    // Jika tidak ada warna, tambahkan warna latar belakang
                    $(this).css('background-color', '#FFFAA0');
                }
            });
            $('#departemen-move-all-right').click(function() {
                // Ambil semua baris dari tabel departemen
                $('#table-departemen tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var departemenId = $(this).data('id');
                    row.find('.checkbox-departemen').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.departemen-cell setelah dipindahkan
                    row.find('td.departemen-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris ke tabel pilih dan taruh di atas
                    $('#table-departemen-pilih tbody').append(row); // Pindahkan ke tabel pilih di bagian atas
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#departemen-move-right').click(function() {
                // Ambil semua baris yang dipilih dari tabel departemen
                $('#table-departemen .checkbox-departemen:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var departemenId = $(this).data('id');
                    row.find('.checkbox-departemen').prop('checked', false); // Hapus status checkbox
                    row.find('td.departemen-cell').css('background-color', '');
                    $('#table-departemen-pilih tbody').append(row); // Pindahkan ke tabel pilih
                });
                $('#button-proses').prop('disabled', false);
            });
            $('#departemen-move-all-left').click(function() {
                // Ambil semua baris dari tabel pilih
                $('#table-departemen-pilih tbody tr').each(function() {
                    var row = $(this);  // Ambil baris
                    var departemenId = $(this).data('id');
                    row.find('.checkbox-departemen').prop('checked', false); // Hapus status checkbox
                    
                    // Menghapus background color dari td.departemen-cell setelah dipindahkan
                    row.find('td.departemen-cell').css('background-color', ''); // Hapus warna latar belakang di dalam td
                    
                    // Pindahkan baris kembali ke tabel departemen dan taruh di atas
                    $('#table-departemen tbody').append(row); // Pindahkan ke tabel departemen di bagian atas
                });
                $('#button-proses').prop('disabled', true);
            });
            $('#departemen-move-left').click(function() {
                // Ambil semua baris yang dipilih dari tabel pilih
                $('#table-departemen-pilih .checkbox-departemen:checked').each(function() {
                    var row = $(this).closest('tr');  // Ambil baris
                    var departemenId = $(this).data('id');
                    row.find('.checkbox-departemen').prop('checked', false); // Hapus status checkbox
                    row.find('td.departemen-cell').css('background-color', '');
                    $('#table-departemen tbody').prepend(row); // Pindahkan kembali ke tabel supplier
                });
                $('#button-proses').prop('disabled', false);
            });
            
            // add request for form
            $('#adjustment-form').submit(function(e) {
                // Clear previously selected suppliers
                $('#selected-suppliers').empty();
                // Collect selected suppliers from the "PILIH" table
                $('#table-supplier-pilih .checkbox-supplier').each(function() {
                    var supplierId = $(this).data('id'); // Get the supplier ID
                    // Create a hidden input for each selected supplier
                    $('#selected-suppliers').append('<input type="hidden" name="selected_suppliers[]" value="' + supplierId + '">');
                });
                
                // Clear previously selected grups
                $('#selected-grups').empty();
                // Collect selected grups from the "PILIH" table
                $('#table-grup-pilih .checkbox-grup').each(function() {
                    var grupId = $(this).data('id'); // Get the grup ID
                    // Create a hidden input for each selected grup
                    $('#selected-grups').append('<input type="hidden" name="selected_grups[]" value="' + grupId + '">');
                });
                
                // Clear previously selected products
                $('#selected-products').empty();
                // Collect selected products from the "PILIH" table
                $('#table-product-pilih .checkbox-product').each(function() {
                    var productId = $(this).data('id'); // Get the product ID
                    // Create a hidden input for each selected product
                    $('#selected-products').append('<input type="hidden" name="selected_products[]" value="' + productId + '">');
                });
                
                // Clear previously selected departemens
                $('#selected-departemens').empty();
                // Collect selected departemens from the "PILIH" table
                $('#table-departemen-pilih .checkbox-departemen').each(function() {
                    var departemenId = $(this).data('id'); // Get the departemen ID
                    // Create a hidden input for each selected departemen
                    $('#selected-departemens').append('<input type="hidden" name="selected_departemens[]" value="' + departemenId + '">');
                });

                // Continue with the form submission
                return true;
            });
        });

        function searchTableSupplier() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputSupplier");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-supplier");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Mencari pada kolom pertama (nama supplier)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        
        function searchTableGrup() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputGrup");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-grup");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Mencari pada kolom pertama (nama supplier)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        
        function searchTableProduct() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputProduct");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-product");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Mencari pada kolom pertama (nama supplier)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        
        function searchTableDepartemen() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputDepartemen");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-departemen");
            tr = table.getElementsByTagName("tr");

            // Looping through semua baris tabel (setelah header) dan menyembunyikan baris yang tidak sesuai
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Mencari pada kolom pertama (nama supplier)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
