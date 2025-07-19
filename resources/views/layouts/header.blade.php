<?php
use app\models\User;
$user = User::where('id', auth()->user()->id)->first();
?>

<style>
    h2.nav-link {
        font-size: 24px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    <div class="container-fluid">
        <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse mx-4" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-4">
                    <a class="nav-link" href="{{ route('index') }}">Dashboard</a>
                </li>
            </ul>
        </div>
        

        <div class="collapse navbar-collapse mx-4" id="navbarSupportedContent">
            <h2 class="nav-link mt-2">{{ $titleHeader ?? '' }}</h2>
        </div>

        <div class="d-flex align-items-center me-4">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-4">
                    <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
