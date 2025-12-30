<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Incoming Friend Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .info-box {
            background: #f0f5ff;
            border-radius: 12px;
            padding: 24px 20px;
            margin-bottom: 20px;
            border-left: 6px solid #2563eb;
        }
        /* copied EXACT button styles from friends.blade.php */
        .btn-home {
            background: #fff !important;
            color: #FF6FA3 !important;
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none !important;
            display: inline-block !important;
            font-weight: 600;
            border: 1px solid rgba(255,111,163,0.25) !important;
            box-shadow: 0 6px 18px rgba(255,111,163,0.06) !important;
        }
        .btn-home:hover{ background: #fff7fb !important; color:#e85a9f !important }
        .container { max-width: 720px; margin: 40px auto; padding: 0 16px; }
        /* white card/container to make content tidy */
        .card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 12px 30px rgba(15,23,42,0.06); border: 1px solid rgba(0,0,0,0.04); }
        h1 { display: flex; align-items: center; justify-content: space-between; gap: 10px; font-size: 1.5rem; margin-bottom: 16px; }
        .btn-home{background:#fff;color:#FF6FA3;padding:8px 14px;border-radius:12px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-weight:600;border:1px solid rgba(255,111,163,0.25);box-shadow:0 6px 18px rgba(255,111,163,0.06)}
        .btn-home:hover{background:#fff7fb;color:#e85a9f}
        .btn-home .icon{font-size:16px;line-height:1;display:inline-flex;align-items:center;justify-content:center}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>
                <span style="display:flex;align-items:center;gap:10px;"><i class="fas fa-user-plus"></i> Incoming Friend Requests</span>
                <a href="{{ route('home') }}" class="btn-home" aria-label="Back to Home"><span class="icon" aria-hidden="true">↩</span><span>Back to Home</span></a>
            </h1>
            <div class="info-box">No requests</div>
        </div>
    </div>
</body>
</html>
