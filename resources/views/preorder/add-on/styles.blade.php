{{-- Supplier --}}
<style>
    /* Basic styles for the searchable select */
    .custom-select-supplier {
        position: relative;
        display: inline-block;
        width: 500px;
    }

    .custom-select-supplier select {
        display: none;
        /* Hide the default select */
    }

    .select-items-supplier div,
    .select-selected {
        background-color: #ffffff;
        border: 1px solid #ddd;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }

    .select-items-supplier {
        position: absolute;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 4px;
        z-index: 99;
        width: 100%;
        box-sizing: border-box;
        max-height: 200px;
        overflow-y: auto;
    }

    .select-items-supplier div:hover {
        background-color: #f1f1f1;
    }

    .search-input-supplier {
        box-sizing: border-box;
        width: 100%;
        padding: 4px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>