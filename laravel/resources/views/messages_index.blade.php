<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#FFF8EE;margin:0;padding:20px}
        .box{max-width:900px;margin:0 auto}
        .friend{background:#fff;border-radius:10px;padding:12px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center}
        .btn{background:#FF6FA3;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}
    </style>
</head>
<body>
    <div class="box">
        <h2>Friends</h2>
        @if($friends->isEmpty())
            <div>No friends yet</div>
        @endif
        @foreach($friends as $f)
            <div class="friend">
                <div>
                    <div style="font-weight:600">{{ $f->name }} ({{ $f->username }})</div>
                    <div style="font-size:13px;color:#777">{{ $f->email }}</div>
                </div>
                <div>
                    <a class="btn" href="{{ url('/messages/' . $f->id) }}">✉️ Send Message</a>
                </div>
            </div>
        @endforeach
        <a href="{{ url('/home') }}">Back to Home</a>
    </div>
</body>
</html>