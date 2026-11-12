<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#081b33">

    <title>@yield('title', 'Dashboard') - ARTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =====================================================
           ARTA — APP SHELL (Premium Blue Editorial)
           Nama variabel & class lama dipertahankan agar halaman
           yang belum diredesign tetap tampil benar.
        ===================================================== */
        :root {
            /* Brand tokens */
            --navy: #081b33;
            --navy-dark: #061426;
            --navy-deep: #040e1c;
            --midnight: #0b2545;
            --blue: #2457d6;
            --blue-hover: #1b45b0;
            --electric: #3478f6;
            --cyan: #38c6e8;
            --blue-light: #f3f7fc;
            --blue-soft: #eaf2ff;

            /* Neutrals */
            --text: #14243a;
            --muted: #56657a;
            --border: #e1e9f2;
            --bg: #f3f7fc;
            --white: #ffffff;

            /* Status */
            --success: #166534;
            --danger: #991b1b;

            /* Layout */
            --sidebar-width: 288px;
            --header-height: 80px;
            --pad-x: clamp(16px, 2.6vw, 48px);

            /* Shape */
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 22px;

            /* Type */
            --font-display: 'Sora', system-ui, sans-serif;
            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;

            --shadow-soft: 0 8px 30px rgba(8, 27, 51, .05);
            --transition: .25s cubic-bezier(.2, .7, .3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            min-width: 320px;
            overflow-x: clip;
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.7;
            color: var(--text);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        img { max-width: 100%; }
        ::selection { color: #fff; background: var(--blue); }

        button:focus-visible,
        a:focus-visible,
        summary:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(52, 120, 246, .55);
            outline-offset: 3px;
            border-radius: 8px;
        }

        .app-shell { min-height: 100vh; min-height: 100dvh; }

        .skip-link {
            position: fixed;
            left: 16px;
            top: -64px;
            z-index: 400;
            padding: 12px 18px;
            color: #fff;
            background: var(--blue);
            border-radius: 10px;
            font-weight: 700;
            transition: top .2s;
        }

        .skip-link:focus { top: 12px; }

        /* Scroll progress */
        .scroll-progress {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 300;
            height: 3px;
            background: linear-gradient(90deg, var(--electric), var(--cyan));
            transform: scaleX(0);
            transform-origin: left;
            will-change: transform;
            pointer-events: none;
        }

        /* =====================================================
           ICON (satu sistem, gaya Lucide)
        ===================================================== */
        .icon {
            width: 21px;
            height: 21px;
            flex: 0 0 21px;
            display: inline-block;
            vertical-align: middle;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .icon-sm { width: 17px; height: 17px; flex-basis: 17px; }

        /* =====================================================
           SIDEBAR
        ===================================================== */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            color: #fff;
            background:
                radial-gradient(circle at 0% 0%, rgba(52,120,246,.26), transparent 46%),
                radial-gradient(circle at 100% 100%, rgba(56,198,232,.10), transparent 40%),
                var(--navy);
            border-right: 1px solid rgba(255,255,255,.06);
            scrollbar-width: thin;
            scrollbar-color: #2c4a72 transparent;
        }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #2c4a72; border-radius: 10px; }

        /* Brand */
        .brand {
            padding: 28px 24px 24px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .brand-link { display: block; color: inherit; }

        .brand h2 {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 0 0 14px;
            color: #fff;
            font-family: var(--font-display);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -.04em;
            line-height: 1.1;
        }

        .brand-logo {
            display: block;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            padding: 5px;
            object-fit: contain;
            background: #fff;
            border-radius: 14px;
            transition: transform var(--transition);
        }

        .brand-link:hover .brand-logo { transform: rotate(-6deg) scale(1.05); }

        .brand p {
            margin: 0;
            color: #b4c9e3;
            font-size: 13px;
            line-height: 1.7;
        }

        /* Navigation */
        .sidebar nav { flex: 1; padding: 10px 14px 20px; }

        .nav-label {
            padding: 24px 12px 8px;
            color: #86a3c7;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            line-height: 1.5;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 48px;
            margin: 4px 0;
            padding: 11px 14px;
            color: #d3e0f1;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.45;
            transition: background var(--transition), color var(--transition), transform var(--transition);
        }

        .nav-link .icon { color: #8fb0d8; transition: color var(--transition), transform var(--transition); }

        .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
            transform: translateX(3px);
        }

        .nav-link:hover .icon { color: #fff; transform: scale(1.1); }

        .nav-link.active {
            background: rgba(52,120,246,.22);
            color: #fff;
            font-weight: 700;
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            top: 10px;
            bottom: 10px;
            left: -14px;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--cyan);
        }

        .nav-link.active .icon { color: var(--cyan); }

        /* Master data submenu */
        .nav-group { margin: 4px 0; }

        .nav-group-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 48px;
            padding: 11px 14px;
            color: #d3e0f1;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.45;
            cursor: pointer;
            list-style: none;
            transition: background var(--transition), color var(--transition);
        }

        .nav-group-summary::-webkit-details-marker { display: none; }
        .nav-group-summary::marker { content: ""; }

        .nav-group-summary:hover,
        .nav-group[open] > .nav-group-summary {
            background: rgba(255,255,255,.07);
            color: #fff;
        }

        .nav-group-summary.active {
            background: rgba(52,120,246,.22);
            color: #fff;
        }

        .nav-group-title { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .nav-group-title .icon { color: #8fb0d8; }
        .nav-group-summary.active .nav-group-title .icon { color: var(--cyan); }

        .nav-chevron { flex: 0 0 auto; transition: transform var(--transition); }
        .nav-group[open] > .nav-group-summary .nav-chevron { transform: rotate(180deg); }

        .nav-submenu {
            margin: 6px 0 10px 26px;
            padding: 4px 0 4px 14px;
            border-left: 1px solid rgba(255,255,255,.18);
            animation: submenu-in .25s ease;
        }

        @keyframes submenu-in {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .nav-sublink {
            display: flex;
            align-items: center;
            min-height: 40px;
            margin: 3px 0;
            padding: 9px 12px;
            color: #c0d2e8;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.45;
            transition: background var(--transition), color var(--transition), transform var(--transition);
        }

        .nav-sublink::before {
            content: "";
            width: 5px;
            height: 5px;
            flex: 0 0 5px;
            margin-right: 11px;
            border-radius: 50%;
            background: #6c8db6;
            transition: background var(--transition), transform var(--transition);
        }

        .nav-sublink:hover { background: rgba(255,255,255,.07); color: #fff; transform: translateX(3px); }
        .nav-sublink:hover::before { transform: scale(1.5); }

        .nav-sublink.active { background: rgba(52,120,246,.22); color: #fff; font-weight: 700; }
        .nav-sublink.active::before { background: var(--cyan); }

        /* Logout */
        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 16px 20px;
            border-top: 1px solid rgba(255,255,255,.1);
            background: rgba(4,14,28,.35);
        }

        .sidebar-bottom form { margin: 0; }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            min-height: 50px;
            padding: 11px 14px;
            color: #e8f0fb;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            transition: background var(--transition), border-color var(--transition), transform var(--transition);
        }

        .logout-button:hover {
            background: rgba(239,68,68,.18);
            border-color: rgba(252,165,165,.5);
        }

        .logout-button:active { transform: scale(.98); }

        /* Mobile menu button */
        .mobile-menu-button {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 46px;
            padding: 9px 16px;
            color: #fff;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
        }

        .mobile-menu-button .icon { width: 19px; height: 19px; }
        .mobile-menu-button .icon-sm { transition: transform var(--transition); }
        .mobile-menu-button[aria-expanded="true"] .icon-sm { transform: rotate(180deg); }

        /* =====================================================
           MAIN AREA (penuh layar, tidak condong ke tengah)
        ===================================================== */
        .main-area {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-height: 100dvh;
            margin-left: var(--sidebar-width);
        }

        /* Topbar */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: var(--header-height);
            padding: 14px var(--pad-x);
            background: rgba(243,247,252,.88);
            border-bottom: 1px solid transparent;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        }

        .topbar.scrolled {
            background: rgba(255,255,255,.92);
            border-color: var(--border);
            box-shadow: 0 10px 30px rgba(8,27,51,.06);
        }

        .topbar-left { display: flex; align-items: center; gap: 15px; min-width: 0; }
        .topbar-heading { min-width: 0; }

        .topbar h2 {
            margin: 0 0 2px;
            color: var(--navy);
            font-family: var(--font-display);
            font-size: clamp(19px, 2vw, 24px);
            font-weight: 700;
            letter-spacing: -.03em;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .breadcrumb-separator { color: #a9b8ca; }
        .breadcrumb-current { color: var(--blue); font-weight: 700; }

        .topbar-right { display: flex; align-items: center; justify-content: flex-end; gap: 13px; flex: 0 0 auto; }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 18px 6px 6px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 999px;
            transition: box-shadow var(--transition), transform var(--transition);
        }

        .user-info:hover { box-shadow: var(--shadow-soft); transform: translateY(-1px); }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            color: #fff;
            background: linear-gradient(145deg, var(--blue), var(--navy));
            border-radius: 50%;
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 700;
        }

        .user-details { min-width: 0; }

        .user-name {
            display: block;
            max-width: 220px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .role-label {
            display: block;
            margin-top: 1px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.35;
        }

        /* Main content */
        .main-content {
            flex: 1;
            min-width: 0;
            width: 100%;
            padding: 24px var(--pad-x) 40px;
        }

        /* Animasi masuk halaman (opacity + transform saja) */
        .main-content > * { animation: page-in .55s var(--transition) both; }
        .main-content > *:nth-child(2) { animation-delay: .05s; }
        .main-content > *:nth-child(3) { animation-delay: .1s; }
        .main-content > *:nth-child(4) { animation-delay: .15s; }
        .main-content > *:nth-child(n+5) { animation-delay: .2s; }

        @keyframes page-in {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: none; }
        }

        /* =====================================================
           CONTENT COMPONENTS (kompatibel dengan halaman lama)
        ===================================================== */
        .page-heading,
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 28px;
        }

        .page-heading h1,
        .page-header h1 {
            margin: 0 0 8px;
            color: var(--navy);
            font-family: var(--font-display);
            font-size: clamp(28px, 3.4vw, 42px);
            font-weight: 800;
            letter-spacing: -.04em;
            line-height: 1.15;
        }

        .page-heading p,
        .page-header p { margin: 0; color: var(--muted); font-size: 16px; line-height: 1.75; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: var(--blue);
            font-size: 14px;
            font-weight: 700;
        }

        .eyebrow::before { content: ""; width: 26px; height: 2px; background: var(--cyan); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card,
        .content-card,
        .panel,
        .form-card {
            position: relative;
            padding: 28px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
        }

        .stat-card { overflow: hidden; transition: transform var(--transition), box-shadow var(--transition); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 44px rgba(8,27,51,.09); }

        /* aksen garis atas pada kartu statistik */
        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--cyan));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .5s var(--transition);
        }

        .stat-card:hover::before { transform: scaleX(1); }

        .stat-label { color: var(--muted); font-size: 14px; font-weight: 600; line-height: 1.6; }

        .stat-number,
        .stat-value {
            margin-top: 10px;
            color: var(--navy);
            font-family: var(--font-display);
            font-size: clamp(32px, 3.2vw, 46px);
            font-weight: 800;
            letter-spacing: -.04em;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
            overflow-wrap: anywhere;
        }

        .stat-note { margin-top: 8px; color: var(--muted); font-size: 14px; line-height: 1.6; }

        .content-card,
        .panel { margin-bottom: 24px; overflow: hidden; }

        .content-card h2,
        .panel h2,
        .form-card h2 {
            margin: 0 0 12px;
            color: var(--navy);
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.025em;
            line-height: 1.35;
        }

        .content-card p,
        .panel p { color: var(--muted); font-size: 15px; line-height: 1.75; }

        .dashboard-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 20px;
            color: #fff;
            background: var(--blue);
            border: 1.5px solid transparent;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
            text-align: center;
            transition: background var(--transition), color var(--transition), border-color var(--transition), transform var(--transition), box-shadow var(--transition);
        }

        .btn:hover {
            background: var(--navy);
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(36,87,214,.22);
        }

        .btn:active { transform: scale(.97); }

        .btn-primary { color: #fff; background: var(--blue); }

        .btn-light { color: var(--blue); background: var(--blue-soft); }
        .btn-light:hover { color: var(--navy); background: #dbe8fc; box-shadow: none; }

        .btn-secondary { color: #334155; background: #e8eef6; }
        .btn-secondary:hover { color: var(--navy); background: #dbe5f2; box-shadow: none; }

        .btn-outline { color: #43546a; background: #fff; border-color: var(--border); }
        .btn-outline:hover { color: var(--blue); background: var(--blue-soft); border-color: #b9cfec; box-shadow: none; }

        .btn-ghost { color: var(--blue); background: transparent; }
        .btn-ghost:hover { background: var(--blue-soft); color: var(--blue); box-shadow: none; }

        .btn-danger { color: #fff; background: #c2410c; }
        .btn-danger:hover { background: #9a3412; box-shadow: 0 10px 22px rgba(194,65,12,.25); }

        .btn-sm { min-height: 36px; padding: 6px 14px; font-size: 13px; }

        /* status loading otomatis untuk form submit */
        .btn.is-loading,
        button.is-loading {
            position: relative;
            pointer-events: none;
            opacity: .85;
        }

        .btn.is-loading::after,
        button.is-loading::after {
            content: "";
            width: 15px;
            height: 15px;
            margin-left: 8px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .inline-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-top: 18px; }

        /* Forms */
        .form-card { margin-bottom: 24px; }

        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 20px; }

        .form-group { min-width: 0; margin-bottom: 20px; }

        label { display: block; margin-bottom: 8px; color: #40536a; font-size: 14px; font-weight: 700; }

        input, select, textarea {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 12px 14px;
            color: var(--text);
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 15px;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        input:hover, select:hover, textarea:hover { border-color: #a6bddb; }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(36,87,214,.12);
        }

        input[type="checkbox"], input[type="radio"] { display: inline-block; width: auto; accent-color: var(--blue); }

        .form-help { display: block; margin-top: 6px; color: var(--muted); font-size: 13px; }
        .field-error { display: block; margin-top: 6px; color: #b91c1c; font-size: 13px; }
        .input-error { border-color: #dc2626; }

        .form-actions,
        .filter-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-top: 16px; }

        /* Alerts */
        .alert {
            display: block;
            padding: 16px 20px;
            margin-bottom: 22px;
            color: #1e40af;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: var(--radius-md);
            font-size: 15px;
        }

        .alert-success { color: var(--success); background: #f0fdf4; border-color: #bbf7d0; }
        .alert-error, .alert-danger { color: var(--danger); background: #fef2f2; border-color: #fecaca; }
        .alert ul { margin: 8px 0 0; padding-left: 22px; }

        /* Tables */
        .table-responsive,
        .table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }

        table { width: 100%; margin-top: 16px; border-collapse: collapse; font-size: 15px; }

        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #edf1f5; vertical-align: middle; }

        th {
            color: #506176;
            background: var(--blue-light);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .02em;
            white-space: nowrap;
        }

        td { line-height: 1.6; }
        tbody tr { transition: background var(--transition); }
        tbody tr:hover { background: #f7faff; }

        .table-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .table-actions form { margin: 0; }
        .table-actions button, .table-actions a { white-space: nowrap; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-admin { color: #1b45b0; background: #dbe8fc; }
        .badge-pimpinan { color: #166534; background: #dcfce7; }
        .badge-neutral { color: #334155; background: #e2e8f0; }
        .badge-success { color: #166534; background: #dcfce7; }
        .badge-secondary { color: #475569; background: #e2e8f0; }

        /* Filter + pagination */
        .filter-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(170px, 220px) auto;
            gap: 12px;
            align-items: end;
        }

        .pagination { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 20px; }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 14px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
        }

        .pagination a:hover { color: var(--blue); background: var(--blue-soft); border-color: #b9cfec; }
        .pagination .current { color: #fff; background: var(--blue); border-color: var(--blue); }
        .pagination .disabled { color: #94a3b8; background: #f8fafc; }
        .pagination-wrapper { margin-top: 18px; }

        .field-stat { margin-top: 18px; }
        .field-stat-line { display: flex; justify-content: space-between; gap: 14px; margin-bottom: 8px; font-size: 15px; }

        .progress-track { height: 8px; overflow: hidden; background: var(--blue-soft); border-radius: 99px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--blue), var(--cyan)); border-radius: 99px; }

        .empty-state { padding: 28px 16px; color: var(--muted); text-align: center; font-size: 15px; }

        .count-pill {
            display: inline-block;
            padding: 4px 12px;
            color: var(--blue);
            background: var(--blue-soft);
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        /* =====================================================
           FOOTER
        ===================================================== */
        .app-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            padding: 24px var(--pad-x);
            color: var(--muted);
            background: #fff;
            border-top: 1px solid var(--border);
            font-size: 14px;
            line-height: 1.7;
        }

        .footer-brand { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .footer-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            color: #fff;
            background: var(--navy);
            border-radius: 12px;
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 800;
        }

        .footer-title { display: block; color: var(--navy); font-family: var(--font-display); font-size: 15px; font-weight: 700; line-height: 1.4; }
        .footer-description { display: block; color: var(--muted); font-size: 13px; line-height: 1.5; }

        .footer-meta { color: var(--muted); font-size: 13px; text-align: right; }
        .footer-meta strong { color: var(--navy); font-weight: 700; }

        /* =====================================================
           RESPONSIVE — TABLET
        ===================================================== */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            :root { --sidebar-width: 252px; }

            .filter-grid { grid-template-columns: 1fr 1fr; }
            .filter-grid .filter-actions { grid-column: 1 / -1; }

            .user-name { max-width: 150px; }
        }

        /* =====================================================
           RESPONSIVE — MOBILE (penuh layar)
        ===================================================== */
        @media (max-width: 760px) {
            :root { --pad-x: 14px; }

            .sidebar {
                position: relative;
                inset: auto;
                width: 100%;
                height: auto;
                max-height: none;
                overflow: visible;
            }

            .brand { padding: 18px 16px; }
            .brand h2 { margin-bottom: 8px; font-size: 26px; }
            .brand-logo { width: 42px; height: 42px; flex-basis: 42px; }
            .brand p { font-size: 12px; }

            .mobile-menu-button { display: inline-flex; margin: 0 14px 14px; }

            .sidebar nav { display: none; padding: 4px 12px 14px; }
            .sidebar.mobile-open nav { display: block; animation: submenu-in .25s ease; }

            .nav-label { padding-top: 16px; }
            .nav-link.active::before { left: -12px; }

            .sidebar-bottom { display: none; margin-top: 0; }
            .sidebar.mobile-open .sidebar-bottom { display: block; }

            .main-area { margin-left: 0; }

            .topbar {
                position: relative;
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                padding: 18px var(--pad-x);
            }

            .topbar-left, .topbar-right { width: 100%; }
            .topbar-right { justify-content: flex-start; }
            .user-name { max-width: 240px; }

            .main-content { padding: 18px var(--pad-x) 28px; }

            .stat-card, .content-card, .panel, .form-card { padding: 20px; border-radius: 18px; }

            .app-footer { align-items: flex-start; flex-direction: column; padding: 20px var(--pad-x); }
            .footer-meta { text-align: left; }
        }

        @media (max-width: 560px) {
            .form-grid, .filter-grid { grid-template-columns: 1fr; }
            .filter-grid .filter-actions { grid-column: auto; }

            .stats-grid { grid-template-columns: 1fr; gap: 12px; }

            .inline-actions .btn { flex: 1 1 auto; }

            .user-info { width: 100%; border-radius: 22px; }
            .user-name { max-width: calc(100vw - 130px); }

            th, td { padding: 12px; }
        }

        /* =====================================================
           REDUCED MOTION
        ===================================================== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
<a href="#mainContent" class="skip-link">Lewati ke konten utama</a>
<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

<div class="app-shell">

    @php
        $currentUser = auth()->user();

        $role = strtolower(trim((string) ($currentUser?->role ?? '')));
        $role = str_replace('-', '_', $role);

        $roleLabels = [
            'super_admin' => 'Super Admin',
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
            'user' => 'Peserta Pelatihan',
        ];

        $roleLabel = $roleLabels[$role] ?? 'Pengguna';

        $isSuperAdmin = in_array($role, ['super_admin', 'superadmin'], true);
        $isAdmin = $role === 'admin';
        $isPimpinan = $role === 'pimpinan';
        $isParticipant = $role === 'user';

        $masterDataCategories = [
            'provinces' => 'Provinsi',
            'districts' => 'Kabupaten/Kota/Mitra/Mandiri',
            'funding-sources' => 'Sumber Dana',
            'training-years' => 'Tahun Pelatihan',
            'training-categories' => 'Kategori Pelatihan',
            'health-worker-types' => 'Jenis Tenaga Kesehatan',
        ];

        $currentCategory = request()->route('category');
        $isMasterDataActive = request()->routeIs('super-admin.master-data.*');

        $pageTitle = trim($__env->yieldContent('title', 'Dashboard'));

        $displayName = $currentUser?->name
            ?? $currentUser?->username
            ?? 'Pengguna';

        $avatarInitial = strtoupper(
            mb_substr(trim((string) $displayName), 0, 1)
        );
    @endphp

    {{-- SVG ICON LIBRARY (gaya Lucide) --}}
    <svg xmlns="http://www.w3.org/2000/svg"
         style="position:absolute;width:0;height:0;overflow:hidden"
         aria-hidden="true">

        <symbol id="icon-building" viewBox="0 0 24 24">
            <path d="M3 21h18M5 21V5l7-2 7 2v16M9 8h1m4 0h1M9 12h1m4 0h1M9 16h1m4 0h1M11 21v-3h2v3"/>
        </symbol>

        <symbol id="icon-dashboard" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <rect x="14" y="14" width="7" height="7" rx="1"/>
        </symbol>

        <symbol id="icon-users" viewBox="0 0 24 24">
            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
            <circle cx="9.5" cy="7" r="4"/>
            <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </symbol>

        <symbol id="icon-settings" viewBox="0 0 24 24">
            <ellipse cx="12" cy="5" rx="8" ry="3"/>
            <path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/>
            <path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>
        </symbol>

        <symbol id="icon-shield" viewBox="0 0 24 24">
            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
            <path d="m9 12 2 2 4-4"/>
        </symbol>

        <symbol id="icon-activity" viewBox="0 0 24 24">
            <path d="M3 3v18h18"/>
            <path d="m7 14 4-4 4 3 5-7"/>
        </symbol>

        <symbol id="icon-training" viewBox="0 0 24 24">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
            <path d="M8 7h8M8 11h8"/>
        </symbol>

        <symbol id="icon-upload" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <path d="m17 8-5-5-5 5M12 3v12"/>
        </symbol>

        <symbol id="icon-report" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
            <path d="M14 2v6h6M8 13h8M8 17h8M8 9h2"/>
        </symbol>

        <symbol id="icon-user" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 21a8 8 0 0 1 16 0"/>
        </symbol>

        <symbol id="icon-chevron" viewBox="0 0 24 24">
            <path d="m6 9 6 6 6-6"/>
        </symbol>

        <symbol id="icon-logout" viewBox="0 0 24 24">
            <path d="M10 17l5-5-5-5M15 12H3"/>
            <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
        </symbol>

        <symbol id="icon-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </symbol>

        <symbol id="icon-home" viewBox="0 0 24 24">
            <path d="m3 10 9-7 9 7"/>
            <path d="M5 9v12h14V9M9 21v-7h6v7"/>
        </symbol>
    </svg>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="appSidebar">

        <div class="brand">
            <a href="{{ url('/') }}" class="brand-link" aria-label="ARTA">
                <h2>
                    <img
                        src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan"
                        class="brand-logo"
                        width="48"
                        height="48"
                    >
                    <span>ARTA</span>
                </h2>

                <p>
                    Aplikasi Rekam Riwayat Pelatihan<br>
                    Tenaga Kesehatan · Bapelkes Kalimantan Selatan
                </p>
            </a>
        </div>

        {{-- MOBILE MENU BUTTON --}}
        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
            aria-expanded="false"
            aria-controls="sidebarNavigation"
        >
            <svg class="icon" aria-hidden="true">
                <use href="#icon-menu"/>
            </svg>
            <span>Menu Navigasi</span>
            <svg class="icon icon-sm" aria-hidden="true">
                <use href="#icon-chevron"/>
            </svg>
        </button>

        <nav id="sidebarNavigation" aria-label="Navigasi utama">

            <div class="nav-label">MENU UTAMA</div>

            {{-- DASHBOARD --}}
            @if ($isSuperAdmin && Route::has('super-admin.dashboard'))
                <a
                    href="{{ route('super-admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}"
                    @if (request()->routeIs('super-admin.dashboard')) aria-current="page" @endif
                >
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-dashboard"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

            @elseif (Route::has('dashboard'))
                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    @if (request()->routeIs('dashboard')) aria-current="page" @endif
                >
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-dashboard"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            @endif

            {{-- SUPER ADMIN --}}
            @if ($isSuperAdmin)

                <div class="nav-label">MANAJEMEN SISTEM</div>

                @if (Route::has('super-admin.users.index'))
                    <a
                        href="{{ route('super-admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}"
                        @if (request()->routeIs('super-admin.users.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-users"/>
                        </svg>
                        <span>Kelola Akun</span>
                    </a>
                @endif

                @if (Route::has('super-admin.master-data.index'))
                    <details
                        class="nav-group"
                        {{ $isMasterDataActive ? 'open' : '' }}
                    >
                        <summary
                            class="nav-group-summary {{ $isMasterDataActive ? 'active' : '' }}"
                            aria-label="Buka atau tutup menu Master Data"
                        >
                            <span class="nav-group-title">
                                <svg class="icon" aria-hidden="true">
                                    <use href="#icon-settings"/>
                                </svg>
                                <span>Master Data</span>
                            </span>

                            <svg class="icon icon-sm nav-chevron" aria-hidden="true">
                                <use href="#icon-chevron"/>
                            </svg>
                        </summary>

                        <div class="nav-submenu">
                            @foreach ($masterDataCategories as $categoryKey => $categoryLabel)
                                <a
                                    href="{{ route('super-admin.master-data.index', ['category' => $categoryKey]) }}"
                                    class="nav-sublink {{ $currentCategory === $categoryKey && $isMasterDataActive ? 'active' : '' }}"
                                    @if ($currentCategory === $categoryKey && $isMasterDataActive) aria-current="page" @endif
                                >
                                    {{ $categoryLabel }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @endif

                @if (Route::has('roles.index'))
                    <a
                        href="{{ route('roles.index') }}"
                        class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                        @if (request()->routeIs('roles.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-shield"/>
                        </svg>
                        <span>Role dan Hak Akses</span>
                    </a>
                @endif

                @if (Route::has('activity-logs.index'))
                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                        @if (request()->routeIs('activity-logs.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-activity"/>
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                @endif

            @endif

            {{-- ADMIN --}}
            @if ($isAdmin)

                <div class="nav-label">PENGELOLAAN DATA</div>

                @if (Route::has('participants.index'))
                    <a
                        href="{{ route('participants.index') }}"
                        class="nav-link {{ request()->routeIs('participants.*') ? 'active' : '' }}"
                        @if (request()->routeIs('participants.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-users"/>
                        </svg>
                        <span>Data Peserta</span>
                    </a>
                @endif

                @if (Route::has('trainings.index'))
                    <a
                        href="{{ route('trainings.index') }}"
                        class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}"
                        @if (request()->routeIs('trainings.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-training"/>
                        </svg>
                        <span>Data Pelatihan</span>
                    </a>
                @endif

                @if (Route::has('imports.index'))
                    <a
                        href="{{ route('imports.index') }}"
                        class="nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('imports.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-upload"/>
                        </svg>
                        <span>Impor Excel</span>
                    </a>
                @endif

                @if (Route::has('reports.index'))
                    <div class="nav-label">PELAPORAN</div>

                    <a
                        href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('reports.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-report"/>
                        </svg>
                        <span>Laporan dan Statistik</span>
                    </a>
                @endif

            @endif

            {{-- PIMPINAN --}}
            @if ($isPimpinan)

                <div class="nav-label">PEMANTAUAN</div>

                @if (Route::has('reports.index'))
                    <a
                        href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('reports.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-report"/>
                        </svg>
                        <span>Laporan Pelatihan</span>
                    </a>
                @endif

            @endif

            {{-- PESERTA --}}
            @if ($isParticipant)

                <div class="nav-label">AKUN PESERTA</div>

                @if (Route::has('participant.profile'))
                    <a
                        href="{{ route('participant.profile') }}"
                        class="nav-link {{ request()->routeIs('participant.profile') ? 'active' : '' }}"
                        @if (request()->routeIs('participant.profile')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-user"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                @endif

            @endif

        </nav>

        {{-- LOGOUT --}}
        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-button">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-logout"/>
                    </svg>
                    <span>Keluar dari Sistem</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN AREA --}}
    <div class="main-area">

        {{-- HEADER --}}
        <header class="topbar" id="appTopbar">

            <div class="topbar-left">

                <div class="topbar-heading">
                    <h2>@yield('title', 'Dashboard')</h2>

                    <div class="breadcrumb" aria-label="Breadcrumb">
                        <span>Bapelkes Kalimantan Selatan</span>

                        <span class="breadcrumb-separator">/</span>

                        <span class="breadcrumb-current">
                            {{ $pageTitle }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="topbar-right">
                <div class="user-info">

                    <div class="user-avatar" aria-hidden="true">
                        {{ $avatarInitial }}
                    </div>

                    <div class="user-details">
                        <span class="user-name">
                            {{ $displayName }}
                        </span>

                        <span class="role-label">
                            {{ $roleLabel }}
                        </span>
                    </div>

                </div>
            </div>

        </header>

        {{-- CONTENT --}}
        <main class="main-content" id="mainContent">

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    <strong>Periksa kembali data yang dimasukkan:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        {{-- FOOTER --}}
        <footer class="app-footer">

            <div class="footer-brand">

                <div class="footer-mark" aria-hidden="true">
                    AR
                </div>

                <div>
                    <span class="footer-title">
                        ARTA
                    </span>

                    <span class="footer-description">
                        Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan
                        · Bapelkes Kalimantan Selatan
                    </span>
                </div>

            </div>

            <div class="footer-meta">
                <div>
                    &copy; {{ date('Y') }}
                    <strong>Bapelkes Kalimantan Selatan</strong>
                </div>

                <div>
                    Sistem pencatatan dan pemantauan riwayat pelatihan tenaga kesehatan.
                </div>
            </div>

        </footer>

    </div>

</div>

{{-- SHELL INTERACTION --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.documentElement;
        var sidebar = document.getElementById('appSidebar');
        var menuButton = document.getElementById('mobileMenuButton');
        var topbar = document.getElementById('appTopbar');
        var progress = document.getElementById('scrollProgress');

        /* =========================================================
           POSISI SCROLL TETAP
           Disimpan sebelum form dikirim / link filter diklik,
           lalu dipulihkan setelah halaman dimuat ulang.
           Juga menyimpan posisi scroll sidebar.
           ========================================================= */
        var SK = 'arta:scroll';
        var SIDE = 'arta:sidebar';
        var saved = null;
        var userMoved = false;

        try {
            var raw = sessionStorage.getItem(SK);
            if (raw) {
                var d = JSON.parse(raw);
                if (d && d.path === location.pathname && Date.now() - d.t < 20000) {
                    saved = d;
                }
                sessionStorage.removeItem(SK);
            }

            var sy = sessionStorage.getItem(SIDE);
            if (sy && sidebar) {
                sidebar.scrollTop = parseInt(sy, 10) || 0;
            }
        } catch (e) {}

        function jumpTo(y) {
            root.style.scrollBehavior = 'auto';
            window.scrollTo(0, y);
            root.style.scrollBehavior = '';
            onScroll();
        }

        function saveScroll() {
            try {
                sessionStorage.setItem(SK, JSON.stringify({
                    y: window.scrollY,
                    path: location.pathname,
                    t: Date.now()
                }));
            } catch (e) {}
        }

        function saveSidebar() {
            try {
                if (sidebar) sessionStorage.setItem(SIDE, String(sidebar.scrollTop));
            } catch (e) {}
        }

        /* Topbar state + scroll progress */
        var ticking = false;

        function onScroll() {
            if (topbar) {
                topbar.classList.toggle('scrolled', window.scrollY > 10);
            }

            if (progress) {
                var max = root.scrollHeight - window.innerHeight;
                progress.style.transform = 'scaleX(' + (max > 0 ? window.scrollY / max : 0) + ')';
            }

            ticking = false;
        }

        onScroll();

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(onScroll);
            }
        }, { passive: true });

        if (saved) {
            jumpTo(saved.y);

            ['wheel', 'touchstart', 'keydown', 'mousedown'].forEach(function (ev) {
                window.addEventListener(ev, function () { userMoved = true; }, { passive: true, once: true });
            });

            /* font & gambar bisa mengubah tinggi halaman: koreksi sekali setelah load */
            window.addEventListener('load', function () {
                if (!userMoved && Math.abs(window.scrollY - saved.y) > 4) {
                    jumpTo(saved.y);
                }
            });
        }

        /* Form GET (filter/pencarian) dan link pagination: tetap di posisi yang sama.
           Form POST (simpan/hapus) biasanya redirect ke halaman lain,
           jadi hanya status loading yang dipasang. */
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!(form instanceof HTMLFormElement) || event.defaultPrevented) return;

            var method = (form.getAttribute('method') || 'GET').toUpperCase();
            var isLogout = form.closest('.sidebar-bottom');

            if (!isLogout && method === 'GET') {
                saveScroll();
            }

            /* cegah klik ganda + tampilkan spinner */
            var submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitter && !form.hasAttribute('data-no-loading')) {
                setTimeout(function () {
                    submitter.classList.add('is-loading');
                    submitter.setAttribute('aria-busy', 'true');
                    submitter.disabled = true;
                }, 0);
            }
        });

        document.addEventListener('click', function (event) {
            var link = event.target.closest('a[href]');
            if (!link) return;

            /* pagination & link yang diberi data-keep-scroll */
            if (link.closest('.pagination') || link.hasAttribute('data-keep-scroll')) {
                saveScroll();
            }

            if (link.closest('#sidebarNavigation')) {
                saveSidebar();
            }
        });

        /* Tombol Back / bfcache: pastikan status loading hilang */
        window.addEventListener('pageshow', function (event) {
            if (!event.persisted) return;

            document.querySelectorAll('.is-loading').forEach(function (el) {
                el.classList.remove('is-loading');
                el.removeAttribute('aria-busy');
                el.disabled = false;
            });
        });

        /* Mobile sidebar */
        if (!sidebar || !menuButton) {
            return;
        }

        function setMenu(open) {
            sidebar.classList.toggle('mobile-open', open);
            menuButton.setAttribute('aria-expanded', String(open));
        }

        menuButton.addEventListener('click', function () {
            setMenu(!sidebar.classList.contains('mobile-open'));
        });

        // Tutup menu setelah pengguna memilih navigasi pada layar mobile.
        sidebar.querySelectorAll('.nav-link, .nav-sublink').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 760) {
                    setMenu(false);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setMenu(false);
            }
        });

        // Kembalikan navigasi ke kondisi desktop saat ukuran layar berubah.
        window.addEventListener('resize', function () {
            if (window.innerWidth > 760) {
                setMenu(false);
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>