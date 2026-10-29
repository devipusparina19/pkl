<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIRAPIK</title>

    <style>
        :root {
            --navy: #12315b;
            --navy-dark: #0b2344;
            --blue: #2563a6;
            --blue-light: #edf4fc;
            --text: #1e293b;
            --muted: #64748b;
            --border: #dce4ee;
            --bg: #f3f6fa;
            --success: #166534;
            --danger: #991b1b;
            --sidebar-width: 286px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Inter, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        .app-shell { min-height: 100vh; }

        /* IKON SVG */
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

        .icon-sm {
            width: 17px;
            height: 17px;
            flex-basis: 17px;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #12315b 0%, #0b2344 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 5px 0 24px rgba(15, 35, 65, .08);
            border-right: 1px solid rgba(255,255,255,.08);
            scrollbar-width: thin;
            scrollbar-color: #49627f transparent;
        }

        .brand {
            padding: 29px 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,.13);
        }

        
.brand h2 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 12px;
    font-size: 29px;
    font-weight: 800;
    letter-spacing: 1.6px;
    line-height: 1.2;
}

.brand-logo {
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    object-fit: contain;
    display: block;
}

        .brand p {
            margin: 0;
            padding-left: 2px;
            color: #c5d4e8;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.8;
            letter-spacing: .15px;
        }

        .sidebar nav {
            padding: 12px 13px 20px;
        }

        .nav-label {
            padding: 21px 12px 9px;
            color: #aebfd5;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: 1.35px;
            line-height: 1.5;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 48px;
            margin: 4px 0;
            padding: 12px 13px;
            color: #dbe6f3;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 550;
            line-height: 1.45;
            transition: background .18s ease, color .18s ease,
                        border-color .18s ease, transform .18s ease;
            overflow-wrap: anywhere;
        }

        .nav-link .icon {
            color: #b5c9e1;
            transition: color .18s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.075);
            color: #fff;
            transform: translateX(2px);
        }

        .nav-link:hover .icon { color: #fff; }

        .nav-link.active {
            background: #fff;
            color: #12315b;
            border-color: rgba(255,255,255,.65);
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        .nav-link.active .icon { color: #1d5a99; }

        /* MASTER DATA */
        .nav-group { margin: 4px 0; }

        .nav-group-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 48px;
            margin: 0;
            padding: 12px 13px;
            color: #dbe6f3;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 550;
            line-height: 1.45;
            cursor: pointer;
            list-style: none;
            transition: background .18s ease, color .18s ease;
        }

        .nav-group-summary::-webkit-details-marker { display: none; }
        .nav-group-summary::marker { content: ""; }

        .nav-group-summary:hover,
        .nav-group[open] > .nav-group-summary {
            background: rgba(255,255,255,.075);
            color: #fff;
        }

        .nav-group-summary.active {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-color: rgba(255,255,255,.10);
        }

        .nav-group-title {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .nav-group-title .icon { color: #b5c9e1; }

        .nav-chevron {
            flex: 0 0 auto;
            width: 17px;
            height: 17px;
            transition: transform .2s ease;
        }

        .nav-group[open] > .nav-group-summary .nav-chevron {
            transform: rotate(180deg);
        }

        .nav-submenu {
            margin: 5px 0 9px 24px;
            padding: 4px 0 4px 15px;
            border-left: 1px solid rgba(255,255,255,.24);
        }

        .nav-sublink {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 39px;
            margin: 3px 0;
            padding: 9px 11px;
            color: #c2d1e4;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.45;
            transition: background .18s ease, color .18s ease;
            overflow-wrap: anywhere;
        }

        .nav-sublink::before {
            content: "";
            width: 5px;
            height: 5px;
            flex: 0 0 5px;
            margin-right: 10px;
            border-radius: 50%;
            background: #7f9bbd;
        }

        .nav-sublink:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .nav-sublink.active {
            background: rgba(255,255,255,.14);
            color: #fff;
            font-weight: 700;
        }

        .nav-sublink.active::before {
            width: 6px;
            height: 6px;
            flex-basis: 6px;
            background: #93c5fd;
        }

        /* LOGOUT */
        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 16px 19px;
            border-top: 1px solid rgba(255,255,255,.13);
            background: rgba(4,20,42,.18);
        }

        .sidebar-bottom form { margin: 0; }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            min-height: 47px;
            padding: 11px 14px;
            background: rgba(255,255,255,.07);
            color: #f1f5f9;
            text-align: center;
            border: 1px solid rgba(255,255,255,.17);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 650;
            transition: background .18s ease, border-color .18s ease;
        }

        .logout-button:hover {
            background: rgba(220,38,38,.18);
            border-color: rgba(252,165,165,.45);
        }

        /* AREA KONTEN */
        .main-area {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            min-height: 88px;
            padding: 18px 34px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            box-shadow: 0 2px 8px rgba(15,23,42,.025);
        }

        .topbar h2 {
            margin: 0 0 3px;
            font-size: 22px;
            font-weight: 750;
            letter-spacing: -.35px;
            line-height: 1.4;
            color: #152d4d;
        }

        .topbar small {
            color: var(--muted);
            font-size: 13px;
        }

        .user-info { display: flex; align-items: center; gap: 12px; }

        .user-chip {
            position: relative;
            padding: 10px 16px 10px 45px;
            background: #f1f6fc;
            color: #17375e;
            border: 1px solid #e0eaf5;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .user-chip::before {
            content: "";
            position: absolute;
            left: 13px;
            top: 50%;
            width: 23px;
            height: 23px;
            transform: translateY(-50%);
            border-radius: 7px;
            background: #dbeafe;
        }

        .user-chip::after {
            content: "";
            position: absolute;
            left: 20px;
            top: calc(50% - 7px);
            width: 9px;
            height: 9px;
            border: 1.7px solid #24558b;
            border-radius: 50%;
        }

        .role-label {
            display: block;
            margin-top: 3px;
            color: #647b96;
            font-size: 12px;
            font-weight: 500;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 32px;
        }

        /* JUDUL */
        .page-heading, .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 27px;
        }

        .page-heading h1, .page-header h1 {
            margin: 0 0 8px;
            color: #17375e;
            font-size: 30px;
            font-weight: 750;
            letter-spacing: -.5px;
            line-height: 1.3;
        }

        .page-heading p, .page-header p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 8px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 750;
            letter-spacing: 1.1px;
        }

        /* KARTU */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card, .content-card, .panel, .form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(15,23,42,.035);
        }

        .stat-label {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .stat-number, .stat-value {
            margin-top: 10px;
            color: var(--navy);
            font-size: 32px;
            font-weight: 750;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .stat-note {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .content-card, .panel { margin-bottom: 23px; overflow: hidden; }

        .content-card h2, .panel h2, .form-card h2 {
            margin: 0 0 12px;
            color: #17375e;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.4;
        }

        .content-card p, .panel p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        /* TOMBOL */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 650;
            line-height: 1.5;
            cursor: pointer;
            text-align: center;
            transition: background .18s ease, box-shadow .18s ease;
        }

        .btn:hover {
            background: #1e40af;
            box-shadow: 0 3px 9px rgba(37,99,235,.15);
        }

        .btn-primary { background: var(--blue); color: white; }
        .btn-light { background: #eaf0f8; color: var(--navy); }
        .btn-light:hover, .btn-secondary:hover { background: #dbe5f2; }
        .btn-secondary { background: #e2e8f0; color: #1e293b; }
        .btn-outline { background: white; color: var(--navy); border-color: #cbd5e1; }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-danger { background: #b91c1c; color: white; }
        .btn-danger:hover { background: #991b1b; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }

        .inline-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
        }

        /* FORM */
        .form-card { margin-bottom: 24px; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 20px;
        }

        .form-group { min-width: 0; margin-bottom: 19px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 650;
        }

        input, select, textarea {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            color: var(--text);
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            outline: 3px solid #dbeafe;
            border-color: var(--blue);
        }

        input[type="checkbox"], input[type="radio"] {
            display: inline-block;
            width: auto;
        }

        .form-help {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .field-error {
            display: block;
            margin-top: 5px;
            color: #b91c1c;
            font-size: 12px;
        }

        .input-error { border-color: #dc2626; }

        .form-actions, .filter-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        /* NOTIFIKASI */
        .alert {
            padding: 15px 18px;
            border-radius: 9px;
            margin-bottom: 22px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            font-size: 14px;
        }

        .alert-success {
            background: #f0fdf4;
            color: var(--success);
            border-color: #bbf7d0;
        }

        .alert-error, .alert-danger {
            background: #fef2f2;
            color: var(--danger);
            border-color: #fecaca;
        }

        .alert ul { margin-bottom: 0; padding-left: 22px; }

        /* TABEL */
        .table-responsive, .table-wrap { width: 100%; overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 14px;
        }

        th, td {
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            background: #f5f8fc;
            color: #475569;
            font-weight: 750;
            white-space: nowrap;
        }

        td { line-height: 1.6; }
        tbody tr:hover { background: #f8fbff; }

        .table-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
        }

        .table-actions form { margin: 0; }
        .table-actions button, .table-actions a { white-space: nowrap; }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-pimpinan { background: #dcfce7; color: #166534; }
        .badge-neutral { background: #e2e8f0; color: #334155; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-secondary { background: #e2e8f0; color: #475569; }

        /* FILTER DAN PAGINASI */
        .filter-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(170px, 220px) auto;
            gap: 12px;
            align-items: end;
        }

        .pagination {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 7px 11px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: white;
            font-size: 13px;
        }

        .pagination .current {
            background: var(--navy);
            border-color: var(--navy);
            color: white;
        }

        .pagination .disabled { color: #94a3b8; background: #f8fafc; }
        .pagination-wrapper { margin-top: 18px; }
        .field-stat { margin-top: 18px; }

        .field-stat-line {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .progress-track {
            height: 10px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--blue);
            border-radius: 10px;
        }

        .empty-state {
            padding: 24px 16px;
            color: var(--muted);
            text-align: center;
            font-size: 14px;
        }

        .count-pill {
            display: inline-block;
            padding: 4px 10px;
            background: var(--blue-light);
            color: var(--navy);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .app-footer {
            padding: 22px 32px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
            border-top: 1px solid var(--border);
            background: white;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            :root { --sidebar-width: 245px; }
            .main-content { padding: 24px 20px; }
            .topbar { padding: 16px 20px; }
            .filter-grid { grid-template-columns: 1fr 1fr; }
            .filter-grid .filter-actions { grid-column: 1 / -1; }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                max-height: none;
                overflow: visible;
            }

            .brand { padding: 20px; }
            .sidebar nav { padding: 10px 12px 16px; }
            .nav-label { padding-top: 16px; }
            .sidebar-bottom { margin-top: 0; }
            .main-area { margin-left: 0; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .main-content { padding: 20px 16px; }
            .stat-card, .content-card, .panel, .form-card { padding: 18px; }
            .stat-number, .stat-value { font-size: 27px; }
            .page-heading h1, .page-header h1 { font-size: 25px; }
            .app-footer { padding: 20px 16px; }
        }

        @media (max-width: 560px) {
            .form-grid, .filter-grid { grid-template-columns: 1fr; }
            .filter-grid .filter-actions { grid-column: auto; }
            .stats-grid { grid-template-columns: 1fr; gap: 12px; }
            .page-heading h1, .page-header h1 { font-size: 23px; }
            .inline-actions .btn { flex: 1 1 auto; }
            .user-chip { max-width: 100%; }
            th, td { padding: 10px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
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
            'health-worker-types' => 'Jenis Tenaga Kesehatan',
        ];

        $currentCategory = request()->route('category');
        $isMasterDataActive = request()->routeIs('super-admin.master-data.*');
    @endphp

    {{-- SVG ikon inline agar tidak membutuhkan library tambahan --}}
    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden" aria-hidden="true">
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
            <circle cx="12" cy="12" r="3"/>
            <path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.6a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.6-1.4-2.4L7.3 15a8 8 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.6a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.6 1.4 2.4-1.4 1.1a8 8 0 0 1-.1 2Z" transform="translate(-1 -1)"/>
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
    </svg>

    <aside class="sidebar">
        
<div class="brand">
    <h2>
        <img
            src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
            alt="Logo Provinsi Kalimantan Selatan"
            class="brand-logo"
        >
        <span>SIRAPIK</span>
    </h2>

    <p>
        Sistem Informasi Riwayat Pelatihan<br>
        Bapelkes Kalimantan Selatan
    </p>
</div>

        <nav>
            <div class="nav-label">MENU UTAMA</div>

            {{-- DASHBOARD --}}
            @if ($isSuperAdmin && Route::has('super-admin.dashboard'))
                <a
                    href="{{ route('super-admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}"
                    @if (request()->routeIs('super-admin.dashboard')) aria-current="page" @endif
                >
                    <svg class="icon" aria-hidden="true"><use href="#icon-dashboard"/></svg>
                    <span>Dashboard</span>
                </a>
            @elseif (Route::has('dashboard'))
                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    @if (request()->routeIs('dashboard')) aria-current="page" @endif
                >
                    <svg class="icon" aria-hidden="true"><use href="#icon-dashboard"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-users"/></svg>
                        <span>Kelola Akun</span>
                    </a>
                @endif

                @if (Route::has('super-admin.master-data.index'))
                    <details class="nav-group" {{ $isMasterDataActive ? 'open' : '' }}>
                        <summary
                            class="nav-group-summary {{ $isMasterDataActive ? 'active' : '' }}"
                            aria-label="Buka atau tutup menu Master Data"
                        >
                            <span class="nav-group-title">
                                <svg class="icon" aria-hidden="true"><use href="#icon-settings"/></svg>
                                <span>Master Data</span>
                            </span>
                            <svg class="icon icon-sm nav-chevron" aria-hidden="true"><use href="#icon-chevron"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-shield"/></svg>
                        <span>Role dan Hak Akses</span>
                    </a>
                @endif

                @if (Route::has('activity-logs.index'))
                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                        @if (request()->routeIs('activity-logs.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true"><use href="#icon-activity"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-users"/></svg>
                        <span>Data Peserta</span>
                    </a>
                @endif

                @if (Route::has('trainings.index'))
                    <a
                        href="{{ route('trainings.index') }}"
                        class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}"
                        @if (request()->routeIs('trainings.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true"><use href="#icon-training"/></svg>
                        <span>Data Pelatihan</span>
                    </a>
                @endif

                @if (Route::has('imports.index'))
                    <a
                        href="{{ route('imports.index') }}"
                        class="nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('imports.*')) aria-current="page" @endif
                    >
                        <svg class="icon" aria-hidden="true"><use href="#icon-upload"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-report"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-report"/></svg>
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
                        <svg class="icon" aria-hidden="true"><use href="#icon-user"/></svg>
                        <span>Profil Saya</span>
                    </a>
                @endif
            @endif
        </nav>

        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-button">
                    <svg class="icon" aria-hidden="true"><use href="#icon-logout"/></svg>
                    <span>Keluar dari Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-area">
        <header class="topbar">
            <div>
                <h2>@yield('title', 'Dashboard')</h2>
                <small>Bapelkes Kalimantan Selatan</small>
            </div>

            <div class="user-info">
                <div class="user-chip">
                    {{ $currentUser?->name ?? $currentUser?->username ?? 'Pengguna' }}
                    <span class="role-label">{{ $roleLabel }}</span>
                </div>
            </div>
        </header>

        <main class="main-content">
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

        <footer class="app-footer">
            &copy; {{ date('Y') }} SIRAPIK · Bapelkes Kalimantan Selatan
            <br>
            Sistem pencatatan dan pemantauan riwayat pelatihan.
        </footer>
    </div>
</div>
</body>
</html>