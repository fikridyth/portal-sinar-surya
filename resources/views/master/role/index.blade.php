@extends('main')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="mb-7" style="width: 60%">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-2">
                        <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                            <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                                <thead>
                                    <tr style="border: 1px solid black; font-size: 12px;">
                                        <th class="text-center">NO</th>
                                        <th class="text-center">STANDAR</th>
                                        <th class="text-center">OTORISASI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $role->nama }}</td>
                                            <td class="text-center"><a href="{{ route('master.role.edit', enkrip($role->id)) }}" class="btn btn-sm btn-primary">Ubah</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('index') }}" class="btn btn-danger">SELESAI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
