@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 80%">
            <div class="d-flex align-items-center justify-content-center">
                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active h3 text-center" aria-current="page">
                                INFORMASI CEK / GIRO
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <form action="{{ route('master.cek-giro.show') }}" method="GET" class="form">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <label class="mx-2">NOMOR CEK / GIRO</label>
                            <input type="text" class="mx-2" name="nomor"  autocomplete="off">
                            <button type="submit" class="btn btn-sm btn-primary mx-2">PROSES</button>
                        </div>

                        <table class="table table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">NOMOR</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">NAMA SUPPLIER</th>
                                    <th class="text-center">DIBUAT TANGGAL</th>
                                    <th class="text-center">JATUH TEMPO</th>
                                    <th class="text-center">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" colspan="7"><b>INPUT DATA TERLEBIH DAHULU</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
