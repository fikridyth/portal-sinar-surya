@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">Transaction Ticket Event</li>
                        <li class="breadcrumb-item active h3" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>

            <div class="mt-2">
                <a href="{{ route('transaction.index') }}" type="button" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <form action="{{ route('transaction.update', $transaction->id) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row mb-4">
                <div class="col mb-2">
                    <select class="form-select @error('id_ticket') is-invalid @enderror" id="id_ticket" name="id_ticket"
                        data-control="select">
                        <option value="{{ $transaction->id_ticket }}">{{ $transaction->ticket->event->nama }} - {{ $transaction->ticket->nama }}</option>
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
                        <input type="number" id="harga" name="harga" value="{{ old('harga', $transaction->ticket->harga ?? null) }}" style="background-color: rgb(241, 241, 241)" readonly
                            class="form-control" autocomplete="off" placeholder="Harga (Otomatis Terisi)" />
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div data-mdb-input-init class="form-outline">
                        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', $transaction->jumlah ?? null) }}"
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
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $transaction->nama ?? null) }}"
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
                        <input type="email" id="email" name="email" value="{{ old('email', $transaction->email ?? null) }}"
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
                        <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $transaction->telepon ?? null) }}"
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

            <div class="mb-7">
                <button type="submit" class="btn btn-primary btn-block mt-4 mb-7">Update</button>
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
                console.log(selectedOption)
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

            var harga = "{{ old('harga', $transaction->ticket->harga ?? null) }}";
            if (harga !== '') {
                $('#harga').val(harga);
            }
        });
    </script>
@endsection