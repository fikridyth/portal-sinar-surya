<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index()
    {
        $title = "Pembayaran Piutang";
        
        return view('pembayaran.piutang.index',compact('title'));
    }
}
