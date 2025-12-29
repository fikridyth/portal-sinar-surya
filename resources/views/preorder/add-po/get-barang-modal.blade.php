<div id="poModal" class="custom-modal">
    <div class="custom-modal-content">
        <form action="{{ route('preorder.order-barang')  }}" method="POST">
            @csrf
            <div class="custom-modal-header">
                <div class="d-flex justify-content-end">
                    <button type="button" hidden class="btn btn-primary" id="closePoModal">&times;</button>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="row w-100">
                        <div class="form-group col-7">
                            <div class="row">
                                <label for="nomorSupplier1" class="col-sm-3 col-form-label">Nama Supplier</label>
                                {{-- <div class="col-sm-2">
                                    <input type="email" disabled class="form-control" id="inputEmail3"
                                        value="{{ $supplier1->nomor }}" placeholder="Email">
                                </div> --}}
                                <div class="col-sm-6">
                                    <input type="email" disabled class="form-control" id="inputEmail3"
                                        value="{{ $supplier1->nama }}" placeholder="Email">
                                        <input type="hidden" name="supplierId" value="{{ $supplier1->id }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-3">
                            <div class="row">
                                <label for="inputPassword3" class="col-sm-4 col-form-label">Currency</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled class="form-control" value="IDR"
                                        id="inputPassword3">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <div class="row">
                                <label for="inputPassword3" class="col-sm-4 col-form-label">Rate</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled class="form-control" value="1"
                                        id="inputPassword3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="custom-modal-body">
                {{-- ISI MODAL KAMU (TETAP) --}}
                <div style="overflow-x:auto; max-height:80%;">
                    <table class="table table-bordered table-sm mt-2" id="modal-po-table">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">
                                    DAFTAR BARANG YANG AKAN DIPESAN
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Order</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="modal-empty">
                                <td colspan="5" class="text-center text-muted">
                                    Tidak ada barang yang di order
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="custom-modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary mx-5" id="cancelPoModal">
                    KEMBALI
                </button>
                {{-- <a href="{{ route('preorder.add-po.cetak', ['results' => urlencode(json_encode($supplier))]) }}" class="btn btn-warning">CETAK</a> --}}
                <button type="submit" class="btn btn-primary mx-5">
                    PROSES
                </button>
            </div>

        </form>

    </div>
</div>
