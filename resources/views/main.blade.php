<!DOCTYPE html>
<html lang="en">

<head>
    <base href="" />
    <title>{{ $title ? $title . ' | ' . config('app.name') : config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name') }}." />
    <meta name="keywords" content="portal-karyawan" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @include('layouts.styles')
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-bg">
        @include('layouts.header')
    </div>

    <div class="d-flex flex-column flex-root" style="margin-top: 20px">
        @yield('content')
    </div>

    @include('layouts.footer')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
    @include('layouts.scripts')
    @yield('scripts')
</body>

</html>
