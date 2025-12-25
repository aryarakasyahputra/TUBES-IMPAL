<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        $me = \App\Models\User::find(session('user_id'));
        if (!$me) return redirect('/login');

        // Only psychologists can access the posting page
        if (($me->role ?? null) !== 'psikolog') {
            return redirect()->route('home')->with('info', 'Hanya psikolog yang dapat membuat posting.');
        }

        return view('posts.create', ['me' => $me]);
    }

    public function store(Request $request)
    {
        // Validate body first. We'll only validate the uploaded file if an actual file is present
        $request->validate([
            'body' => 'nullable|string|max:200',
        ]);

        // If a file was uploaded, validate and ensure it's a valid image
        $hasAttachment = $request->hasFile('attachment');
        if ($hasAttachment) {
            $request->validate([
                'attachment' => 'image|mimes:jpg,jpeg,png|max:4096',
            ]);
        }

        // Use session-based user as app middleware relies on session('user_id')
        $me = \App\Models\User::find(session('user_id'));
        if (!$me) {
            return redirect('/login');
        }

        // Only psychologists are allowed to create posts
        if (($me->role ?? null) !== 'psikolog') {
            return redirect()->route('home')->withErrors(['post' => 'Hanya psikolog yang dapat membuat posting.']);
        }

        $hasBody = trim((string) $request->body) !== '';
        $hasAttachment = $request->hasFile('attachment');
        if (!$hasBody && !$hasAttachment) {
            return back()->withErrors(['post' => 'Tulisan kosong dan tidak ada gambar yang diunggah']);
        }

        $data = [
            'user_id' => $me->id,
            'body' => $request->input('body'),
        ];

        if ($hasAttachment) {
            $path = $request->file('attachment')->store('posts', 'public');
            $data['image'] = $path;
        }

        Post::create($data);

        return redirect()->route('home')->with('success', 'Posting berhasil dibuat.');
    }
}
