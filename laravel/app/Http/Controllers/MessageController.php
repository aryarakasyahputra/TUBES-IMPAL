<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $meId = session('user_id');
        $friendIds = Friendship::where(function($q) use ($meId) {
            $q->where('user_id', $meId)->where('status', 'accepted');
        })->orWhere(function($q) use ($meId) {
            $q->where('friend_id', $meId)->where('status', 'accepted');
        })->get()->map(function($f) use ($meId) {
            return $f->user_id == $meId ? $f->friend_id : $f->user_id;
        })->unique()->values();

        $friends = User::whereIn('id', $friendIds)->get();
        return view('messages_index', ['friends' => $friends]);
    }

    public function thread($id)
    {
        $meId = session('user_id');
        $isFriend = Friendship::where(function($q) use ($meId, $id) {
            $q->where('user_id', $meId)->where('friend_id', $id)->where('status', 'accepted');
        })->orWhere(function($q) use ($meId, $id) {
            $q->where('user_id', $id)->where('friend_id', $meId)->where('status', 'accepted');
        })->exists();
        if (!$isFriend) return redirect('/messages')->withErrors(['msg' => 'Anda bukan teman dengan user ini']);
        $messages = Message::where(function($q) use ($meId, $id) {
            $q->where('sender_id', $meId)->where('recipient_id', $id);
        })->orWhere(function($q) use ($meId, $id) {
            $q->where('sender_id', $id)->where('recipient_id', $meId);
        })->orderBy('created_at')->get();
        $friend = User::find($id);
        return view('messages_thread', ['messages' => $messages, 'friend' => $friend]);
    }

    public function send(Request $request, $id)
    {
        $meId = session('user_id');

        // Detect low-level PHP upload errors (file too large, partial upload, etc.)
        if (isset($_FILES['attachment']) && is_array($_FILES['attachment']) && ($_FILES['attachment']['error'] ?? 0) !== UPLOAD_ERR_OK && ($_FILES['attachment']['error'] ?? 0) !== UPLOAD_ERR_NO_FILE) {
            $err = $_FILES['attachment']['error'];
            $msg = 'Upload file gagal';
            if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                $msg = 'Upload gagal: file terlalu besar untuk diupload (periksa upload_max_filesize dan post_max_size di konfigurasi PHP).';
            } elseif ($err === UPLOAD_ERR_PARTIAL) {
                $msg = 'Upload gagal: file hanya terupload sebagian.';
            } else {
                $msg = 'Upload gagal: terjadi kesalahan pada server saat mengunggah file.';
            }
            return back()->withErrors(['attachment' => $msg]);
        }

        $request->validate([
            'body' => 'nullable|string|max:200',
            'attachment' => 'nullable|image|max:4096',
        ]);

        $isFriend = Friendship::where(function($q) use ($meId, $id) {
            $q->where('user_id', $meId)->where('friend_id', $id)->where('status', 'accepted');
        })->orWhere(function($q) use ($meId, $id) {
            $q->where('user_id', $id)->where('friend_id', $meId)->where('status', 'accepted');
        })->exists();
        if (!$isFriend) return back()->withErrors(['msg' => 'Anda bukan teman dengan user ini']);

        // Ensure message isn't empty if no attachment
        $hasBody = trim((string) $request->body) !== '';
        $hasAttachment = $request->hasFile('attachment');
        if (!$hasBody && !$hasAttachment) {
            return back()->withErrors(['body' => 'Pesan kosong dan tidak ada file terlampir']);
        }

        // Sanitize body if present: trim spaces and trailing newlines to avoid tall bubbles
        $clean = $hasBody ? preg_replace("/(\r?\n){3,}/", "\n\n", trim((string) $request->body)) : null;

        $data = [
            'sender_id' => $meId,
            'recipient_id' => $id,
            'body' => $clean,
        ];

        if ($hasAttachment) {
            $path = $request->file('attachment')->store('messages', 'public');
            $data['attachment'] = $path;
        }

        Message::create($data);
        return redirect()->route('messages.thread', ['id' => $id]);
    }
}
