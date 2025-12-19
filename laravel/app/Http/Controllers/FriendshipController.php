<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;

class FriendshipController extends Controller
{
    public function send($id)
    {
        // Block admin while viewing-as-user from sending friend requests
        if (session('is_admin') && session('viewing_as_user')) {
            return back()->withErrors(['friend' => 'Admin tidak dapat menambah teman saat mode lihat sebagai user.']);
        }
        $meId = session('user_id');
        if ($meId == $id) return back()->withErrors(['friend' => 'Tidak bisa menambahkan diri sendiri']);
        $me = User::find($meId);
        if ($me && method_exists($me, 'hasFriendRequestTo') && $me->hasFriendRequestTo($id)) {
            return back()->with('info', 'Permintaan pertemanan sudah ada.');
        }

        // Instead of creating a direct 'friendship' record, create an internal email that will appear in the recipient's Email/Inbox
        \App\Models\Email::create([
            'from_user_id' => $meId,
            'to_user_id' => $id,
            'subject' => 'Permintaan pertemanan',
            'body' => ($me->username ?? $me->name) . " mengirim permintaan pertemanan",
            'type' => 'friend_request',
            'data' => null,
        ]);

        return back()->with('success', 'Permintaan pertemanan terkirim melalui Email.');
    }

    public function incoming()
    {
        $meId = session('user_id');

        // Load friend-request emails addressed to the user
        $requests = \App\Models\Email::where('to_user_id', $meId)
            ->where('type', 'friend_request')
            ->with('fromUser')
            ->orderByDesc('created_at')
            ->get();

        return view('friend_requests', ['requests' => $requests]);
    }

    public function accept($id)
    {
        // Block admin while viewing-as-user from accepting requests
        if (session('is_admin') && session('viewing_as_user')) {
            return back()->withErrors(['friend' => 'Admin tidak dapat mengelola pertemanan saat mode lihat sebagai user.']);
        }
        $meId = session('user_id');

        // Try legacy Friendship pending first (backwards compatibility)
        $f = Friendship::where('user_id', $id)->where('friend_id', $meId)->first();
        if ($f) {
            $f->update(['status' => 'accepted']);
            Friendship::firstOrCreate([
                'user_id' => $meId,
                'friend_id' => $id,
            ], ['status' => 'accepted']);

            return redirect()->back()->with('success', 'Permintaan pertemanan diterima.');
        }

        // Otherwise try email-based friend request (id is email id)
        $email = \App\Models\Email::where('id', $id)->where('to_user_id', $meId)->where('type', 'friend_request')->first();
        if (!$email) return back()->withErrors(['friend' => 'Permintaan tidak ditemukan']);

        $from = $email->from_user_id;
        $to = $email->to_user_id;

        // create accepted friendship both ways
        Friendship::firstOrCreate(['user_id' => $from, 'friend_id' => $to], ['status' => 'accepted']);
        Friendship::firstOrCreate(['user_id' => $to, 'friend_id' => $from], ['status' => 'accepted']);

        // remove the email request
        $email->delete();

        return redirect()->back()->with('success', 'Permintaan pertemanan diterima.');
    }

    public function reject($id)
    {
        // Block admin while viewing-as-user from rejecting requests
        if (session('is_admin') && session('viewing_as_user')) {
            return back()->withErrors(['friend' => 'Admin tidak dapat mengelola pertemanan saat mode lihat sebagai user.']);
        }
        $meId = session('user_id');

        // Legacy friend request
        $f = Friendship::where('user_id', $id)->where('friend_id', $meId)->first();
        if ($f) {
            $f->delete();
            return redirect()->back()->with('success', 'Permintaan pertemanan ditolak.');
        }

        // Email-based friend request
        $email = \App\Models\Email::where('id', $id)->where('to_user_id', $meId)->where('type', 'friend_request')->first();
        if (!$email) return back()->withErrors(['friend' => 'Permintaan tidak ditemukan']);
        $email->delete();
        return redirect()->back()->with('success', 'Permintaan pertemanan ditolak.');
    }
}
