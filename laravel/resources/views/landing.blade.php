<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Curhatin — Jurnal Emosi Digital</title>
    <style>
        :root{--pink:#FF6FA3;--bg:#FFF8EE;--muted:#6b7280}
        html,body{height:100%;margin:0;font-family:Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif;background:var(--bg);color:#1f1b20}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px}
        .card{width:100%;max-width:980px;background:#fff;border-radius:16px;padding:48px;box-shadow:0 18px 40px rgba(15,23,42,0.06);display:flex;gap:32px;align-items:center}
        .hero{flex:1}
        .brand{font-size:40px;font-weight:800;color:var(--pink);margin:0 0 8px}
        .tag{font-size:18px;color:#333;margin:0 0 18px}
        .desc{color:var(--muted);font-size:15px;line-height:1.6;margin-bottom:26px}
        .actions{display:flex;gap:12px;flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 18px;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;border:0;text-decoration:none}
        .btn-primary{background:linear-gradient(135deg,var(--pink) 0%,#E85A9F 100%);color:#fff;box-shadow:0 10px 24px rgba(255,111,163,0.12)}
        .btn-outline{background:#fff;color:var(--pink);border:1px solid rgba(255,111,163,0.18)}
        .aside{width:320px;flex:0 0 320px}
        .card.small{padding:20px}

        /* Responsive */
        @media (max-width:880px){
            .card{flex-direction:column;align-items:flex-start;padding:28px}
            .aside{width:100%;order:2}
            .hero{order:1;width:100%}
            .brand{font-size:32px}
            .tag{font-size:16px}
            .actions{width:100%}
            .btn{flex:1;min-width:140px}
        }

        /* Accessibility focus */
        .btn:focus{outline:3px solid rgba(255,111,163,0.18);outline-offset:3px}
    </style>
</head>
<body>
    <main class="wrap">
        <section class="card" role="main" aria-labelledby="curhatin-title">
            <div class="hero">
                <h1 id="curhatin-title" class="brand">Curhatin</h1>
                <p class="tag">Jurnal emosi digitalmu. Ekspresikan, pahami, dan tumbuh.</p>
                <p class="desc">Tempat aman untuk berbagai perasaan tanpa batas. Curhatin membantu Anda menulis, merefleksi, dan melacak perkembangan emosional dengan privasi dan keleluasaan penuh.</p>

                <div class="actions" aria-hidden="false">
                    <a href="{{ url('/register') }}" class="btn btn-primary" role="button" aria-label="Daftar ke Curhatin">Daftar</a>
                    <a href="{{ url('/login') }}" class="btn btn-outline" role="button" aria-label="Masuk ke Curhatin">Masuk</a>
                </div>
            </div>

            <aside class="aside card small" aria-hidden="true">
                <h3 style="margin:0 0 8px;color:var(--pink);">Mengapa Curhatin?</h3>
                <ul style="margin:0;padding-left:18px;color:var(--muted);line-height:1.6">
                    <li>Privasi penuh untuk catatan pribadi</li>
                    <li>Tracking emosi dengan cara yang mudah</li>
                    <li>Ringan dan fokus pada tulisan</li>
                </ul>
            </aside>
        </section>
    </main>
</body>
</html>
