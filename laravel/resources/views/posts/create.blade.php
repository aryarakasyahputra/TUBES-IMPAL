<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Posting - Curhatin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background:#FFF8EE; margin:0; padding:40px; }
        .card { max-width:900px;margin:0 auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        textarea{width:100%;min-height:160px;padding:12px;border-radius:8px;border:1px solid #eee;font-size:15px}
        .btn{background:#FF6FA3;color:#fff;border:none;padding:10px 16px;border-radius:8px;font-weight:700;cursor:pointer}
        .back{display:inline-block;margin-bottom:12px;color:#666;text-decoration:none}
        .info{background:#FFF9E6;padding:12px;border-radius:8px;border:1px solid #FFD966;color:#666}
    </style>
</head>
<body>
    <div class="card">
        <a class="back" href="{{ url('/home') }}">← Kembali ke Home</a>

        @if($me->role === 'anonim')
            <div class="info">Sebagai pengguna anonim, Anda tidak dapat membuat posting.</div>
        @else
            @if($errors->any())
                <div style="color:red;margin-bottom:12px">{{ $errors->first() }}</div>
            @endif
            @if(session('success'))
                <div style="color:green;margin-bottom:12px">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div style="border:3px dashed #e8eef2;border-radius:12px;padding:18px;background:#fff;display:flex;align-items:center;gap:12px">
                    <input type="file" id="postAttachment" name="attachment" accept="image/jpeg,image/png" style="display:none">
                    <button type="button" id="postChoose" style="width:44px;height:44px;border-radius:50%;border:none;background:#fff;box-shadow:0 6px 18px rgba(0,0,0,0.06);cursor:pointer">➕</button>

                    <div style="flex:1">
                        <textarea id="postBody" name="body" maxlength="200" placeholder="Bagikan apa yang kamu rasakan..." style="width:100%;min-height:80px;padding:12px;border-radius:8px;border:1px solid #eee;">{{ old('body') }}</textarea>
                        <div style="margin-top:6px;font-size:13px;color:#666">(Opsional) Anda tidak perlu mengunggah foto — cukup tuliskan isi posting jika ingin tanpa gambar.</div>
                        <div id="postPreview" style="margin-top:8px;display:none"><img id="postPreviewImg" src="" style="max-height:180px;border-radius:8px;display:block"></div>
                    </div>

                    <div style="text-align:right;min-width:120px">
                        <div id="postCounter" style="font-weight:600;margin-bottom:8px">0/200</div>
                        <button class="btn" type="submit">Post</button>
                    </div>
                </div>
            </form>

            <script>
            (function(){
                const choose = document.getElementById('postChoose');
                const input = document.getElementById('postAttachment');
                const preview = document.getElementById('postPreview');
                const img = document.getElementById('postPreviewImg');
                const body = document.getElementById('postBody');
                const counter = document.getElementById('postCounter');

                choose.addEventListener('click', () => input.click());
                input.addEventListener('change', () => {
                    const f = input.files && input.files[0];
                    if (!f) { preview.style.display='none'; img.src=''; return; }
                    if (!f.type.startsWith('image/')) { alert('Hanya gambar (jpg/png) yang diperbolehkan.'); input.value=''; return; }
                    if (f.size > 4*1024*1024) { alert('File terlalu besar (maks 4MB)'); input.value=''; return; }
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; preview.style.display='block'; };
                    reader.readAsDataURL(f);
                });
                const update = () => counter.textContent = (body.value.length) + '/200';
                body.addEventListener('input', update); update();
            })();
            </script>
        @endif
    </div>
</body>
</html>
