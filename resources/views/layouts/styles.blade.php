<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/zen-favicon.png') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
<!-- MDB -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* for readonly like disabled */
    .readonly-input {
        background-color: #e9ecef;
        cursor: not-allowed;
        border: 1px solid #ced4da;
    }

    /* for a tag blur like disabled */
    .disabled-link {
        pointer-events: none; /* Prevents clicking */
        cursor: not-allowed; /* Shows a not-allowed cursor */
        text-decoration: none; /* Removes underline */
        opacity: 0.5; /* Makes the link look faded */
    }

    .col-0-5 {
        flex: 0 0 5.33%; /* Lebar 1/12 dari lebar kontainer (5.33% x 12 = 100%) */
        max-width: 5.33%;
    }
</style>