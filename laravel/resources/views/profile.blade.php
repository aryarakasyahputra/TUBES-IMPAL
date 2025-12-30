<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil - {{ $user->name ?? $user->username }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#f1f5f9;margin:0;padding:20px;color:#0f172a}
        .container{max-width:900px;margin:0 auto}
        .profile-card{background:#fff;padding:22px;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,0.06)}
        .profile-top{display:flex;align-items:center;gap:18px}
        .profile-photo{width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid #FFE4F0}
        .profile-info{flex:1}
        .profile-name{font-size:20px;font-weight:700;margin:0;color:#222}
        .profile-username{color:#656f7a;font-size:14px;margin-top:4px}
        .actions{display:flex;gap:12px;margin-top:16px}
        .btn{padding:10px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:600}
        .btn-primary{background:linear-gradient(135deg,#F78BB8,#F0679F);color:#fff}
        .btn-outline-danger{background:#fff;color:#b91c1c;border:1px solid rgba(185,28,28,0.12);padding:10px 14px;border-radius:10px;display:inline-flex;align-items:center;gap:8px}
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-top">
                <img class="profile-photo" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" alt="Profile Photo">
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <div class="profile-username">@{{ $user->username }}</div>

                    <div class="actions">
                        <a class="btn btn-primary" href="{{ url('/messages/' . $user->id) }}">Kirim Pesan</a>

                        {{-- TOMBOL BLOKIR: hanya tampil bila bukan profil sendiri --}}
                        @if(isset($user) && $user->id !== auth()->id())
                            <a href="#" class="btn btn-outline-danger" onclick="return confirm('Blokir pengguna ini?')">
                                <i class="fas fa-ban" aria-hidden="true"></i>
                                Blokir
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- tambahan: informasi ringkas --}}
            @if(!empty($user->bio))
                <div style="margin-top:18px;color:#475569">{{ $user->bio }}</div>
            @endif
        </div>
    </div>
</body>
</html>
