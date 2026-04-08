<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPsikolog = User::where('role', 'psikolog')->where('is_admin', false)->count();
        $totalAnonim = User::where('role', 'anonim')->where('is_admin', false)->count();

        $pendingCount = User::where('role', 'psikolog')
            ->where('is_admin', false)
            ->where('is_verified', false)
            ->count();

        $pendingPsikolog = User::where('role', 'psikolog')
            ->where('is_admin', false)
            ->where('is_verified', false)
            ->latest()
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        session()->forget('viewing_as_user');

        return view('admin_dashboard', compact(
            'totalUsers',
            'totalPsikolog',
            'totalAnonim',
            'pendingCount',
            'pendingPsikolog',
            'recentUsers'
        ));
    }

    public function viewAsUser()
    {
        session(['viewing_as_user' => true]);
        return redirect('/home');
    }

    public function exitView()
    {
        session()->forget('viewing_as_user');
        return redirect('/admin/dashboard')->with('success', 'Anda kembali ke Admin Dashboard');
    }

    public function verifications()
    {
        $pendingCount = User::where('role', 'psikolog')
            ->where('is_admin', false)
            ->where('is_verified', false)
            ->count();

        $pendingPsikolog = User::where('role', 'psikolog')
            ->where('is_admin', false)
            ->where('is_verified', false)
            ->latest()
            ->get();

        $verifiedPsikolog = User::where('role', 'psikolog')
            ->where('is_admin', false)
            ->where('is_verified', true)
            ->latest()
            ->take(10)
            ->get();

        return view('admin_verifications', compact(
            'pendingCount',
            'pendingPsikolog',
            'verifiedPsikolog'
        ));
    }

    // ✅ APPROVE
    public function verify($id)
    {
        $user = User::findOrFail($id);

        if ($user->role != 'psikolog') {
            return back()->with('error', 'User ini bukan psikolog');
        }

        $user->update([
            'is_verified' => true,
            'verification_status' => 'approved',
            'rejection_reason' => null
        ]);

        return back()->with('success', 'Psikolog ' . $user->name . ' berhasil diverifikasi!');
    }

    // ❌ REJECT (SUDAH DIPERBAIKI)
    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role != 'psikolog') {
            return back()->with('error', 'User ini bukan psikolog');
        }

        $user->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
            'rejection_reason' => $request->reason ?? 'STR tidak lengkap atau tidak sesuai'
        ]);

        return back()->with('success', 'Psikolog ' . $user->name . ' ditolak.');
    }

    public function users()
    {
        $users = User::latest()->get();
        $me = User::find(session('user_id'));

        return view('admin_users', compact('users', 'me'));
    }

    public function toggleAdmin(Request $request, $id)
    {
        $meId = session('user_id');
        $u = User::find($id);

        if (!$u) {
            return redirect()->back()->withErrors(['user' => 'User not found']);
        }

        if ($u->id == $meId && $u->is_admin && $request->input('allow_self_demote') !== '1') {
            return back()->withErrors(['user' => 'Cannot demote yourself from admin.']);
        }

        $u->update(['is_admin' => !$u->is_admin]);

        return redirect()->route('admin.users')->with('success', 'Updated user admin status.');
    }

    public function suspend(Request $request, $id)
    {
        $u = User::find($id);

        if (!$u) {
            return redirect()->route('admin.users')->withErrors(['user' => 'User not found']);
        }

        $action = $request->input('action', 'suspend');

        if ($action === 'suspend') {
            $reason = (string) $request->input('reason');
            $u->update([
                'is_suspended' => true,
                'suspended_reason' => $reason
            ]);
        } else {
            $u->update([
                'is_suspended' => false,
                'suspended_reason' => null
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User suspension status updated.');
    }

    public function message(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $meId = session('user_id');
        $u = User::find($id);

        if (!$u) {
            return redirect()->route('admin.users')->withErrors(['user' => 'User not found']);
        }

        $clean = trim((string) $request->body);
        $clean = preg_replace("/(\r?\n){3,}/", "\n\n", $clean);

        $message = Message::create([
            'sender_id' => $meId,
            'recipient_id' => $id,
            'body' => $clean,
        ]);

        try {
            event(new \App\Events\MessageSent($message));
        } catch (\Throwable $e) {
            logger()->warning('Broadcast failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.users')->with('success', 'Message sent to user.');
    }
}