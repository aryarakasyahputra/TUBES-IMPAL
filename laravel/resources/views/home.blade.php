<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curhatin - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { background: #FFF8EE; font-family: 'Poppins', sans-serif; margin: 0; }
        .sidebar { width: 220px; background: #F5F5F5; height: 100vh; position: fixed; left: 0; top: 0; display: flex; flex-direction: column; align-items: center; padding-top: 32px; }
        .sidebar .user { margin-bottom: 32px; text-align: center; }
        .sidebar .user img { width: 60px; border-radius: 50%; margin-bottom: 8px; }
        .sidebar .user span { display: block; font-weight: 600; }
        .sidebar nav { width: 100%; }
        .sidebar nav a { display: flex; align-items: center; padding: 12px 32px; color: #444; text-decoration: none; font-size: 16px; margin-bottom: 8px; border-radius: 8px; transition: background .2s; }
        .sidebar nav a:hover { background: #FFEBF2; color: #BE5985; }
        .main { margin-left: 220px; padding: 32px 40px; }
        .search { width: 100%; max-width: 400px; margin-bottom: 24px; }
        .search input { width: 100%; padding: 10px 16px; border-radius: 20px; border: 1px solid #DDD; font-size: 15px; }
        .feed { background: #fff; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px; }
        .post { background: #FDF9F0; border-radius: 14px; padding: 18px 22px; margin-bottom: 18px; }
        .post .author { font-weight: 700; color: #BE5985; margin-bottom: 6px; }
        .post img { max-width: 180px; border-radius: 10px; margin-top: 10px; }
        .logout-btn { margin-top: 32px; background: #FF6FA3; color: #fff; border: none; border-radius: 20px; padding: 10px 24px; font-weight: 600; cursor: pointer; }
        @media (max-width: 900px) { .sidebar { width: 100px; } .main { margin-left: 100px; padding: 16px; } }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="user">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="User">
            <span>USER</span>
        </div>
        <nav>
            <a href="{{ url('/home') }}"><span>🏠</span>&nbsp; Home</a>
            <a href="{{ url('/messages') }}"><span>💬</span>&nbsp; Masagge</a>
            <a href="#"><span>📝</span>&nbsp; Posting</a>
            <a href="#"><span>✉️</span>&nbsp; Email</a>
        </nav>
        <form method="POST" action="/logout">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>
    <div class="main">
        <form class="search" method="GET" action="{{ url('/search') }}">
            <input type="text" name="q" placeholder="search user" value="{{ request('q') ?? '' }}">
        </form>
        @if(session('success'))
            <div style="color:green; margin-bottom:12px;">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div style="color:blue; margin-bottom:12px;">{{ session('info') }}</div>
        @endif
        @if(isset($me) && $me->is_suspended)
            <div style="padding:12px;border-radius:8px;background:#ffecec;color:#7a1414;margin-bottom:12px;">
                <strong>Akun Anda saat ini disuspend oleh admin.</strong>
                <div style="margin-top:6px">Alasan: {{ $me->suspended_reason ?? 'Tidak ada keterangan' }}</div>
                <div style="margin-top:8px;color:#333;font-size:13px">Jika Anda merasa ini keliru silakan hubungi admin.</div>
            </div>
        @endif
        <div class="feed">
            <div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
                <a href="{{ url('/friend-requests') }}" style="background:#FF6FA3;color:#fff;padding:8px 12px;border-radius:8px;text-decoration:none">Friend Requests</a>
            </div>
            
        @if(session('user_role') == 'anonim')
                <div style="background:#FFF9E6;padding:16px;border-radius:12px;border:2px solid #FFD966;margin-bottom:16px;">
                    <strong style="color:#996515;">ℹ️ Informasi</strong>
                    <p style="margin-top:8px;color:#666;">Sebagai pengguna anonim, Anda tidak dapat membuat posting. Anda dapat melihat posting dari pengguna lain dan mengirim pesan ke teman.</p>
                </div>
            @endif
            
            @if(session('is_admin') && session('viewing_as_user'))
                <div style="background:#D1ECF1;padding:16px;border-radius:12px;border:2px solid #17A2B8;margin-bottom:16px;">
                    <strong style="color:#0C5460;">👁️ Mode: Melihat Sebagai User</strong>
                    <p style="margin-top:8px;color:#0C5460;">Anda sedang melihat aplikasi dari perspektif user biasa. Klik tombol di bawah untuk kembali ke Admin Dashboard.</p>
                    <a href="{{ url('/admin/exit-view') }}" style="display:inline-block;margin-top:10px;background:#17A2B8;color:#fff;padding:8px 16px;border-radius:8px;text-decoration:none;font-weight:600;">← Kembali ke Admin Dashboard</a>
                </div>
            @endif
            
            <div class="post">
                <div class="author">AMANDA</div>
                <div>Semangat semuanya!!!!! buat yang mau curhat boleh request ke aku yah jangan di pendem sendiri :&gt;</div>
            </div>
            <div class="post">
                <div class="author">Melinda</div>
                <div>Aku bisa loh jadi temen curhat kamu jangan malu malu, buat request ke aku yahhh</div>
            </div>
            <div class="post">
                <div class="author">Melinda</div>
                <div>Kamu ngerasa gelisah ?? Sini curhatin ke aku aja aku bakalan jadi temen curhat kamu yang bisa jadi pendengar yang baik loh</div>
                <img src="https://img.freepik.com/free-photo/back-view-kid-walking-school-corridor_23-2149741162.jpg" alt="Curhat">
            </div>
        </div>
    </div>
</body>
</html>
