<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#12345a">
    <meta name="description" content="SIRAPIK - Sistem Informasi Pelatihan Bapelkes Provinsi Kalimantan Selatan.">

    <title>SIRAPIK | Bapelkes Provinsi Kalimantan Selatan</title>

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        :root {
            --navy: #12345a;
            --navy-deep: #0b2340;
            --navy-light: #1d4c7c;
            --blue: #2463a6;
            --blue-hover: #194c87;
            --blue-soft: #edf4fc;
            --ink: #26364a;
            --muted: #64748b;
            --border: #dce4ee;
            --surface: #ffffff;
            --page: #f3f6fa;
            --green: #207653;
            --green-soft: #e9f6ef;
            --red: #b34545;
            --red-soft: #fbefef;
            --radius: 12px;
            --shadow: 0 4px 18px rgba(19, 49, 83, .055);
            --shadow-hover: 0 10px 26px rgba(19, 49, 83, .10);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 24px;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--page);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        body,
        button,
        input,
        select {
            font-family: "Segoe UI", Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        select {
            font: inherit;
        }

        button,
        a,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        a:focus-visible,
        button:focus-visible,
        select:focus-visible {
            outline: 3px solid rgba(36, 99, 166, .32);
            outline-offset: 3px;
        }

        img {
            max-width: 100%;
        }

        .public-container {
            width: min(1480px, calc(100% - 64px));
            margin-inline: auto;
        }

        /* HEADER */

        .public-nav {
            position: relative;
            z-index: 10;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            min-height: 86px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .brand-mark {
            width: 49px;
            height: 49px;
            flex: 0 0 49px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: linear-gradient(145deg, var(--navy-light), var(--navy-deep));
            color: #fff;
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 4px 10px rgba(18, 52, 90, .16);
        }

        .brand-title {
            color: var(--navy);
            font-size: 23px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: .4px;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-label {
            color: var(--muted);
            font-size: 14px;
        }

        /* BUTTONS */

        .btn {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 19px;
            border: 1px solid var(--blue);
            border-radius: 8px;
            background: var(--blue);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
            text-align: center;
            cursor: pointer;
            transition: background .18s ease,
                        border-color .18s ease,
                        box-shadow .18s ease,
                        transform .18s ease;
        }

        .btn:hover {
            background: var(--blue-hover);
            border-color: var(--blue-hover);
            box-shadow: 0 4px 12px rgba(36, 99, 166, .15);
        }

        .btn-outline {
            background: #fff;
            color: var(--navy);
            border-color: #cbd7e5;
        }

        .btn-outline:hover {
            background: var(--blue-soft);
            color: var(--navy);
            border-color: #a9bfd9;
        }

        .btn-arrow {
            font-size: 18px;
            line-height: 1;
        }

        /* HERO */

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: clamp(58px, 6vw, 92px) 0;
            background-color: var(--navy-deep);
            background-image:
                linear-gradient(
                    105deg,
                    rgba(8, 29, 54, .93) 0%,
                    rgba(14, 47, 81, .86) 52%,
                    rgba(20, 64, 105, .72) 100%
                ),
                url("{{ asset('images/background-sirapik.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
        }

        .hero::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: 440px;
            height: 440px;
            right: -160px;
            bottom: -280px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 50%;
            box-shadow:
                0 0 0 45px rgba(255, 255, 255, .025),
                0 0 0 90px rgba(255, 255, 255, .02);
            pointer-events: none;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(300px, .75fr);
            align-items: center;
            gap: clamp(36px, 5vw, 76px);
        }

        .hero-copy {
            min-width: 0;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #d0e4f9;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.6;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 28px;
            height: 2px;
            background: #91c6fa;
            border-radius: 5px;
        }

        .hero h1 {
            max-width: 850px;
            margin: 10px 0 20px;
            color: #fff;
            font-size: clamp(38px, 4.2vw, 58px);
            font-weight: 760;
            line-height: 1.19;
            letter-spacing: -.8px;
            text-wrap: balance;
        }

        .hero-description {
            max-width: 760px;
            margin: 0 0 30px;
            color: #e0eaf5;
            font-size: 17px;
            line-height: 1.9;
        }

        .hero .btn {
            min-height: 49px;
            padding-inline: 22px;
            background: #fff;
            border-color: #fff;
            color: var(--navy);
        }

        .hero .btn:hover {
            background: #edf4fc;
            border-color: #edf4fc;
            transform: translateY(-1px);
        }

        .hero-note {
            min-width: 0;
            padding: clamp(22px, 2.5vw, 32px);
            border: 1px solid rgba(255, 255, 255, .23);
            border-radius: 14px;
            background: rgba(7, 29, 54, .48);
            box-shadow: 0 12px 35px rgba(0, 0, 0, .08);
            backdrop-filter: blur(5px);
        }

        .hero-note-top {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
        }

        .hero-note-icon {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .19);
            border-radius: 10px;
            background: rgba(255, 255, 255, .10);
            color: #fff;
            font-size: 22px;
        }

        .hero-note-label {
            color: #c5dcf4;
            font-size: 12px;
            font-weight: 750;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-note strong {
            display: block;
            margin-top: 3px;
            color: #fff;
            font-size: 18px;
            font-weight: 750;
            line-height: 1.5;
        }

        .hero-note p {
            margin: 0;
            color: #e0eaf5;
            font-size: 15px;
            line-height: 1.9;
        }

        .hero-note-divider {
            height: 1px;
            margin: 21px 0;
            background: rgba(255, 255, 255, .16);
        }

        .hero-note-footer {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: #d2e5f8;
            font-size: 13px;
            line-height: 1.7;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            margin-top: 7px;
            border-radius: 50%;
            background: #75d4a2;
        }

        /* MAIN DASHBOARD */

        .section {
            padding: clamp(34px, 4vw, 54px) 0;
            scroll-margin-top: 20px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 24px;
        }

        .section-heading-main {
            min-width: 0;
        }

        .section-kicker {
            margin-bottom: 6px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .section-heading h2 {
            margin: 0 0 7px;
            color: var(--navy);
            font-size: clamp(25px, 2.4vw, 32px);
            font-weight: 780;
            line-height: 1.35;
            letter-spacing: -.35px;
        }

        .section-heading p {
            max-width: 780px;
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .data-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            padding: 9px 13px;
            border: 1px solid #dce8f5;
            border-radius: 7px;
            background: #fff;
            color: #4d6682;
            font-size: 13px;
            font-weight: 650;
        }

        .data-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #39976b;
        }

        /* CARDS */

        .filter-card,
        .stat-card,
        .content-card {
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        /* FILTER */

        .filter-card {
            padding: clamp(19px, 2.3vw, 29px);
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 21px;
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .filter-title-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns:
                minmax(145px, .8fr)
                minmax(220px, 1.35fr)
                minmax(185px, 1fr)
                auto;
            align-items: end;
            gap: 17px;
        }

        .form-field {
            min-width: 0;
        }

        .form-field label {
            display: block;
            margin-bottom: 8px;
            color: #34465d;
            font-size: 14px;
            font-weight: 750;
            line-height: 1.5;
        }

        .form-field select {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 49px;
            padding: 10px 36px 10px 13px;
            border: 1px solid #cbd7e5;
            border-radius: 7px;
            background-color: #fff;
            color: var(--ink);
            font-size: 14px;
            line-height: 1.5;
            cursor: pointer;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .form-field select:hover {
            border-color: #a8bdd5;
        }

        .form-field select:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(36, 99, 166, .10);
        }

        .filter-grid > .btn {
            min-height: 49px;
            white-space: nowrap;
        }

        /* STATISTICS */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-top: 22px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: clamp(20px, 2.3vw, 29px);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 15px;
            font-weight: 700;
            line-height: 1.65;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 22px;
        }

        .stat-value {
            margin-top: 16px;
            color: var(--navy);
            font-size: clamp(32px, 3vw, 43px);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -.7px;
            overflow-wrap: anywhere;
            font-variant-numeric: tabular-nums;
        }

        .stat-caption {
            margin-top: 10px;
            color: #758499;
            font-size: 13px;
            line-height: 1.75;
        }

        .stat-caption strong {
            color: #52647a;
            font-weight: 700;
        }

        /* DATA TABLES */

        .data-grid {
            display: grid;
            grid-template-columns: minmax(0, .9fr) minmax(0, 1.25fr);
            align-items: start;
            gap: 21px;
            margin-top: 22px;
        }

        .content-card {
            overflow: hidden;
        }

        .content-card-header {
            padding: 24px 25px 19px;
            border-bottom: 1px solid #edf1f6;
        }

        .content-card h3 {
            margin: 0;
            color: var(--navy);
            font-size: 19px;
            font-weight: 780;
            line-height: 1.5;
        }

        .card-description {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .card-body {
            padding: 0 23px 21px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            margin-top: 17px;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #edf1f5;
        }

        th {
            background: #f2f6fb;
            color: #52647a;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.6;
            letter-spacing: .35px;
            text-transform: uppercase;
        }

        th:first-child {
            border-radius: 6px 0 0 6px;
        }

        th:last-child {
            border-radius: 0 6px 6px 0;
        }

        td {
            color: #36475c;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover td {
            background: #f8fbff;
        }

        .table-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            padding: 5px 9px;
            border-radius: 6px;
            background: var(--blue-soft);
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .empty {
            padding: 28px 13px !important;
            color: var(--muted);
            font-size: 14px;
            text-align: center;
            line-height: 1.8;
        }

        /* RANKING TENAGA KESEHATAN */

        .ranking-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 17px;
            padding: 22px;
        }

        .ranking-panel {
            min-width: 0;
            padding: 19px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .ranking-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 13px;
        }

        .ranking-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            font-size: 22px;
            font-weight: 800;
        }

        .ranking-high .ranking-icon {
            background: var(--green-soft);
            color: var(--green);
        }

        .ranking-low .ranking-icon {
            background: var(--red-soft);
            color: var(--red);
        }

        .ranking-heading h4 {
            margin: 0;
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
            line-height: 1.5;
        }

        .ranking-heading p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .ranking-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ranking-item {
            display: grid;
            grid-template-columns: 32px minmax(0, 1fr) auto;
            align-items: center;
            gap: 11px;
            padding: 13px 0;
            border-bottom: 1px solid #edf1f5;
        }

        .ranking-item:last-child {
            border-bottom: none;
        }

        .ranking-number {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            background: #f0f4f9;
            color: #49617d;
            font-size: 13px;
            font-weight: 800;
        }

        .ranking-item:first-child .ranking-number {
            background: #e6eef9;
            color: var(--navy);
        }

        .ranking-name {
            min-width: 0;
            color: #3d4d61;
            font-size: 14px;
            font-weight: 650;
            line-height: 1.65;
            overflow-wrap: anywhere;
        }

        .ranking-total {
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .ranking-total small {
            color: #8794a5;
            font-size: 11px;
            font-weight: 600;
        }

        .ranking-empty {
            padding: 17px;
            border: 1px dashed #d9e2ec;
            border-radius: 7px;
            background: #f8fafc;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* FOOTER */

        .public-footer {
            margin-top: 0;
            padding: 32px 0;
            background: var(--navy-deep);
            color: #cfdaea;
            font-size: 13px;
            line-height: 1.8;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px 28px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .footer-mark {
            width: 41px;
            height: 41px;
            display: grid;
            place-items: center;
            flex: 0 0 41px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 8px;
            color: #fff;
            font-size: 20px;
            font-weight: 800;
        }

        .footer-inner strong {
            color: #fff;
            font-size: 15px;
            font-weight: 800;
        }

        .footer-copy {
            max-width: 460px;
            color: #bdcadd;
            text-align: right;
        }

        /* LAPTOP / TABLET LANDSCAPE */

        @media (max-width: 1100px) {
            .public-container {
                width: calc(100% - 48px);
            }

            .hero-grid {
                grid-template-columns: minmax(0, 1.2fr) minmax(270px, .8fr);
                gap: 32px;
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-grid > .btn {
                width: 100%;
            }

            .data-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        /* TABLET PORTRAIT */

        @media (max-width: 760px) {
            body {
                font-size: 15px;
            }

            .public-container {
                width: calc(100% - 38px);
            }

            .nav-inner {
                min-height: 76px;
            }

            .hero {
                padding: 48px 0 50px;
            }

            .hero-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 28px;
            }

            .hero h1 {
                max-width: 650px;
                font-size: clamp(34px, 6vw, 44px);
            }

            .hero-description {
                max-width: 680px;
                font-size: 16px;
            }

            .hero-note {
                max-width: 680px;
            }

            .section {
                padding: 35px 0 40px;
            }

            .section-heading h2 {
                font-size: 27px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 34px;
            }

            .data-grid {
                gap: 16px;
            }

            .content-card-header {
                padding: 21px 21px 16px;
            }

            .card-body {
                padding-inline: 18px;
            }

            .ranking-grid {
                padding: 17px;
                gap: 12px;
            }

            .ranking-panel {
                padding: 15px;
            }
        }

        /* HP */

        @media (max-width: 540px) {
            body {
                font-size: 15px;
            }

            .public-container {
                width: calc(100% - 30px);
            }

            .nav-inner {
                min-height: 72px;
                gap: 10px;
            }

            .brand {
                gap: 9px;
            }

            .brand-mark {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
                border-radius: 8px;
                font-size: 21px;
            }

            .brand-title {
                font-size: 18px;
            }

            .brand-subtitle {
                max-width: 175px;
                margin-top: 2px;
                font-size: 10px;
                line-height: 1.45;
            }

            .nav-actions {
                gap: 0;
            }

            .nav-label {
                display: none;
            }

            .nav-inner > .nav-actions .btn {
                min-height: 39px;
                gap: 5px;
                padding: 8px 10px;
                font-size: 11px;
                white-space: nowrap;
            }

            .hero {
                padding: 39px 0 41px;
            }

            .eyebrow {
                font-size: 11px;
                letter-spacing: 1px;
            }

            .hero h1 {
                margin: 10px 0 15px;
                font-size: clamp(31px, 8vw, 39px);
                line-height: 1.23;
                letter-spacing: -.45px;
            }

            .hero-description {
                margin-bottom: 22px;
                font-size: 15px;
                line-height: 1.85;
            }

            .hero .btn {
                min-height: 45px;
                padding-inline: 16px;
                font-size: 13px;
            }

            .hero-note {
                padding: 18px;
                border-radius: 10px;
            }

            .hero-note-top {
                gap: 10px;
                margin-bottom: 14px;
            }

            .hero-note-icon {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
            }

            .hero-note-label {
                font-size: 11px;
            }

            .hero-note strong {
                font-size: 16px;
            }

            .hero-note p {
                font-size: 14px;
                line-height: 1.8;
            }

            .hero-note-divider {
                margin: 15px 0;
            }

            .hero-note-footer {
                font-size: 12px;
            }

            .section {
                padding: 29px 0 33px;
            }

            .section-heading {
                align-items: flex-start;
                margin-bottom: 18px;
            }

            .section-kicker {
                font-size: 11px;
            }

            .section-heading h2 {
                font-size: 24px;
            }

            .section-heading p {
                font-size: 14px;
            }

            .data-badge {
                display: none;
            }

            .filter-card {
                padding: 17px;
                border-radius: 10px;
            }

            .filter-title {
                margin-bottom: 16px;
                font-size: 15px;
            }

            .filter-title-icon {
                width: 31px;
                height: 31px;
                flex-basis: 31px;
            }

            .filter-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 14px;
            }

            .form-field label {
                margin-bottom: 7px;
                font-size: 14px;
            }

            .form-field select {
                min-height: 48px;
                padding-left: 12px;
                font-size: 14px;
            }

            .filter-grid > .btn {
                min-height: 47px;
                font-size: 14px;
            }

            .stats-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
                margin-top: 15px;
            }

            .stat-card {
                padding: 18px;
            }

            .stat-icon {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
                font-size: 19px;
            }

            .stat-label {
                font-size: 14px;
            }

            .stat-value {
                margin-top: 9px;
                font-size: 32px;
            }

            .stat-caption {
                margin-top: 6px;
                font-size: 12px;
            }

            .data-grid {
                gap: 14px;
                margin-top: 15px;
            }

            .content-card-header {
                padding: 18px 17px 14px;
            }

            .content-card h3 {
                font-size: 17px;
            }

            .card-description {
                font-size: 13px;
            }

            .card-body {
                padding: 0 13px 14px;
            }

            table {
                min-width: 290px;
                margin-top: 13px;
                font-size: 13px;
            }

            th,
            td {
                padding: 11px 9px;
            }

            th {
                font-size: 11px;
            }

            .table-count {
                font-size: 12px;
            }

            .empty {
                font-size: 13px;
            }

            .ranking-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
                padding: 13px;
            }

            .ranking-panel {
                padding: 15px;
            }

            .ranking-heading h4 {
                font-size: 14px;
            }

            .ranking-heading p {
                font-size: 12px;
            }

            .ranking-name,
            .ranking-total {
                font-size: 13px;
            }

            .ranking-number {
                width: 28px;
                height: 28px;
            }

            .ranking-item {
                grid-template-columns: 30px minmax(0, 1fr) auto;
                gap: 8px;
            }

            .ranking-empty {
                font-size: 13px;
            }

            .public-footer {
                padding: 25px 0;
                font-size: 12px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
            }

            .footer-copy {
                text-align: left;
            }
        }

        /* HP KECIL */

        @media (max-width: 360px) {
            .public-container {
                width: calc(100% - 24px);
            }

            .brand {
                gap: 7px;
            }

            .brand-mark {
                width: 35px;
                height: 35px;
                flex-basis: 35px;
                font-size: 19px;
            }

            .brand-title {
                font-size: 16px;
            }

            .brand-subtitle {
                max-width: 130px;
                font-size: 9px;
            }

            .nav-inner > .nav-actions .btn {
                padding-inline: 8px;
                font-size: 10px;
            }

            .hero h1 {
                font-size: 29px;
            }

            .filter-card {
                padding: 13px;
            }

            .ranking-grid {
                padding: 10px;
            }

            .ranking-panel {
                padding: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <header class="public-nav">
        <div class="public-container nav-inner">
            <a href="{{ route('public.dashboard') }}"
               class="brand"
               aria-label="Beranda SIRAPIK">

                <div class="brand-mark" aria-hidden="true">S</div>

                <div>
                    <div class="brand-title">SIRAPIK</div>
                    <div class="brand-subtitle">
                        Bapelkes Provinsi Kalimantan Selatan
                    </div>
                </div>
            </a>

            <div class="nav-actions">
                <span class="nav-label">Portal informasi publik</span>

                <a class="btn btn-outline" href="{{ route('login') }}">
                    Login Internal
                    <span class="btn-arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        {{-- HERO --}}
        <section class="hero">
            <div class="public-container hero-grid">
                <div class="hero-copy">
                    <div class="eyebrow">Sistem Informasi Pelatihan</div>

                    <h1>
                        Informasi pelatihan kesehatan dalam satu sistem.
                    </h1>

                    <p class="hero-description">
                        SIRAPIK menyajikan ringkasan data pelatihan Bapelkes
                        Provinsi Kalimantan Selatan berdasarkan tahun,
                        kabupaten/kota atau mitra, serta sumber dana.
                        Informasi ini mendukung pemantauan pelaksanaan
                        pelatihan tenaga kesehatan secara lebih terarah.
                    </p>

                    <a class="btn" href="#dashboard">
                        Lihat Dashboard
                        <span class="btn-arrow" aria-hidden="true">↓</span>
                    </a>
                </div>

                <aside class="hero-note">
                    <div class="hero-note-top">
                        <div class="hero-note-icon" aria-hidden="true">▤</div>

                        <div>
                            <div class="hero-note-label">Akses informasi</div>
                            <strong>Dashboard untuk publik</strong>
                        </div>
                    </div>

                    <p>
                        Ringkasan informasi pelatihan dapat diakses tanpa akun.
                        Pengelolaan data dan administrasi tersedia bagi petugas
                        internal sesuai hak akses masing-masing.
                    </p>

                    <div class="hero-note-divider"></div>

                    <div class="hero-note-footer">
                        <span class="status-dot" aria-hidden="true"></span>
                        <span>
                            Informasi berdasarkan data yang tercatat di SIRAPIK
                        </span>
                    </div>
                </aside>
            </div>
        </section>

        {{-- DASHBOARD --}}
        <section class="section public-container" id="dashboard">
            <div class="section-heading">
                <div class="section-heading-main">
                    <div class="section-kicker">Ringkasan data</div>

                    <h2>Dashboard Pelatihan</h2>

                    <p>
                        Gunakan filter di bawah untuk melihat statistik
                        pelatihan berdasarkan kriteria yang dipilih.
                    </p>
                </div>

                <div class="data-badge">
                    <span class="data-badge-dot" aria-hidden="true"></span>
                    Data pelatihan
                </div>
            </div>

            {{-- FILTER --}}
            <form
                method="GET"
                action="{{ route('public.dashboard') }}"
                class="filter-card"
                id="dashboard-filter"
            >
                <div class="filter-title">
                    <span class="filter-title-icon" aria-hidden="true">⌕</span>
                    Filter data pelatihan
                </div>

                <div class="filter-grid">
                    <div class="form-field">
                        <label for="year">Tahun pelatihan</label>

                        <select name="year" id="year">
                            @forelse ($years as $availableYear)
                                <option
                                    value="{{ $availableYear }}"
                                    @selected((int) $year === (int) $availableYear)
                                >
                                    {{ $availableYear }}
                                </option>
                            @empty
                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="district">Kabupaten/Kota/Mitra</label>

                        <select name="district" id="district">
                            <option value="">Semua Kabupaten/Kota/Mitra</option>

                            @foreach ($districts as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected($district === $item)
                                >
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="funding_source">Sumber dana</label>

                        <select name="funding_source" id="funding_source">
                            <option value="">Semua sumber dana</option>

                            @foreach ($fundingSources as $source)
                                <option
                                    value="{{ $source }}"
                                    @selected($fundingSource === $source)
                                >
                                    {{ $source }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn" type="submit">
                        Terapkan Filter
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
            </form>

            {{-- STATISTIK --}}
            <div class="stats-grid">
                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Jumlah pelatihan</div>
                        <div class="stat-icon" aria-hidden="true">▤</div>
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalTrainings, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Jumlah pelatihan sesuai filter yang dipilih
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Peserta yang sudah di latih</div>
                        <div class="stat-icon" aria-hidden="true">♙</div>
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalParticipants, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Jumlah peserta yang sudah dilatih per individu
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Tahun laporan</div>
                        <div class="stat-icon" aria-hidden="true">▦</div>
                    </div>

                    <div class="stat-value">{{ $year }}</div>

                    <div class="stat-caption">
                        Tahun pelaksanaan pelatihan yang sedang ditampilkan
                    </div>
                </article>
            </div>

            {{-- REKAP DAN PERINGKAT --}}
            <div class="data-grid">
                {{-- REKAP WILAYAH --}}
                <section class="content-card">
                    <div class="content-card-header">
                        <h3>Rekap Kabupaten/Kota/Mitra</h3>

                        <p class="card-description">
                            Jumlah pelatihan berdasarkan wilayah atau mitra
                            yang tercatat.
                        </p>
                    </div>

                    <div class="card-body">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kabupaten/Kota/Mitra</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($trainingByDistrict as $row)
                                        <tr>
                                            <td>{{ $row->district }}</td>
                                            <td>
                                                <span class="table-count">
                                                    {{ number_format($row->total, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="empty">
                                                Belum ada data untuk filter ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- PERINGKAT TENAGA KESEHATAN --}}
                <section class="content-card">
                    <div class="content-card-header">
                        <h3>Partisipasi Tenaga Kesehatan</h3>

                        <p class="card-description">
                            Peringkat jenis tenaga kesehatan berdasarkan jumlah
                            peserta unik yang mengikuti pelatihan sesuai filter
                            yang dipilih.
                        </p>
                    </div>

                    <div class="ranking-grid">
                        {{-- LIMA TERTINGGI --}}
                        <div class="ranking-panel ranking-high">
                            <div class="ranking-heading">
                                <div class="ranking-icon" aria-hidden="true">↑</div>

                                <div>
                                    <h4>5 Tertinggi</h4>
                                    <p>Peserta terbanyak</p>
                                </div>
                            </div>

                            @if (isset($topNakes) && $topNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($topNakes as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) $nakes->total, 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Belum ada data peserta untuk ditampilkan
                                    pada filter ini.
                                </div>
                            @endif
                        </div>

                        {{-- LIMA TERENDAH --}}
                        <div class="ranking-panel ranking-low">
                            <div class="ranking-heading">
                                <div class="ranking-icon" aria-hidden="true">↓</div>

                                <div>
                                    <h4>5 Terendah</h4>
                                    <p>Peserta paling sedikit</p>
                                </div>
                            </div>

                            @if (isset($bottomNakes) && $bottomNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($bottomNakes as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) $nakes->total, 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Belum ada data peserta untuk ditampilkan
                                    pada filter ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="public-footer">
        <div class="public-container footer-inner">
            <div class="footer-brand">
                <div class="footer-mark" aria-hidden="true">S</div>

                <div>
                    <strong>SIRAPIK</strong><br>
                    Bapelkes Provinsi Kalimantan Selatan
                </div>
            </div>

            <div class="footer-copy">
                © {{ date('Y') }} SIRAPIK.
                Informasi ditampilkan berdasarkan data yang tersedia.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dashboardLink = document.querySelector('a[href="#dashboard"]');
            const dashboard = document.getElementById('dashboard');

            if (dashboardLink && dashboard) {
                dashboardLink.addEventListener('click', function (event) {
                    event.preventDefault();

                    dashboard.scrollIntoView({
                        behavior: window.matchMedia(
                            '(prefers-reduced-motion: reduce)'
                        ).matches ? 'auto' : 'smooth',
                        block: 'start'
                    });

                    if (window.history && window.history.replaceState) {
                        window.history.replaceState(
                            null,
                            '',
                            '#dashboard'
                        );
                    }
                });
            }
        });
    </script>
</body>
</html>