@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card">
            <div class="card-body">
                {{-- <h5 class="text-center">DAFTAR PENERIMAAN BARANG</h5> --}}
                <div id="scrollContainer" style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                    {{ $dataTable->table() }}
                </div>
                <div class="text-center">
                    <a class="btn btn-danger mt-2" href="{{ route('index') }}">KEMBALI</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the scrollable container by its ID
            var scrollContainer = document.getElementById('scrollContainer');
            
            // Scroll to the bottom of the container
            scrollContainer.scrollTop = scrollContainer.scrollHeight;
        });
    </script>
@endsection
