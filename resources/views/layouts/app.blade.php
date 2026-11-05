
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SIRAPIK</title>

    <style>
        :root {
            --navy: #12315b;
            --navy-dark: #0b2344;
            --navy-deep: #081c37;
            --blue: #2563a6;
            --blue-hover: #1d4f87;
            --blue-light: #edf4fc;
            --blue-soft: #e2edf9;

            --text: #1e293b;
            --muted: #64748b;
            --border: #dce4ee;
            --bg: #f3f6fa;
            --white: #ffffff;

            --success: #166534;
            --danger: #991b1b;

            --sidebar-width: 286px;
            --header-height: 82px;

            --radius-sm: 7px;
            --radius-md: 10px;
            --radius-lg: 13px;

            --shadow-soft: 0 2px 10px rgba(15, 35, 65, .045);
            --transition: .2s ease;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Inter, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
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

        button:focus-visible,
        a:focus-visible,
        summary:focus-visible {
            outline: 3px solid #93c5fd;
            outline-offset: 3px;
        }

        .app-shell {
            min-height: 100vh;
        }

        /* =========================================
           ICON
        ========================================= */

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

        /* =========================================
           SIDEBAR
        ========================================= */

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;

            color: #fff;
            background: var(--navy);
            border-right: 1px solid rgba(255,255,255,.08);
            box-shadow: 4px 0 20px rgba(15, 35, 65, .07);

            scrollbar-width: thin;
            scrollbar-color: #49627f transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #49627f;
            border-radius: 10px;
        }

        /* BRAND */

        .brand {
            padding: 27px 24px 23px;
            border-bottom: 1px solid rgba(255,255,255,.13);
        }

        .brand h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 12px;
            color: #fff;
            font-size: 29px;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.2;
        }

        .brand-logo {
            display: block;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            object-fit: contain;
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

        /* SIDEBAR NAVIGATION */

        .sidebar nav {
            flex: 1;
            padding: 12px 13px 20px;
        }

        .nav-label {
            padding: 21px 12px 9px;
            color: #aebfd5;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: 1.3px;
            line-height: 1.5;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 48px;
            margin: 4px 0;
            padding: 11px 13px;

            color: #dbe6f3;
            border: 1px solid transparent;
            border-radius: 8px;

            font-size: 15px;
            font-weight: 550;
            line-height: 1.45;

            transition:
                background var(--transition),
                color var(--transition),
                border-color var(--transition),
                transform var(--transition);
        }

        .nav-link .icon {
            color: #b5c9e1;
            transition: color var(--transition);
        }

        .nav-link:hover {
            background: rgba(255,255,255,.075);
            color: #fff;
            transform: translateX(2px);
        }

        .nav-link:hover .icon {
            color: #fff;
        }

        .nav-link.active {
            background: #fff;
            color: var(--navy);
            border-color: rgba(255,255,255,.65);
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(0,0,0,.07);
        }

        .nav-link.active .icon {
            color: var(--blue);
        }

        /* MASTER DATA SUBMENU */

        .nav-group {
            margin: 4px 0;
        }

        .nav-group-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 48px;
            padding: 11px 13px;

            color: #dbe6f3;
            border: 1px solid transparent;
            border-radius: 8px;

            font-size: 15px;
            font-weight: 550;
            line-height: 1.45;

            cursor: pointer;
            list-style: none;
            transition: background var(--transition), color var(--transition);
        }

        .nav-group-summary::-webkit-details-marker {
            display: none;
        }

        .nav-group-summary::marker {
            content: "";
        }

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

        .nav-group-title .icon {
            color: #b5c9e1;
        }

        .nav-chevron {
            flex: 0 0 auto;
            width: 17px;
            height: 17px;
            transition: transform var(--transition);
        }

        .nav-group[open] > .nav-group-summary .nav-chevron {
            transform: rotate(180deg);
        }

        .nav-submenu {
            margin: 5px 0 9px 24px;
            padding: 4px 0 4px 15px;
            border-left: 1px solid rgba(255,255,255,.24);
            animation: submenu-in .18s ease;
        }

        @keyframes submenu-in {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-sublink {
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

            transition: background var(--transition), color var(--transition);
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

        /* SIDEBAR LOGOUT */

        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 16px 19px;
            border-top: 1px solid rgba(255,255,255,.13);
            background: rgba(4,20,42,.18);
        }

        .sidebar-bottom form {
            margin: 0;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            min-height: 47px;
            padding: 11px 14px;

            color: #f1f5f9;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.17);
            border-radius: 8px;

            font-size: 14px;
            font-weight: 650;
            transition: background var(--transition), border-color var(--transition);
        }

        .logout-button:hover {
            background: rgba(220,38,38,.18);
            border-color: rgba(252,165,165,.45);
        }

        /* MOBILE SIDEBAR TOGGLE */

        .mobile-menu-button {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 42px;
            padding: 9px 13px;

            color: var(--navy);
            background: var(--blue-light);
            border: 1px solid #d7e5f4;
            border-radius: 8px;

            font-size: 14px;
            font-weight: 700;
        }

        .mobile-menu-button .icon {
            width: 19px;
            height: 19px;
        }

        /* =========================================
           MAIN AREA
        ========================================= */

        .main-area {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        /* =========================================
           HEADER
        ========================================= */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            min-height: var(--header-height);
            padding: 15px 34px;

            background: rgba(255,255,255,.97);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(15,23,42,.025);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .topbar-heading {
            min-width: 0;
        }

        .topbar h2 {
            margin: 0 0 3px;
            color: #152d4d;
            font-size: 22px;
            font-weight: 750;
            letter-spacing: -.35px;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .breadcrumb-separator {
            color: #a0aec0;
            font-size: 14px;
        }

        .breadcrumb-current {
            color: var(--blue);
            font-weight: 650;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 13px;
            flex: 0 0 auto;
        }

        /* USER PROFILE */

        .user-info {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;

            color: #fff;
            background: var(--navy);
            border: 2px solid #e4edf7;
            border-radius: 50%;

            font-size: 15px;
            font-weight: 750;
        }

        .user-details {
            min-width: 0;
        }

        .user-name {
            display: block;
            max-width: 220px;
            color: #17375e;
            font-size: 14px;
            font-weight: 750;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .role-label {
            display: block;
            margin-top: 2px;
            color: #647b96;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.4;
        }

        /* =========================================
           MAIN CONTENT
        ========================================= */

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 32px;
        }

        /* PAGE HEADING */

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
            color: #17375e;
            font-size: 30px;
            font-weight: 750;
            letter-spacing: -.5px;
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
            font-weight: 750;
            letter-spacing: 1.1px;
        }

        /* =========================================
           CONTENT COMPONENTS
        ========================================= */

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
            padding: 24px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
        }

        .stat-label {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .stat-number,
        .stat-value {
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

        .content-card,
        .panel {
            margin-bottom: 23px;
            overflow: hidden;
        }

        .content-card h2,
        .panel h2,
        .form-card h2 {
            margin: 0 0 12px;
            color: #17375e;
            font-size: 20px;
            font-weight: 700;
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

        /* BUTTONS */

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            padding: 10px 16px;
            color: #fff;
            background: var(--blue);
            border: 1px solid transparent;
            border-radius: 8px;

            font-size: 14px;
            font-weight: 650;
            line-height: 1.5;
            text-align: center;

            transition: background var(--transition), box-shadow var(--transition);
        }

        .btn:hover {
            background: var(--blue-hover);
            box-shadow: 0 3px 9px rgba(37,99,235,.15);
        }

        .btn-primary {
            color: white;
            background: var(--blue);
        }

        .btn-light {
            color: var(--navy);
            background: #eaf0f8;
        }

        .btn-light:hover,
        .btn-secondary:hover {
            background: #dbe5f2;
        }

        .btn-secondary {
            color: #1e293b;
            background: #e2e8f0;
        }

        .btn-outline {
            color: var(--navy);
            background: white;
            border-color: #cbd5e1;
        }

        .btn-outline:hover {
            background: #f1f5f9;
        }

        .btn-danger {
            color: white;
            background: #b91c1c;
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

        /* FORMS */

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
            font-weight: 650;
        }

        input,
        select,
        textarea {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 11px 12px;

            color: var(--text);
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 8px;

            font-size: 14px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 3px solid #dbeafe;
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

        /* ALERTS */

        .alert {
            padding: 15px 18px;
            margin-bottom: 22px;
            color: #1e40af;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 9px;
            font-size: 14px;
        }

        .alert-success {
            color: var(--success);
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .alert-error,
        .alert-danger {
            color: var(--danger);
            background: #fef2f2;
            border-color: #fecaca;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 22px;
        }

        /* TABLES */

        .table-responsive,
        .table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            margin-top: 16px;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            color: #475569;
            background: #f5f8fc;
            font-weight: 750;
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

        /* BADGES */

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-admin {
            color: #1e40af;
            background: #dbeafe;
        }

        .badge-pimpinan {
            color: #166534;
            background: #dcfce7;
        }

        .badge-neutral {
            color: #334155;
            background: #e2e8f0;
        }

        .badge-success {
            color: #166534;
            background: #dcfce7;
        }

        .badge-secondary {
            color: #475569;
            background: #e2e8f0;
        }

        /* FILTER AND PAGINATION */

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
            background: white;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-size: 13px;
        }

        .pagination .current {
            color: white;
            background: var(--navy);
            border-color: var(--navy);
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
            overflow: hidden;
            background: #e2e8f0;
            border-radius: 10px;
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
            color: var(--navy);
            background: var(--blue-light);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .app-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;

            padding: 21px 32px;
            color: #64748b;
            background: #fff;
            border-top: 1px solid var(--border);

            font-size: 13px;
            line-height: 1.7;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .footer-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 37px;
            height: 37px;
            flex: 0 0 37px;

            color: white;
            background: var(--navy);
            border-radius: 8px;

            font-size: 13px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .footer-title {
            display: block;
            color: var(--navy);
            font-size: 13px;
            font-weight: 750;
            line-height: 1.5;
        }

        .footer-description {
            display: block;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .footer-meta {
            color: #64748b;
            font-size: 12px;
            text-align: right;
        }

        .footer-meta strong {
            color: var(--navy);
            font-weight: 700;
        }

        /* =========================================
           RESPONSIVE: TABLET
        ========================================= */

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            :root {
                --sidebar-width: 245px;
            }

            .main-content {
                padding: 24px 20px;
            }

            .topbar {
                padding: 15px 20px;
            }

            .filter-grid {
                grid-template-columns: 1fr 1fr;
            }

            .filter-grid .filter-actions {
                grid-column: 1 / -1;
            }

            .user-name {
                max-width: 150px;
            }
        }

        /* =========================================
           RESPONSIVE: MOBILE
        ========================================= */

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                inset: auto;
                width: 100%;
                height: auto;
                max-height: none;
                overflow: visible;
                box-shadow: 0 3px 12px rgba(15,35,65,.08);
            }

            .brand {
                padding: 17px 20px;
            }

            .brand h2 {
                margin-bottom: 7px;
                font-size: 25px;
            }

            .brand-logo {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
            }

            .brand p {
                font-size: 12px;
            }

            .mobile-menu-button {
                display: inline-flex;
                margin: 12px 16px 0;
            }

            .sidebar nav {
                display: none;
                padding: 8px 12px 15px;
            }

            .sidebar.mobile-open nav {
                display: block;
            }

            .nav-label {
                padding-top: 15px;
            }

            .sidebar-bottom {
                display: none;
                margin-top: 0;
            }

            .sidebar.mobile-open .sidebar-bottom {
                display: block;
            }

            .main-area {
                margin-left: 0;
            }

            .topbar {
                position: relative;
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                padding: 17px 18px;
            }

            .topbar-left {
                width: 100%;
            }

            .topbar-right {
                width: 100%;
                justify-content: flex-start;
            }

            .topbar h2 {
                font-size: 20px;
            }

            .user-name {
                max-width: 240px;
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
                align-items: flex-start;
                flex-direction: column;
                padding: 19px 16px;
            }

            .footer-meta {
                text-align: left;
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

            .user-info {
                width: 100%;
            }

            .user-name {
                max-width: calc(100vw - 100px);
            }

            th,
            td {
                padding: 10px;
            }

            .main-content {
                padding: 17px 12px;
            }

            .topbar {
                padding: 15px 14px;
            }
        }

        /* REDUCE MOTION */

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
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

    {{-- SVG ICON LIBRARY --}}
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
        <header class="topbar">

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

        {{-- FOOTER --}}
        <footer class="app-footer">

            <div class="footer-brand">

                <div class="footer-mark" aria-hidden="true">
                    SP
                </div>

                <div>
                    <span class="footer-title">
                        SIRAPIK
                    </span>

                    <span class="footer-description">
                        Sistem Informasi Riwayat Pelatihan
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
                    Sistem pencatatan dan pemantauan riwayat pelatihan.
                </div>
            </div>

        </footer>

    </div>

</div>

{{-- MOBILE SIDEBAR INTERACTION --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('appSidebar');
        const menuButton = document.getElementById('mobileMenuButton');

        if (!sidebar || !menuButton) {
            return;
        }

        menuButton.addEventListener('click', function () {
            const isOpen = sidebar.classList.toggle('mobile-open');

            menuButton.setAttribute('aria-expanded', String(isOpen));
        });

        // Tutup menu setelah pengguna memilih navigasi pada layar mobile.
        sidebar.querySelectorAll('.nav-link, .nav-sublink').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 760) {
                    sidebar.classList.remove('mobile-open');
                    menuButton.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Kembalikan navigasi ke kondisi desktop saat ukuran layar berubah.
        window.addEventListener('resize', function () {
            if (window.innerWidth > 760) {
                sidebar.classList.remove('mobile-open');
                menuButton.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>

</body>
</html>