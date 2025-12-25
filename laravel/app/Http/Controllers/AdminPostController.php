<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    public function destroy($id)
    {
        // Ensure requester is admin (handled by EnsureAdmin middleware if route is under admin),
        // but double-check session in case route isn't under admin prefix.
        if (!session('is_admin')) {
            return back()->withErrors(['admin' => 'Anda tidak memiliki izin.']);
        }

        $post = Post::find($id);
        if (!$post) {
            return back()->withErrors(['post' => 'Posting tidak ditemukan.']);
        }

        // Delete associated image file if present
        if ($post->image) {
            try {
                Storage::disk('public')->delete($post->image);
            } catch (\Throwable $e) {
                // ignore file deletion errors
            }
        }

        $post->delete();

        return redirect()->back()->with('success', 'Posting berhasil dihapus.');
    }
}
