<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Kredit;
use App\Models\Langganan;
use App\Models\Ppn;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        $user = User::where('id', auth()->user()->id)->first();
        $product = Product::orderBy('kode', 'asc')->first();
        $unit = Unit::orderBy('id', 'asc')->first();
        $departemen = Departemen::orderBy('id', 'asc')->first();
        $supplier = Supplier::orderBy('id', 'asc')->first();
        $langganan = Langganan::orderBy('id', 'asc')->first();
        $ppn = Ppn::first();
        $preorder = Preorder::where('receive_type', 'B')->where('is_cancel', 1)->orderBy('id', 'desc')->first();
        $kredit = Kredit::whereNotNull('nomor')->orderBy('id', 'desc')->first();

        return view('dashboard', compact('title', 'user', 'product', 'unit', 'departemen', 'supplier', 'langganan', 'ppn', 'preorder', 'kredit'));
    }
}
