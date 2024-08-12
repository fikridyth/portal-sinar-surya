<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        $user = User::where('id', auth()->user()->id)->first();

        return view('dashboard', compact('title', 'user'));
    }
}
