@extends('main')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h3" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-8">
            <div class="card-body">
                <h5 class="mt-2">Welcome To Portal Sinar Surya.</h5>
                <div class="dropdown-divider mt-3 mb-3"></div>
                {{-- @if (auth()->user()->role == 'admin_ticket')
                    <h5 class="mt-4">Recent Events:</h5>
                    <div class="row">
                        @if ($events->isEmpty())
                            <p>No Event, Add Event First.</p>
                        @else
                            @foreach ($events as $event)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="bg-image hover-overlay" data-mdb-ripple-init data-mdb-ripple-color="light">
                                            <img class="img-fluid rounded" style="width: 100%; height: 400px; object-fit: cover;" src="/storage/{{ $event->gambar }}" />
                                            <a href="#!">
                                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $event->nama }} - {{ $event->lokasi }}</h5>
                                            <p>{{ $event->kategori }}, {{ $event->tanggal_mulai->setTimezone('Asia/Jakarta')->format('d F Y') }} - {{ $event->tanggal_selesai->setTimezone('Asia/Jakarta')->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="btn btn-primary" href="{{ route('master.event.index') }}">Go To Event</a>
                @else
                    <h5 class="mt-4">Recent Transactions:</h5>
                    <div class="row">
                        @if ($transactions->isEmpty())
                            <p>No Transaction.</p>
                        @else
                            @foreach ($transactions as $transaction)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="bg-image hover-overlay" data-mdb-ripple-init data-mdb-ripple-color="light">
                                            <img class="img-fluid rounded" style="width: 100%; height: 400px; object-fit: cover;" src="/storage/{{ $transaction->ticket->event->gambar }}" />
                                            <a href="#!">
                                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $transaction->ticket->event->nama }} - {{ $transaction->ticket->event->lokasi }} ({{ $transaction->ticket->event->kategori }})</h5>
                                            <p>Nama: {{ $transaction->nama }}</p>
                                            <p class="mt-n3">Tanggal: {{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s') }}</p>
                                            <p class="mt-n3">Detail: {{ $transaction->jumlah }} Pcs ({{ $transaction->ticket->nama }})</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="btn btn-primary" href="{{ route('transaction.index') }}">Go To Transaction</a>
                @endif --}}
            </div>
        </div>
    </div>
@endsection
