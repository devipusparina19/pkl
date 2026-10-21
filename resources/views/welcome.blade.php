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
            --muted: #68788d;
            --border: #e0e7ef;
            --surface: #ffffff;
            --page: #f4f7fb;
            --green: #207653;
            --green-soft: #e9f6ef;
            --red: #b34545;
            --red-soft: #fbefef;
            --radius: 12px;
            --shadow: 0 5px 20px rgba(19, 49, 83, .055);
            --shadow-hover: 0 12px 28px rgba(19, 49, 83, .10);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 20px;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--page);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
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
            outline: 3px solid rgba(36, 99, 166, .28);
            outline-offset: 3px;
        }

        .public-container {
            width: min(1200px, calc(100% - 56px));
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
            min-height: 82px;
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
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: linear-gradient(145deg, var(--navy-light), var(--navy-deep));
            color: #fff;
            font-size: 25px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 4px 10px rgba(18, 52, 90, .16);
        }

        .brand-title {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: .5px;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.45;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-label {
            color: var(--muted);
            font-size: 12px;
        }

        /* BUTTONS */

        .btn {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 10px 17px;
            border: 1px solid var(--blue);
            border-radius: 8px;
            background: var(--blue);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease,
                        box-shadow .18s ease, transform .18s ease;
        }

        .btn:hover {
            background: var(--blue-hover);
            border-color: var(--blue-hover);
            box-shadow: 0 4px 12px rgba(36, 99, 166, .15);
        }

        .btn-outline {
            background: #fff;
            color: var(--navy);
            border-color: #d4deea;
        }

        .btn-outline:hover {
            background: var(--blue-soft);
            color: var(--navy);
            border-color: #b9cde3;
        }

        .btn-arrow {
            font-size: 17px;
            line-height: 1;
        }

        /* HERO */

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 68px 0 72px;
            background-color: var(--navy-deep);
            background-image:
                linear-gradient(
                    105deg,
                    rgba(8, 29, 54, .91) 0%,
                    rgba(14, 47, 81, .84) 50%,
                    rgba(20, 64, 105, .69) 100%
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
            width: 400px;
            height: 400px;
            right: -140px;
            bottom: -250px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 50%;
            box-shadow:
                0 0 0 45px rgba(255, 255, 255, .025),
                0 0 0 90px rgba(255, 255, 255, .02);
            pointer-events: none;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(270px, .72fr);
            align-items: center;
            gap: 58px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 8px;
            color: #c9e1fa;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.6;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 26px;
            height: 2px;
            background: #91c6fa;
            border-radius: 5px;
        }

        .hero h1 {
            max-width: 720px;
            margin: 12px 0 18px;
            color: #fff;
            font-size: clamp(34px, 4vw, 48px);
            font-weight: 750;
            line-height: 1.18;
            letter-spacing: -.8px;
            text-wrap: balance;
        }

        .hero-description {
            max-width: 650px;
            margin: 0 0 27px;
            color: #e0eaf5;
            font-size: 15px;
            line-height: 1.9;
        }

        .hero .btn {
            min-height: 46px;
            padding-inline: 20px;
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
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 14px;
            background: rgba(7, 29, 54, .43);
            box-shadow: 0 12px 35px rgba(0, 0, 0, .08);
            backdrop-filter: blur(5px);
        }

        .hero-note-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .hero-note-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .17);
            border-radius: 10px;
            background: rgba(255, 255, 255, .10);
            color: #fff;
            font-size: 20px;
        }

        .hero-note-label {
            color: #bcd6f1;
            font-size: 10px;
            font-weight: 750;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .hero-note strong {
            display: block;
            margin-top: 2px;
            color: #fff;
            font-size: 16px;
            font-weight: 750;
            line-height: 1.5;
        }

        .hero-note p {
            margin: 0;
            color: #e0eaf5;
            font-size: 13px;
            line-height: 1.85;
        }

        .hero-note-divider {
            height: 1px;
            margin: 19px 0;
            background: rgba(255, 255, 255, .15);
        }

        .hero-note-footer {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #d2e5f8;
            font-size: 11px;
            line-height: 1.6;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            flex: 0 0 7px;
            border-radius: 50%;
            background: #75d4a2;
        }

        /* MAIN DASHBOARD */

        .section {
            padding: 40px 0 48px;
            scroll-margin-top: 20px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .section-heading-main {
            min-width: 0;
        }

        .section-kicker {
            margin-bottom: 5px;
            color: var(--blue);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .section-heading h2 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 780;
            line-height: 1.4;
            letter-spacing: -.35px;
        }

        .section-heading p {
            max-width: 720px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .data-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
            padding: 7px 11px;
            border: 1px solid #dce8f5;
            border-radius: 7px;
            background: #fff;
            color: #4d6682;
            font-size: 11px;
            font-weight: 650;
        }

        .data-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #39976b;
        }

        /* FILTER */

        .filter-card,
        .stat-card,
        .content-card {
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .filter-card {
            padding: 22px;
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 17px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 750;
        }

        .filter-title-icon {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 16px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns:
                minmax(135px, .82fr)
                minmax(210px, 1.35fr)
                minmax(170px, 1fr)
                auto;
            align-items: end;
            gap: 15px;
        }

        .form-field {
            min-width: 0;
        }

        .form-field label {
            display: block;
            margin-bottom: 7px;
            color: #34465d;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.5;
        }

        .form-field select {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 44px;
            padding: 9px 34px 9px 12px;
            border: 1px solid #d4deea;
            border-radius: 7px;
            background-color: #fff;
            color: var(--ink);
            font-size: 12px;
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
            min-height: 44px;
            white-space: nowrap;
        }

        /* STATISTICS */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 17px;
            margin-top: 18px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 23px 24px;
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
            gap: 12px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.6;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: var(--blue-soft);
            color: var(--blue);
            font-size: 19px;
        }

        .stat-value {
            margin-top: 15px;
            color: var(--navy);
            font-size: clamp(30px, 3vw, 37px);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
            font-variant-numeric: tabular-nums;
        }

        .stat-caption {
            margin-top: 9px;
            color: #7a899b;
            font-size: 11px;
            line-height: 1.7;
        }

        .stat-caption strong {
            color: #52647a;
            font-weight: 700;
        }

        /* DATA CARDS */

        .data-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.25fr);
            align-items: start;
            gap: 18px;
            margin-top: 18px;
        }

        .content-card {
            overflow: hidden;
        }

        .content-card-header {
            padding: 22px 22px 17px;
            border-bottom: 1px solid #edf1f6;
        }

        .content-card h3 {
            margin: 0;
            color: var(--navy);
            font-size: 16px;
            font-weight: 780;
            line-height: 1.5;
        }

        .card-description {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.75;
        }

        .card-body {
            padding: 0 22px 18px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overscroll-behavior-x: contain;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #edf1f5;
        }

        th {
            background: #f5f8fc;
            color: #52647a;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.6;
            letter-spacing: .3px;
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
            line-height: 1.65;
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
            min-width: 30px;
            padding: 4px 8px;
            border-radius: 6px;
            background: var(--blue-soft);
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .empty {
            padding: 26px 12px !important;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            line-height: 1.7;
        }

        /* RANKING NAKES */

        .ranking-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
            gap: 13px;
            padding: 19px;
        }

        .ranking-panel {
            min-width: 0;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .ranking-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .ranking-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            font-size: 19px;
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
            font-size: 12px;
            font-weight: 800;
            line-height: 1.5;
        }

        .ranking-heading p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .ranking-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ranking-item {
            display: grid;
            grid-template-columns: 27px minmax(0, 1fr) auto;
            align-items: center;
            gap: 8px;
            padding: 11px 0;
            border-bottom: 1px solid #edf1f5;
        }

        .ranking-item:last-child {
            border-bottom: none;
        }

        .ranking-number {
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            background: #f0f4f9;
            color: #49617d;
            font-size: 11px;
            font-weight: 800;
        }

        .ranking-item:first-child .ranking-number {
            background: #e6eef9;
            color: var(--navy);
        }

        .ranking-name {
            min-width: 0;
            color: #3d4d61;
            font-size: 11px;
            font-weight: 650;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .ranking-total {
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .ranking-total small {
            color: #8794a5;
            font-size: 9px;
            font-weight: 600;
        }

        .ranking-empty {
            padding: 14px;
            border: 1px dashed #d9e2ec;
            border-radius: 7px;
            background: #f8fafc;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        /* FOOTER */

        .public-footer {
            margin-top: 0;
            padding: 28px 0;
            background: var(--navy-deep);
            color: #cfdaea;
            font-size: 11px;
            line-height: 1.8;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px 28px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-mark {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            flex: 0 0 36px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 8px;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
        }

        .footer-inner strong {
            color: #fff;
            font-size: 13px;
            font-weight: 800;
        }

        .footer-copy {
            max-width: 410px;
            color: #bdcadd;
            text-align: right;
        }

        /* RESPONSIVE: LAPTOP KECIL / TABLET */

        @media (max-width: 1080px) {
            .hero-grid {
                grid-template-columns: minmax(0, 1.2fr) minmax(245px, .8fr);
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

            .ranking-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .public-container {
                width: calc(100% - 36px);
            }

            .nav-inner {
                min-height: 74px;
            }

            .hero {
                padding: 48px 0 50px;
                background-position: center;
            }

            .hero-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 28px;
            }

            .hero h1 {
                max-width: 620px;
                font-size: clamp(32px, 6vw, 41px);
            }

            .hero-description {
                max-width: 620px;
                font-size: 14px;
            }

            .hero-note {
                max-width: 620px;
            }

            .section {
                padding: 32px 0 38px;
            }

            .section-heading h2 {
                font-size: 23px;
            }

            .stats-grid {
                gap: 12px;
            }

            .stat-card {
                padding: 19px;
            }

            .data-grid {
                gap: 15px;
            }

            .content-card-header {
                padding: 19px 19px 15px;
            }

            .card-body {
                padding-inline: 19px;
            }

            .ranking-grid {
                padding: 16px;
                gap: 11px;
            }
        }

        /* RESPONSIVE: HP */

        @media (max-width: 540px) {
            body {
                font-size: 13px;
            }

            .public-container {
                width: calc(100% - 30px);
            }

            .nav-inner {
                min-height: 69px;
                gap: 9px;
            }

            .brand {
                gap: 9px;
            }

            .brand-mark {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
                border-radius: 8px;
                font-size: 21px;
            }

            .brand-title {
                font-size: 17px;
            }

            .brand-subtitle {
                max-width: 185px;
                margin-top: 2px;
                font-size: 10px;
                line-height: 1.4;
            }

            .nav-actions {
                gap: 0;
            }

            .nav-label {
                display: none;
            }

            .nav-inner > .nav-actions .btn {
                min-height: 36px;
                gap: 4px;
                padding: 8px 10px;
                font-size: 11px;
                white-space: nowrap;
            }

            .hero {
                padding: 38px 0 40px;
            }

            .eyebrow {
                font-size: 10px;
                letter-spacing: 1.1px;
            }

            .hero h1 {
                margin: 10px 0 13px;
                font-size: clamp(29px, 8vw, 35px);
                line-height: 1.24;
                letter-spacing: -.45px;
            }

            .hero-description {
                margin-bottom: 21px;
                font-size: 13px;
                line-height: 1.85;
            }

            .hero .btn {
                min-height: 42px;
                padding-inline: 15px;
                font-size: 12px;
            }

            .hero-note {
                padding: 17px;
                border-radius: 10px;
            }

            .hero-note-top {
                gap: 10px;
                margin-bottom: 13px;
            }

            .hero-note-icon {
                width: 38px;
                height: 38px;
                flex-basis: 38px;
            }

            .hero-note strong {
                font-size: 14px;
            }

            .hero-note p {
                font-size: 12px;
                line-height: 1.8;
            }

            .hero-note-divider {
                margin: 14px 0;
            }

            .section {
                padding: 27px 0 31px;
            }

            .section-heading {
                align-items: flex-start;
                margin-bottom: 17px;
            }

            .section-heading h2 {
                font-size: 21px;
            }

            .section-heading p {
                font-size: 12px;
            }

            .data-badge {
                display: none;
            }

            .filter-card {
                padding: 16px;
            }

            .filter-title {
                margin-bottom: 14px;
                font-size: 13px;
            }

            .filter-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 13px;
            }

            .form-field label {
                margin-bottom: 6px;
                font-size: 12px;
            }

            .form-field select {
                min-height: 44px;
                padding-left: 11px;
                font-size: 12px;
            }

            .filter-grid > .btn {
                min-height: 43px;
                font-size: 12px;
            }

            .stats-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 10px;
                margin-top: 13px;
            }

            .stat-card {
                padding: 16px 17px;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                flex-basis: 36px;
                font-size: 17px;
            }

            .stat-label {
                font-size: 12px;
            }

            .stat-value {
                margin-top: 8px;
                font-size: 29px;
            }

            .stat-caption {
                margin-top: 5px;
                font-size: 10px;
            }

            .data-grid {
                gap: 13px;
                margin-top: 13px;
            }

            .content-card-header {
                padding: 16px 16px 13px;
            }

            .content-card h3 {
                font-size: 15px;
            }

            .card-description {
                font-size: 11px;
            }

            .card-body {
                padding: 0 13px 13px;
            }

            table {
                min-width: 265px;
                margin-top: 12px;
                font-size: 11px;
            }

            th,
            td {
                padding: 10px 8px;
            }

            th {
                font-size: 9px;
            }

            .ranking-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 11px;
                padding: 13px;
            }

            .ranking-panel {
                padding: 14px;
            }

            .ranking-heading h4 {
                font-size: 12px;
            }

            .ranking-name,
            .ranking-total {
                font-size: 11px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 13px;
            }

            .footer-copy {
                text-align: left;
            }
        }

        @media (max-width: 360px) {
            .public-container {
                width: calc(100% - 24px);
            }

            .brand-subtitle {
                max-width: 145px;
                font-size: 9px;
            }

            .brand-title {
                font-size: 16px;
            }

            .nav-inner > .nav-actions .btn {
                padding-inline: 8px;
                font-size: 10px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .filter-card {
                padding: 13px;
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
            <a href="{{ route('public.dashboard') }}" class="brand" aria-label="Beranda SIRAPIK">
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
                <div>
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
                        Informasi berdasarkan data yang tercatat di SIRAPIK
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
                        <div class="stat-label">Peserta terdaftar</div>
                        <div class="stat-icon" aria-hidden="true">♙</div>
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalParticipants, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Peserta unik dari riwayat pelatihan yang sesuai filter
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

                {{-- PERINGKAT JENIS NAKES --}}
                <section class="content-card">
                    <div class="content-card-header">
                        <h3>Partisipasi Tenaga Kesehatan</h3>

                        <p class="card-description">
                            Lima jenis tenaga kesehatan dengan keikutsertaan
                            tertinggi dan terendah pada filter yang dipilih.
                        </p>
                    </div>

                    <div class="ranking-grid">
                        {{-- LIMA TERTINGGI --}}
                        <div class="ranking-panel ranking-high">
                            <div class="ranking-heading">
                                <div class="ranking-icon" aria-hidden="true">↑</div>

                                <div>
                                    <h4>5 Tertinggi</h4>
                                    <p>Partisipasi terbanyak</p>
                                </div>
                            </div>

                            @php
                                $topNakes = $topNakes ?? collect();
                            @endphp

                            @if ($topNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($topNakes->take(5) as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name ?? $nakes->profession ?? $nakes->jenis_nakes ?? 'Tidak diketahui' }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) ($nakes->total ?? $nakes->total_participations ?? 0), 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Data peringkat belum tersedia untuk filter ini.
                                    Pastikan data profesi peserta sudah sesuai
                                    dengan master jenis tenaga kesehatan.
                                </div>
                            @endif
                        </div>

                        {{-- LIMA TERENDAH --}}
                        <div class="ranking-panel ranking-low">
                            <div class="ranking-heading">
                                <div class="ranking-icon" aria-hidden="true">↓</div>

                                <div>
                                    <h4>5 Terendah</h4>
                                    <p>Partisipasi paling sedikit</p>
                                </div>
                            </div>

                            @php
                                $bottomNakes = $bottomNakes ?? collect();
                            @endphp

                            @if ($bottomNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($bottomNakes->take(5) as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name ?? $nakes->profession ?? $nakes->jenis_nakes ?? 'Tidak diketahui' }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) ($nakes->total ?? $nakes->total_participations ?? 0), 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Data peringkat belum tersedia untuk filter ini.
                                    Pastikan data profesi peserta sudah sesuai
                                    dengan master jenis tenaga kesehatan.
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
                        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                            ? 'auto'
                            : 'smooth',
                        block: 'start'
                    });

                    if (window.history && window.history.replaceState) {
                        window.history.replaceState(null, '', '#dashboard');
                    }
                });
            }
        });
    </script>
</body>
</html>