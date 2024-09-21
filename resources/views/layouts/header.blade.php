<?php
use app\models\User;
$user = User::where('id', auth()->user()->id)->first();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    <div class="container-fluid">
        <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse mx-4" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{-- <li class="nav-item">
                    <img class="mt-1" src="{{ asset('assets/images/zenwalker-logo.png') }}" width="125"
                        alt="Fikri Hidayat">
                </li> --}}
                <li class="nav-item mx-4">
                    <a class="nav-link" href="{{ route('index') }}">Dashboard</a>
                </li>
                {{-- <li class="nav-item dropdown me-3 mx-2">
                    <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#"
                        id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                        Master
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li>
                            <a class="dropdown-item" href="{{ route('master.unit.index') }}">Unit</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master.departemen.index') }}">Departemen</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master.supplier.index') }}">Supplier</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('master.product.index') }}">Product</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown mx-2">
                    <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#"
                        id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                        Preorder
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li>
                            <a class="dropdown-item" href="{{ route('preorder.index') }}">PO</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('daftar-po') }}">Daftar PO</a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>

        <div class="d-flex align-items-center me-4">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{-- <li class="nav-item mx-4">
                    <p class="nav-link disabled" style="color: #6c757d">{{ $user->name }} -
                        {{ str_replace('_', ' ', $user->role) }}</p>
                </li> --}}
                <li class="nav-item mx-4">
                    <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
                </li>
                {{-- <li>
                    <p class="dropdown-item mb-n1 disabled" style="color: #6c757d">{{ $user->name }} -
                        {{ str_replace('_', ' ', $user->role) }}</p>
                    <a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a>
                </li> --}}
            </ul>
            {{-- <div class="dropdown">
                <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                    id="navbarDropdownMenuAvatar" role="button" aria-expanded="false">
                    <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="25"
                        alt="Black and White Portrait of a Man" loading="lazy" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <p class="dropdown-item mb-n1 disabled" style="color: #6c757d">{{ $user->name }} -
                            {{ str_replace('_', ' ', $user->role) }}</p>
                        <a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a>
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
</nav>
