<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $me = User::find(session('user_id'));
        if (session('user_role') === 'anonim') {
            // Anonymous users should only see posts made by psychologists
            $posts = Post::with('user')
                ->whereHas('user', function($q){ $q->where('role', 'psikolog'); })
                ->orderByDesc('created_at')
                ->get();
        } else {
            $posts = Post::with('user')->orderByDesc('created_at')->get();
        }

        // Detect whether the public/storage symlink is correctly pointing to storage/app/public
        $storageLinkMissing = true;
        try {
            $publicStorage = public_path('storage');
            $expected = realpath(storage_path('app/public'));
            $actual = realpath($publicStorage);
            if ($actual && $expected && $actual === $expected) {
                $storageLinkMissing = false;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Count pending friend-request emails for badge in sidebar
        $friendRequestCount = 0;
        if ($me) {
            $friendRequestCount = \App\Models\Email::where('to_user_id', $me->id)->where('type', 'friend_request')->count();
        }

        return view('home', ['me' => $me, 'posts' => $posts, 'storageLinkMissing' => $storageLinkMissing, 'friendRequestCount' => $friendRequestCount]);
    }
}
