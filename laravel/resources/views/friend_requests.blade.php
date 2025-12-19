<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Friend Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#FFF8EE;margin:0;padding:20px}
        .box{max-width:900px;margin:0 auto}
        .req{background:#fff;border-radius:10px;padding:12px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center}
        .btn{background:#FF6FA3;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer;margin-left:6px}
        .actions{display:flex}
    </style>
</head>
<body>
    <div class="box">
        <h2>Incoming Friend Requests</h2>
        @if($requests->isEmpty())
            <div>No requests</div>
        @endif
        @foreach($requests as $r)
            <div class="req">
                <div>
                    @php
                        // Support both legacy Friendship objects (with requester) and new Email objects (with fromUser)
                        $from = $r->requester ?? $r->fromUser ?? null;
                    @endphp
                    <div style="font-weight:600">{{ $from->name ?? ($from->username ?? 'Unknown') }} ({{ $from->username ?? '' }})</div>
                    <div style="font-size:13px;color:#777">{{ $from->email ?? '' }}</div>
                    <div style="margin-top:8px;color:#444;font-size:14px">{{ $r->subject ?? ($r->requester_message ?? '') }}</div>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ url('/friend/' . $r->id . '/accept') }}">
                        @csrf
                        <button class="btn" type="submit">Accept</button>
                    </form>
                    <form method="POST" action="{{ url('/friend/' . $r->id . '/reject') }}">
                        @csrf
                        <button class="btn" style="background:#ccc;color:#222" type="submit">Reject</button>
                    </form>
                </div>
            </div>
        @endforeach
        <a href="{{ url('/home') }}">Back to Home</a>
    </div>
</body>
</html>
