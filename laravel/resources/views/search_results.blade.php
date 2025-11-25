<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#FFF8EE;margin:0;padding:20px}
        .box{max-width:900px;margin:0 auto}
        .user{background:#fff;border-radius:10px;padding:12px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center}
        .btn{background:#FF6FA3;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}
        .info{color:#666;margin-bottom:12px}
    </style>
</head>
<body>
    <div class="box">
        <h2>Hasil pencarian untuk "{{ $query }}"</h2>
        <div class="info">{{ $results->count() }} hasil</div>
        @foreach($results as $user)
            <div class="user">
                <div>
                    <div style="font-weight:600">{{ $user->name }} ({{ $user->username }})</div>
                    <div style="font-size:13px;color:#777">{{ $user->email }}</div>
                </div>
                <div>
                    <form method="POST" action="{{ url('/friend/' . $user->id) }}">
                        @csrf
                        <button class="btn" type="submit">Add Friend</button>
                    </form>
                </div>
            </div>
        @endforeach
        <a href="{{ url('/home') }}">Back to Home</a>
    </div>
</body>
</html>