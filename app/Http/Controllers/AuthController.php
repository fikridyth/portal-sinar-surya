<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        $title = 'Login Portal';
        return view('auth.login', compact('title'));
    }

    public function loginSubmit(Request $request)
    {
        // Jalankan pengecekan waktu CMOS
        $exitCode = Artisan::call('check:rtc-time');
        // dd($exitCode);

        // Jika waktu CMOS tidak valid (exitCode tidak 0), hentikan eksekusi dan beri respons error
        if ($exitCode !== 0) {
            return redirect()->back()->withInput()->withErrors(["The CMOS time is invalid!"]);
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('index');
        } else {
            return redirect()->back()->withInput()->withErrors(["Wrong Username or Password!"]);
        }
    }

    // public function registerSubmit(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required',
    //         'role' => 'required',
    //     ]);

    //     $data = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //         'role' => $request->role,
    //     ];

    //     User::create($data);

    //     return Redirect::route('auth.login')
    //         ->with('alert.status', '00')
    //         ->with('alert.message', "Create User Success!");
    // }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect(route('auth.login'));
    }
}
