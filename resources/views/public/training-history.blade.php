<!DOCTYPE html>
<html lang="id" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#081B33">
    <meta name="description" content="ARTA: Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan. Lihat riwayat pelatihan peserta.">

    <title>Riwayat Pelatihan — ARTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script>document.documentElement.className = 'js';</script>

    <style>
        :root {
            --navy: #081B33;
            --midnight: #0B2545;
            --royal: #2457D6;
            --electric: #3478F6;
            --soft: #EAF2FF;
            --ice: #F3F7FC;
            --slate: #64748B;
            --text: #14243A;
            --border: #E1E9F2;
            --cyan: #38C6E8;
            --ok: #0F6B4A;
            --ok-bg: #E8F6EF;
            --ok-line: #B7E2CC;
            --font-display: "Sora", "Segoe UI", Arial, sans-serif;
            --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
            --ease: cubic-bezier(.2, .7, .2, 1);
            --pad: clamp(16px, 4.2vw, 72px);
            --nav-h: 76px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.65;
            color: var(--text);
            background: var(--ice);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        main { flex: 1 0 auto; }
        a { color: inherit; }

        /* Ikon (satu sistem: gaya Lucide, sprite SVG) */
        svg.i {
            width: 1em;
            height: 1em;
            flex: none;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        :focus-visible {
            outline: 3px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 8px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
            border: 0;
        }

        .skip {
            position: absolute;
            left: 12px;
            top: -64px;
            z-index: 100;
            padding: 10px 16px;
            border-radius: 10px;
            background: #fff;
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
            transition: top .2s;
        }

        .skip:focus { top: 12px; }

        .progress {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 60;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--cyan), var(--electric));
            transform: scaleX(0);
            transform-origin: left;
            will-change: transform;
        }

        /* ---------- Navbar ---------- */
        .nav {
            position: sticky;
            top: 0;
            z-index: 50;
            transition: background .3s, box-shadow .3s;
        }

        .nav.is-scrolled {
            background: rgba(8, 27, 51, .92);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(8, 27, 51, .25);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: var(--nav-h);
            padding: 0 var(--pad);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            color: #fff;
            text-decoration: none;
        }

        .nav-logo {
            display: grid;
            place-items: center;
            flex: none;
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: #fff;
            overflow: hidden;
        }

        .nav-logo img {
            width: 100%;
            height: 100%;
            padding: 4px;
            object-fit: contain;
        }

        .nav-name {
            min-width: 0;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 20px;
            letter-spacing: .04em;
            line-height: 1.1;
        }

        .nav-sub {
            display: block;
            max-width: 340px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-family: var(--font-body);
            font-weight: 500;
            font-size: 11.5px;
            letter-spacing: 0;
            color: rgba(255, 255, 255, .75);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border-radius: 999px;
            color: rgba(255, 255, 255, .82);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s, background .2s;
        }

        .nav-link .i { font-size: 17px; }

        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .nav-link[aria-current="page"] {
            color: #fff;
            background: rgba(52, 120, 246, .35);
        }

        .nav-link[aria-current="page"]::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 3px;
            width: 18px;
            height: 3px;
            border-radius: 3px;
            background: var(--cyan);
            transform: translateX(-50%);
        }

        /* ---------- Hero ---------- */
        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            margin-top: calc(var(--nav-h) * -1);
            padding: calc(var(--nav-h) + clamp(36px, 6vw, 84px)) var(--pad) clamp(48px, 7vw, 96px);
            color: #fff;
            background: var(--navy);
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -3;
            background:
                linear-gradient(120deg, rgba(8, 27, 51, .96) 0%, rgba(11, 37, 69, .9) 55%, rgba(36, 87, 214, .55) 100%),
                url("{{ asset('images/login-bapelkes.jpg') }}") center / cover no-repeat;
        }

        /* garis grid halus */
        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background-image:
                linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
            background-size: 56px 56px;
            -webkit-mask-image: linear-gradient(180deg, transparent, #000 30%, #000 70%, transparent);
            mask-image: linear-gradient(180deg, transparent, #000 30%, #000 70%, transparent);
        }

        .orb {
            position: absolute;
            z-index: -1;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
            animation: drift 24s ease-in-out infinite alternate;
        }

        .orb.a {
            width: 400px;
            height: 400px;
            left: -110px;
            top: 30px;
            background: var(--royal);
            opacity: .5;
        }

        .orb.b {
            width: 320px;
            height: 320px;
            right: 4%;
            bottom: -70px;
            background: var(--cyan);
            opacity: .22;
            animation-duration: 30s;
        }

        @keyframes drift {
            to { transform: translate3d(50px, -36px, 0); }
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(0, .75fr);
            align-items: center;
            gap: clamp(28px, 5vw, 80px);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            max-width: 100%;
            padding: 8px 14px 8px 10px;
            margin-bottom: 22px;
            border: 1px solid rgba(105, 214, 162, .4);
            border-radius: 999px;
            background: rgba(105, 214, 162, .12);
            color: #C9F2DD;
            font-size: 13px;
            font-weight: 600;
        }

        .badge .i { font-size: 17px; color: #69D6A2; }

        .hero h1 {
            margin: 0 0 20px;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2.3rem, 6.6vw, 5.2rem);
            line-height: 1.04;
            letter-spacing: -.035em;
            overflow-wrap: anywhere;
        }

        .hero h1 em {
            display: block;
            font-style: normal;
            color: var(--cyan);
        }

        .hero-lead {
            max-width: 580px;
            margin: 0 0 28px;
            font-size: clamp(1rem, 1.5vw, 1.125rem);
            line-height: 1.8;
            color: rgba(255, 255, 255, .85);
        }

        .hero-who {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            max-width: 100%;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .08);
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .chip .i { font-size: 16px; color: var(--cyan); }
        .chip span { overflow: hidden; text-overflow: ellipsis; }

        /* Kartu total (momen utama) */
        .stat-wrap {
            position: relative;
            perspective: 900px;
        }

        .stat {
            position: relative;
            overflow: hidden;
            padding: clamp(24px, 3vw, 36px);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, .22);
            background: linear-gradient(160deg, rgba(255, 255, 255, .14), rgba(255, 255, 255, .05));
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            transform-style: preserve-3d;
            transition: transform .25s var(--ease);
            will-change: transform;
        }

        .orbit {
            position: absolute;
            right: -90px;
            top: -90px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 1.5px dashed rgba(56, 198, 232, .45);
            animation: spin 38s linear infinite;
            pointer-events: none;
        }

        .orbit.two {
            right: -50px;
            top: -50px;
            width: 220px;
            height: 220px;
            border-style: solid;
            border-color: rgba(255, 255, 255, .14);
            animation-duration: 26s;
            animation-direction: reverse;
        }

        .orbit::after {
            content: "";
            position: absolute;
            left: 50%;
            top: -6px;
            width: 12px;
            height: 12px;
            margin-left: -6px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 16px 3px rgba(56, 198, 232, .7);
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .stat-label {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, .85);
        }

        .stat-label .i { font-size: 21px; color: var(--cyan); }

        .stat-number {
            position: relative;
            display: block;
            min-width: 2ch;
            margin: 14px 0 6px;
            font-family: var(--font-display);
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            font-size: clamp(4rem, 9vw, 7rem);
            line-height: 1;
            letter-spacing: -.045em;
        }

        .stat-note {
            position: relative;
            font-size: 14px;
            color: rgba(255, 255, 255, .75);
        }

        /* ---------- Tombol ---------- */
        .btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 52px;
            padding: 12px 22px;
            border-radius: 14px;
            border: 1.5px solid transparent;
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s var(--ease), box-shadow .2s, background .2s, border-color .2s;
        }

        .btn .i { font-size: 18px; transition: transform .2s var(--ease); }

        .btn-primary {
            color: #fff;
            background: linear-gradient(110deg, var(--royal), var(--electric));
            box-shadow: 0 12px 26px rgba(36, 87, 214, .3);
        }

        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36, 87, 214, .4); }
        .btn-primary:hover .i { transform: translateX(-4px); }
        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            color: var(--royal);
            background: #fff;
            border-color: #C5D6F5;
        }

        .btn-secondary:hover { background: var(--soft); transform: translateY(-2px); }

        .btn-ghost {
            color: #fff;
            background: rgba(255, 255, 255, .08);
            border-color: rgba(255, 255, 255, .3);
        }

        .btn-ghost:hover { background: rgba(255, 255, 255, .16); transform: translateY(-2px); }
        .btn-ghost:hover .i { transform: translateY(3px); }

        .btn-print {
            color: #fff;
            background: #0F6B4A;
            border-color: #0F6B4A;
            box-shadow: 0 10px 22px rgba(15, 107, 74, .2);
        }

        .btn-print:hover { background: #0B563B; transform: translateY(-2px); }

        /* status memuat pada tombol ekspor (halaman tetap di posisi yang sama) */
        .btn .spin {
            display: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2.5px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            animation: spin .7s linear infinite;
        }

        .btn.is-loading { pointer-events: none; opacity: .92; }
        .btn.is-loading .spin { display: inline-block; }
        .btn.is-loading .i { display: none; }

        /* ---------- Konten ---------- */
        main { padding: 0 var(--pad) clamp(48px, 6vw, 80px); }

        .success {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-top: 28px;
            padding: 16px 20px;
            border: 1px solid var(--ok-line);
            border-left: 5px solid #1E9C6B;
            border-radius: 14px;
            background: var(--ok-bg);
            color: var(--ok);
        }

        .success .i { margin-top: 3px; font-size: 22px; color: #1E9C6B; }
        .success strong { display: block; font-size: 15px; }
        .success p { margin: 2px 0 0; font-size: 14px; color: #36765A; }

        .layout {
            display: grid;
            grid-template-columns: minmax(300px, 400px) minmax(0, 1fr);
            gap: clamp(28px, 4vw, 64px);
            align-items: start;
            padding-top: clamp(32px, 4.5vw, 56px);
        }

        .side {
            position: sticky;
            top: calc(var(--nav-h) + 16px);
        }

        .section-head { margin-bottom: 20px; }

        .section-head h2 {
            margin: 0 0 6px;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.5rem, 3.2vw, 2.2rem);
            letter-spacing: -.025em;
            line-height: 1.15;
            color: var(--navy);
        }

        .section-head p { margin: 0; font-size: 15px; color: var(--slate); }

        /* Profil bergaya bento */
        .bento {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin: 0;
        }

        .tile {
            min-width: 0;
            padding: 16px 18px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fff;
            transition: transform .25s var(--ease), box-shadow .25s, border-color .25s;
        }

        .tile:hover {
            transform: translateY(-3px);
            border-color: #C5D6F5;
            box-shadow: 0 14px 30px rgba(8, 27, 51, .08);
        }

        .tile.wide { grid-column: 1 / -1; }

        .tile.main {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, var(--navy), var(--royal));
        }

        .tile dt {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--slate);
        }

        .tile dt .i { font-size: 16px; color: var(--royal); }
        .tile.main dt { color: rgba(255, 255, 255, .78); }
        .tile.main dt .i { color: var(--cyan); }

        .tile dd {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            overflow-wrap: anywhere;
        }

        .tile.main dd {
            font-family: var(--font-display);
            font-size: clamp(1.25rem, 2.2vw, 1.6rem);
            line-height: 1.25;
            color: #fff;
        }

        /* Pencarian riwayat */
        .finder {
            position: relative;
            margin-bottom: 22px;
        }

        .finder .i {
            position: absolute;
            left: 16px;
            top: 50%;
            font-size: 19px;
            color: var(--slate);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .finder input {
            width: 100%;
            min-height: 52px;
            padding: 12px 16px 12px 46px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            background: #fff;
            font: inherit;
            font-size: 15px;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
        }

        .finder input::placeholder { color: #8A9AB0; }

        .finder input:focus {
            outline: none;
            border-color: var(--electric);
            box-shadow: 0 0 0 4px rgba(52, 120, 246, .15);
        }

        .count {
            display: inline-flex;
            align-items: center;
            margin-top: 12px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--soft);
            color: var(--royal);
            font-size: 13px;
            font-weight: 700;
        }

        .stat-years {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            font-size: 13.5px;
            font-weight: 600;
            color: #fff;
        }

        .stat-years .i { font-size: 16px; color: var(--cyan); }

        /* Saring per tahun */
        .years {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 0 22px;
        }

        .year-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            padding: 8px 16px;
            border: 1.5px solid var(--border);
            border-radius: 999px;
            background: #fff;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            color: #3D5069;
            cursor: pointer;
            transition: transform .2s var(--ease), background .2s, border-color .2s, color .2s;
        }

        .year-chip small {
            padding: 1px 8px;
            border-radius: 999px;
            background: var(--ice);
            font-size: 12px;
            font-weight: 700;
            color: var(--slate);
        }

        .year-chip:hover { transform: translateY(-2px); border-color: #C5D6F5; }

        .year-chip.is-active {
            border-color: transparent;
            background: linear-gradient(110deg, var(--royal), var(--electric));
            color: #fff;
        }

        .year-chip.is-active small { background: rgba(255, 255, 255, .22); color: #fff; }

        /* Linimasa riwayat */
        .timeline {
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 23px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            border-radius: 2px;
            background: linear-gradient(180deg, var(--electric), var(--cyan) 60%, var(--border));
        }

        .tl-item {
            position: relative;
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr);
            gap: 14px;
            padding-bottom: 16px;
        }

        .tl-item[hidden] { display: none; }

        .tl-node {
            position: relative;
            z-index: 1;
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid var(--ice);
            background: linear-gradient(135deg, var(--royal), var(--electric));
            color: #fff;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 15px;
            font-variant-numeric: tabular-nums;
            box-shadow: 0 8px 18px rgba(36, 87, 214, .3);
        }

        .tl-card {
            min-width: 0;
            padding: 20px 22px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #fff;
            transition: transform .25s var(--ease), box-shadow .25s, border-color .25s;
        }

        .tl-item:hover .tl-card {
            transform: translateX(4px);
            border-color: #C5D6F5;
            box-shadow: 0 16px 36px rgba(8, 27, 51, .08);
        }

        .tl-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .tl-name {
            margin: 0;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.05rem, 1.8vw, 1.3rem);
            line-height: 1.3;
            letter-spacing: -.015em;
            color: var(--navy);
            overflow-wrap: anywhere;
        }

        .tl-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 10px;
            margin: 14px 0 0;
            padding: 0;
            list-style: none;
        }

        .tl-meta li {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            max-width: 100%;
            padding: 6px 12px;
            border-radius: 10px;
            background: var(--ice);
            font-size: 13.5px;
            font-weight: 600;
            color: #3D5069;
        }

        .tl-meta .i { font-size: 15px; color: var(--royal); }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex: none;
            padding: 6px 12px;
            border-radius: 8px;
            background: var(--ok-bg);
            color: var(--ok);
            font-size: 13px;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #1E9C6B;
        }

        .empty {
            padding: 48px 24px;
            text-align: center;
            color: var(--slate);
            border: 1.5px dashed #C5D6F5;
            border-radius: 20px;
            background: #fff;
        }

        .empty[hidden] { display: none; }

        .empty .i {
            display: block;
            margin: 0 auto 12px;
            font-size: 36px;
            color: var(--royal);
        }

        .empty strong {
            display: block;
            margin-bottom: 4px;
            font-family: var(--font-display);
            font-size: 17px;
            color: var(--navy);
        }

        /* Aksi */
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 28px;
            padding-top: 26px;
            border-top: 1px solid var(--border);
        }

        .actions p {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0;
            max-width: 520px;
            font-size: 14px;
            color: var(--slate);
        }

        .actions p .i { margin-top: 3px; font-size: 18px; color: var(--royal); }

        .btn-row { display: flex; flex-wrap: wrap; gap: 10px; }

        /* Footer */
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 26px var(--pad);
            background: var(--navy);
            color: rgba(255, 255, 255, .8);
        }

        .footer b {
            display: block;
            font-family: var(--font-display);
            color: #fff;
            font-size: 17px;
            letter-spacing: .04em;
        }

        .footer span { font-size: 13px; }

        /* Muncul saat digulir (hanya jika JS aktif) */
        .js .reveal {
            opacity: 0;
            transform: translate3d(0, 24px, 0);
            transition: opacity .7s var(--ease), transform .7s var(--ease);
            transition-delay: var(--d, 0s);
        }

        .js .reveal.in { opacity: 1; transform: none; }

        /* Urutan masuk hero (satu rangkaian saat halaman dibuka) */
        .rise {
            opacity: 0;
            transform: translate3d(0, 22px, 0);
            animation: rise .8s var(--ease) forwards;
        }

        .rise.d1 { animation-delay: .1s; }
        .rise.d2 { animation-delay: .2s; }
        .rise.d3 { animation-delay: .3s; }
        .rise.d4 { animation-delay: .42s; }

        @keyframes rise { to { opacity: 1; transform: none; } }

        /* ---------- Responsif ---------- */
        @media (max-width: 1000px) {
            .hero-grid { grid-template-columns: 1fr; align-items: start; }
            .layout { grid-template-columns: 1fr; }
            .side { position: static; }
            .bento { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .tile.wide { grid-column: 1 / -1; }
        }

        @media (max-width: 700px) {
            .bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 600px) {
            :root { --nav-h: 68px; }
            .nav-sub { display: none; }
            .nav-link .t { display: none; }
            .nav-link { padding: 10px 12px; }
            .nav-logo { width: 42px; height: 42px; }
            .bento { grid-template-columns: 1fr; }
            .tl-top { flex-direction: column; gap: 10px; }
            .tl-item { grid-template-columns: 40px minmax(0, 1fr); gap: 10px; }
            .tl-node { width: 40px; height: 40px; font-size: 13px; }
            .timeline::before { left: 19px; }
            .tl-card { padding: 16px; }
            .actions { align-items: stretch; flex-direction: column; }
            .btn-row, .btn { width: 100%; }
            .footer { flex-direction: column; align-items: flex-start; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }

            *, *::before, *::after {
                animation: none !important;
                transition-duration: .01ms !important;
            }

            .rise,
            .js .reveal { opacity: 1; transform: none; }
        }

        /* ---------- Cetak ---------- */
        @page { size: A4 landscape; margin: 15mm; }

        @media print {
            html, body {
                background: #fff !important;
                color: #14243A !important;
                font-size: 10pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .progress, .skip, .nav, .orb, .orbit, .actions, .footer, .finder, .years, .hero-cta, .hero::after {
                display: none !important;
            }

            .hero { margin: 0 !important; padding: 22px 25px !important; break-inside: avoid; }
            .hero::before { display: none !important; }
            .hero h1 { font-size: 24pt !important; }
            .hero-grid { grid-template-columns: 1.2fr .8fr !important; align-items: center !important; }
            .stat-number { font-size: 36pt !important; }
            .stat { transform: none !important; }

            .rise, .js .reveal { opacity: 1 !important; transform: none !important; animation: none !important; }

            main { padding: 0 !important; }
            .layout { display: block !important; padding-top: 16px !important; }
            .side { position: static !important; margin-bottom: 16px; }
            .bento { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
            .tile, .tl-card { break-inside: avoid; box-shadow: none !important; }
            .tl-item { break-inside: avoid; }
            .tl-item[hidden] { display: grid !important; }
            a { text-decoration: none !important; color: inherit !important; }
        }
    </style>
</head>

<body>
@php
    $parseDate = function ($d) {
        try {
            return $d ? \Illuminate\Support\Carbon::parse($d) : null;
        } catch (\Throwable $e) {
            return null;
        }
    };

    // Pelatihan terbaru di atas
    $histories = $participant->histories
        ->sortByDesc(fn ($h) => optional($parseDate($h->training?->start_date))->timestamp ?? 0)
        ->values();

    $yearOf = fn ($h) => optional($parseDate($h->training?->start_date))->year;

    $yearCounts = $histories
        ->map($yearOf)
        ->filter()
        ->countBy()
        ->sortKeysDesc();

    $years = $yearCounts->keys();
    $yearRange = $years->count() > 1
        ? $years->last() . ' – ' . $years->first()
        : ($years->first() ?? null);
@endphp

{{-- Sprite ikon (gaya Lucide) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <symbol id="i-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></symbol>
        <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
        <symbol id="i-card" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></symbol>
        <symbol id="i-building" viewBox="0 0 24 24"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/></symbol>
        <symbol id="i-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
        <symbol id="i-steth" viewBox="0 0 24 24"><path d="M11 2v2M5 2v2M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
        <symbol id="i-layers" viewBox="0 0 24 24"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></symbol>
        <symbol id="i-wallet" viewBox="0 0 24 24"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></symbol>
        <symbol id="i-download" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></symbol>
        <symbol id="i-left" viewBox="0 0 24 24"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></symbol>
        <symbol id="i-down" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></symbol>
        <symbol id="i-shield" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></symbol>
        <symbol id="i-calendar" viewBox="0 0 24 24"><path d="M8 2v4M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></symbol>
        <symbol id="i-inbox" viewBox="0 0 24 24"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></symbol>
    </defs>
</svg>

<div class="progress" id="progress" aria-hidden="true"></div>

<a class="skip" href="#riwayat">Lewati ke riwayat pelatihan</a>

<header class="nav" id="nav">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('public.dashboard') }}"
           aria-label="ARTA, Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan. Kembali ke dashboard publik">
            <span class="nav-logo">
                <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                     alt="Logo Provinsi Kalimantan Selatan" width="46" height="46">
            </span>

            <span class="nav-name">
                ARTA
                <span class="nav-sub">Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</span>
            </span>
        </a>

        <nav class="nav-links" aria-label="Navigasi utama">
            <a class="nav-link" href="{{ route('public.dashboard') }}">
                <svg class="i" aria-hidden="true"><use href="#i-dashboard"/></svg>
                <span class="t">Dashboard</span>
            </a>

            <a class="nav-link" href="{{ route('public.training-history') }}" aria-current="page">
                <svg class="i" aria-hidden="true"><use href="#i-search"/></svg>
                <span class="t">Riwayat pelatihan</span>
            </a>
        </nav>
    </div>
</header>

<section class="hero" aria-labelledby="hero-title">
    <span class="orb a" aria-hidden="true"></span>
    <span class="orb b" aria-hidden="true"></span>

    <div class="hero-grid">
        <div>
            <span class="badge rise">
                <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                Data peserta ditemukan
            </span>

            <h1 id="hero-title" class="rise d1">
                Riwayat pelatihan Anda
                <em>tercatat di ARTA.</em>
            </h1>

            <p class="hero-lead rise d2">
                ARTA (Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan) menampilkan
                identitas peserta dan seluruh pelatihan yang pernah diikuti,
                sesuai data yang tersimpan di sistem.
            </p>

            <div class="hero-who rise d3">
                <span class="chip">
                    <svg class="i" aria-hidden="true"><use href="#i-user"/></svg>
                    <span>{{ $participant->name ?: '-' }}</span>
                </span>

                @if ($participant->profession)
                    <span class="chip">
                        <svg class="i" aria-hidden="true"><use href="#i-steth"/></svg>
                        <span>{{ $participant->profession }}</span>
                    </span>
                @endif
            </div>

            <div class="btn-row hero-cta rise d4">
                <a class="btn btn-ghost" href="#riwayat" data-scroll>
                    <svg class="i" aria-hidden="true"><use href="#i-down"/></svg>
                    Lihat riwayat pelatihan
                </a>
            </div>
        </div>

        <div class="stat-wrap rise d3">
            <div class="stat" id="stat">
                <span class="orbit" aria-hidden="true"></span>
                <span class="orbit two" aria-hidden="true"></span>

                <span class="stat-label">
                    <svg class="i" aria-hidden="true"><use href="#i-book"/></svg>
                    Total riwayat pelatihan
                </span>

                <strong class="stat-number" id="counter"
                        data-target="{{ $participant->histories->count() }}">
                    {{ $participant->histories->count() }}
                </strong>

                <span class="stat-note">Jumlah keikutsertaan yang tercatat</span>

                @if ($yearRange)
                    <span class="stat-years">
                        <svg class="i" aria-hidden="true"><use href="#i-calendar"/></svg>
                        Tahun pelatihan: {{ $yearRange }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

<main>
    <div class="success reveal" role="status">
        <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>

        <div>
            <strong>Data peserta berhasil ditemukan.</strong>
            <p>NIK yang dimasukkan cocok dengan data peserta yang terdaftar di ARTA.</p>
        </div>
    </div>

    <div class="layout">
        <aside class="side" aria-labelledby="profil-title">
            <div class="section-head reveal">
                <h2 id="profil-title">Informasi peserta</h2>
                <p>Identitas peserta yang terdaftar dalam sistem.</p>
            </div>

            <dl class="bento">
                <div class="tile main wide reveal">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-user"/></svg>Nama lengkap</dt>
                    <dd>{{ $participant->name ?: '-' }}</dd>
                </div>

                <div class="tile reveal" style="--d:.05s">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-card"/></svg>NIK</dt>
                    <dd>{{ $participant->nik ?: '-' }}</dd>
                </div>

                <div class="tile reveal" style="--d:.1s">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-card"/></svg>NIP</dt>
                    <dd>{{ $participant->nip ?: '-' }}</dd>
                </div>

                <div class="tile wide reveal" style="--d:.15s">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-steth"/></svg>Profesi</dt>
                    <dd>{{ $participant->profession ?: '-' }}</dd>
                </div>

                <div class="tile wide reveal" style="--d:.2s">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-building"/></svg>Instansi</dt>
                    <dd>{{ $participant->institution ?: '-' }}</dd>
                </div>

                <div class="tile wide reveal" style="--d:.25s">
                    <dt><svg class="i" aria-hidden="true"><use href="#i-pin"/></svg>Kabupaten/Kota/Mitra</dt>
                    <dd>{{ $participant->district ?: '-' }}</dd>
                </div>
            </dl>
        </aside>

        <section id="riwayat" aria-labelledby="riwayat-title" style="scroll-margin-top: calc(var(--nav-h) + 12px)">
            <div class="section-head reveal">
                <h2 id="riwayat-title">Daftar riwayat pelatihan</h2>
                <p>Seluruh pelatihan yang pernah diikuti peserta, terbaru di atas.</p>
                <span class="count" id="count-label">{{ $participant->histories->count() }} data</span>
            </div>

            @if ($histories->isEmpty())
                <div class="empty reveal">
                    <svg class="i" aria-hidden="true"><use href="#i-inbox"/></svg>
                    <strong>Belum ada riwayat pelatihan</strong>
                    Data keikutsertaan pelatihan peserta ini belum tercatat di sistem.
                </div>
            @else
                @if ($years->count() > 1)
                    <div class="years reveal" role="group" aria-label="Saring berdasarkan tahun pelatihan">
                        <button type="button" class="year-chip is-active" data-year="all" aria-pressed="true">
                            Semua tahun <small>{{ $histories->count() }}</small>
                        </button>

                        @foreach ($years as $y)
                            <button type="button" class="year-chip" data-year="{{ $y }}" aria-pressed="false">
                                {{ $y }} <small>{{ $yearCounts[$y] }}</small>
                            </button>
                        @endforeach
                    </div>
                @endif

                @if ($histories->count() > 4)
                    <div class="finder reveal">
                        <label class="sr-only" for="finder-input">Cari nama pelatihan, sumber dana, atau status</label>
                        <svg class="i" aria-hidden="true"><use href="#i-search"/></svg>
                        <input type="search" id="finder-input" placeholder="Cari nama pelatihan, sumber dana, atau status"
                               autocomplete="off" enterkeyhint="search">
                    </div>
                @endif

                <ol class="timeline" id="timeline">
                    @foreach ($histories as $history)
                        @php
                            $training = $history->training;
                            $start = $parseDate($training?->start_date);
                            $attrs = $training ? $training->getAttributes() : [];
                            $end = array_key_exists('end_date', $attrs) ? $parseDate($attrs['end_date']) : null;
                            $place = null;

                            foreach (['location', 'lokasi', 'venue', 'place', 'tempat'] as $col) {
                                if (!empty($attrs[$col] ?? null)) {
                                    $place = $attrs[$col];
                                    break;
                                }
                            }

                            $fmt = fn ($d) => $d->locale('id')->translatedFormat('d M Y');

                            $dateText = null;
                            if ($start) {
                                $dateText = ($end && !$end->isSameDay($start))
                                    ? $fmt($start) . ' – ' . $fmt($end)
                                    : $fmt($start);
                            }
                        @endphp
                        <li class="tl-item reveal"
                            style="--d: {{ min($loop->index, 6) * 0.05 }}s"
                            data-year="{{ $start?->year ?? '' }}"
                            data-search="{{ \Illuminate\Support\Str::lower(
                                ($history->training?->name ?? '') . ' '
                                . ($start?->year ?? '') . ' '
                                . ($place ?? '') . ' '
                                . ($history->training?->batch_number ?? '') . ' '
                                . ($history->training?->funding_source ?? '') . ' '
                                . ($history->status ?? '')
                            ) }}">
                            <span class="tl-node" aria-hidden="true">{{ $loop->iteration }}</span>

                            <article class="tl-card">
                                <div class="tl-top">
                                    <h3 class="tl-name">
                                        <span class="sr-only">Pelatihan nomor {{ $loop->iteration }}: </span>
                                        {{ $history->training?->name ?? 'Nama pelatihan tidak tersedia' }}
                                    </h3>

                                    @if ($history->status)
                                        <span class="status">{{ $history->status }}</span>
                                    @endif
                                </div>

                                <ul class="tl-meta">
                                    @if ($dateText)
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-calendar"/></svg>
                                            {{ $dateText }}
                                        </li>
                                    @endif
                                    <li>
                                        <svg class="i" aria-hidden="true"><use href="#i-layers"/></svg>
                                        Angkatan {{ $history->training?->batch_number ?: '-' }}
                                    </li>
                                    <li>
                                        <svg class="i" aria-hidden="true"><use href="#i-wallet"/></svg>
                                        {{ $history->training?->funding_source ?: '-' }}
                                    </li>
                                    @if ($place)
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-pin"/></svg>
                                            {{ $place }}
                                        </li>
                                    @endif
                                </ul>
                            </article>
                        </li>
                    @endforeach
                </ol>

                <div class="empty" id="no-result" hidden>
                    <svg class="i" aria-hidden="true"><use href="#i-search"/></svg>
                    <strong>Tidak ada pelatihan yang cocok</strong>
                    Coba kata kunci lain atau pilih tahun yang berbeda.
                </div>
            @endif

            <div class="actions reveal">
                <p>
                    <svg class="i" aria-hidden="true"><use href="#i-shield"/></svg>
                    Informasi ini ditampilkan berdasarkan data yang tersimpan di sistem ARTA.
                </p>

                <div class="btn-row">
                    <a class="btn btn-print" id="export-btn"
                       href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute(
                           'public.training-history.export',
                           now()->addMinutes(30),
                           ['participant' => $participant->id]
                       ) }}">
                        <svg class="i" aria-hidden="true"><use href="#i-download"/></svg>
                        <span class="spin" aria-hidden="true"></span>
                        <span id="export-label">Ekspor PDF</span>
                    </a>

                    <a class="btn btn-secondary" href="{{ route('public.training-history') }}">
                        <svg class="i" aria-hidden="true"><use href="#i-search"/></svg>
                        Cari data lain
                    </a>

                    <a class="btn btn-primary" href="{{ route('public.dashboard') }}">
                        <svg class="i" aria-hidden="true"><use href="#i-left"/></svg>
                        Kembali ke dashboard publik
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>

<footer class="footer">
    <div>
        <b>ARTA</b>
        <span>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</span>
    </div>

    <span>&copy; {{ date('Y') }} ARTA. Seluruh hak cipta dilindungi.</span>
</footer>

<script>
    (function () {
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Posisi gulir tetap saat muat ulang / kembali ---------- */
        var KEY = 'arta-scroll:' + location.pathname + location.search;
        var navType = '';

        try {
            var entry = performance.getEntriesByType('navigation')[0];
            navType = entry ? entry.type : '';
        } catch (e) {}

        try {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
        } catch (e) {}

        function saveScroll() {
            try { sessionStorage.setItem(KEY, String(window.scrollY || 0)); } catch (e) {}
        }

        function restoreScroll() {
            if (navType !== 'reload' && navType !== 'back_forward') { return; }
            if (location.hash) { return; }

            var saved = null;
            try { saved = sessionStorage.getItem(KEY); } catch (e) {}
            if (saved === null) { return; }

            var y = parseInt(saved, 10) || 0;
            var root = document.documentElement;
            var prev = root.style.scrollBehavior;

            root.style.scrollBehavior = 'auto';
            window.scrollTo(0, y);
            root.style.scrollBehavior = prev;
        }

        restoreScroll();
        window.addEventListener('load', function () {
            restoreScroll();
            setTimeout(restoreScroll, 250);
        });
        window.addEventListener('pagehide', saveScroll);
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') { saveScroll(); }
        });

        /* ---------- Navbar + progress bar gulir ---------- */
        var nav = document.getElementById('nav');
        var bar = document.getElementById('progress');
        var ticking = false;

        function update() {
            var y = window.scrollY || document.documentElement.scrollTop;
            var max = document.documentElement.scrollHeight - window.innerHeight;

            nav.classList.toggle('is-scrolled', y > 12);
            bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        }, { passive: true });

        update();

        /* ---------- Tautan anchor: gulir halus tanpa lompat ke atas ---------- */
        document.querySelectorAll('a[data-scroll], a.skip').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var id = a.getAttribute('href');
                var target = id && id.charAt(0) === '#' ? document.querySelector(id) : null;

                if (!target) { return; }

                e.preventDefault();
                target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });

                if (!target.hasAttribute('tabindex')) { target.setAttribute('tabindex', '-1'); }
                target.focus({ preventScroll: true });
            });
        });

        /* ---------- Muncul saat digulir ---------- */
        var items = document.querySelectorAll('.reveal');

        if ('IntersectionObserver' in window && !reduce) {
            var io = new IntersectionObserver(function (list) {
                list.forEach(function (en) {
                    if (en.isIntersecting) {
                        en.target.classList.add('in');
                        io.unobserve(en.target);
                    }
                });
            }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });

            items.forEach(function (el) { io.observe(el); });
        } else {
            items.forEach(function (el) { el.classList.add('in'); });
        }

        /* ---------- Counter total ---------- */
        var counter = document.getElementById('counter');
        var target = parseInt(counter.getAttribute('data-target'), 10) || 0;

        if (!reduce && target > 0) {
            var start = null;
            var dur = 1100;

            counter.textContent = '0';

            (function step(t) {
                if (start === null) { start = t; }

                var p = Math.min((t - start) / dur, 1);
                counter.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));

                if (p < 1) { requestAnimationFrame(step); }
            })(performance.now());
        }

        /* ---------- Kartu total miring mengikuti kursor (hanya transform) ---------- */
        var stat = document.getElementById('stat');
        var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

        if (stat && fine && !reduce) {
            var wrap = stat.parentNode;

            wrap.addEventListener('pointermove', function (e) {
                var r = wrap.getBoundingClientRect();
                var x = (e.clientX - r.left) / r.width - .5;
                var y = (e.clientY - r.top) / r.height - .5;

                stat.style.transform =
                    'rotateY(' + (x * 8).toFixed(2) + 'deg) rotateX(' + (-y * 8).toFixed(2) + 'deg)';
            });

            wrap.addEventListener('pointerleave', function () {
                stat.style.transform = '';
            });
        }

        /* ---------- Saring riwayat: kata kunci + tahun ---------- */
        var input = document.getElementById('finder-input');
        var rows = document.querySelectorAll('#timeline .tl-item');
        var chips = document.querySelectorAll('.year-chip');
        var empty = document.getElementById('no-result');
        var label = document.getElementById('count-label');
        var yearSel = 'all';

        function applyFilter() {
            var q = input ? input.value.trim().toLowerCase() : '';
            var shown = 0;

            rows.forEach(function (row) {
                var okQ = q === '' || row.getAttribute('data-search').indexOf(q) !== -1;
                var okY = yearSel === 'all' || row.getAttribute('data-year') === yearSel;
                var ok = okQ && okY;

                row.hidden = !ok;
                if (ok) {
                    shown++;
                    row.classList.add('in');
                }
            });

            if (empty) { empty.hidden = shown !== 0; }

            label.textContent = (q === '' && yearSel === 'all')
                ? rows.length + ' data'
                : shown + ' dari ' + rows.length + ' data';
        }

        if (rows.length) {
            if (input) { input.addEventListener('input', applyFilter); }

            chips.forEach(function (chip) {
                chip.addEventListener('click', function () {
                    yearSel = chip.getAttribute('data-year');

                    chips.forEach(function (c) {
                        var on = c === chip;
                        c.classList.toggle('is-active', on);
                        c.setAttribute('aria-pressed', on ? 'true' : 'false');
                    });

                    applyFilter();
                });
            });
        }

        /* ---------- Tombol ekspor: tampilkan memuat, tetap di posisi ---------- */
        var exportBtn = document.getElementById('export-btn');
        var exportLabel = document.getElementById('export-label');

        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                saveScroll();
                exportBtn.classList.add('is-loading');
                exportBtn.setAttribute('aria-busy', 'true');
                exportLabel.textContent = 'Menyiapkan PDF';

                setTimeout(function () {
                    exportBtn.classList.remove('is-loading');
                    exportBtn.removeAttribute('aria-busy');
                    exportLabel.textContent = 'Ekspor PDF';
                }, 4000);
            });
        }
    })();
</script>
</body>
</html>