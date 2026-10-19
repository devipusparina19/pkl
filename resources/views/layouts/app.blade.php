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
            --blue: #1d4ed8;
            --blue-light: #eff6ff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f4f7fb;
            --success: #166534;
            --danger: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 16px;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        .app-shell {
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 270px;
            background: var(--navy);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 10;
            overflow-y: auto;
        }

        .brand {
            padding: 27px 24px;
            border-bottom: 1px solid #ffffff20;
        }

        .brand h2 {
            margin: 0 0 7px;
            font-size: 27px;
            letter-spacing: 1px;
        }

        .brand p {
            margin: 0;
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.7;
        }

        .sidebar nav {
            padding-bottom: 16px;
        }

        .nav-label {
            padding: 24px 22px 10px;
            color: #c1d1e5;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-link {
            display: block;
            margin: 4px 12px;
            padding: 13px 15px;
            color: #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            line-height: 1.5;
            transition: background .15s;
            overflow-wrap: anywhere;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #ffffff19;
            color: white;
        }

        .nav-link.active {
            border-left: 4px solid #93c5fd;
            padding-left: 11px;
        }

        /* GRUP MENU MASTER DATA */
        .nav-group {
            margin: 0;
        }

        .nav-group-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 4px 12px;
            padding: 13px 15px;
            color: #e2e8f0;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 15px;
            line-height: 1.5;
            cursor: pointer;
            list-style: none;
            transition: background .15s, color .15s;
            overflow-wrap: anywhere;
        }

        .nav-group-summary::-webkit-details-marker {
            display: none;
        }

        .nav-group-summary::marker {
            content: "";
        }

        .nav-group-summary:hover,
        .nav-group[open] > .nav-group-summary {
            background: #ffffff19;
            color: white;
        }

        .nav-group-summary.active {
            border-left: 4px solid #93c5fd;
            padding-left: 11px;
        }

        .nav-group-title {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .nav-chevron {
            flex-shrink: 0;
            font-size: 11px;
            transition: transform .18s ease;
        }

        .nav-group[open] > .nav-group-summary .nav-chevron {
            transform: rotate(180deg);
        }

        /* SUBMENU SIDEBAR */
        .nav-submenu {
            margin: 0 12px 8px 24px;
            padding-left: 10px;
            border-left: 1px solid #ffffff35;
        }

        .nav-group .nav-submenu {
            margin-top: 0;
        }

        .nav-sublink {
            display: block;
            padding: 9px 12px;
            margin: 3px 0;
            color: #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .nav-sublink:hover,
        .nav-sublink.active {
            background: #ffffff19;
            color: #ffffff;
        }

        .nav-sublink.active {
            border-left: 3px solid #93c5fd;
            padding-left: 9px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid #ffffff20;
        }

        .logout-button {
            width: 100%;
            background: #ffffff18;
            color: white;
            text-align: left;
            border: 1px solid #ffffff20;
        }

        .logout-button:hover {
            background: #ffffff2b;
        }

        /* AREA KONTEN */
        .main-area {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            min-height: 82px;
            padding: 17px 32px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .topbar h2 {
            margin: 0;
            font-size: 21px;
            line-height: 1.4;
        }

        .topbar small {
            color: var(--muted);
            font-size: 14px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-chip {
            background: var(--blue-light);
            color: var(--navy);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .role-label {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            font-weight: 400;
            color: var(--muted);
        }

        .main-content {
            flex: 1;
            padding: 32px;
            min-width: 0;
        }

        /* JUDUL HALAMAN */
        .page-heading,
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 27px;
        }

        .page-heading h1,
        .page-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
            line-height: 1.3;
        }

        .page-heading p,
        .page-header p {
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
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* KARTU */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card,
        .content-card,
        .panel,
        .form-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px #0f172a05;
        }

        .stat-label {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .stat-number,
        .stat-value {
            margin-top: 10px;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--navy);
            overflow-wrap: anywhere;
        }

        .stat-note {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .content-card,
        .panel {
            margin-bottom: 23px;
            overflow: hidden;
        }

        .content-card h2,
        .panel h2,
        .form-card h2 {
            margin: 0 0 12px;
            font-size: 20px;
            line-height: 1.4;
        }

        .content-card p,
        .panel p {
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
            display: inline-block;
            border: 1px solid transparent;
            border-radius: 7px;
            background: var(--blue);
            color: white;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
            cursor: pointer;
            text-align: center;
            transition: background .15s;
        }

        .btn:hover {
            background: #1e40af;
        }

        .btn-primary {
            background: var(--blue);
            color: white;
        }

        .btn-light {
            background: #eaf0f8;
            color: var(--navy);
        }

        .btn-light:hover,
        .btn-secondary:hover {
            background: #dbe5f2;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-outline {
            background: white;
            color: var(--navy);
            border-color: #cbd5e1;
        }

        .btn-outline:hover {
            background: #f1f5f9;
        }

        .btn-danger {
            background: #b91c1c;
            color: white;
        }

        .btn-danger:hover {
            background: #991b1b;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }

        .inline-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
        }

        /* FORM */
        .form-card {
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 20px;
        }

        .form-group {
            min-width: 0;
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            background: white;
            color: var(--text);
            font-size: 14px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid #bfdbfe;
            border-color: var(--blue);
        }

        input[type="checkbox"],
        input[type="radio"] {
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

        .input-error {
            border-color: #dc2626;
        }

        .form-actions,
        .filter-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        /* NOTIFIKASI */
        .alert {
            padding: 15px 18px;
            border-radius: 8px;
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

        .alert-error,
        .alert-danger {
            background: #fef2f2;
            color: var(--danger);
            border-color: #fecaca;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 22px;
        }

        /* TABEL */
        .table-responsive,
        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 14px;
        }

        th,
        td {
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            white-space: nowrap;
        }

        td {
            line-height: 1.6;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .table-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
        }

        .table-actions form {
            margin: 0;
        }

        .table-actions button,
        .table-actions a {
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-admin {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-pimpinan {
            background: #dcfce7;
            color: #166534;
        }

        .badge-neutral {
            background: #e2e8f0;
            color: #334155;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-secondary {
            background: #e2e8f0;
            color: #475569;
        }

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

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 7px 11px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: white;
            font-size: 13px;
        }

        .pagination .current {
            background: var(--navy);
            border-color: var(--navy);
            color: white;
        }

        .pagination .disabled {
            color: #94a3b8;
            background: #f8fafc;
        }

        .pagination-wrapper {
            margin-top: 18px;
        }

        .field-stat {
            margin-top: 18px;
        }

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
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 230px;
            }

            .main-area {
                margin-left: 230px;
            }

            .main-content {
                padding: 24px 20px;
            }

            .topbar {
                padding: 16px 20px;
            }

            .filter-grid {
                grid-template-columns: 1fr 1fr;
            }

            .filter-grid .filter-actions {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                max-height: none;
            }

            .brand {
                padding: 20px;
            }

            .nav-label {
                padding-top: 16px;
            }

            .sidebar-bottom {
                margin-top: 0;
            }

            .main-area {
                margin-left: 0;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .main-content {
                padding: 20px 16px;
            }

            .stat-card,
            .content-card,
            .panel,
            .form-card {
                padding: 18px;
            }

            .stat-number,
            .stat-value {
                font-size: 27px;
            }

            .page-heading h1,
            .page-header h1 {
                font-size: 25px;
            }

            .app-footer {
                padding: 20px 16px;
            }

            .nav-group-summary {
                margin-right: 12px;
                margin-left: 12px;
            }
        }

        @media (max-width: 560px) {
            .form-grid,
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid .filter-actions {
                grid-column: auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .page-heading h1,
            .page-header h1 {
                font-size: 23px;
            }

            .inline-actions .btn {
                flex: 1 1 auto;
            }

            .user-chip {
                max-width: 100%;
            }

            th,
            td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
<div class="app-shell">

    @php
        $currentUser = auth()->user();
        $role = strtolower(trim((string) ($currentUser?->role ?? '')));

        // Mendukung format role lama dan baru.
        if ($role === 'super-admin') {
            $role = 'super_admin';
        }

        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
            'user' => 'Peserta Pelatihan',
        ];

        $roleLabel = $roleLabels[$role] ?? 'Pengguna';

        $isSuperAdmin = $role === 'super_admin';
        $isAdmin = $role === 'admin';
        $isPimpinan = $role === 'pimpinan';
        $isParticipant = $role === 'user';

        $masterDataCategories = [
            'provinces' => 'Provinsi',
            'districts' => 'Kabupaten/Kota/Mitra',
            'funding-sources' => 'Sumber Dana',
            'training-years' => 'Tahun Pelatihan',
            'health-worker-types' => 'Jenis Tenaga Kesehatan',
        ];

        $currentCategory = request()->route('category');
        $isMasterDataActive = request()->routeIs('super-admin.master-data.*');
    @endphp

    <aside class="sidebar">
        <div class="brand">
            <h2>SIRAPIK</h2>
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
                    @if (request()->routeIs('super-admin.dashboard'))
                        aria-current="page"
                    @endif
                >
                    ▦ &nbsp; Dashboard
                </a>
            @elseif (Route::has('dashboard'))
                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    @if (request()->routeIs('dashboard'))
                        aria-current="page"
                    @endif
                >
                    ▦ &nbsp; Dashboard
                </a>
            @endif

            {{-- SUPER ADMIN --}}
            @if ($isSuperAdmin)
                <div class="nav-label">MANAJEMEN SISTEM</div>

                {{-- KELOLA AKUN --}}
                @if (Route::has('super-admin.users.index'))
                    <a
                        href="{{ route('super-admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}"
                        @if (request()->routeIs('super-admin.users.*'))
                            aria-current="page"
                        @endif
                    >
                        👥 &nbsp; Kelola Akun
                    </a>
                @endif

                {{-- MASTER DATA: MENU BISA DIBUKA DAN DITUTUP --}}
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
                                <span aria-hidden="true">⚙</span>
                                <span>Master Data</span>
                            </span>

                            <span class="nav-chevron" aria-hidden="true">▼</span>
                        </summary>

                        <div class="nav-submenu">
                            @foreach ($masterDataCategories as $categoryKey => $categoryLabel)
                                <a
                                    href="{{ route('super-admin.master-data.index', ['category' => $categoryKey]) }}"
                                    class="nav-sublink {{ $currentCategory === $categoryKey && $isMasterDataActive ? 'active' : '' }}"
                                    @if ($currentCategory === $categoryKey && $isMasterDataActive)
                                        aria-current="page"
                                    @endif
                                >
                                    {{ $categoryLabel }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @endif

                {{-- ROLE DAN HAK AKSES --}}
                @if (Route::has('roles.index'))
                    <a
                        href="{{ route('roles.index') }}"
                        class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                        @if (request()->routeIs('roles.*'))
                            aria-current="page"
                        @endif
                    >
                        🔐 &nbsp; Role dan Hak Akses
                    </a>
                @endif

                {{-- LOG AKTIVITAS --}}
                @if (Route::has('activity-logs.index'))
                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                        @if (request()->routeIs('activity-logs.*'))
                            aria-current="page"
                        @endif
                    >
                        ▤ &nbsp; Log Aktivitas
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
                        @if (request()->routeIs('participants.*'))
                            aria-current="page"
                        @endif
                    >
                        👥 &nbsp; Data Peserta
                    </a>
                @endif

                @if (Route::has('trainings.index'))
                    <a
                        href="{{ route('trainings.index') }}"
                        class="nav-link {{ request()->routeIs('trainings.*') ? 'active' : '' }}"
                        @if (request()->routeIs('trainings.*'))
                            aria-current="page"
                        @endif
                    >
                        ▤ &nbsp; Data Pelatihan
                    </a>
                @endif

                @if (Route::has('imports.index'))
                    <a
                        href="{{ route('imports.index') }}"
                        class="nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('imports.*'))
                            aria-current="page"
                        @endif
                    >
                        ⇧ &nbsp; Impor Excel
                    </a>
                @endif

                @if (Route::has('reports.index'))
                    <div class="nav-label">PELAPORAN</div>

                    <a
                        href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('reports.*'))
                            aria-current="page"
                        @endif
                    >
                        ▥ &nbsp; Laporan dan Statistik
                    </a>
                @endif
            @endif

            {{-- PIMPINAN: HANYA MELIHAT DATA --}}
            @if ($isPimpinan)
                <div class="nav-label">PEMANTAUAN</div>

                @if (Route::has('participants.index'))
                    <a
                        href="{{ route('participants.index') }}"
                        class="nav-link {{ request()->routeIs('participants.index', 'participants.show') ? 'active' : '' }}"
                        @if (request()->routeIs('participants.index', 'participants.show'))
                            aria-current="page"
                        @endif
                    >
                        👥 &nbsp; Data Peserta
                    </a>
                @endif

                @if (Route::has('trainings.index'))
                    <a
                        href="{{ route('trainings.index') }}"
                        class="nav-link {{ request()->routeIs('trainings.index', 'trainings.show') ? 'active' : '' }}"
                        @if (request()->routeIs('trainings.index', 'trainings.show'))
                            aria-current="page"
                        @endif
                    >
                        ▤ &nbsp; Data Pelatihan
                    </a>
                @endif

                @if (Route::has('reports.index'))
                    <a
                        href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        @if (request()->routeIs('reports.*'))
                            aria-current="page"
                        @endif
                    >
                        ▥ &nbsp; Laporan Pelatihan
                    </a>
                @endif
            @endif

            {{-- PESERTA, JIKA ROLE INI DIGUNAKAN --}}
            @if ($isParticipant)
                <div class="nav-label">AKUN PESERTA</div>

                @if (Route::has('participant.profile'))
                    <a
                        href="{{ route('participant.profile') }}"
                        class="nav-link {{ request()->routeIs('participant.profile') ? 'active' : '' }}"
                        @if (request()->routeIs('participant.profile'))
                            aria-current="page"
                        @endif
                    >
                        👤 &nbsp; Profil Saya
                    </a>
                @endif
            @endif
        </nav>

        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-button">
                    ↪ &nbsp; Keluar dari Sistem
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
                    {{ $currentUser?->name ?? 'Pengguna' }}
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