<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curhatin - Curhat Online Sekarang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --pink-50:  #fff0f6;
            --pink-100: #ffd6e8;
            --pink-200: #ffadd2;
            --pink-400: #f06595;
            --pink-500: #e64980;
            --pink-600: #c2255c;
            --pink-700: #a61e4d;
            --pink-900: #4b0c24;
        }

        html, body {
            width: 100%;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--pink-50);
            overflow-x: hidden;
        }

        /* NAV */
        .nav {
            width: 100%;
            height: 68px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--pink-100);
            display: flex;
            align-items: center;
            padding: 0 48px;
            gap: 14px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--pink-500);
            display: flex; align-items: center; justify-content: center;
        }
        .nav-logo svg { width: 20px; height: 20px; fill: #fff; }

        .nav-brand {
            font-size: 20px; font-weight: 800;
            color: var(--pink-600); letter-spacing: -0.5px;
        }

        .nav-tagline {
            margin-left: auto;
            font-size: 13px; font-weight: 500;
            color: var(--pink-500);
            background: var(--pink-50);
            border: 1px solid var(--pink-200);
            padding: 5px 16px;
            border-radius: 100px;
        }

        /* HERO */
        .hero {
            min-height: calc(100vh - 68px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
            background:
                radial-gradient(ellipse 70% 55% at 5% 95%, rgba(255,173,210,0.4) 0%, transparent 60%),
                radial-gradient(ellipse 55% 45% at 95% 5%, rgba(230,73,128,0.2) 0%, transparent 55%),
                var(--pink-50);
        }

        .hero-inner {
            width: 100%;
            max-width: 1060px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 52px;
            align-items: center;
        }

        /* LEFT */
        .hero-left { display: flex; flex-direction: column; gap: 24px; }

        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff;
            border: 1px solid var(--pink-200);
            border-radius: 100px;
            padding: 6px 16px 6px 10px;
            width: fit-content;
        }
        .badge-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #2ecc71;
            box-shadow: 0 0 0 3px rgba(46,204,113,0.2);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 3px rgba(46,204,113,0.2); }
            50%      { box-shadow: 0 0 0 6px rgba(46,204,113,0.08); }
        }
        .badge span { font-size: 12px; font-weight: 600; color: var(--pink-700); }

        .hero-heading {
            font-size: clamp(34px, 4.5vw, 56px);
            font-weight: 800;
            color: var(--pink-900);
            line-height: 1.18;
            letter-spacing: -1.5px;
        }
        .hero-heading em { font-style: normal; color: var(--pink-500); }

        .hero-sub {
            font-size: 16px;
            color: var(--pink-600);
            line-height: 1.75;
            max-width: 440px;
        }

        .chips { display: flex; gap: 10px; flex-wrap: wrap; }
        .chip {
            display: flex; align-items: center; gap: 6px;
            background: #fff;
            border: 1px solid var(--pink-100);
            border-radius: 100px;
            padding: 6px 14px;
            font-size: 12px; font-weight: 600;
            color: var(--pink-700);
        }
        .chip svg { width: 13px; height: 13px; fill: var(--pink-500); flex-shrink: 0; }

        /* RIGHT CARD */
        .auth-card {
            background: #fff;
            border: 1px solid var(--pink-100);
            border-radius: 28px;
            padding: 44px 40px;
            box-shadow: 0 24px 64px rgba(198,37,92,0.12), 0 4px 16px rgba(198,37,92,0.06);
            display: flex;
            flex-direction: column;
            gap: 14px;
            animation: float 5s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }

        .card-eyebrow {
            font-size: 11px; font-weight: 700;
            letter-spacing: 1.2px;
            color: var(--pink-400);
            text-transform: uppercase;
        }
        .card-title {
            font-size: 24px; font-weight: 800;
            color: var(--pink-900);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .card-desc {
            font-size: 14px; color: var(--pink-400); line-height: 1.6;
            margin-bottom: 4px;
        }

        /* BUTTONS */
        .btn-login {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 16px 24px;
            background: var(--pink-500); color: #fff;
            border: none; border-radius: 14px;
            font-size: 16px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none; cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-login:hover { background: var(--pink-600); transform: translateY(-2px); }
        .btn-login:active { transform: scale(0.98); }
        .btn-login svg { width: 18px; height: 18px; fill: #fff; flex-shrink: 0; }

        .btn-register {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 15px 24px;
            background: #fff; color: var(--pink-600);
            border: 2px solid var(--pink-200); border-radius: 14px;
            font-size: 16px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s, transform 0.15s;
        }
        .btn-register:hover { border-color: var(--pink-500); background: var(--pink-50); transform: translateY(-2px); }
        .btn-register:active { transform: scale(0.98); }
        .btn-register svg { width: 18px; height: 18px; fill: var(--pink-500); flex-shrink: 0; }

        .btn-dashboard {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 16px 24px;
            background: var(--pink-500); color: #fff;
            border: none; border-radius: 14px;
            font-size: 16px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-dashboard:hover { background: var(--pink-600); }
        .btn-dashboard svg { width: 18px; height: 18px; fill: #fff; }

        .divider {
            display: flex; align-items: center; gap: 10px;
            color: var(--pink-200); font-size: 12px; font-weight: 500;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: var(--pink-100);
        }

        .card-note {
            font-size: 12px; color: var(--pink-400);
            text-align: center; line-height: 1.6;
        }

        /* RESPONSIVE */
        @media (max-width: 860px) {
            .hero-inner { grid-template-columns: 1fr; max-width: 480px; }
            .hero-left { text-align: center; align-items: center; }
            .hero-sub { text-align: center; }
            @keyframes float { 0%,100% { transform: none; } }
        }
        @media (max-width: 480px) {
            .nav { padding: 0 20px; }
            .nav-tagline { display: none; }
            .auth-card { padding: 32px 24px; border-radius: 20px; }
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav class="nav">
        <div class="nav-logo">
            <svg viewBox="0 0 24 24"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
        </div>
        <span class="nav-brand">Curhatin</span>
        <span class="nav-tagline">Curhat Online Sekarang</span>
    </nav>

    <!-- HERO -->
    <main class="hero">
        <div class="hero-inner">

            <!-- LEFT -->
            <div class="hero-left">
                <div class="badge">
                    <div class="badge-dot"></div>
                    <span>Online &amp; siap mendengarkan</span>
                </div>

                <h1 class="hero-heading">
                    Kamu nggak<br>
                    sendirian koq,<br>
                    <em>curhat di sini.</em>
                </h1>

                <p class="hero-sub">
                    Ruang aman untuk berbagi perasaan tanpa judgement. Sepenuhnya gratis dan anonim.
                </p>

                <div class="chips">
                    <div class="chip">
                        <svg viewBox="0 0 24 24"><path d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zm-1 6h2v2h-2V7zm0 4h2v6h-2v-6z"/></svg>
                        Anonim
                    </div>
                    <div class="chip">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        Gratis
                    </div>
                    <div class="chip">
                        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        Privat
                    </div>
                    <div class="chip">
                        <svg viewBox="0 0 24 24"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
                        No Judgement
                    </div>
                </div>
            </div>

            <!-- RIGHT — hanya tombol, tanpa form input -->
            <div class="auth-card">
                @if (Route::has('login'))
                    @auth
                        <div class="card-eyebrow">Selamat datang kembali</div>
                        <div class="card-title">Yuk lanjut curhat!</div>
                        <div class="card-desc">Kamu sudah login. Dashboard sudah menunggumu.</div>
                        <a href="{{ url('/dashboard') }}" class="btn-dashboard">
                            <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            Buka Dashboard
                        </a>
                    @else
                        <div class="card-eyebrow">Mulai sekarang</div>
                        <div class="card-title">Kamu tidak sendirian.</div>
                        <div class="card-desc">Bergabung dan mulai curhat secara gratis. Aman dan anonim.</div>

                        <a href="{{ route('login') }}" class="btn-login">
                            <svg viewBox="0 0 24 24"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
                            Masuk
                        </a>

                        <div class="divider">atau</div>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-register">
                                <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Daftar Gratis
                            </a>
                        @else
                            <a href="{{ url('/register') }}" class="btn-register">
                                <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Daftar Gratis
                            </a>
                        @endif

                        <p class="card-note">Dengan mendaftar, kamu menyetujui syarat &amp; ketentuan Curhatin.</p>
                    @endauth
                @else
                    <div class="card-eyebrow">Mulai sekarang</div>
                    <div class="card-title">Kamu tidak sendirian.</div>
                    <div class="card-desc">Bergabung dan mulai curhat secara gratis.</div>
                    <a href="{{ url('/login') }}" class="btn-login">
                        <svg viewBox="0 0 24 24"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
                        Masuk
                    </a>
                    <div class="divider">atau</div>
                    <a href="{{ url('/register') }}" class="btn-register">
                        <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Daftar Gratis
                    </a>
                @endif
            </div>

        </div>
    </main>

</body>
</html>