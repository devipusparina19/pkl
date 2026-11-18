<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#081b33">

    <title>Lupa Password — ARTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #081b33; --royal: #2457d6; --electric: #3478f6; --soft: #eaf2ff;
            --ice: #f3f7fc; --slate: #64748b; --ink: #14243a; --border: #e1e9f2; --cyan: #38c6e8;
            --green: #166534; --green-soft: #f0fdf4; --red: #991b1b; --red-soft: #fef2f2;
            --f-display: 'Sora', system-ui, sans-serif;
            --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
            --r: 14px; --ease: cubic-bezier(.2, .7, .3, 1);
        }
        *, *::before, *::after { box-sizing: border-box; }
        [hidden] { display: none !important; }
        body {
            min-height: 100vh; min-height: 100dvh; margin: 0; overflow-x: hidden;
            color: var(--ink); background: #fff; font-family: var(--f-body);
            font-size: 16px; line-height: 1.7; -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        button, input, textarea { font: inherit; }
        :focus-visible { outline: 3px solid rgba(52,120,246,.55); outline-offset: 3px; border-radius: 8px; }

        .fp-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); min-height: 100vh; min-height: 100dvh; }

        /* Brand */
        .fp-brand {
            position: relative; isolation: isolate; overflow: hidden;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 48px clamp(28px, 4.5vw, 72px); color: #fff;
            background:
                radial-gradient(800px 460px at 85% 10%, rgba(52,120,246,.3), transparent 60%),
                linear-gradient(160deg, #05162a 0%, #08223f 60%, #092746 100%);
        }
        .fp-brand::after {
            content: ""; position: absolute; z-index: -1; right: -180px; bottom: -180px;
            width: 520px; height: 520px; border-radius: 50%;
            border: 1px solid rgba(130,183,255,.22);
            box-shadow: 0 0 0 60px rgba(130,183,255,.04), 0 0 0 120px rgba(130,183,255,.025);
        }
        .fp-brand-head { display: flex; align-items: center; gap: 16px; }
        .fp-logo { flex: 0 0 56px; width: 56px; height: 56px; padding: 6px; background: #fff; border-radius: 16px; }
        .fp-logo img { display: block; width: 100%; height: 100%; object-fit: contain; }
        .fp-brand-head strong { display: block; font-family: var(--f-display); font-size: 20px; font-weight: 800; letter-spacing: -.03em; line-height: 1.2; }
        .fp-brand-head span { display: block; color: rgba(255,255,255,.7); font-size: 14px; }

        .fp-brand-body { margin: 48px 0; }
        .fp-brand-body h1 {
            margin: 0 0 14px; font-family: var(--f-display);
            font-size: clamp(34px, 4.4vw, 60px); font-weight: 800; letter-spacing: -.045em; line-height: 1.06;
        }
        .fp-brand-body > p { max-width: 46ch; margin: 0 0 28px; color: rgba(255,255,255,.76); line-height: 1.8; }

        .fp-steps { display: grid; gap: 14px; margin: 0; padding: 0; list-style: none; counter-reset: step; }
        .fp-steps li {
            position: relative; padding: 14px 16px 14px 62px; border: 1px solid rgba(255,255,255,.16);
            border-radius: var(--r); background: rgba(255,255,255,.06);
            color: #e4efff; font-size: 15px; line-height: 1.55; counter-increment: step;
        }
        .fp-steps li::before {
            content: counter(step); position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            width: 34px; height: 34px; display: grid; place-items: center; border-radius: 50%;
            background: var(--cyan); color: var(--navy); font-family: var(--f-display); font-weight: 800;
        }
        .fp-brand-foot { padding-top: 22px; border-top: 1px solid rgba(255,255,255,.18); color: rgba(255,255,255,.76); font-size: 14px; line-height: 1.75; }

        /* Panel form */
        .fp-panel {
            display: flex; flex-direction: column; justify-content: center;
            padding: 48px clamp(28px, 5vw, 80px);
            background: radial-gradient(600px 300px at 100% 0%, var(--soft), transparent 70%), #fff;
        }
        .fp-inner { width: 100%; max-width: 560px; margin-inline: auto; animation: rise .6s var(--ease) both; }
        @keyframes rise { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

        .fp-eyebrow { display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px; color: var(--royal); font-size: 15px; font-weight: 700; }
        .fp-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }
        .fp-inner h2 { margin: 0 0 12px; color: var(--navy); font-family: var(--f-display); font-size: clamp(30px, 3.6vw, 46px); font-weight: 800; letter-spacing: -.045em; line-height: 1.1; }
        .fp-inner h2 em { font-style: normal; color: var(--royal); }
        .fp-lead { margin: 0 0 26px; color: var(--slate); font-size: 17px; }

        .alert { margin-bottom: 20px; padding: 15px 18px; border: 1px solid transparent; border-radius: var(--r); font-size: 15px; line-height: 1.6; }
        .alert-success { border-color: #bbf7d0; background: var(--green-soft); color: var(--green); }
        .alert-error { border-color: #fecaca; background: var(--red-soft); color: var(--red); }
        .alert-success strong { display: block; margin-bottom: 4px; font-size: 16px; }
        .alert-info { border-color: #cfe0ff; background: var(--soft); color: #1e3a8a; font-size: 14px; }

        .field { margin-bottom: 20px; }
        .field label { display: block; margin-bottom: 8px; color: #40536a; font-size: 14px; font-weight: 700; }
        .field label small { color: var(--slate); font-weight: 500; }
        .ctrl {
            display: block; width: 100%; padding: 0 16px; height: 56px;
            border: 1.5px solid var(--border); border-radius: var(--r); outline: none;
            background: #fff; color: var(--ink); font-size: 16px; transition: border-color .25s, box-shadow .25s;
        }
        textarea.ctrl { height: auto; min-height: 96px; padding: 14px 16px; resize: vertical; }
        .ctrl::placeholder { color: #8998ab; }
        .ctrl:hover { border-color: #a6bddb; }
        .ctrl:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }

        .hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }

        .btn {
            display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; min-height: 58px;
            padding: 12px 22px; border: 0; border-radius: 999px; background: var(--royal); color: #fff;
            font-size: 16px; font-weight: 700; cursor: pointer; transition: background .25s, transform .25s var(--ease), box-shadow .25s;
        }
        .btn:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
        .btn:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }

        .fp-back { margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--border); text-align: center; }
        .fp-back a { display: inline-flex; padding: 8px 4px; color: var(--royal); font-size: 15px; font-weight: 700; }
        .fp-back a:hover { color: var(--navy); }

        @media (max-width: 1000px) {
            .fp-layout { grid-template-columns: 1fr; }
            .fp-brand { padding: 32px clamp(20px, 5vw, 40px) 36px; }
            .fp-brand-body { margin: 28px 0 8px; }
            .fp-brand-foot { display: none; }
            .fp-panel { padding: 40px clamp(20px, 5vw, 40px) 48px; }
            .fp-inner { max-width: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition-duration: .01ms !important; }
        }
    </style>
</head>

<body>
<main class="fp-layout">

    <section class="fp-brand">
        <header class="fp-brand-head">
            <div class="fp-logo">
                <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}" alt="Logo Provinsi Kalimantan Selatan">
            </div>
            <div>
                <strong>ARTA</strong>
                <span>Bapelkes Provinsi Kalimantan Selatan</span>
            </div>
        </header>

        <div class="fp-brand-body">
            <h1>Pemulihan akun yang aman</h1>
            <p>Reset password dilakukan melalui verifikasi oleh Super Admin, tanpa email dan tanpa tautan yang bisa dibajak.</p>

            <ol class="fp-steps">
                <li>Kirim permintaan reset dengan username dan nama lengkap Anda.</li>
                <li>Super Admin memverifikasi identitas Anda secara langsung.</li>
                <li>Anda menerima password sementara dan wajib menggantinya saat login.</li>
            </ol>
        </div>

        <div class="fp-brand-foot">
            Setiap permintaan dan persetujuan reset tercatat. Jangan membagikan password sementara kepada siapa pun.
        </div>
    </section>

    <section class="fp-panel">
        <div class="fp-inner">

            <span class="fp-eyebrow">Pemulihan Akun</span>
            <h2>Lupa <em>password?</em></h2>
            <p class="fp-lead">Ajukan reset password kepada Super Admin.</p>

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('recovery_sent'))
                <div class="alert alert-success" role="status">
                    <strong>Permintaan diterima</strong>
                    Jika data Anda sesuai, Super Admin akan memverifikasi identitas Anda lalu memberikan password sementara.
                    Silakan hubungi Super Admin atau atasan Anda bila belum ada kabar dalam 1 hari kerja.
                </div>

                <div class="fp-back">
                    <a href="{{ route('login') }}">Kembali ke halaman login</a>
                </div>
            @else
                <form id="fpForm" method="POST" action="{{ route('password.forgot.store') }}" autocomplete="off">
                    @csrf

                    <div class="field">
                        <label for="username">Username atau Email Akun</label>
                        <input class="ctrl" type="text" id="username" name="username"
                               value="{{ old('username') }}" placeholder="Contoh: admin atau admin@arta.local"
                               autocomplete="username" required maxlength="255">
                    </div>

                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <input class="ctrl" type="text" id="name" name="name"
                               value="{{ old('name') }}" placeholder="Sesuai nama pada akun"
                               autocomplete="name" required maxlength="255">
                    </div>

                    <div class="field">
                        <label for="note">Keterangan <small>(opsional, mis. nomor yang bisa dihubungi)</small></label>
                        <textarea class="ctrl" id="note" name="note" maxlength="500"
                                  placeholder="Tambahkan info yang membantu Super Admin memverifikasi Anda">{{ old('note') }}</textarea>
                    </div>

                    {{-- Honeypot: harus kosong. Disembunyikan dari pengguna sungguhan. --}}
                    <div class="hp" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="alert alert-info" role="note">
                        <strong>Akun Super Admin</strong> tidak dapat direset lewat halaman ini.
                        Hubungi pengelola server aplikasi.
                    </div>

                    <button class="btn" type="submit" id="fpSubmit">Kirim Permintaan Reset</button>
                </form>

                <div class="fp-back">
                    <a href="{{ route('login') }}">Kembali ke halaman login</a>
                </div>
            @endif

        </div>
    </section>
</main>

<script>
    (() => {
        const form = document.getElementById('fpForm');
        if (!form) return;

        const btn = document.getElementById('fpSubmit');

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.textContent = 'Mengirim...';
        });

        window.addEventListener('pageshow', () => {
            btn.disabled = false;
            btn.textContent = 'Kirim Permintaan Reset';
        });
    })();
</script>
</body>
</html>