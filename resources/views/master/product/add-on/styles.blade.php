{{-- Unit --}}
<style>
    /* Basic styles for the searchable select */
    .custom-select-unit {
        position: relative;
        display: inline-block;
        width: 225px;
    }

    .custom-select-unit select {
        display: none;
        /* Hide the default select */
    }

    .select-items-unit div,
    .select-selected {
        background-color: #ffffff;
        border: 1px solid #ddd;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }

    .select-items-unit {
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

    .select-items-unit div:hover {
        background-color: #f1f1f1;
    }

    .search-input-unit {
        box-sizing: border-box;
        width: 100%;
        padding: 4px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

{{-- Departemen --}}
<style>
    /* Basic styles for the searchable select */
    .custom-select-departemen {
        position: relative;
        display: inline-block;
        width: 225px;
    }

    .custom-select-departemen select {
        display: none;
        /* Hide the default select */
    }

    .select-items-departemen div,
    .select-selected {
        background-color: #ffffff;
        border: 1px solid #ddd;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }

    .select-items-departemen {
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

    .select-items-departemen div:hover {
        background-color: #f1f1f1;
    }

    .search-input-departemen {
        box-sizing: border-box;
        width: 100%;
        padding: 4px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

{{-- Supplier --}}
<style>
    /* Basic styles for the searchable select */
    .custom-select-supplier {
        position: relative;
        display: inline-block;
        width: 225px;
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

    /* Slider */
    /* Hide the default checkbox */
    .slider-checkbox {
        display: none;
    }

    /* Slider container */
    .slider-container {
        display: inline-block;
        position: relative;
    }

    /* Slider label */
    .slider-label {
        display: block;
        width: 60px;
        height: 34px;
        background-color: #ccc;
        border-radius: 50px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    /* Slider indicator */
    .slider-label::before {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        left: 4px;
        top: 4px;
        transition: transform 0.3s;
    }

    /* Change slider appearance when checked */
    .slider-checkbox:checked + .slider-label {
        background-color: #4CAF50;
    }

    .slider-checkbox:checked + .slider-label::before {
        transform: translateX(26px);
    }
</style>