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
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/zen-favicon.png') }}">
    <!-- Font Awesome -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" /> --}}
    {{-- <link href="/assets/css/all.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" />

    <!-- Google Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" /> --}}
    {{-- <link href="/assets/css/css.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css/css.css') }}" rel="stylesheet" />
    
    <!-- MDB -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" /> --}}
    {{-- <link href="/assets/css/mdb.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css/mdb.min.css') }}" rel="stylesheet" />
    <style type="text/css">
        .login {
            margin: 200px auto;
        }
    </style>
    @yield('styles')
</head>

<body>
    <section class="login">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="text-center">
                        <h2 class="text-dark fw-bolder mb-3">Portal Sinar Surya</h2>
                    </div>
                    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-login" data-mdb-pill-init href="#pills-login"
                                role="tab" aria-controls="pills-login" aria-selected="true">Login</a>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-register" data-mdb-pill-init href="#pills-register"
                                role="tab" aria-controls="pills-register" aria-selected="false">Register</a>
                        </li> --}}
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-login" role="tabpanel"
                            aria-labelledby="tab-login">
                            <form action="{{ route('auth.login-submit') }}" method="POST" class="form">
                                @csrf
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" name="username" id="loginUsername" class="form-control" autofocus
                                        placeholder="ENTER USERNAME" autocomplete="off" required style="text-transform: uppercase;"/>
                                    <label class="form-label">USERNAME</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" name="password" id="loginPassword" class="form-control"
                                        placeholder="ENTER PASSWORD" autocomplete="off" required style="text-transform: uppercase;" />
                                    <label class="form-label">PASSWORD</label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block mb-4">SIGN IN</button>
                            </form>
                        </div>
                        {{-- <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                            <form action="{{ route('auth.register-submit') }}" method="POST" class="form">
                                @csrf
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" name="name" id="registerName" class="form-control"
                                        autocomplete="off" required />
                                    <label class="form-label" for="registerEmail">Name</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" name="email" id="registerEmail" class="form-control"
                                        autocomplete="off" required />
                                    <label class="form-label" for="registerEmail">Email</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-3">
                                    <input type="password" name="password" id="registerPassword"
                                        class="form-control" autocomplete="off" required />
                                    <label class="form-label" for="registerPassword">Password</label>
                                </div>

                                <div>
                                    <label class="form-check-label" for="role">Role: </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role"
                                        id="inlineRadio1" value="admin_ticket" required/>
                                    <label class="form-check-label" for="inlineRadio1">Admin Ticket</label>
                                </div>

                                <div class="form-check form-check-inline mb-4">
                                    <input class="form-check-input" type="radio" name="role"
                                        id="inlineRadio2" value="admin_transaction" required/>
                                    <label class="form-check-label" for="inlineRadio2">Admin Transaction</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-3">Sign Up</button>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MDB -->
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script> --}}
    {{-- <script type="text/javascript" src="/assets/js/mdb.umd.min.js"></script> --}}
    <script type="text/javascript" src="{{ asset('assets/js/mdb.umd.min.js') }}"></script>

    {{-- Swal --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{-- <script src="/assets/js/sweetalert2@11.js"></script> --}}
    <script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>

    {{-- Jquery --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    {{-- <script src="/assets/js/jquery.min.js"></script> --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    {{-- Validation --}}
    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const usernameInput = document.getElementById('loginUsername');
            usernameInput.value = usernameInput.value.toUpperCase();
            const passwordInput = document.getElementById('loginPassword');
            passwordInput.value = passwordInput.value.toUpperCase();
        });

        $(document).ready(function() {
            $('#tab-register').change(function() {
                $('#registerEmail, #registerPassword').val('');
            });
        });

        $(document).ready(function() {
            @if ($errors->any())
                var status = `01`;
                var message = ``;
                @foreach ($errors->all() as $error)
                    message += `{{ $error }}<br/>`;
                @endforeach
                console.log(status, message)
                show_alert_dialog(status, message);
            @endif
        });

        function show_alert_dialog(status, message) {
            if (status == "00")
                Swal.fire({
                    title: "Success",
                    html: message,
                    icon: "success",
                });
            else
                Swal.fire({
                    title: "Process Failed",
                    html: message,
                    icon: "warning",
                });
        }
    </script>

    {{-- Redirect Message --}}
    @if (session('alert'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('alert')['message'] }}',
                showConfirmButton: true,
            });
        </script>
    @endif
</body>
