@php /** Simple posts list */ @endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Posts</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;margin:20px;background:#f8f6f0}
        .post{background:white;padding:12px;border-radius:8px;margin-bottom:12px;display:flex;gap:12px;align-items:center}
        .thumb{max-width:160px;max-height:120px;object-fit:cover;border-radius:6px}
        .caption{color:#333}
        a{color:inherit;text-decoration:none}
    </style>
</head>
<body>
    <h1>Postingan</h1>
    <p><a href="/posting">Buat posting baru</a></p>

    @if($posts->isEmpty())
        <p>Tidak ada postingan.</p>
    @endif

    @foreach($posts as $post)
        <div class="post">
            <a href="{{ route('posts.show', ['id' => $post->id]) }}">
                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" class="thumb" alt="post image">
                @else
                    <div style="width:160px;height:120px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#999">No image</div>
                @endif
            </a>
            <div>
                <a href="{{ route('posts.show', ['id' => $post->id]) }}" style="font-weight:600">{{ \Illuminate\Support\Str::limit($post->caption ?? '—', 80) }}</a>
                <div class="small">oleh user #{{ $post->user_id }} · {{ $post->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @endforeach

</body>
</html>
