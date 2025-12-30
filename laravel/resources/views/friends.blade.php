<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Friends</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Pink theme to match provided design */
        body{background:#FFF8EE;margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}
        .container { max-width: 1100px; margin: 28px auto; padding: 12px 20px; box-sizing:border-box }
        h1 { display: flex; align-items: center; gap: 10px; font-size: 1.35rem; margin-bottom: 18px; color:#FF6FA3 }
        h1 i{ color:#FF6FA3 }

        /* unread message indicator */
        .msg-indicator { margin-bottom: 14px }
        .msg-link { display:inline-flex;align-items:center;gap:8px;color:#6b21a8;text-decoration:none;font-weight:600 }
        .msg-link .badge { background:#FF6FA3;color:#fff;padding:4px 8px;border-radius:999px;font-size:13px;box-shadow:0 6px 18px rgba(255,111,163,0.06) }

        /* white card with soft pink shadow like attachment */
        .info-box {
            background: #fff;
            border-radius: 12px;
            padding: 24px 20px;
            margin-bottom: 20px;
            box-shadow: 0 18px 30px rgba(255,111,163,0.08);
            border: 1px solid rgba(255,111,163,0.04);
            color:#374151;
        }

        /* pink outlined back button similar to screenshot */
        .btn-home {
            background: #fff;
            color: #FF6FA3;
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            border: 1px solid rgba(255,111,163,0.25);
            box-shadow: 0 6px 18px rgba(255,111,163,0.06);
        }
        .btn-home:hover{ background: #fff7fb; color:#e85a9f }

        /* responsive tweaks */
        @media (max-width:600px){
            .container{padding:16px}
            h1{font-size:1.1rem}
            .info-box{padding:20px}
            .btn-home{padding:8px 14px}
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user-friends"></i> Friends</h1>
        <div class="info-box">No friends yet</div>

        {{-- Unread messages indicator (Blade + Laravel query). Adds only new code. --}}
        @php
            $unreadCount = 0;
            if (session('user_id')) {
                try {
                    $unreadCount = \App\Models\Message::where('recipient_id', session('user_id'))
                        ->where('is_read', false)
                        ->count();
                } catch (\Throwable $e) {
                    $unreadCount = 0; // fail-safe if model/table missing
                }
            }
        @endphp

        @if($unreadCount > 0)
            <div class="msg-indicator">
                <a href="{{ url('/messages') }}" class="msg-link" aria-label="Lihat pesan baru">
                    <i class="fas fa-envelope"></i>
                    Pesan baru
                    <span class="badge">{{ $unreadCount }}</span>
                </a>
            </div>
        @endif
        <a href="{{ route('home') }}" class="btn-home">Back to Home</a>
    </div>
</body>
</html>
