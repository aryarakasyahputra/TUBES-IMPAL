<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emails</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#FFF8EE;margin:0;padding:24px;color:#2a1f25}
        .box{max-width:980px;margin:0 auto}
        .header{display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:16px;gap:10px}
        .title{font-weight:700;font-size:20px;color:#111}
        /* Neat Back to Home button */
        .btn-home{background:#fff;color:#FF6FA3;padding:8px 14px;border-radius:12px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-weight:600;border:1px solid rgba(255,111,163,0.25);box-shadow:0 6px 18px rgba(255,111,163,0.06)}
        .btn-home:hover{background:#fff7fb;color:#e85a9f}
        .btn-home .icon{font-size:16px;line-height:1;display:inline-flex;align-items:center;justify-content:center}
        .list{background:#fff;border-radius:16px;box-shadow:0 10px 24px rgba(240,103,159,.12);padding:8px}
        .email{background:#fff;border-radius:12px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;transition:background .2s ease}
        .email + .email{border-top:1px solid #f1f5f9}
        .email:hover{background:#fff6fb}
        .meta{font-size:13px;color:#6b7280}
        .subject{font-weight:600}
        .empty{padding:18px;color:#6b7280}
        a{color:#F0679F;text-decoration:none}
    </style>
</head>
<body>
    <div class="box">
        <div class="header">
            <div class="title">Inbox</div>
            <a class="btn-home" href="{{ url('/home') }}" aria-label="Back to Home"><span class="icon" aria-hidden="true">↩</span><span>Back to Home</span></a>
        </div>
        <div class="list">
            @if($emails->isEmpty())
                <div class="empty">No emails</div>
            @endif
            @foreach($emails as $e)
                <div class="email">
                    <div>
                        <div class="subject">{{ $e->subject ?? '(No subject)' }}</div>
                        <div class="meta">From: {{ optional($e->fromUser)->name ?? 'System' }} · {{ $e->created_at->diffForHumans() }}</div>
                    </div>
                    <div>
                        <a class="btn" href="#" style="background:linear-gradient(135deg,#F78BB8,#F0679F);color:#fff;padding:8px 12px;border-radius:10px;text-decoration:none;">Open</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
