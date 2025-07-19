{{-- Unit --}}
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitModalLabel">List Unit</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAMA UNIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-mdb-ripple-init>Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Departemen --}}
<div class="modal fade" id="departemenModal" tabindex="-1" aria-labelledby="departemenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departemenModalLabel">List Departemen</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>UNIT</th>
                            <th>NAMA DEPARTEMEN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departemens as $departemen)
                            <tr>
                                <td>{{ $departemen->id }}</td>
                                <td>{{ $departemen->unit->nama }}</td>
                                <td>{{ $departemen->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-mdb-ripple-init>Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Supplier --}}
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">List Supplier</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>KODE</th>
                            <th>NAMA SUPPLIER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->nomor }}</td>
                                <td>{{ $supplier->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-mdb-ripple-init>Save changes</button>
            </div>
        </div>
    </div>
</div>
