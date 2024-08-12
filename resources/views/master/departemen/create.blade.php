@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">Transaction Ticket Event</li>
                        <li class="breadcrumb-item active h3" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="mt-2">
                <a href="{{ route('transaction.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <form action="{{ route('transaction.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="row mb-4">
                <div class="col mb-2">
                    <select class="form-select @error('id_ticket') is-invalid @enderror" id="id_ticket" name="id_ticket"
                        data-control="select">
                        <option value="">---Select Event & Ticket---</option>
                        @foreach ($tickets as $ticket)
                            <option value="{{ $ticket->id }}">{{ $ticket->event->nama }} - {{ $ticket->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_ticket')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}" style="background-color: rgb(241, 241, 241)" readonly
                            class="form-control" autocomplete="off" placeholder="Harga (Otomatis Terisi)" />
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}"
                            class="form-control @error('jumlah') is-invalid @enderror" autocomplete="off" />
                        <label class="form-label" for="jumlah">Jumlah Tiket (Pcs)</label>
                        @error('jumlah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            class="form-control @error('nama') is-invalid @enderror" autocomplete="off" />
                        <label class="form-label" for="nama">Nama</label>
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" autocomplete="off" />
                        <label class="form-label" for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}"
                            class="form-control @error('telepon') is-invalid @enderror" autocomplete="off" />
                        <label class="form-label" for="telepon">Nomor Telepon</label>
                        @error('telepon')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn btn-primary btn-block mt-4 mb-7">Tambah</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var tickets = {!! json_encode($tickets) !!};
        $(document).ready(function() {
            $('#id_ticket').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === '') {
                    $('#harga').val('');
                } else {
                    var selectedTicket = tickets.find(function(ticket) {
                        return ticket.id == selectedOption;
                    });
                    if (selectedTicket) {
                        $('#harga').val(selectedTicket.harga);
                    } else {
                        $('#harga').val('');
                    }
                }
            });

            $('#id_ticket').change();
        });
    </script>
@endsection