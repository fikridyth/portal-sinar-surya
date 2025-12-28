@extends('main')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div style="overflow-x: auto; height: 650px; border: 1px solid #ccc;">
                    {{ $dataTable->table() }}
                </div>
                <div class="text-center">
                    {{-- <a href="{{ route('master.product.create') }}" class="btn btn-danger">Kembali</a> --}}
                    <button type="button" onclick="window.history.back()" class="btn btn-danger mt-4 mb-7">KEMBALI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.querySelector('#kunjungan-table');
        
            table.addEventListener('change', function(e) {
                const checkbox = e.target;
                if (checkbox.classList.contains('kunjungan-checkbox')) {
                    const rowId = checkbox.dataset.id;
                    const hari = checkbox.dataset.hari;
                    const checked = checkbox.checked ? 1 : 0;
        
                    fetch(`/master/kunjungan/${rowId}/update`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            hari: hari,
                            value: checked
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            console.log('Update berhasil');
                            location.reload();
                        } else {
                            console.error('Update gagal');
                            alert(data.message || 'Update gagal');
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        });
    </script>              
@endsection
