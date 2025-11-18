<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Login GET
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register GET
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Register POST
Route::post('/register', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'username' => 'required|unique:users,username',
        'password' => 'required|min:6|confirmed',
    ]);
    $user = User::create([
        'name' => $request->username,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
});

// Login POST
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if ($user && Hash::check($request->password, $user->password)) {
        session(['user_id' => $user->id, 'user_name' => $user->name]);
        return redirect('/home');
    }
    return back()->withErrors(['email' => 'Email atau password salah']);
});

// Logout
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('logout');

// Home/dashboard (setelah login)
Route::get('/home', function () {
    if (!session('user_id')) {
        return redirect('/login');
    }
    return view('home');
})->name('home');
