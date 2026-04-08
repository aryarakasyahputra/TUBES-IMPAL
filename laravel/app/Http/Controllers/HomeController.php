<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $me = User::find(session('user_id'));
        $posts = Post::with('user')->orderByDesc('created_at')->get();

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

        // Count pending friend-request emails and unread messages for badge in sidebar
        $friendRequestCount = 0;
        $unreadMessageCount = 0;
        if ($me) {
            $friendRequestCount = \App\Models\Email::where('to_user_id', $me->id)->where('type', 'friend_request')->count();
            $unreadMessageCount = \App\Models\Message::where('recipient_id', $me->id)->where('is_read', false)->count();
        }

        return view('home', ['me' => $me, 'posts' => $posts, 'storageLinkMissing' => $storageLinkMissing, 'friendRequestCount' => $friendRequestCount, 'unreadMessageCount' => $unreadMessageCount]);
    }
}
