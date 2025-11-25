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

// Search users
use Illuminate\Support\Facades\DB;

Route::get('/search', function (Request $request) {
    $q = $request->query('q');
    if (!$q) {
        return view('search_results', ['results' => collect(), 'query' => '']);
    }
    $results = User::where('name', 'like', "%{$q}%")
        ->orWhere('username', 'like', "%{$q}%")
        ->orWhere('email', 'like', "%{$q}%")
        ->get();
    return view('search_results', ['results' => $results, 'query' => $q]);
})->name('search');

// Send friend request (simple create pending)
Route::post('/friend/{id}', function ($id) {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    if ($meId == $id) return back()->withErrors(['friend' => 'Tidak bisa menambahkan diri sendiri']);
    $me = User::find($meId);
    if ($me->hasFriendRequestTo($id)) {
        return back()->with('info', 'Permintaan pertemanan sudah ada.');
    }
    \App\Models\Friendship::create([
        'user_id' => $meId,
        'friend_id' => $id,
        'status' => 'pending',
    ]);
    return back()->with('success', 'Permintaan pertemanan terkirim.');
});

// Messages: list friends to message
Route::get('/messages', function () {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    // get accepted friends both directions
    $friendIds = \App\Models\Friendship::where(function($q) use ($meId) {
        $q->where('user_id', $meId)->where('status', 'accepted');
    })->orWhere(function($q) use ($meId) {
        $q->where('friend_id', $meId)->where('status', 'accepted');
    })->get()->map(function($f) use ($meId) {
        return $f->user_id == $meId ? $f->friend_id : $f->user_id;
    })->unique()->values();

    $friends = \App\Models\User::whereIn('id', $friendIds)->get();
    return view('messages_index', ['friends' => $friends]);
})->name('messages.index');

// Show conversation with a friend
Route::get('/messages/{id}', function ($id) {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    // ensure friendship
    $isFriend = \App\Models\Friendship::where(function($q) use ($meId, $id) {
        $q->where('user_id', $meId)->where('friend_id', $id)->where('status', 'accepted');
    })->orWhere(function($q) use ($meId, $id) {
        $q->where('user_id', $id)->where('friend_id', $meId)->where('status', 'accepted');
    })->exists();
    if (!$isFriend) return redirect('/messages')->withErrors(['msg' => 'Anda bukan teman dengan user ini']);
    $messages = \App\Models\Message::where(function($q) use ($meId, $id) {
        $q->where('sender_id', $meId)->where('recipient_id', $id);
    })->orWhere(function($q) use ($meId, $id) {
        $q->where('sender_id', $id)->where('recipient_id', $meId);
    })->orderBy('created_at')->get();
    $friend = \App\Models\User::find($id);
    return view('messages_thread', ['messages' => $messages, 'friend' => $friend]);
})->name('messages.thread');

// Send message to friend
Route::post('/messages/{id}', function (
    Request $request, $id
) {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    $request->validate(['body' => 'required|string']);
    // ensure friendship
    $isFriend = \App\Models\Friendship::where(function($q) use ($meId, $id) {
        $q->where('user_id', $meId)->where('friend_id', $id)->where('status', 'accepted');
    })->orWhere(function($q) use ($meId, $id) {
        $q->where('user_id', $id)->where('friend_id', $meId)->where('status', 'accepted');
    })->exists();
    if (!$isFriend) return back()->withErrors(['msg' => 'Anda bukan teman dengan user ini']);

    \App\Models\Message::create([
        'sender_id' => $meId,
        'recipient_id' => $id,
        'body' => $request->body,
    ]);
    return redirect()->route('messages.thread', ['id' => $id]);
});

// List incoming friend requests
Route::get('/friend-requests', function () {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    $requests = \App\Models\Friendship::where('friend_id', $meId)
        ->where('status', 'pending')
        ->with('requester')
        ->get();
    return view('friend_requests', ['requests' => $requests]);
})->name('friend.requests');

// Accept friend request
Route::post('/friend/{id}/accept', function ($id) {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    $f = \App\Models\Friendship::where('user_id', $id)->where('friend_id', $meId)->first();
    if (!$f) return back()->withErrors(['friend' => 'Permintaan tidak ditemukan']);
    $f->update(['status' => 'accepted']);
    // also create reciprocal record if you want mutual friendship
    \App\Models\Friendship::firstOrCreate([
        'user_id' => $meId,
        'friend_id' => $id,
    ], ['status' => 'accepted']);
    return redirect()->back()->with('success', 'Permintaan pertemanan diterima.');
});

// Reject (delete) friend request
Route::post('/friend/{id}/reject', function ($id) {
    $meId = session('user_id');
    if (!$meId) return redirect('/login');
    $f = \App\Models\Friendship::where('user_id', $id)->where('friend_id', $meId)->first();
    if (!$f) return back()->withErrors(['friend' => 'Permintaan tidak ditemukan']);
    $f->delete();
    return redirect()->back()->with('success', 'Permintaan pertemanan ditolak.');
});
