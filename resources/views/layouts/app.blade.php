@php
    /* =========================================================
       DATA LAYOUT (logika role & menu dipertahankan)
    ========================================================= */
    $currentUser = auth()->user();

    $role = strtolower(trim((string) ($currentUser?->role ?? '')));
    $role = str_replace('-', '_', $role);

    $roleLabels = [
        'super_admin' => 'Super Admin',
        'superadmin'  => 'Super Admin',
        'admin'       => 'Admin',
        'pimpinan'    => 'Pimpinan',
        'user'        => 'Peserta Pelatihan',
    ];

    $roleLabel = $roleLabels[$role] ?? 'Pengguna';

    $isSuperAdmin  = in_array($role, ['super_admin', 'superadmin'], true);
    $isAdmin       = $role === 'admin';
    $isPimpinan    = $role === 'pimpinan';
    $isParticipant = $role === 'user';

    $masterDataCategories = [
        'provinces'           => 'Provinsi',
        'districts'           => 'Kabupaten/Kota/Mitra/Mandiri',
        'funding-sources'     => 'Sumber Dana',
        'training-years'      => 'Tahun Pelatihan',
        'training-categories' => 'Kategori Pelatihan',
        'health-worker-types' => 'Jenis Tenaga Kesehatan',
    ];

    $currentCategory    = request()->route('category');
    $isMasterDataActive = request()->routeIs('super-admin.master-data.*');

    $rawTitle = trim($__env->yieldContent('title', 'Dashboard'));
    /* buang akhiran " · ARTA" / " - ARTA" agar judul tidak dobel */
    $pageTitle = trim(preg_replace('/\s*[·\-–|]\s*ARTA\s*$/u', '', $rawTitle)) ?: 'Dashboard';

    $displayName = $currentUser?->name
        ?? $currentUser?->username
        ?? 'Pengguna';

    $avatarInitial = strtoupper(mb_substr(trim((string) $displayName), 0, 1));

    /* Route dashboard sesuai role */
    $dashRoute = ($isSuperAdmin && Route::has('super-admin.dashboard'))
        ? 'super-admin.dashboard'
        : (Route::has('dashboard') ? 'dashboard' : null);

    /* Struktur menu (dirender dengan @foreach agar kode ringkas) */
    $sections = [];

    if ($dashRoute) {
        $sections[] = [
            'label' => 'MENU UTAMA',
            'items' => [
                ['type' => 'link', 'route' => $dashRoute, 'pattern' => $dashRoute, 'icon' => 'icon-dashboard', 'label' => 'Dashboard'],
            ],
        ];
    }

    if ($isSuperAdmin) {
        $sections[] = [
            'label' => 'MANAJEMEN SISTEM',
            'items' => [
                ['type' => 'link',  'route' => 'super-admin.users.index',       'pattern' => 'super-admin.users.*',  'icon' => 'icon-users',    'label' => 'Kelola Akun'],
                ['type' => 'group', 'route' => 'super-admin.master-data.index', 'pattern' => 'super-admin.master-data.*', 'icon' => 'icon-settings', 'label' => 'Master Data'],
                ['type' => 'link',  'route' => 'roles.index',                   'pattern' => 'roles.*',              'icon' => 'icon-shield',   'label' => 'Role dan Hak Akses'],
                ['type' => 'link',  'route' => 'activity-logs.index',           'pattern' => 'activity-logs.*',      'icon' => 'icon-activity', 'label' => 'Log Aktivitas'],
            ],
        ];
    }

    if ($isAdmin) {
        $sections[] = [
            'label' => 'PENGELOLAAN DATA',
            'items' => [
                ['type' => 'link', 'route' => 'participants.index', 'pattern' => 'participants.*', 'icon' => 'icon-users',    'label' => 'Data Peserta'],
                ['type' => 'link', 'route' => 'trainings.index',    'pattern' => 'trainings.*',    'icon' => 'icon-training', 'label' => 'Data Pelatihan'],
                ['type' => 'link', 'route' => 'imports.index',      'pattern' => 'imports.*',      'icon' => 'icon-upload',   'label' => 'Impor Excel'],
            ],
        ];
        $sections[] = [
            'label' => 'PELAPORAN',
            'items' => [
                ['type' => 'link', 'route' => 'reports.index', 'pattern' => 'reports.*', 'icon' => 'icon-report', 'label' => 'Laporan dan Statistik'],
            ],
        ];
    }

    if ($isPimpinan) {
        $sections[] = [
            'label' => 'PEMANTAUAN',
            'items' => [
                ['type' => 'link', 'route' => 'reports.index', 'pattern' => 'reports.*', 'icon' => 'icon-report', 'label' => 'Laporan Pelatihan'],
            ],
        ];
    }

    if ($isParticipant) {
        $sections[] = [
            'label' => 'AKUN PESERTA',
            'items' => [
                ['type' => 'link', 'route' => 'participant.profile', 'pattern' => 'participant.profile', 'icon' => 'icon-user', 'label' => 'Profil Saya'],
            ],
        ];
    }

    /* Pintasan footer = link menu yang tersedia untuk role ini */
    $footerLinks = collect($sections)
        ->flatMap(fn ($s) => $s['items'])
        ->filter(fn ($i) => $i['type'] === 'link' && Route::has($i['route']))
        ->take(4);

    $navIndex = 0;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#081b33">

    <title>{{ $pageTitle }} · ARTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =====================================================
           ARTA — APP SHELL (Premium Blue Editorial)
           Nama variabel & class lama dipertahankan agar halaman
           yang belum diredesign tetap tampil benar.
        ===================================================== */
        :root {
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

            --text: #14243a;
            --muted: #56657a;
            --border: #e1e9f2;
            --bg: #f3f7fc;
            --white: #ffffff;

            --success: #166534;
            --danger: #991b1b;

            --sidebar-width: 312px;
            --header-height: 88px;
            --pad-x: clamp(16px, 2.6vw, 48px);

            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 22px;

            --font-display: 'Sora', system-ui, sans-serif;
            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;

            --shadow-soft: 0 8px 30px rgba(8, 27, 51, .05);
            --ease: cubic-bezier(.2, .7, .2, 1);
            --transition: .25s cubic-bezier(.2, .7, .3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }

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

        body.menu-open { overflow: hidden; }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        img { max-width: 100%; }
        ::selection { color: #fff; background: var(--blue); }

        button:focus-visible, a:focus-visible, summary:focus-visible,
        input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(52, 120, 246, .55);
            outline-offset: 3px;
            border-radius: 8px;
        }

        .app-shell { min-height: 100vh; min-height: 100dvh; }

        .skip-link {
            position: fixed; left: 16px; top: -64px; z-index: 600;
            padding: 12px 18px; color: #fff; background: var(--blue);
            border-radius: 10px; font-weight: 700; transition: top .2s;
        }
        .skip-link:focus { top: 12px; }

        /* Scroll progress (progress bar milik halaman disembunyikan agar tidak dobel) */
        .scroll-progress {
            position: fixed; inset: 0 0 auto 0; z-index: 500; height: 3px;
            background: linear-gradient(90deg, var(--electric), var(--cyan));
            transform: scaleX(0); transform-origin: left;
            will-change: transform; pointer-events: none;
        }
        .md-progress, .ld-progress, .arta-progress { display: none !important; }

        /* Icon (satu sistem, gaya Lucide) */
        .icon {
            width: 22px; height: 22px; flex: 0 0 22px;
            display: inline-block; vertical-align: middle;
            fill: none; stroke: currentColor; stroke-width: 1.8;
            stroke-linecap: round; stroke-linejoin: round;
        }
        .icon-sm { width: 18px; height: 18px; flex-basis: 18px; }

        /* =====================================================
           SIDEBAR
        ===================================================== */
        .sidebar {
            position: fixed; inset: 0 auto 0 0; z-index: 200;
            width: var(--sidebar-width);
            height: 100vh; height: 100dvh;
            overflow: hidden; isolation: isolate;
            color: #fff;
            background: linear-gradient(180deg, #081b33 0%, #071729 55%, #050f1f 100%);
            border-right: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-deco { position: absolute; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }

        .sidebar-deco .grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 38px 38px;
            -webkit-mask-image: linear-gradient(180deg, #000, transparent 62%);
            mask-image: linear-gradient(180deg, #000, transparent 62%);
        }

        .sidebar-deco .orb { position: absolute; border-radius: 50%; animation: orb-float 18s ease-in-out infinite; }
        .sidebar-deco .orb.a {
            width: 340px; height: 340px; top: -130px; left: -120px;
            background: radial-gradient(circle, rgba(52,120,246,.5), transparent 68%);
        }
        .sidebar-deco .orb.b {
            width: 300px; height: 300px; bottom: -110px; right: -120px;
            background: radial-gradient(circle, rgba(56,198,232,.26), transparent 68%);
            animation-delay: -8s;
        }

        @keyframes orb-float {
            0%, 100% { transform: translate3d(0, 0, 0); }
            50% { transform: translate3d(22px, 26px, 0); }
        }

        .sidebar-inner {
            display: flex; flex-direction: column;
            height: 100%; overflow-x: hidden; overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin; scrollbar-color: #2c4a72 transparent;
        }
        .sidebar-inner::-webkit-scrollbar { width: 5px; }
        .sidebar-inner::-webkit-scrollbar-thumb { background: #2c4a72; border-radius: 10px; }

        /* Brand (logo & font besar) */
        .brand { position: relative; padding: 30px 24px 24px; border-bottom: 1px solid rgba(255,255,255,.09); }

        .brand-link { display: flex; align-items: center; gap: 18px; color: inherit; }

        .brand-logo-wrap { position: relative; display: grid; place-items: center; width: 92px; height: 92px; flex: 0 0 92px; }

        .brand-ring {
            position: absolute; inset: 0; border-radius: 28px;
            background: conic-gradient(from 0deg, var(--cyan), var(--electric), transparent 40%, var(--cyan));
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 2px));
            mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 2px));
            animation: ring-spin 9s linear infinite;
            opacity: .9;
        }
        @keyframes ring-spin { to { transform: rotate(360deg); } }

        .brand-logo {
            display: block; width: 80px; height: 80px; padding: 8px;
            object-fit: contain; background: #fff; border-radius: 24px;
            box-shadow: 0 14px 34px rgba(0,0,0,.35), 0 0 0 6px rgba(255,255,255,.05);
            transition: transform .4s var(--ease);
        }
        .brand-link:hover .brand-logo { transform: scale(1.06) rotate(-4deg); }

        .brand-text { min-width: 0; }

        .brand-name {
            display: block;
            font-family: var(--font-display);
            font-size: 52px; font-weight: 800; line-height: 1; letter-spacing: -.055em;
            background: linear-gradient(95deg, #fff 30%, #9fd9ff 70%, var(--cyan));
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; color: transparent;
        }

        .brand-tag {
            display: inline-block; margin-top: 8px; padding: 4px 11px;
            color: var(--cyan); font-size: 11px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
            border: 1px solid rgba(56,198,232,.35); border-radius: 999px; background: rgba(56,198,232,.08);
        }

        .brand-desc { margin: 18px 0 0; color: #bcd0e8; font-size: 13.5px; line-height: 1.75; }

        .sidebar-close {
            display: none; position: absolute; top: 14px; right: 14px;
            width: 40px; height: 40px; place-items: center; padding: 0;
            color: #fff; background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.16); border-radius: 12px;
        }

        /* Navigation */
        .sidebar nav { flex: 1; padding: 8px 16px 22px; }

        .nav-label {
            display: flex; align-items: center; gap: 12px;
            padding: 26px 10px 10px;
            color: #86a8d2; font-size: 12px; font-weight: 800; letter-spacing: .14em; line-height: 1.5;
        }
        .nav-label::after { content: ""; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(255,255,255,.16), transparent); }

        .nav-link, .nav-group { animation: nav-in .5s var(--ease) backwards; animation-delay: calc(var(--n, 0) * 55ms + .1s); }

        @keyframes nav-in {
            from { opacity: 0; transform: translate3d(-14px, 0, 0); }
            to { opacity: 1; transform: none; }
        }

        .nav-icon {
            display: grid; place-items: center; width: 42px; height: 42px; flex: 0 0 42px;
            color: #9ec0e8; background: rgba(255,255,255,.06);
            border-radius: 13px;
            transition: background var(--transition), color var(--transition), transform var(--transition), box-shadow var(--transition);
        }

        .nav-link {
            position: relative; display: flex; align-items: center; gap: 14px;
            min-height: 56px; margin: 5px 0; padding: 7px 12px 7px 8px;
            color: #d8e5f5; border-radius: 16px;
            font-size: 16px; font-weight: 600; line-height: 1.4;
            transition: background var(--transition), color var(--transition), transform var(--transition);
        }

        .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; transform: translateX(4px); }
        .nav-link:hover .nav-icon { color: #fff; background: rgba(255,255,255,.12); transform: scale(1.06); }

        .nav-link.active {
            color: #fff; font-weight: 700;
            background: linear-gradient(90deg, rgba(52,120,246,.34), rgba(52,120,246,.07));
            box-shadow: inset 0 0 0 1px rgba(120,170,255,.22);
        }
        .nav-link.active .nav-icon {
            color: #fff;
            background: linear-gradient(135deg, var(--electric), var(--cyan));
            box-shadow: 0 8px 22px rgba(52,120,246,.5);
        }
        .nav-link.active::before {
            content: ""; position: absolute; top: 12px; bottom: 12px; left: -16px; width: 5px;
            border-radius: 0 6px 6px 0; background: var(--cyan);
            box-shadow: 0 0 16px var(--cyan);
        }

        /* Master data submenu */
        .nav-group { margin: 5px 0; }

        .nav-group-summary {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            min-height: 56px; padding: 7px 14px 7px 8px;
            color: #d8e5f5; border-radius: 16px;
            font-size: 16px; font-weight: 600; line-height: 1.4;
            cursor: pointer; list-style: none;
            transition: background var(--transition), color var(--transition);
        }
        .nav-group-summary::-webkit-details-marker { display: none; }
        .nav-group-summary::marker { content: ""; }

        .nav-group-summary:hover, .nav-group[open] > .nav-group-summary { background: rgba(255,255,255,.07); color: #fff; }

        .nav-group-summary.active {
            color: #fff; font-weight: 700;
            background: linear-gradient(90deg, rgba(52,120,246,.34), rgba(52,120,246,.07));
            box-shadow: inset 0 0 0 1px rgba(120,170,255,.22);
        }
        .nav-group-summary.active .nav-icon {
            color: #fff; background: linear-gradient(135deg, var(--electric), var(--cyan));
            box-shadow: 0 8px 22px rgba(52,120,246,.5);
        }

        .nav-group-title { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .nav-chevron { flex: 0 0 auto; color: #9ec0e8; transition: transform var(--transition); }
        .nav-group[open] > .nav-group-summary .nav-chevron { transform: rotate(180deg); }

        .nav-submenu {
            margin: 6px 0 12px 28px; padding: 4px 0 4px 14px;
            border-left: 1px solid rgba(255,255,255,.18);
            animation: submenu-in .25s ease;
        }
        @keyframes submenu-in {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: none; }
        }

        .nav-sublink {
            display: flex; align-items: center; min-height: 44px; margin: 3px 0; padding: 9px 12px;
            color: #c3d5ea; border-radius: 12px;
            font-size: 14.5px; font-weight: 500; line-height: 1.4;
            transition: background var(--transition), color var(--transition), transform var(--transition);
        }
        .nav-sublink::before {
            content: ""; width: 6px; height: 6px; flex: 0 0 6px; margin-right: 12px;
            border-radius: 50%; background: #6c8db6;
            transition: background var(--transition), transform var(--transition);
        }
        .nav-sublink:hover { background: rgba(255,255,255,.07); color: #fff; transform: translateX(3px); }
        .nav-sublink:hover::before { transform: scale(1.5); }
        .nav-sublink.active { background: rgba(52,120,246,.24); color: #fff; font-weight: 700; }
        .nav-sublink.active::before { background: var(--cyan); box-shadow: 0 0 10px var(--cyan); }

        /* Sidebar bottom: kartu sesi + keluar */
        .sidebar-bottom {
            margin-top: auto; padding: 18px 16px 20px;
            border-top: 1px solid rgba(255,255,255,.09);
            background: rgba(4,14,28,.45);
        }

        .session-card {
            display: flex; align-items: center; gap: 13px; margin-bottom: 12px; padding: 12px;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 18px;
        }
        .session-card .user-avatar { width: 46px; height: 46px; flex-basis: 46px; }
        .session-meta { min-width: 0; }
        .session-meta strong {
            display: block; color: #fff; font-size: 15px; font-weight: 700; line-height: 1.3;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .session-meta span { display: block; color: var(--cyan); font-size: 12.5px; font-weight: 700; }

        .sidebar-bottom form { margin: 0; }

        .logout-button {
            display: flex; align-items: center; justify-content: center; gap: 11px;
            width: 100%; min-height: 52px; padding: 11px 14px;
            color: #e8f0fb; background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.14); border-radius: 14px;
            font-size: 15px; font-weight: 700;
            transition: background var(--transition), border-color var(--transition), transform var(--transition);
        }
        .logout-button:hover { background: rgba(239,68,68,.2); border-color: rgba(252,165,165,.55); }
        .logout-button:active { transform: scale(.98); }

        .sidebar button:focus-visible, .sidebar a:focus-visible, .sidebar summary:focus-visible {
            outline-color: var(--cyan);
        }

        .sidebar-overlay {
            position: fixed; inset: 0; z-index: 150;
            background: rgba(4,14,28,.62);
            -webkit-backdrop-filter: blur(3px); backdrop-filter: blur(3px);
            opacity: 0; visibility: hidden;
            transition: opacity .3s ease, visibility 0s .3s;
        }
        .sidebar-overlay.show { opacity: 1; visibility: visible; transition-delay: 0s; }

        /* =====================================================
           MAIN AREA (penuh layar)
        ===================================================== */
        .main-area {
            display: flex; flex-direction: column;
            min-height: 100vh; min-height: 100dvh;
            margin-left: var(--sidebar-width);
        }

        /* Topbar */
        .topbar {
            position: sticky; top: 0; z-index: 90;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            min-height: var(--header-height);
            padding: 14px var(--pad-x);
            background: rgba(243,247,252,.86);
            border-bottom: 1px solid transparent;
            -webkit-backdrop-filter: blur(16px); backdrop-filter: blur(16px);
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        }
        .topbar.scrolled {
            background: rgba(255,255,255,.9);
            border-color: var(--border);
            box-shadow: 0 12px 34px rgba(8,27,51,.07);
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
        .topbar-heading { min-width: 0; }

        .topbar h2 {
            margin: 0 0 2px; color: var(--navy);
            font-family: var(--font-display);
            font-size: clamp(22px, 2.5vw, 32px); font-weight: 800;
            letter-spacing: -.04em; line-height: 1.2; overflow-wrap: anywhere;
        }

        .breadcrumb {
            display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
            color: var(--muted); font-size: 13.5px; line-height: 1.5;
        }
        .breadcrumb-separator { color: #a9b8ca; }
        .breadcrumb-current { color: var(--blue); font-weight: 700; }

        .mobile-menu-button {
            display: none; place-items: center; width: 48px; height: 48px; flex: 0 0 48px; padding: 0;
            color: #fff; background: linear-gradient(135deg, var(--blue), var(--electric));
            border: 0; border-radius: 15px;
            box-shadow: 0 10px 22px rgba(36,87,214,.35);
            transition: transform var(--transition);
        }
        .mobile-menu-button:active { transform: scale(.94); }

        .topbar-right { display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex: 0 0 auto; }

        .clock-chip {
            display: flex; align-items: center; gap: 11px; min-width: 238px; padding: 9px 16px;
            color: var(--navy); background: #fff;
            border: 1px solid var(--border); border-radius: 999px;
        }
        .clock-chip .icon { color: var(--blue); }
        .clock-chip strong { display: block; font-size: 13.5px; font-weight: 700; line-height: 1.25; }
        .clock-chip span { display: block; color: var(--muted); font-size: 12px; font-weight: 600; line-height: 1.25; font-variant-numeric: tabular-nums; }

        .user-info {
            display: flex; align-items: center; gap: 12px; padding: 6px 20px 6px 6px;
            background: #fff; border: 1px solid var(--border); border-radius: 999px;
            transition: box-shadow var(--transition), transform var(--transition);
        }
        .user-info:hover { box-shadow: var(--shadow-soft); transform: translateY(-1px); }

        .user-avatar {
            position: relative; display: grid; place-items: center;
            width: 46px; height: 46px; flex: 0 0 46px;
            color: #fff; background: linear-gradient(145deg, var(--blue), var(--navy));
            border-radius: 50%;
            font-family: var(--font-display); font-size: 16px; font-weight: 700;
        }
        .user-avatar.online::after {
            content: ""; position: absolute; right: 0; bottom: 0; width: 12px; height: 12px;
            background: #22c55e; border: 2px solid #fff; border-radius: 50%;
        }
        .session-card .user-avatar.online::after { border-color: #0b2545; }

        .user-details { min-width: 0; }
        .user-name {
            display: block; max-width: 220px; color: var(--navy);
            font-size: 14.5px; font-weight: 700; line-height: 1.35; overflow-wrap: anywhere;
        }
        .role-label { display: block; margin-top: 1px; color: var(--blue); font-size: 12.5px; font-weight: 700; line-height: 1.35; }

        /* Main content */
        .main-content { flex: 1; min-width: 0; width: 100%; padding: 24px var(--pad-x) 36px; }

        .main-content > * { animation: page-in .55s var(--ease) backwards; }
        .main-content > *:nth-child(2) { animation-delay: .05s; }
        .main-content > *:nth-child(3) { animation-delay: .1s; }
        .main-content > *:nth-child(4) { animation-delay: .15s; }
        .main-content > *:nth-child(n+5) { animation-delay: .2s; }

        @keyframes page-in {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: none; }
        }

        /* Halaman yang sudah punya banner notifikasi sendiri: sembunyikan alert layout agar tidak dobel */
        .main-content:has(.md-alert, .arta-alert) > .alert { display: none; }

        /* =====================================================
           CONTENT COMPONENTS (kompatibel dengan halaman lama)
        ===================================================== */
        .page-heading, .page-header {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
            gap: 18px; margin-bottom: 28px;
        }
        .page-heading h1, .page-header h1 {
            margin: 0 0 8px; color: var(--navy); font-family: var(--font-display);
            font-size: clamp(28px, 3.4vw, 42px); font-weight: 800; letter-spacing: -.04em; line-height: 1.15;
        }
        .page-heading p, .page-header p { margin: 0; color: var(--muted); font-size: 16px; line-height: 1.75; }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 10px; margin-bottom: 10px;
            color: var(--blue); font-size: 14px; font-weight: 700;
        }
        .eyebrow::before { content: ""; width: 26px; height: 2px; background: var(--cyan); }

        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }

        .stat-card, .content-card, .panel, .form-card {
            position: relative; padding: 28px; background: #fff;
            border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft);
        }

        .stat-card { overflow: hidden; transition: transform var(--transition), box-shadow var(--transition); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 44px rgba(8,27,51,.09); }
        .stat-card::before {
            content: ""; position: absolute; inset: 0 0 auto 0; height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--cyan));
            transform: scaleX(0); transform-origin: left; transition: transform .5s var(--transition);
        }
        .stat-card:hover::before { transform: scaleX(1); }

        .stat-label { color: var(--muted); font-size: 14px; font-weight: 600; line-height: 1.6; }

        .stat-number, .stat-value {
            margin-top: 10px; color: var(--navy); font-family: var(--font-display);
            font-size: clamp(32px, 3.2vw, 46px); font-weight: 800; letter-spacing: -.04em; line-height: 1.15;
            font-variant-numeric: tabular-nums; overflow-wrap: anywhere;
        }
        .stat-note { margin-top: 8px; color: var(--muted); font-size: 14px; line-height: 1.6; }

        .content-card, .panel { margin-bottom: 24px; overflow: hidden; }

        .content-card h2, .panel h2, .form-card h2 {
            margin: 0 0 12px; color: var(--navy); font-family: var(--font-display);
            font-size: 22px; font-weight: 700; letter-spacing: -.025em; line-height: 1.35;
        }
        .content-card p, .panel p { color: var(--muted); font-size: 15px; line-height: 1.75; }

        .dashboard-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            min-height: 44px; padding: 10px 20px; color: #fff; background: var(--blue);
            border: 1.5px solid transparent; border-radius: 999px;
            font-size: 14px; font-weight: 700; line-height: 1.4; text-align: center;
            transition: background var(--transition), color var(--transition), border-color var(--transition), transform var(--transition), box-shadow var(--transition);
        }
        .btn:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 10px 22px rgba(36,87,214,.22); }
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

        /* Spinner otomatis (hanya untuk tombol yang belum punya loading sendiri) */
        [data-layout-loading] { position: relative; pointer-events: none; opacity: .85; }
        [data-layout-loading]::after {
            content: ""; width: 15px; height: 15px; margin-left: 8px; flex: none;
            border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%;
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
            display: block; width: 100%; min-width: 0; padding: 12px 14px;
            color: var(--text); background: #fff;
            border: 1.5px solid var(--border); border-radius: var(--radius-md);
            font-size: 15px;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        input:hover, select:hover, textarea:hover { border-color: #a6bddb; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
        input[type="checkbox"], input[type="radio"] { display: inline-block; width: auto; accent-color: var(--blue); }

        .form-help { display: block; margin-top: 6px; color: var(--muted); font-size: 13px; }
        .field-error { display: block; margin-top: 6px; color: #b91c1c; font-size: 13px; }
        .input-error { border-color: #dc2626; }

        .form-actions, .filter-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-top: 16px; }

        /* Alerts */
        .alert {
            display: block; padding: 16px 20px; margin-bottom: 22px;
            color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe;
            border-radius: var(--radius-md); font-size: 15px;
        }
        .alert-success { color: var(--success); background: #f0fdf4; border-color: #bbf7d0; }
        .alert-error, .alert-danger { color: var(--danger); background: #fef2f2; border-color: #fecaca; }
        .alert ul { margin: 8px 0 0; padding-left: 22px; }

        /* Tables (margin atas hanya di dalam kartu agar tabel halaman baru tidak terdorong) */
        .table-responsive, .table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 15px; }
        .content-card table, .panel table, .form-card table, .table-responsive table, .table-wrap table { margin-top: 16px; }

        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #edf1f5; vertical-align: middle; }
        th { color: #506176; background: var(--blue-light); font-size: 13px; font-weight: 700; letter-spacing: .02em; white-space: nowrap; }
        td { line-height: 1.6; }
        tbody tr { transition: background var(--transition); }
        tbody tr:hover { background: #f7faff; }

        .table-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .table-actions form { margin: 0; }
        .table-actions button, .table-actions a { white-space: nowrap; }

        /* Badges */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .badge-admin { color: #1b45b0; background: #dbe8fc; }
        .badge-pimpinan { color: #166534; background: #dcfce7; }
        .badge-neutral { color: #334155; background: #e2e8f0; }
        .badge-success { color: #166534; background: #dcfce7; }
        .badge-secondary { color: #475569; background: #e2e8f0; }

        /* Filter + pagination */
        .filter-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(170px, 220px) auto; gap: 12px; align-items: end; }

        .pagination { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 20px; }
        .pagination a, .pagination span {
            display: inline-block; padding: 8px 14px; background: #fff;
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600; transition: var(--transition);
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
            display: inline-block; padding: 4px 12px; color: var(--blue); background: var(--blue-soft);
            border-radius: 999px; font-size: 13px; font-weight: 700;
        }

        /* =====================================================
           FOOTER (ringkas & polos)
        ===================================================== */
        .app-footer {
            margin: 0 var(--pad-x) 20px;
            padding: 16px 22px;
            color: var(--muted);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 13.5px;
            line-height: 1.5;
        }

        .footer-row {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
            gap: 12px 28px;
        }

        .footer-brand { display: flex; align-items: center; gap: 12px; min-width: 0; }

        .footer-logo { width: 38px; height: 38px; flex: 0 0 38px; object-fit: contain; }

        .footer-brand-text strong {
            display: block; color: var(--navy);
            font-family: var(--font-display); font-size: 15px; font-weight: 700; line-height: 1.3;
        }
        .footer-brand-text span { display: block; font-size: 12.5px; }

        .footer-links { display: flex; flex-wrap: wrap; gap: 4px 20px; margin: 0; padding: 0; list-style: none; }
        .footer-links a {
            color: var(--muted); font-size: 13.5px; font-weight: 600;
            transition: color var(--transition);
        }
        .footer-links a:hover { color: var(--blue); }

        .footer-bottom {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px 16px;
            margin-top: 12px; padding-top: 12px;
            border-top: 1px solid var(--border);
            font-size: 12.5px;
        }

        .to-top {
            display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px;
            color: var(--muted); background: transparent;
            border: 0; border-radius: 8px;
            font-size: 12.5px; font-weight: 600;
            transition: color var(--transition), background var(--transition);
        }
        .to-top:hover { color: var(--blue); background: var(--blue-soft); }

        /* =====================================================
           RESPONSIVE
        ===================================================== */
        @media (max-width: 1280px) {
            .clock-chip { display: none; }
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        /* Tablet & HP: sidebar menjadi drawer, konten penuh layar */
        @media (max-width: 1024px) {
            .sidebar {
                width: min(88vw, 348px);
                transform: translate3d(-102%, 0, 0);
                visibility: hidden;
                transition: transform .4s var(--ease), visibility 0s .4s;
                box-shadow: none;
            }
            .sidebar.open {
                transform: none; visibility: visible;
                transition-delay: 0s;
                box-shadow: 30px 0 80px rgba(0,0,0,.45);
            }
            .sidebar-close { display: grid; }
            .brand { padding-right: 64px; }

            .main-area { margin-left: 0; }
            .mobile-menu-button { display: grid; }
            .topbar { padding-top: 12px; padding-bottom: 12px; }
        }

        @media (max-width: 900px) {
            .filter-grid { grid-template-columns: 1fr 1fr; }
            .filter-grid .filter-actions { grid-column: 1 / -1; }
        }

        @media (max-width: 760px) {
            :root { --pad-x: 14px; --header-height: 76px; }

            .main-content { padding: 18px var(--pad-x) 26px; }
            .stat-card, .content-card, .panel, .form-card { padding: 20px; border-radius: 18px; }

            .user-details { display: none; }
            .user-info { padding: 5px; }

            .topbar h2 { font-size: 21px; }
            .breadcrumb { font-size: 12.5px; }
            .breadcrumb > span:first-child { display: none; }
            .breadcrumb-separator { display: none; }

            .app-footer { padding: 14px 16px; margin-bottom: 14px; }
            .footer-row { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 560px) {
            .form-grid, .filter-grid { grid-template-columns: 1fr; }
            .filter-grid .filter-actions { grid-column: auto; }
            .stats-grid { grid-template-columns: 1fr; gap: 12px; }
            .inline-actions .btn { flex: 1 1 auto; }
            th, td { padding: 12px; }

            .brand-name { font-size: 46px; }
            .brand-logo-wrap { width: 84px; height: 84px; flex-basis: 84px; }
            .brand-logo { width: 72px; height: 72px; }
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

{{-- SVG ICON LIBRARY (gaya Lucide) --}}
<svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden" aria-hidden="true">
    <symbol id="icon-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></symbol>
    <symbol id="icon-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    <symbol id="icon-settings" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></symbol>
    <symbol id="icon-shield" viewBox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="icon-activity" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="m7 14 4-4 4 3 5-7"/></symbol>
    <symbol id="icon-training" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8M8 11h8"/></symbol>
    <symbol id="icon-upload" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5M12 3v12"/></symbol>
    <symbol id="icon-report" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h8M8 9h2"/></symbol>
    <symbol id="icon-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="icon-chevron" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="icon-logout" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></symbol>
    <symbol id="icon-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="icon-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="icon-calendar" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="icon-arrow-right" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></symbol>
    <symbol id="icon-arrow-up" viewBox="0 0 24 24"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></symbol>
</svg>

<div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

<div class="app-shell">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="sidebar" id="appSidebar" aria-label="Sidebar">

        <div class="sidebar-deco" aria-hidden="true">
            <span class="grid"></span>
            <span class="orb a"></span>
            <span class="orb b"></span>
        </div>

        <div class="sidebar-inner" id="sidebarScroll">

            <div class="brand">
                <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
                    <svg class="icon" aria-hidden="true"><use href="#icon-x"/></svg>
                </button>

                <a href="{{ url('/') }}" class="brand-link" aria-label="ARTA, ke beranda">
                    <span class="brand-logo-wrap">
                        <span class="brand-ring" aria-hidden="true"></span>
                        <img
                            src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                            alt="Logo Provinsi Kalimantan Selatan"
                            class="brand-logo"
                            width="80" height="80"
                            decoding="async"
                        >
                    </span>

                    <span class="brand-text">
                        <span class="brand-name">ARTA</span>
                        <span class="brand-tag">Bapelkes Kalsel</span>
                    </span>
                </a>

                <p class="brand-desc">
                    Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan
                </p>
            </div>

            <nav id="sidebarNavigation" aria-label="Navigasi utama">

                @foreach ($sections as $section)
                    @php
                        $visibleItems = collect($section['items'])->filter(fn ($i) => Route::has($i['route']));
                    @endphp

                    @if ($visibleItems->isNotEmpty())
                        <div class="nav-label">{{ $section['label'] }}</div>

                        @foreach ($visibleItems as $item)
                            @php $navIndex++; @endphp

                            @if ($item['type'] === 'group')
                                <details class="nav-group" style="--n: {{ $navIndex }}" {{ $isMasterDataActive ? 'open' : '' }}>
                                    <summary
                                        class="nav-group-summary {{ $isMasterDataActive ? 'active' : '' }}"
                                        aria-label="Buka atau tutup menu {{ $item['label'] }}"
                                    >
                                        <span class="nav-group-title">
                                            <span class="nav-icon">
                                                <svg class="icon" aria-hidden="true"><use href="#{{ $item['icon'] }}"/></svg>
                                            </span>
                                            <span>{{ $item['label'] }}</span>
                                        </span>

                                        <svg class="icon icon-sm nav-chevron" aria-hidden="true"><use href="#icon-chevron"/></svg>
                                    </summary>

                                    <div class="nav-submenu">
                                        @foreach ($masterDataCategories as $categoryKey => $categoryLabel)
                                            @php $subActive = $currentCategory === $categoryKey && $isMasterDataActive; @endphp
                                            <a
                                                href="{{ route('super-admin.master-data.index', ['category' => $categoryKey]) }}"
                                                class="nav-sublink {{ $subActive ? 'active' : '' }}"
                                                @if ($subActive) aria-current="page" @endif
                                            >
                                                {{ $categoryLabel }}
                                            </a>
                                        @endforeach
                                    </div>
                                </details>
                            @else
                                @php $isActive = request()->routeIs($item['pattern']); @endphp
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="nav-link {{ $isActive ? 'active' : '' }}"
                                    style="--n: {{ $navIndex }}"
                                    @if ($isActive) aria-current="page" @endif
                                >
                                    <span class="nav-icon">
                                        <svg class="icon" aria-hidden="true"><use href="#{{ $item['icon'] }}"/></svg>
                                    </span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

            </nav>

            {{-- SESI + KELUAR --}}
            <div class="sidebar-bottom">
                <div class="session-card">
                    <div class="user-avatar online" aria-hidden="true">{{ $avatarInitial }}</div>
                    <div class="session-meta">
                        <strong>{{ $displayName }}</strong>
                        <span>{{ $roleLabel }}</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" data-no-loading>
                    @csrf

                    <button type="submit" class="logout-button">
                        <svg class="icon" aria-hidden="true"><use href="#icon-logout"/></svg>
                        <span>Keluar dari Sistem</span>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    {{-- ===================== MAIN AREA ===================== --}}
    <div class="main-area">

        {{-- HEADER --}}
        <header class="topbar" id="appTopbar">

            <div class="topbar-left">
                <button
                    type="button"
                    class="mobile-menu-button"
                    id="mobileMenuButton"
                    aria-expanded="false"
                    aria-controls="appSidebar"
                    aria-label="Buka menu navigasi"
                >
                    <svg class="icon" aria-hidden="true"><use href="#icon-menu"/></svg>
                </button>

                <div class="topbar-heading">
                    <h2>{{ $pageTitle }}</h2>

                    <div class="breadcrumb" aria-label="Breadcrumb">
                        <span>Bapelkes Kalimantan Selatan</span>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">{{ $pageTitle }}</span>
                    </div>
                </div>
            </div>

            <div class="topbar-right">
                <div class="clock-chip" aria-hidden="true">
                    <svg class="icon" aria-hidden="true"><use href="#icon-calendar"/></svg>
                    <div>
                        <strong id="topDate">&nbsp;</strong>
                        <span id="topTime">&nbsp;</span>
                    </div>
                </div>

                <div class="user-info">
                    <div class="user-avatar online" aria-hidden="true">{{ $avatarInitial }}</div>

                    <div class="user-details">
                        <span class="user-name">{{ $displayName }}</span>
                        <span class="role-label">{{ $roleLabel }}</span>
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

        {{-- ===================== FOOTER ===================== --}}
        <footer class="app-footer">

            <div class="footer-row">
                <div class="footer-brand">
                    <img
                        src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan"
                        class="footer-logo"
                        width="38" height="38"
                        loading="lazy"
                        decoding="async"
                    >

                    <div class="footer-brand-text">
                        <strong>ARTA</strong>
                        <span>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</span>
                    </div>
                </div>

                @if ($footerLinks->isNotEmpty())
                    <ul class="footer-links" aria-label="Pintasan">
                        @foreach ($footerLinks as $link)
                            <li><a href="{{ route($link['route']) }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} Bapelkes Kalimantan Selatan. Seluruh hak dilindungi.
                </div>

                <button type="button" class="to-top" id="backToTop">
                    <svg class="icon icon-sm" aria-hidden="true"><use href="#icon-arrow-up"/></svg>
                    Kembali ke atas
                </button>
            </div>

        </footer>

    </div>

</div>

{{-- SHELL INTERACTION --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.documentElement;
        var sidebar = document.getElementById('appSidebar');
        var sideScroll = document.getElementById('sidebarScroll');
        var menuButton = document.getElementById('mobileMenuButton');
        var closeButton = document.getElementById('sidebarClose');
        var overlay = document.getElementById('sidebarOverlay');
        var topbar = document.getElementById('appTopbar');
        var progress = document.getElementById('scrollProgress');

        /* ---------- 1. Tanggal & jam di header ---------- */
        var dateEl = document.getElementById('topDate');
        var timeEl = document.getElementById('topTime');

        function tickClock() {
            if (!dateEl || !timeEl) return;
            var n = new Date();
            dateEl.textContent = n.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
            timeEl.textContent = n.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            }).replace('.', ':');
        }
        tickClock();
        setInterval(tickClock, 20000);

        /* ---------- 2. Posisi scroll tetap ---------- */
        var SK = 'arta:scroll';
        var SIDE = 'arta:sidebar';
        var saved = null;
        var userMoved = false;
        var sideRestored = false;

        try {
            var raw = sessionStorage.getItem(SK);
            if (raw) {
                var d = JSON.parse(raw);
                if (d && d.path === location.pathname && Date.now() - d.t < 20000) { saved = d; }
                sessionStorage.removeItem(SK);
            }

            var sy = sessionStorage.getItem(SIDE);
            if (sy && sideScroll) {
                sideScroll.scrollTop = parseInt(sy, 10) || 0;
                sideRestored = true;
            }
        } catch (e) {}

        /* pastikan menu aktif terlihat di sidebar (tanpa menggeser halaman) */
        if (!sideRestored && sideScroll) {
            var activeEl = sideScroll.querySelector('.nav-link.active, .nav-sublink.active');
            if (activeEl) {
                var top = activeEl.getBoundingClientRect().top - sideScroll.getBoundingClientRect().top + sideScroll.scrollTop;
                if (top > sideScroll.clientHeight - 160) {
                    sideScroll.scrollTop = top - sideScroll.clientHeight / 2;
                }
            }
        }

        function jumpTo(y) {
            root.style.scrollBehavior = 'auto';
            window.scrollTo(0, y);
            root.style.scrollBehavior = '';
            onScroll();
        }

        function saveScroll() {
            try {
                sessionStorage.setItem(SK, JSON.stringify({
                    y: window.scrollY, path: location.pathname, t: Date.now()
                }));
            } catch (e) {}
        }

        function saveSidebar() {
            try { if (sideScroll) sessionStorage.setItem(SIDE, String(sideScroll.scrollTop)); } catch (e) {}
        }

        /* ---------- 3. Topbar + progress ---------- */
        var ticking = false;

        function onScroll() {
            if (topbar) topbar.classList.toggle('scrolled', window.scrollY > 10);

            if (progress) {
                var max = root.scrollHeight - window.innerHeight;
                progress.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, window.scrollY / max) : 0) + ')';
            }
            ticking = false;
        }

        onScroll();
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
        }, { passive: true });
        window.addEventListener('resize', onScroll);

        if (saved) {
            jumpTo(saved.y);

            ['wheel', 'touchstart', 'keydown', 'mousedown'].forEach(function (ev) {
                window.addEventListener(ev, function () { userMoved = true; }, { passive: true, once: true });
            });

            /* font & gambar bisa mengubah tinggi halaman: koreksi sekali setelah load */
            window.addEventListener('load', function () {
                if (!userMoved && Math.abs(window.scrollY - saved.y) > 4) { jumpTo(saved.y); }
            });
        }

        /* ---------- 4. Form submit: simpan posisi + loading ---------- */
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!(form instanceof HTMLFormElement) || event.defaultPrevented) return;

            var isLogout = form.hasAttribute('data-no-loading');

            /* disimpan untuk semua form kecuali keluar; hanya dipakai bila halaman tujuan sama */
            if (!isLogout) saveScroll();

            var submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitter || isLogout) return;

            /* jika halaman sudah memasang loading sendiri, jangan dobel */
            if (submitter.classList.contains('is-loading')) return;

            setTimeout(function () {
                submitter.setAttribute('data-layout-loading', '');
                submitter.setAttribute('aria-busy', 'true');
                submitter.disabled = true;
            }, 0);
        });

        document.addEventListener('click', function (event) {
            var link = event.target.closest('a[href]');
            if (!link) return;

            if (link.closest('.pagination, .md-pagination-links') || link.hasAttribute('data-keep-scroll')) {
                saveScroll();
            }
            if (link.closest('#sidebarNavigation')) { saveSidebar(); }
        });

        /* Tombol Back / bfcache: bersihkan status loading */
        window.addEventListener('pageshow', function (event) {
            if (!event.persisted) return;

            document.querySelectorAll('[data-layout-loading], .is-loading').forEach(function (el) {
                el.removeAttribute('data-layout-loading');
                el.classList.remove('is-loading');
                el.removeAttribute('aria-busy');
                el.disabled = false;
            });
        });

        /* ---------- 5. Drawer sidebar (HP & tablet) ---------- */
        function setMenu(open, moveFocus) {
            if (!sidebar) return;
            sidebar.classList.toggle('open', open);
            if (overlay) overlay.classList.toggle('show', open);
            document.body.classList.toggle('menu-open', open);
            if (menuButton) {
                menuButton.setAttribute('aria-expanded', String(open));
                menuButton.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            }
            if (moveFocus) {
                if (open && closeButton) { closeButton.focus({ preventScroll: true }); }
                else if (!open && menuButton) { menuButton.focus({ preventScroll: true }); }
            }
        }

        if (menuButton) {
            menuButton.addEventListener('click', function () {
                setMenu(!sidebar.classList.contains('open'), true);
            });
        }
        if (closeButton) closeButton.addEventListener('click', function () { setMenu(false, true); });
        if (overlay) overlay.addEventListener('click', function () { setMenu(false, true); });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                setMenu(false, true);
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 1024 && sidebar && sidebar.classList.contains('open')) {
                setMenu(false, false);
            }
        });

        /* ---------- 6. Kembali ke atas (aksi eksplisit dari pengguna) ---------- */
        var toTop = document.getElementById('backToTop');
        if (toTop) {
            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });
</script>

@stack('scripts')

</body>
</html>