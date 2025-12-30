@php /** Post detail view */ @endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Post #{{ $post->id }}</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;margin:20px;background:#f8f6f0}
        .card{background:white;padding:16px;border-radius:8px;max-width:760px}
        .image{max-width:100%;max-height:600px;object-fit:contain;border-radius:6px}
    </style>
</head>
<body>
    <p><a href="{{ route('posts.index') }}">← Kembali ke daftar posting</a></p>
    <div class="card">
        @if($post->image_path)
            <img src="{{ asset('storage/' . $post->image_path) }}" class="image" alt="post image">
        @endif

        <h3>Caption</h3>
        <p>{{ $post->caption ?? '—' }}</p>

        <div class="small">ID: {{ $post->id }} · oleh user #{{ $post->user_id }} · {{ $post->created_at->toDayDateTimeString() }}</div>
    </div>
</body>
</html>
