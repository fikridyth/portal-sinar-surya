@extends('main')

@section('content')
    <form action="{{ route('master.role.update', enkrip($role->id)) }}" class="form" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="d-flex justify-content-center">
            <div style="width: 90%;">
                <div class="card mb-2 mt-2" style="background-color: rgb(255, 200, 194);">
                    <div class="d-flex align-items-center">
                        <h4 class="mt-3 mb-2 mx-auto" style="padding-left: 160px;">UBAH OTORITAS - {{ auth()->user()->role->nama }}</h4>
                        <button type="submit" class="btn btn-success mt-2 mx-3 ml-auto" style="width: 120px;">SELESAI</button>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        <div class="row mx-3">
                            <div class="col-10" style="width: 100%;">
                                <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">OTORISASI USER</a>
                            </div>
                            <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                <input type="checkbox" name="value[]" @if (in_array("31", $listRole)) checked @endif value="31">
                            </div>
                        </div>
                        <div class="row mx-3">
                            <div class="col-10" style="width: 100%;">
                                <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">OTORISASI STANDAR</a>
                            </div>
                            <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                <input type="checkbox" name="value[]" @if (in_array("32", $listRole)) checked @endif value="32">
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider mt-3 mb-3"></div>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-danger disabled-not-blur mb-2" style="width: 100%;">MASTER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("1", $listRole)) checked @endif value="1" class="checkbox-master" onchange="updateCheckboxesMaster(this)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">UNIT</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("2", $listRole)) checked @endif value="2" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">DEPARTEMEN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("3", $listRole)) checked @endif value="3" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">SUPPLIER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("4", $listRole)) checked @endif value="4" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">KUNJUNGAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("5", $listRole)) checked @endif value="5" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PERSEDIAAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("6", $listRole)) checked @endif value="6" class="checkbox-master">
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">HARGA</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("7", $listRole)) checked @endif value="7" class="checkbox-master">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">KARTU STOK</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("8", $listRole)) checked @endif value="8" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">BANK</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("9", $listRole)) checked @endif value="9" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PENDAFTARAN GIRO</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("10", $listRole)) checked @endif value="10" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PPN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("11", $listRole)) checked @endif value="11" class="checkbox-master">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">LANGGANAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("37", $listRole)) checked @endif value="37" class="checkbox-master">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-danger disabled-not-blur mb-2" style="width: 100%;">ORDER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("12", $listRole)) checked @endif value="12" class="checkbox-order" onchange="updateCheckboxesOrder(this)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PREORDER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("13", $listRole)) checked @endif value="13" class="checkbox-order">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">DAFTAR PREORDER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("14", $listRole)) checked @endif value="14" class="checkbox-order">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-danger disabled-not-blur mb-2" style="width: 100%;">RECEIVE</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("15", $listRole)) checked @endif value="15" class="checkbox-receive" onchange="updateCheckboxesReceive(this)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PENERIMAAN - P.O</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("16", $listRole)) checked @endif value="16" class="checkbox-receive">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PENERIMAAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("17", $listRole)) checked @endif value="17" class="checkbox-receive">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">RETUR BARANG</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("18", $listRole)) checked @endif value="18" class="checkbox-receive">
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">DAFTAR RETUR BARANG</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("19", $listRole)) checked @endif value="19" class="checkbox-receive">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PERSETUJUAN HARGA JUAL</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("20", $listRole)) checked @endif value="20" class="checkbox-receive">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">BATAL PERSETUJUAN HARGA JUAL</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("40", $listRole)) checked @endif value="40" class="checkbox-receive">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-danger disabled-not-blur mb-2" style="width: 100%;">PEMBAYARAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("21", $listRole)) checked @endif value="21" class="checkbox-pembayaran" onchange="updateCheckboxesPembayaran(this)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PEMBAYARAN HUTANG</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("22", $listRole)) checked @endif value="22" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">HAPUS PEMBAYARAN HUTANG</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("23", $listRole)) checked @endif value="23" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PEMBAYARAN CEK/GIRO</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("24", $listRole)) checked @endif value="24" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">KONFIRMASI CEK/GIRO</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("25", $listRole)) checked @endif value="25" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">ORDER PENJUALAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("38", $listRole)) checked @endif value="38" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PENJUALAN KREDIT</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("39", $listRole)) checked @endif value="39" class="checkbox-pembayaran">
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">RETUR PENJUALAN KREDIT</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("40", $listRole)) checked @endif value="40" class="checkbox-pembayaran">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PEMBAYARAN PIUTANG</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("26", $listRole)) checked @endif value="26" class="checkbox-pembayaran">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5">
                            <div style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-danger disabled-not-blur mb-2" style="width: 100%;">LAPORAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("27", $listRole)) checked @endif value="27" class="checkbox-laporan" onchange="updateCheckboxesLaporan(this)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">HARGA JUAL < HARGA BELI</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("28", $listRole)) checked @endif value="28" class="checkbox-laporan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">HARGA SEMENTARA</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("29", $listRole)) checked @endif value="29" class="checkbox-laporan">
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">STOCK OPNAME</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("30", $listRole)) checked @endif value="30" class="checkbox-laporan">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PERUBAHAN SUPPLIER</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("33", $listRole)) checked @endif value="33" class="checkbox-laporan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">PENYESUAIAN PERSEDIAAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("34", $listRole)) checked @endif value="34" class="checkbox-laporan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">EDIT PENYESUAIAN PERSEDIAAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("35", $listRole)) checked @endif value="35" class="checkbox-laporan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10" style="width: 95%;">
                                        <a href="#" class="btn btn-light disabled-not-blur mb-2" style="color: blue; border: 1px solid black; width: 100%;">HISTORY PENYESUAIAN PERSEDIAAN</a>
                                    </div>
                                    <div class="col-2 d-flex align-items-center mx-n3" style="width: 10%;">
                                        <input type="checkbox" name="value[]" @if (in_array("36", $listRole)) checked @endif value="36" class="checkbox-laporan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function updateCheckboxesMaster(checkbox) {
            const checkboxes = document.querySelectorAll('.checkbox-master');
            if (checkbox.checked) {
                // Jika checkbox 1 di-check, centang semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = true;
                    }
                });
            } else {
                // Jika checkbox 1 di-uncheck, uncheck semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }

        function updateCheckboxesOrder(checkbox) {
            const checkboxes = document.querySelectorAll('.checkbox-order');
            if (checkbox.checked) {
                // Jika checkbox 1 di-check, centang semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = true;
                    }
                });
            } else {
                // Jika checkbox 1 di-uncheck, uncheck semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
        
        function updateCheckboxesReceive(checkbox) {
            const checkboxes = document.querySelectorAll('.checkbox-receive');
            if (checkbox.checked) {
                // Jika checkbox 1 di-check, centang semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = true;
                    }
                });
            } else {
                // Jika checkbox 1 di-uncheck, uncheck semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
        
        function updateCheckboxesPembayaran(checkbox) {
            const checkboxes = document.querySelectorAll('.checkbox-pembayaran');
            if (checkbox.checked) {
                // Jika checkbox 1 di-check, centang semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = true;
                    }
                });
            } else {
                // Jika checkbox 1 di-uncheck, uncheck semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
        
        function updateCheckboxesLaporan(checkbox) {
            const checkboxes = document.querySelectorAll('.checkbox-laporan');
            if (checkbox.checked) {
                // Jika checkbox 1 di-check, centang semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = true;
                    }
                });
            } else {
                // Jika checkbox 1 di-uncheck, uncheck semua checkbox lainnya
                checkboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }
    </script>
@endsection