@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('preorder.get-list-barang') }}" method="POST">
                    @csrf
                    <div class="card-body mt-n4">
                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier1" class="col-sm-2 col-form-label">Supplier Header</label>
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nomor }}" placeholder="Email">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier1->nama }}" placeholder="Email">
                                            <input type="hidden" name="supplierId" value="{{ $supplier1->id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Penjualan Rata2</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->penjualan_rata }}" autocomplete="off" name="penjualan_rata" class="form-control" id="inputPassword3" autofocus onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 2</label>
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier2->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Waktu Kunjungan</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->waktu_kunjungan }}" autocomplete="off" name="waktu_kunjungan" class="form-control" id="inputPassword3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                    <div class="row">
                                        <label for="nomorSupplier2" class="col-sm-2 col-form-label">Nomor Supplier 3</label>
                                        <div class="col-sm-2">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nomor ?? '' }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" disabled class="form-control" id="inputEmail3"
                                                value="{{ $supplier3->nama ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Minimum</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->stok_minimum }}" autocomplete="off" name="stok_minimum" oninput="updateStokMin(this)" class="form-control" id="inputStokMinimum" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="row w-100">
                                <div class="form-group col-9">
                                </div>
                                <div class="form-group col-3">
                                    <div class="row">
                                        <label for="inputPassword3" class="col-sm-6 col-form-label">Stok Maksimum</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="{{ $penjualan->stok_maksimum }}" autocomplete="off" name="stok_maksimum" oninput="updateStokMax(this)" class="form-control" id="inputStokMaximum" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Hari</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-2">
                            <input type="text" id="nama_supplier_1" name='dataSupplier1' value="{{ $supplier1->nama ?? null }}" hidden />
                            <input type="text" id="nama_supplier_2" name='dataSupplier2' value="{{ $supplier2->nama ?? null }}" hidden />
                            <input type="text" id="nama_supplier_3" name='dataSupplier3' value="{{ $supplier3->nama ?? null }}" hidden />
                            <button type="button" onclick="window.history.back()" class="btn btn-danger mx-5">BATAL</button>
                            <button type="submit" class="btn btn-primary">GET BARANG</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[type="text"]');
        
            inputs.forEach((input, index) => {
                let isFirstInput = true;
        
                // Replace value lama saat mulai mengetik
                input.addEventListener('keydown', function (e) {
                    if (isFirstInput && e.key >= '0' && e.key <= '9') {
                        input.value = '';
                        isFirstInput = false;
                    }
        
                    if (e.key === 'Enter') {
                        e.preventDefault();
        
                        // Jika bukan input terakhir → fokus ke input berikutnya
                        if (index < inputs.length - 4) {
                            inputs[index + 1].focus();
                        } 
                        // Jika input terakhir → submit form
                        else {
                            form.submit();
                        }
                    }
                });
        
                // Reset flag saat fokus ulang
                input.addEventListener('focus', function () {
                    isFirstInput = true;
                });
            });
        });
    </script>
@endsection
