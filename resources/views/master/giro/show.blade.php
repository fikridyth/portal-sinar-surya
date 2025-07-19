@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3 text-center" aria-current="page">BUKU CEK / GIRO
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7" class="text-center">GIRO</th>
                            </tr>
                            <tr>
                                <th class="text-center">NOMOR</th>
                                <th class="text-center">DIBAYARKAN KEPADA</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">JATUH TEMPO</th>
                                <th class="text-center">JUMLAH</th>
                                <th class="text-center">RUSAK</th>
                                <th class="text-center">RESERVE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($giroDetail as $detail)
                                <tr data-id="{{ $detail->id }}">
                                    <input type="text" hidden name="giro_header" value="{{ $giroHeader->id }}">
                                    <td class="text-center">{{ $detail->nomor }}</td>
                                    <td>{{ $detail->nama }}</td>
                                    <td class="text-center">{{ $detail->tanggal_awal }}</td>
                                    <td class="text-center">{{ $detail->tanggal_akhir }}</td>
                                    <td class="text-end">{{ number_format($detail->jumlah, 0) }}</td>
                                    <td class="text-center">
                                        @if ($detail->flag == 3)
                                            RUSAK
                                        @elseif ($detail->flag == 5)
                                            RESERVE
                                        @else
                                            <input type="checkbox" disabled data-id="{{ $detail->id }}">
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($detail->flag == 2 || $detail->flag == 3)
                                            TERPAKAI
                                        @else
                                            <input type="checkbox" class="giro-checkbox" data-id="{{ $detail->id }}" data-flag="{{ $detail->flag }}">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                
                <div class="align-items-center mt-4">
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('master.giro.index') }}" class="btn btn-warning mx-2">KEMBALI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.giro-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const giroId = this.getAttribute('data-id');
                const flagId = this.getAttribute('data-flag');
                let confirmation = null;
                if (flagId == 5) {
                    confirmation = confirm("Batal reserve giro?");
                } else {
                    confirmation = confirm("Reserve giro ini?");
                }
                
                if (confirmation) {
                    document.getElementById('giro_id').value = giroId; // Set the ID in the hidden input
                    document.getElementById('giroForm').action = `{{ url('master/giro/update-reserve') }}/${giroId}`; // Update the form action
                    document.getElementById('giroForm').submit(); // Submit the form
                } else {
                    this.checked = false; // Uncheck the checkbox if user selects "No"
                }
            }
        });
    });
</script>
@endsection
