<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversation with {{ $friend->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#f1f5f9;margin:0;padding:20px;color:#0f172a}
        .box{max-width:900px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
        .header{display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fff;border-radius:14px;box-shadow:0 8px 18px rgba(15,23,42,0.08)}
        .header-title{font-weight:700;font-size:18px}
        .chat-card{background:#e2e8f0;border-radius:18px;box-shadow:0 10px 24px rgba(15,23,42,0.1);overflow:hidden;display:flex;flex-direction:column;min-height:60vh}
        .messages{padding:18px;display:flex;flex-direction:column;gap:12px;overflow-y:auto;max-height:60vh}
        .row{display:flex}
        .row.me{justify-content:flex-end}
        .row.other{justify-content:flex-start}
        .bubble{max-width:70%;padding:12px 14px;border-radius:16px;line-height:1.5;white-space:pre-wrap;word-break:break-word;box-shadow:0 2px 8px rgba(0,0,0,0.08);position:relative}
        .bubble.me{background:#dcf8c6;border-bottom-right-radius:6px}
        .bubble.other{background:#fff;border-bottom-left-radius:6px}
        .meta{font-size:11px;color:#475569;margin-top:6px;text-align:right}
        .form{display:flex;gap:10px;padding:12px;background:#fff;border-top:1px solid #e2e8f0}
        textarea{flex:1;padding:12px 14px;border-radius:12px;border:1px solid #cbd5e1;resize:vertical;min-height:64px;max-height:200px;font-family:'Poppins',sans-serif}
        textarea:focus{outline:none;border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,0.18)}
        .btn{background:#22c55e;color:#fff;border:none;padding:12px 18px;border-radius:12px;cursor:pointer;font-weight:600;box-shadow:0 8px 16px rgba(34,197,94,0.35)}
        .btn:hover{background:#16a34a}
        a{color:#0f172a;text-decoration:none;font-weight:600}
    </style>
</head>
<body>
    <div class="box">
        <div class="header">
            <div>
                <div class="header-title">{{ $friend->name }} ({{ $friend->username }})</div>
                <div style="font-size:12px;color:#475569">Percakapan pribadi</div>
            </div>
        </div>

        <div class="chat-card">
            <div class="messages">
                @foreach($messages as $m)
                    @if($m->sender_id == session('user_id'))
                        <div class="row me">
                            <div class="bubble me">
                                {{ $m->body }}
                                <div class="meta">{{ $m->created_at }}</div>
                            </div>
                        </div>
                    @else
                        <div class="row other">
                            <div class="bubble other">
                                {{ $m->body }}
                                <div class="meta" style="text-align:left">{{ $m->created_at }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <form class="form" method="POST" action="{{ url('/messages/' . $friend->id) }}">
                @csrf
                <textarea name="body" rows="3" placeholder="Ketik pesan..."></textarea>
                <button class="btn" type="submit">Kirim</button>
            </form>
        </div>

        <div><a href="{{ url('/messages') }}">⬅ Kembali ke daftar teman</a></div>
    </div>
</body>
</html>