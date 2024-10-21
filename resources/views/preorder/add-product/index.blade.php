@extends('main')

@section('content')
    <div class="container">

        <div class="card">
            <div class="card-body">
                <form action="{{ route('receive-po.add-product.update', enkrip($id)) }}" id="purchase-order-form" method="POST" class="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <h2 class="breadcrumb-item active h3" aria-current="page">PEMESANAN BARANG - PURCHASE ORDER</h2>
                        {{ $dataTable->table() }}
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mx-3">PROSES</button>
                        <button type="button" onclick="window.history.back()" class="btn btn-danger mx-3">KEMBALI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
    
    <script>
        let selectedIds = [];

        $(document).ready(function() {
            // Track checkbox changes
            $('#search-product-table').on('change', '.row-checkbox', function() {
                const id = $(this).val();
                if ($(this).is(':checked')) {
                    if (!selectedIds.includes(id)) {
                        selectedIds.push(id);
                    }
                } else {
                    selectedIds = selectedIds.filter(item => item !== id);
                }
            });

            // Restore checkbox states after table redraw
            $('#search-product-table').on('draw.dt', function() {
                $('.row-checkbox').each(function() {
                    const id = $(this).val();
                    $(this).prop('checked', selectedIds.includes(id));
                });
            });

            // On form submission, add hidden inputs for selected IDs
            $('#purchase-order-form').on('submit', function() {
                // Clear any previous hidden inputs
                $('input[name="selected_ids[]"]').remove();

                // Add selected IDs as hidden inputs
                selectedIds.forEach(function(id) {
                    $(this).append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
                }, this); // "this" refers to the form

                // Check if hidden inputs are correctly added
                const hiddenInputs = $(this).find('input[name="selected_ids[]"]');
            });
        });
    </script>
@endsection 