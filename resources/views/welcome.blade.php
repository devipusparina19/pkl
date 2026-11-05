<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#102f50">
    <meta name="description" content="SIRAPIK - Sistem Informasi Pelatihan Bapelkes Provinsi Kalimantan Selatan.">

    <title>SIRAPIK | Bapelkes Provinsi Kalimantan Selatan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #102f50;
            --navy-deep: #09233d;
            --navy-soft: #1c4b75;
            --blue: #2874b8;
            --blue-bright: #3989ce;
            --blue-pale: #edf5fc;
            --gold: #d9b36b;
            --gold-pale: #fbf5e9;
            --ink: #24384d;
            --muted: #6f8092;
            --muted-light: #91a0ae;
            --line: #e2e9f0;
            --surface: #ffffff;
            --canvas: #f4f7fa;
            --green: #27845d;
            --green-pale: #eaf6ef;
            --orange: #b87543;
            --orange-pale: #fff3e9;
            --shadow-sm: 0 4px 16px rgba(16, 47, 80, .045);
            --shadow: 0 12px 34px rgba(16, 47, 80, .075);
            --shadow-lg: 0 22px 50px rgba(16, 47, 80, .12);
            --radius: 14px;
            --radius-sm: 9px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 110px;
        }

        body {
            min-width: 320px;
            overflow-x: hidden;
            color: var(--ink);
            background: var(--surface);
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        body,
        button,
        select {
            font-family: 'DM Sans', sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        select {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .public-container {
            width: min(1440px, calc(100% - 80px));
            margin-inline: auto;
        }

        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            flex: 0 0 auto;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* TOPBAR */

        .topbar {
            color: rgba(255,255,255,.82);
            background: var(--navy-deep);
            font-size: 13px;
        }

        .topbar-inner {
            min-height: 39px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar-item {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .topbar-item .icon {
            width: 15px;
            height: 15px;
            color: var(--gold);
        }

        .topbar-right {
            color: rgba(255,255,255,.65);
        }

        /* NAVIGATION */

        .public-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid rgba(226,233,240,.9);
            box-shadow: 0 4px 18px rgba(16,47,80,.035);
            backdrop-filter: blur(16px);
        }

        .nav-inner {
            width: min(1440px, calc(100% - 80px));
            min-height: 86px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            margin-inline: auto;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 13px;
            flex: 0 0 auto;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            padding: 5px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #f8fafc;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: .4px;
            line-height: 1.2;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 31px;
        }

        .nav-link {
            position: relative;
            padding-block: 10px;
            color: #526579;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
            transition: color .2s ease;
        }

        .nav-link::after {
            position: absolute;
            right: 0;
            bottom: 2px;
            left: 0;
            height: 2px;
            content: "";
            background: var(--gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .22s ease;
        }

        .nav-link:hover,
        .nav-link:focus-visible {
            color: var(--blue);
        }

        .nav-link:hover::after,
        .nav-link:focus-visible::after {
            transform: scaleX(1);
        }

        .nav-button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            padding: 0 19px;
            border: 1px solid var(--navy);
            border-radius: 7px;
            color: white;
            background: var(--navy);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .nav-button:hover {
            background: var(--blue);
            border-color: var(--blue);
            box-shadow: 0 7px 18px rgba(40,116,184,.2);
            transform: translateY(-2px);
        }

        .nav-button .icon {
            width: 16px;
            height: 16px;
        }

        .mobile-menu-button {
            width: 45px;
            height: 45px;
            display: none;
            place-items: center;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--navy);
            background: white;
        }

        .mobile-menu-button .icon {
            width: 23px;
            height: 23px;
        }

        /* HERO */

        .hero {
            position: relative;
            min-height: 590px;
            display: flex;
            align-items: center;
            overflow: hidden;
            color: white;
            background:
                linear-gradient(90deg, rgba(6,28,49,.96) 0%, rgba(10,43,72,.88) 48%, rgba(13,53,86,.56) 100%),
                url("{{ asset('images/background-sirapik.jpg') }}") center/cover no-repeat;
        }

        .hero::before {
            position: absolute;
            top: -270px;
            right: -170px;
            width: 700px;
            height: 700px;
            content: "";
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            position: absolute;
            top: -150px;
            right: -50px;
            width: 460px;
            height: 460px;
            content: "";
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(280px, .6fr);
            align-items: center;
            gap: clamp(35px, 6vw, 90px);
            padding-block: 86px;
        }

        .hero-content {
            max-width: 820px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 24px;
            padding: 9px 15px;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 30px;
            color: #f2d99f;
            background: rgba(255,255,255,.075);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-label::before {
            width: 7px;
            height: 7px;
            content: "";
            border-radius: 50%;
            background: var(--gold);
            box-shadow: 0 0 0 4px rgba(217,179,107,.15);
        }

        .hero h1 {
            max-width: 850px;
            margin-bottom: 24px;
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(42px, 5vw, 66px);
            font-weight: 800;
            line-height: 1.14;
            letter-spacing: -2.1px;
        }

        .hero h1 span {
            color: #edca83;
        }

        .hero-description {
            max-width: 710px;
            color: rgba(255,255,255,.82);
            font-size: 16px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
            margin-top: 34px;
        }

        .button-primary {
            min-height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 0 23px;
            border: 1px solid #edca83;
            border-radius: 7px;
            color: var(--navy-deep);
            background: #edca83;
            font-size: 14px;
            font-weight: 800;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .button-primary:hover {
            background: white;
            border-color: white;
            box-shadow: 0 9px 22px rgba(0,0,0,.15);
            transform: translateY(-3px);
        }

        .button-primary .icon {
            width: 17px;
            height: 17px;
        }

        .hero-side {
            position: relative;
            padding: 29px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 13px;
            background: rgba(255,255,255,.09);
            box-shadow: 0 18px 55px rgba(0,0,0,.12);
            backdrop-filter: blur(13px);
        }

        .hero-side::before {
            position: absolute;
            top: -1px;
            left: 28px;
            width: 58px;
            height: 3px;
            content: "";
            background: var(--gold);
        }

        .hero-side-icon {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            margin-bottom: 21px;
            border-radius: 11px;
            color: var(--navy);
            background: #edca83;
        }

        .hero-side-icon .icon {
            width: 26px;
            height: 26px;
        }

        .hero-side-label {
            margin-bottom: 8px;
            color: #edca83;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-side h3 {
            margin-bottom: 13px;
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.45;
        }

        .hero-side p {
            color: rgba(255,255,255,.78);
            font-size: 14px;
            line-height: 1.9;
        }

        .hero-side-line {
            height: 1px;
            margin: 22px 0 16px;
            background: rgba(255,255,255,.17);
        }

        .hero-side-status {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.86);
            font-size: 12px;
            font-weight: 600;
        }

        .hero-side-status span {
            width: 8px;
            height: 8px;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #72d6a1;
            box-shadow: 0 0 0 4px rgba(114,214,161,.13);
        }

        /* SECTION LABEL */

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 13px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .section-label::before {
            width: 25px;
            height: 2px;
            content: "";
            background: var(--gold);
        }

        /* ABOUT */

        .about-section {
            position: relative;
            padding: 85px 0;
            overflow: hidden;
            background: #f5f8fb;
        }

        .about-section::before {
            position: absolute;
            top: 70px;
            left: -170px;
            width: 330px;
            height: 330px;
            content: "";
            border: 1px solid rgba(40,116,184,.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .about-grid {
            position: relative;
            display: grid;
            grid-template-columns: minmax(300px, .88fr) minmax(0, 1.12fr);
            align-items: center;
            gap: clamp(35px, 6vw, 85px);
        }

        .about-visual {
            position: relative;
            min-height: 410px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 15px;
            background:
                linear-gradient(145deg, rgba(7,34,59,.94), rgba(24,76,119,.87)),
                url("{{ asset('images/background-sirapik.jpg') }}") center/cover;
            box-shadow: var(--shadow-lg);
        }

        .about-visual::before,
        .about-visual::after {
            position: absolute;
            content: "";
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50%;
        }

        .about-visual::before {
            width: 390px;
            height: 390px;
        }

        .about-visual::after {
            width: 285px;
            height: 285px;
        }

        .about-visual-content {
            position: relative;
            z-index: 2;
            padding: 30px;
            color: white;
            text-align: center;
        }

        .about-logo {
            width: 108px;
            height: 108px;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
            padding: 11px;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 19px;
            background: white;
            box-shadow: 0 12px 30px rgba(0,0,0,.14);
        }

        .about-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .about-visual-content strong {
            display: block;
            font-family: 'Manrope', sans-serif;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 1.4px;
        }

        .about-visual-content span {
            display: block;
            margin-top: 7px;
            color: rgba(255,255,255,.8);
            font-size: 13px;
        }

        .about-content h2 {
            max-width: 680px;
            margin-bottom: 20px;
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(30px, 3.3vw, 43px);
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -.9px;
        }

        .about-content p {
            margin-bottom: 15px;
            color: #64778a;
            font-size: 15px;
            line-height: 1.95;
        }

        .about-points {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px 20px;
            margin-top: 27px;
        }

        .about-point {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .about-point span {
            width: 29px;
            height: 29px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 50%;
            color: var(--green);
            background: var(--green-pale);
            font-size: 14px;
            font-weight: 800;
        }

        /* DASHBOARD */

        .dashboard-section {
            position: relative;
            padding: 85px 0 95px;
            background: var(--canvas);
        }

        .dashboard-section::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 5px;
            content: "";
            background: linear-gradient(90deg, var(--navy) 0 22%, var(--blue) 22% 70%, var(--gold) 70% 100%);
        }

        .dashboard-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 32px;
        }

        .dashboard-heading h2 {
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(34px, 4vw, 45px);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .dashboard-heading p {
            max-width: 720px;
            margin-top: 12px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.85;
        }

        .dashboard-badge {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0 16px;
            border: 1px solid #d8e7f4;
            border-radius: 30px;
            color: var(--blue);
            background: #edf5fc;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .dashboard-badge span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #35a675;
            box-shadow: 0 0 0 4px rgba(53,166,117,.12);
        }

        /* FILTER */

        .filter-card {
            position: relative;
            margin-bottom: 25px;
            padding: 27px 29px 29px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .filter-card::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 4px;
            content: "";
            border-radius: var(--radius) 0 0 var(--radius);
            background: var(--blue);
        }

        .filter-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: 20px;
            font-weight: 800;
        }

        .filter-heading span {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            color: var(--blue);
            background: var(--blue-pale);
        }

        .filter-heading .icon {
            width: 21px;
            height: 21px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: minmax(140px, .85fr) minmax(220px, 1.45fr) minmax(170px, 1fr) auto;
            align-items: end;
            gap: 17px;
        }

        .form-field label {
            display: block;
            margin-bottom: 9px;
            color: #42566b;
            font-size: 14px;
            font-weight: 800;
        }

        .form-field select {
            width: 100%;
            height: 51px;
            padding: 0 40px 0 14px;
            border: 1px solid #dce5ed;
            border-radius: 7px;
            outline: none;
            color: #40566b;
            background-color: white;
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .form-field select:hover {
            border-color: #aebfd0;
        }

        .form-field select:focus-visible {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(40,116,184,.12);
        }

        .filter-submit {
            min-height: 51px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 21px;
            border: 1px solid var(--navy);
            border-radius: 7px;
            color: white;
            background: var(--navy);
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .filter-submit:hover {
            border-color: var(--blue);
            background: var(--blue);
            box-shadow: 0 7px 18px rgba(40,116,184,.17);
            transform: translateY(-2px);
        }

        /* STATISTICS */

        .stats-grid {
            display: grid;
            grid-template-columns: 1.1fr 1.1fr .8fr;
            gap: 17px;
            margin-bottom: 25px;
        }

        .stat-card {
            position: relative;
            min-height: 184px;
            overflow: hidden;
            padding: 25px 27px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: white;
            box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .stat-card:hover {
            border-color: #cbdbea;
            box-shadow: var(--shadow);
            transform: translateY(-4px);
        }

        .stat-card::after {
            position: absolute;
            right: -40px;
            bottom: -65px;
            width: 150px;
            height: 150px;
            content: "";
            border: 1px solid #e7eff7;
            border-radius: 50%;
        }

        .stat-card:nth-child(2)::after {
            border-color: #e3f0e9;
        }

        .stat-card:nth-child(3)::after {
            border-color: #f0e7d6;
        }

        .stat-top {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .stat-label {
            color: #718296;
            font-size: 14px;
            font-weight: 700;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 11px;
            color: var(--blue);
            background: var(--blue-pale);
        }

        .stat-card:nth-child(2) .stat-icon {
            color: var(--green);
            background: var(--green-pale);
        }

        .stat-card:nth-child(3) .stat-icon {
            color: #a47b35;
            background: var(--gold-pale);
        }

        .stat-icon .icon {
            width: 23px;
            height: 23px;
        }

        .stat-value {
            position: relative;
            z-index: 1;
            margin-top: 12px;
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(34px, 3.2vw, 43px);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }

        .stat-caption {
            position: relative;
            z-index: 1;
            margin-top: 7px;
            color: #8997a6;
            font-size: 12px;
            line-height: 1.6;
        }

        /* DATA GRID */

        .data-grid {
            display: grid;
            grid-template-columns: minmax(0, .95fr) minmax(0, 1.2fr);
            align-items: start;
            gap: 20px;
        }

        .content-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .content-card-header {
            position: relative;
            padding: 25px 26px 21px;
            border-bottom: 1px solid var(--line);
        }

        .content-card-header::before {
            position: absolute;
            top: 25px;
            bottom: 23px;
            left: 0;
            width: 3px;
            content: "";
            background: var(--gold);
        }

        .content-card-header h3 {
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: 19px;
            font-weight: 800;
            line-height: 1.45;
        }

        .card-description {
            margin-top: 7px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .card-body {
            padding: 19px 22px 23px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd7e3 transparent;
        }

        .table-wrap table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .table-wrap th {
            padding: 14px 13px;
            color: #63768a;
            background: #f5f8fb;
            font-size: 13px;
            font-weight: 800;
            text-align: left;
        }

        .table-wrap th:first-child {
            border-radius: 6px 0 0 6px;
        }

        .table-wrap th:last-child {
            border-radius: 0 6px 6px 0;
        }

        .table-wrap td {
            padding: 16px 13px;
            border-bottom: 1px solid #edf1f5;
            color: #4d6074;
            font-size: 14px;
        }

        .table-wrap tbody tr:last-child td {
            border-bottom: 0;
        }

        .table-wrap tbody tr {
            transition: background .18s ease;
        }

        .table-wrap tbody tr:hover {
            background: #f8fbfe;
        }

        .table-count {
            min-width: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 12px;
            border: 1px solid #dceaf6;
            border-radius: 30px;
            color: var(--blue);
            background: var(--blue-pale);
            font-size: 12px;
            font-weight: 800;
        }

        .empty {
            padding: 32px 14px !important;
            color: #8493a2 !important;
            text-align: center;
            white-space: normal;
        }

        /* RANKING */

        .ranking-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            padding: 19px;
        }

        .ranking-panel {
            min-width: 0;
            padding: 19px;
            border: 1px solid #e8eef3;
            border-radius: 10px;
            background: #fbfcfd;
        }

        .ranking-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .ranking-icon {
            width: 41px;
            height: 41px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 800;
        }

        .ranking-high .ranking-icon {
            color: var(--green);
            background: var(--green-pale);
        }

        .ranking-low .ranking-icon {
            color: var(--orange);
            background: var(--orange-pale);
        }

        .ranking-heading h4 {
            color: var(--navy);
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 800;
        }

        .ranking-heading p {
            margin-top: 2px;
            color: #8997a5;
            font-size: 12px;
        }

        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            list-style: none;
        }

        .ranking-item {
            display: grid;
            grid-template-columns: 28px minmax(0, 1fr) auto;
            align-items: center;
            gap: 9px;
            padding: 12px 0;
            border-bottom: 1px solid #eaf0f4;
        }

        .ranking-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .ranking-number {
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            color: var(--blue);
            background: var(--blue-pale);
            font-size: 12px;
            font-weight: 800;
        }

        .ranking-name {
            min-width: 0;
            overflow-wrap: anywhere;
            color: #506277;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.5;
        }

        .ranking-total {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .ranking-total small {
            color: #8a98a6;
            font-size: 11px;
            font-weight: 500;
        }

        .ranking-empty {
            padding: 22px 9px;
            color: #8493a2;
            font-size: 13px;
            line-height: 1.8;
            text-align: center;
        }

        /* FOOTER */

        .public-footer {
            color: white;
            background: #081f36;
        }

        .footer-main {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(220px, .6fr);
            gap: 65px;
            padding-top: 52px;
            padding-bottom: 42px;
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .footer-logo {
            width: 49px;
            height: 49px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            padding: 5px;
            border-radius: 10px;
            background: white;
        }

        .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .footer-brand h3 {
            margin-bottom: 6px;
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: 21px;
            font-weight: 800;
        }

        .footer-brand p {
            max-width: 600px;
            color: rgba(255,255,255,.65);
            font-size: 13px;
            line-height: 1.85;
        }

        .footer-links h4 {
            margin-bottom: 14px;
            color: #edca83;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 800;
        }

        .footer-links a {
            width: fit-content;
            display: block;
            margin-bottom: 9px;
            color: rgba(255,255,255,.72);
            font-size: 13px;
            transition: color .2s ease, transform .2s ease;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(3px);
        }

        .footer-bottom {
            padding: 19px 0;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .footer-bottom-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: rgba(255,255,255,.58);
            font-size: 12px;
        }

        /* REVEAL ANIMATION */

        .reveal {
            opacity: 0;
            transform: translateY(15px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* FOCUS */

        a:focus-visible,
        button:focus-visible,
        select:focus-visible {
            outline: 3px solid rgba(57,137,206,.45);
            outline-offset: 3px;
        }

        /* TABLET */

        @media (max-width: 1100px) {
            .public-container,
            .nav-inner {
                width: calc(100% - 52px);
            }

            .nav-menu {
                gap: 19px;
            }

            .nav-link {
                font-size: 13px;
            }

            .hero-inner {
                gap: 35px;
            }

            .hero h1 {
                font-size: clamp(40px, 5vw, 54px);
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-submit {
                width: 100%;
            }

            .data-grid {
                grid-template-columns: 1fr;
            }

            .about-grid {
                gap: 40px;
            }
        }

        /* MOBILE */

        @media (max-width: 760px) {
            .public-container,
            .nav-inner {
                width: calc(100% - 36px);
            }

            .topbar-inner {
                min-height: 36px;
                justify-content: center;
            }

            .topbar-right {
                display: none;
            }

            .topbar-item {
                font-size: 11px;
            }

            .nav-inner {
                min-height: 73px;
            }

            .brand-logo {
                width: 46px;
                height: 46px;
            }

            .brand-title {
                font-size: 21px;
            }

            .brand-subtitle {
                max-width: 205px;
                font-size: 10px;
            }

            .mobile-menu-button {
                display: grid;
                flex: 0 0 auto;
            }

            .nav-menu {
                position: absolute;
                top: 100%;
                right: 0;
                left: 0;
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 12px 20px 20px;
                border-top: 1px solid var(--line);
                background: white;
                box-shadow: 0 15px 24px rgba(16,47,80,.1);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-link {
                padding: 13px 4px;
                border-bottom: 1px solid #edf1f5;
                font-size: 14px;
            }

            .nav-link::after {
                display: none;
            }

            .nav-button {
                margin-top: 13px;
            }

            .hero {
                min-height: auto;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 35px;
                padding-block: 65px 72px;
            }

            .hero h1 {
                font-size: clamp(36px, 8vw, 49px);
                letter-spacing: -1.2px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-side {
                max-width: 540px;
                padding: 25px;
            }

            .hero-side h3 {
                font-size: 20px;
            }

            .about-section {
                padding: 60px 0;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .about-visual {
                min-height: 310px;
            }

            .about-content h2 {
                font-size: 32px;
            }

            .about-content p {
                font-size: 14px;
            }

            .dashboard-section {
                padding: 62px 0 70px;
            }

            .dashboard-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 17px;
                margin-bottom: 25px;
            }

            .dashboard-heading h2 {
                font-size: 35px;
            }

            .dashboard-heading p {
                font-size: 14px;
            }

            .dashboard-badge {
                min-height: 39px;
                font-size: 11px;
            }

            .filter-card {
                padding: 22px 20px 22px 23px;
            }

            .filter-heading {
                font-size: 18px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .form-field label {
                font-size: 13px;
            }

            .form-field select,
            .filter-submit {
                height: 49px;
                min-height: 49px;
                font-size: 14px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .stat-card {
                min-height: 155px;
                padding: 22px;
            }

            .stat-value {
                font-size: 36px;
            }

            .stat-label {
                font-size: 14px;
            }

            .data-grid {
                grid-template-columns: 1fr;
                gap: 17px;
            }

            .content-card-header {
                padding: 21px 22px 18px;
            }

            .content-card-header h3 {
                font-size: 17px;
            }

            .card-description {
                font-size: 12px;
            }

            .card-body {
                padding: 15px;
            }

            .table-wrap th,
            .table-wrap td {
                font-size: 12px;
            }

            .ranking-grid {
                grid-template-columns: 1fr;
                gap: 13px;
                padding: 15px;
            }

            .ranking-panel {
                padding: 18px;
            }

            .ranking-heading h4 {
                font-size: 15px;
            }

            .ranking-name {
                font-size: 13px;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 28px;
                padding-top: 36px;
                padding-bottom: 28px;
            }

            .footer-brand h3 {
                font-size: 20px;
            }

            .footer-bottom-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 6px;
                font-size: 11px;
            }
        }

        @media (max-width: 420px) {
            .public-container,
            .nav-inner {
                width: calc(100% - 30px);
            }

            .brand {
                gap: 9px;
            }

            .brand-title {
                font-size: 19px;
            }

            .brand-subtitle {
                max-width: 175px;
                font-size: 9px;
            }

            .hero-inner {
                padding-block: 55px 60px;
            }

            .hero h1 {
                font-size: 35px;
            }

            .hero-description {
                font-size: 14px;
            }

            .hero-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .button-primary {
                width: fit-content;
            }

            .hero-side {
                padding: 21px;
            }

            .about-visual {
                min-height: 275px;
            }

            .about-content h2 {
                font-size: 28px;
            }

            .about-points {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dashboard-heading h2 {
                font-size: 31px;
            }

            .filter-card {
                padding: 19px 16px 19px 19px;
            }

            .filter-heading {
                font-size: 16px;
            }

            .stat-value {
                font-size: 33px;
            }

            .content-card-header {
                padding: 18px;
            }

            .card-body {
                padding: 12px;
            }

            .ranking-grid {
                padding: 12px;
            }

            .footer-brand {
                gap: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>

<body>

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="public-container topbar-inner">
            <div class="topbar-item">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 7v5l3 2"></path>
                </svg>
                <span>Portal Informasi Publik Bapelkes</span>
            </div>

            <div class="topbar-item topbar-right">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8M8 13h5"></path>
                </svg>
                <span>Sistem Informasi Pelatihan</span>
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <header class="public-nav">
        <div class="nav-inner">

            <a href="{{ route('public.dashboard') }}" class="brand" aria-label="SIRAPIK - Beranda">
                <div class="brand-logo">
                    <img
                        src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan"
                    >
                </div>

                <div>
                    <div class="brand-title">SIRAPIK</div>
                    <div class="brand-subtitle">
                        Bapelkes Provinsi Kalimantan Selatan
                    </div>
                </div>
            </a>

            <button
                type="button"
                class="mobile-menu-button"
                id="mobile-menu-button"
                aria-label="Buka menu navigasi"
                aria-controls="nav-menu"
                aria-expanded="false"
            >
                <svg class="icon menu-open-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16"></path>
                </svg>
                <svg class="icon menu-close-icon" viewBox="0 0 24 24" aria-hidden="true" style="display:none">
                    <path d="m6 6 12 12M18 6 6 18"></path>
                </svg>
            </button>

            <nav class="nav-menu" id="nav-menu" aria-label="Navigasi utama">
                <a href="#beranda" class="nav-link">Beranda</a>
                <a href="#tentang" class="nav-link">Tentang SIRAPIK</a>
                <a href="#dashboard" class="nav-link">Data Pelatihan</a>

                <a href="{{ route('public.training-history') }}" class="nav-button">
                    Riwayat Pelatihan
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6"></path>
                    </svg>
                </a>
            </nav>

        </div>
    </header>

    <main>

        {{-- HERO --}}
        <section class="hero" id="beranda">
            <div class="public-container hero-inner">

                <div class="hero-content">
                    <div class="hero-label">
                        Sistem Informasi Pelatihan
                    </div>

                    <h1>
                        Informasi Pelatihan Kesehatan
                        <span>Dalam Satu Sistem.</span>
                    </h1>

                    <p class="hero-description">
                        SIRAPIK menyajikan informasi dan ringkasan data pelatihan
                        Bapelkes Provinsi Kalimantan Selatan. Melalui sistem ini,
                        masyarakat dapat melihat informasi pelatihan berdasarkan
                        tahun, kabupaten/kota atau mitra, serta sumber dana.
                    </p>

                    <div class="hero-actions">
                        <a href="#dashboard" class="button-primary">
                            Lihat Data Pelatihan
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <aside class="hero-side">
                    <div class="hero-side-icon">
                        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"></path>
                            <path d="M4 5.5v13A2.5 2.5 0 0 1 6.5 16H20"></path>
                            <path d="M9 7h7M9 10h7"></path>
                        </svg>
                    </div>

                    <div class="hero-side-label">Portal Informasi</div>

                    <h3>Informasi pelatihan lebih mudah diakses.</h3>

                    <p>
                        Melalui SIRAPIK, masyarakat dapat melihat ringkasan
                        pelatihan yang telah tercatat dalam sistem. Informasi
                        tersedia secara terbuka tanpa harus masuk ke akun pengguna.
                    </p>

                    <div class="hero-side-line"></div>

                    <div class="hero-side-status">
                        <span></span>
                        Informasi berdasarkan data SIRAPIK
                    </div>
                </aside>

            </div>
        </section>

        {{-- TENTANG SIRAPIK --}}
        <section class="about-section" id="tentang">
            <div class="public-container about-grid">

                <div class="about-visual reveal">
                    <div class="about-visual-content">
                        <div class="about-logo">
                            <img
                                src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                                alt="Logo Provinsi Kalimantan Selatan"
                            >
                        </div>

                        <strong>SIRAPIK</strong>
                        <span>Bapelkes Provinsi Kalimantan Selatan</span>
                    </div>
                </div>

                <div class="about-content reveal">
                    <div class="section-label">Mengenal SIRAPIK</div>

                    <h2>
                        Mendukung Pengelolaan Informasi Pelatihan Kesehatan.
                    </h2>

                    <p>
                        SIRAPIK merupakan Sistem Informasi Pelatihan yang
                        digunakan untuk menyajikan informasi pelatihan
                        Bapelkes Provinsi Kalimantan Selatan.
                    </p>

                    <p>
                        Informasi yang tersedia mencakup ringkasan pelatihan,
                        jumlah peserta, rekap berdasarkan kabupaten/kota
                        atau mitra, serta partisipasi tenaga kesehatan.
                        Penyajian informasi ini diharapkan membantu pengguna
                        memperoleh gambaran data pelatihan dengan lebih mudah.
                    </p>

                    <div class="about-points">
                        <div class="about-point">
                            <span>✓</span>
                            Informasi pelatihan
                        </div>

                        <div class="about-point">
                            <span>✓</span>
                            Ringkasan statistik
                        </div>

                        <div class="about-point">
                            <span>✓</span>
                            Filter data pelatihan
                        </div>

                        <div class="about-point">
                            <span>✓</span>
                            Rekap partisipasi
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- DASHBOARD DATA PELATIHAN --}}
        <section class="dashboard-section" id="dashboard">
            <div class="public-container">

                {{-- JUDUL DASHBOARD --}}
                <div class="dashboard-heading reveal">
                    <div>
                        <div class="section-label">Pusat Informasi Data</div>

                        <h2>Data Pelatihan</h2>

                        <p>
                            Bagian ini menampilkan jumlah pelatihan, jumlah peserta,
                            serta informasi pelatihan berdasarkan wilayah atau mitra
                            dan jenis tenaga kesehatan. Gunakan filter untuk melihat
                            data sesuai tahun dan sumber dana yang diinginkan.
                        </p>
                    </div>

                    <div class="dashboard-badge">
                        <span></span>
                        Data Pelatihan
                    </div>
                </div>

                {{-- FILTER DATA --}}
                <form
                    method="GET"
                    action="{{ route('public.dashboard') }}"
                    class="filter-card reveal"
                    id="dashboard-filter"
                >
                    <div class="filter-heading">
                        <span>
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 5h16M7 12h10M10 19h4"></path>
                                <circle cx="8" cy="5" r="1.5"></circle>
                                <circle cx="15" cy="12" r="1.5"></circle>
                                <circle cx="12" cy="19" r="1.5"></circle>
                            </svg>
                        </span>
                        Filter Data Pelatihan
                    </div>

                    <div class="filter-grid">

                        <div class="form-field">
                            <label for="year">Tahun Pelatihan</label>

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
                            <label for="district">
                                Kabupaten/Kota/Mitra/Mandiri
                            </label>

                            <select name="district" id="district">
                                <option value="">
                                    Semua Kabupaten/Kota/Mitra/Mandiri
                                </option>

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
                            <label for="funding_source">Sumber Dana</label>

                            <select name="funding_source" id="funding_source">
                                <option value="">Semua Sumber Dana</option>

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

                        <button class="filter-submit" type="submit">
                            Terapkan Filter
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"></path>
                            </svg>
                        </button>

                    </div>
                </form>

                {{-- RINGKASAN STATISTIK --}}
                <div class="stats-grid">

                    <article class="stat-card reveal">
                        <div class="stat-top">
                            <div class="stat-label">Jumlah Pelatihan</div>

                            <div class="stat-icon">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 4h16v16H4z"></path>
                                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="stat-value">
                            {{ number_format($totalTrainings, 0, ',', '.') }}
                        </div>

                        <div class="stat-caption">
                            Jumlah pelatihan sesuai filter yang dipilih
                        </div>
                    </article>

                    <article class="stat-card reveal">
                        <div class="stat-top">
                            <div class="stat-label">Peserta yang Sudah Dilatih</div>

                            <div class="stat-icon">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M16 20v-1.5a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4V20"></path>
                                    <circle cx="9.5" cy="7" r="4"></circle>
                                    <path d="M17 11a4 4 0 0 0 0-8M21 20v-1.5a4 4 0 0 0-3-3.87"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="stat-value">
                            {{ number_format($totalParticipants, 0, ',', '.') }}
                        </div>

                        <div class="stat-caption">
                            Jumlah peserta yang sudah dilatih per individu
                        </div>
                    </article>

                    <article class="stat-card reveal">
                        <div class="stat-top">
                            <div class="stat-label">Tahun Laporan</div>

                            <div class="stat-icon">
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                    <path d="M16 3v4M8 3v4M3 10h18"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="stat-value">
                            {{ $year }}
                        </div>

                        <div class="stat-caption">
                            Tahun pelaksanaan pelatihan yang sedang ditampilkan
                        </div>
                    </article>

                </div>

                {{-- REKAP DATA DAN PERINGKAT --}}
                <div class="data-grid">

                    {{-- REKAP KABUPATEN/KOTA/MITRA --}}
                    <section class="content-card reveal">
                        <div class="content-card-header">
                            <h3>Rekap Kabupaten/Kota/Mitra/Mandiri</h3>

                            <p class="card-description">
                                Jumlah pelatihan berdasarkan wilayah atau mitra
                                yang tercatat dalam sistem.
                            </p>
                        </div>

                        <div class="card-body">
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Kabupaten/Kota/Mitra/Mandiri</th>
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

                    {{-- PARTISIPASI TENAGA KESEHATAN --}}
                    <section class="content-card reveal">
                        <div class="content-card-header">
                            <h3>Partisipasi Tenaga Kesehatan</h3>

                            <p class="card-description">
                                Peringkat jenis tenaga kesehatan berdasarkan
                                jumlah peserta unik yang mengikuti pelatihan
                                sesuai filter yang dipilih.
                            </p>
                        </div>

                        <div class="ranking-grid">

                            {{-- 5 TERTINGGI --}}
                            <div class="ranking-panel ranking-high">
                                <div class="ranking-heading">
                                    <div class="ranking-icon">↑</div>

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

                            {{-- 5 TERENDAH --}}
                            <div class="ranking-panel ranking-low">
                                <div class="ranking-heading">
                                    <div class="ranking-icon">↓</div>

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

            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <footer class="public-footer">
        <div class="public-container footer-main">

            <div class="footer-brand">
                <div class="footer-logo">
                    <img
                        src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan"
                    >
                </div>

                <div>
                    <h3>SIRAPIK</h3>

                    <p>
                        Sistem Informasi Pelatihan Bapelkes Provinsi
                        Kalimantan Selatan. Menyediakan informasi dan
                        ringkasan data pelatihan melalui satu sistem.
                    </p>
                </div>
            </div>

            <div class="footer-links">
                <h4>Navigasi</h4>
                <a href="#beranda">Beranda</a>
                <a href="#tentang">Tentang SIRAPIK</a>
                <a href="#dashboard">Data Pelatihan</a>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="public-container footer-bottom-inner">
                <span>
                    © {{ date('Y') }} SIRAPIK. Hak Cipta Dilindungi.
                </span>

                <span>
                    Bapelkes Provinsi Kalimantan Selatan
                </span>
            </div>
        </div>
    </footer>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuButton = document.getElementById('mobile-menu-button');
            const navMenu = document.getElementById('nav-menu');
            const openIcon = menuButton?.querySelector('.menu-open-icon');
            const closeIcon = menuButton?.querySelector('.menu-close-icon');

            function setMenuState(isOpen) {
                if (!menuButton || !navMenu) return;

                navMenu.classList.toggle('active', isOpen);
                menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                menuButton.setAttribute(
                    'aria-label',
                    isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'
                );

                if (openIcon) openIcon.style.display = isOpen ? 'none' : 'inline-block';
                if (closeIcon) closeIcon.style.display = isOpen ? 'inline-block' : 'none';
            }

            if (menuButton && navMenu) {
                menuButton.addEventListener('click', function () {
                    const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
                    setMenuState(!isOpen);
                });

                navMenu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        setMenuState(false);
                    });
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        setMenuState(false);
                    }
                });

                window.addEventListener('resize', function () {
                    if (window.innerWidth > 760) {
                        setMenuState(false);
                    }
                });
            }

            // Animasi elemen ketika masuk ke area pandang.
            const revealElements = document.querySelectorAll('.reveal');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function (entries, currentObserver) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            currentObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -25px 0px'
                });

                revealElements.forEach(function (element) {
                    observer.observe(element);
                });
            } else {
                revealElements.forEach(function (element) {
                    element.classList.add('is-visible');
                });
            }
        });
    </script>

</body>
</html>