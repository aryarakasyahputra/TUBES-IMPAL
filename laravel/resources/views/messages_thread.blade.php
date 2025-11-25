<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversation with {{ $friend->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#FFF8EE;margin:0;padding:20px}
        .box{max-width:900px;margin:0 auto}
        .msg{background:#fff;border-radius:10px;padding:12px;margin-bottom:10px;max-width:70%}
        .me{background:#FFEBF2;margin-left:auto}
        .other{background:#F7F7F7}
        .form{display:flex;gap:8px;margin-top:12px}
        textarea{flex:1;padding:10px;border-radius:8px;border:1px solid #ddd}
        .btn{background:#FF6FA3;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}
    </style>
</head>
<body>
    <div class="box">
        <h2>Conversation with {{ $friend->name }} ({{ $friend->username }})</h2>
        <div>
            @foreach($messages as $m)
                @if($m->sender_id == session('user_id'))
                    <div class="msg me">{{ $m->body }}<div style="font-size:11px;color:#999;margin-top:6px">{{ $m->created_at }}</div></div>
                @else
                    <div class="msg other">{{ $m->body }}<div style="font-size:11px;color:#999;margin-top:6px">{{ $m->created_at }}</div></div>
                @endif
            @endforeach
        </div>
        <form class="form" method="POST" action="{{ url('/messages/' . $friend->id) }}">
            @csrf
            <textarea name="body" rows="3" placeholder="Type your message..."></textarea>
            <button class="btn" type="submit">Send</button>
        </form>
        <div style="margin-top:12px"><a href="{{ url('/messages') }}">Back to Friends</a></div>
    </div>
</body>
</html>