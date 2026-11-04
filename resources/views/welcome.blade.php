<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#12345a">
    <meta name="description" content="SIRAPIK - Sistem Informasi Pelatihan Bapelkes Provinsi Kalimantan Selatan.">

    <title>SIRAPIK | Bapelkes Provinsi Kalimantan Selatan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #12345a;
            --primary-dark: #092541;
            --primary-light: #1c568a;
            --blue: #2672b9;
            --blue-light: #eaf3fc;
            --gold: #d6a84f;
            --ink: #26384c;
            --muted: #718096;
            --border: #e5eaf0;
            --white: #ffffff;
            --background: #f5f7fa;
            --green: #21845b;
            --shadow: 0 8px 30px rgba(15, 45, 75, .07);
            --shadow-hover: 0 16px 38px rgba(15, 45, 75, .13);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            width: 100%;
            min-width: 320px;
            overflow-x: hidden;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--white);
            line-height: 1.7;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        select {
            font: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        /* CONTAINER */

        .public-container {
            width: calc(100% - 80px);
            max-width: 1600px;
            margin: 0 auto;
        }

        /* TOPBAR */

        .topbar {
            background: var(--primary-dark);
            color: rgba(255,255,255,.9);
            font-size: 13px;
        }

        .topbar-inner {
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar-left,
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .topbar-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-icon {
            color: var(--gold);
            font-size: 15px;
        }

        /* NAVBAR */

        .public-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
            background: rgba(255,255,255,.98);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 3px 16px rgba(15,45,75,.04);
            backdrop-filter: blur(12px);
        }

        .nav-inner {
            width: calc(100% - 80px);
            max-width: 1600px;
            min-height: 86px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
            gap: 13px;
        }

        .brand-logo {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 12px;
            background: #f4f7fa;
            border: 1px solid var(--border);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            color: var(--primary);
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
            gap: 32px;
        }

        .nav-link {
            position: relative;
            white-space: nowrap;
            color: #42546a;
            font-size: 14px;
            font-weight: 700;
            transition: color .2s ease;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -9px;
            width: 0;
            height: 2px;
            background: var(--gold);
            transition: width .2s ease;
        }

        .nav-link:hover {
            color: var(--blue);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 20px;
            border-radius: 6px;
            color: white;
            background: var(--primary);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            transition: .2s ease;
        }

        .nav-button:hover {
            background: var(--blue);
            transform: translateY(-2px);
        }

        .mobile-menu-button {
            display: none;
            width: 44px;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: white;
            color: var(--primary);
            font-size: 23px;
            cursor: pointer;
        }

        /* HERO */

        .hero {
            position: relative;
            min-height: 550px;
            display: flex;
            align-items: center;
            overflow: hidden;
            color: white;
            background:
                linear-gradient(
                    90deg,
                    rgba(5,29,54,.94) 0%,
                    rgba(9,44,76,.86) 48%,
                    rgba(13,54,88,.57) 100%
                ),
                url("{{ asset('images/background-sirapik.jpg') }}") center/cover no-repeat;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 580px;
            height: 580px;
            right: -170px;
            top: -190px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 390px;
            height: 390px;
            right: -75px;
            top: -95px;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.35fr .65fr;
            align-items: center;
            gap: 65px;
            padding-top: 75px;
            padding-bottom: 75px;
        }

        .hero-content {
            max-width: 850px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 23px;
            padding: 8px 15px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 30px;
            background: rgba(255,255,255,.09);
            color: #f3d89e;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-label::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
        }

        .hero h1 {
            max-width: 850px;
            margin-bottom: 22px;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(40px, 4.5vw, 62px);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -1.8px;
        }

        .hero h1 span {
            color: #f0c979;
        }

        .hero-description {
            max-width: 750px;
            color: rgba(255,255,255,.85);
            font-size: 16px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 13px;
            margin-top: 30px;
        }

        .button-primary {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 24px;
            border-radius: 6px;
            color: var(--primary-dark);
            background: #f0ca7a;
            font-size: 14px;
            font-weight: 800;
            transition: .2s ease;
        }

        .button-primary:hover {
            background: white;
            transform: translateY(-3px);
        }

        .hero-side {
            position: relative;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px;
            background: rgba(255,255,255,.11);
            backdrop-filter: blur(12px);
        }

        .hero-side-icon {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            margin-bottom: 20px;
            border-radius: 10px;
            color: var(--primary-dark);
            background: #f0ca7a;
            font-size: 25px;
        }

        .hero-side-label {
            margin-bottom: 7px;
            color: #f0ca7a;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-side h3 {
            margin-bottom: 13px;
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: 21px;
            line-height: 1.45;
        }

        .hero-side p {
            color: rgba(255,255,255,.82);
            font-size: 14px;
            line-height: 1.9;
        }

        .hero-side-line {
            height: 1px;
            margin: 22px 0 16px;
            background: rgba(255,255,255,.2);
        }

        .hero-side-status {
            display: flex;
            align-items: center;
            gap: 9px;
            color: rgba(255,255,255,.88);
            font-size: 12px;
        }

        .hero-side-status span {
            width: 8px;
            height: 8px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #75d8a5;
        }

        /* SECTION UMUM */

        .section-label {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        /* TENTANG SIRAPIK */

        .about-section {
            padding: 55px 0 40px;
            background: #f5f8fb;
        }

        .about-grid {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            align-items: center;
            gap: 48px;
        }

        .about-visual {
            position: relative;
            min-height: 370px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 12px;
            background:
                linear-gradient(145deg, rgba(8,38,68,.92), rgba(27,89,139,.83)),
                url("{{ asset('images/background-sirapik.jpg') }}") center/cover;
        }

        .about-visual::before,
        .about-visual::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50%;
        }

        .about-visual::before {
            width: 340px;
            height: 340px;
        }

        .about-visual::after {
            width: 250px;
            height: 250px;
        }

        .about-visual-content {
            position: relative;
            z-index: 2;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .about-logo {
            width: 105px;
            height: 105px;
            display: grid;
            place-items: center;
            margin: 0 auto 19px;
            padding: 10px;
            border-radius: 18px;
            background: white;
        }

        .about-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .about-visual-content strong {
            display: block;
            font-family: 'Manrope', sans-serif;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .about-visual-content span {
            display: block;
            margin-top: 5px;
            color: rgba(255,255,255,.85);
            font-size: 13px;
        }

        .about-content h2 {
            margin-bottom: 18px;
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(29px, 3vw, 39px);
            font-weight: 800;
            line-height: 1.35;
        }

        .about-content p {
            margin-bottom: 15px;
            color: #68798b;
            font-size: 15px;
            line-height: 1.9;
        }

        .about-points {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 24px;
        }

        .about-point {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
        }

        .about-point span {
            width: 27px;
            height: 27px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 50%;
            color: var(--green);
            background: #e8f6ef;
            font-size: 13px;
        }

        /* DASHBOARD */

        .dashboard-section {
            padding: 55px 0 85px;
            background: var(--background);
        }

        .dashboard-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .dashboard-heading h2 {
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.3;
        }

        .dashboard-heading p {
            max-width: 650px;
            margin-top: 7px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .dashboard-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 11px 17px;
            border: 1px solid #dce9f5;
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
        }

        /* FILTER */

        .filter-card {
            margin-bottom: 22px;
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: white;
            box-shadow: var(--shadow);
        }

        .filter-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 20px;
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: 18px;
            font-weight: 800;
        }

        .filter-heading span {
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 19px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr auto;
            align-items: end;
            gap: 17px;
        }

        .form-field label {
            display: block;
            margin-bottom: 8px;
            color: #43566d;
            font-size: 13px;
            font-weight: 800;
        }

        .form-field select {
            width: 100%;
            height: 49px;
            padding: 0 13px;
            border: 1px solid #dce4ed;
            border-radius: 6px;
            outline: none;
            color: #41546a;
            background: #fff;
            font-size: 13px;
            transition: border .2s, box-shadow .2s;
        }

        .form-field select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(38,114,185,.1);
        }

        .filter-submit {
            height: 49px;
            padding: 0 22px;
            border: 0;
            border-radius: 6px;
            color: white;
            background: var(--primary);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s ease;
        }

        .filter-submit:hover {
            background: var(--blue);
            transform: translateY(-2px);
        }

        /* KARTU STATISTIK */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            position: relative;
            min-height: 165px;
            overflow: hidden;
            padding: 24px 26px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: white;
            box-shadow: var(--shadow);
            transition: .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 110px;
            height: 110px;
            right: -38px;
            bottom: -52px;
            border-radius: 50%;
            background: #edf4fb;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
            font-weight: 700;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 21px;
        }

        .stat-value {
            position: relative;
            z-index: 1;
            margin-top: 10px;
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.3;
        }

        .stat-caption {
            position: relative;
            z-index: 1;
            margin-top: 5px;
            color: #8a97a7;
            font-size: 11px;
            line-height: 1.6;
        }

        /* REKAP DAN PERINGKAT */

        .data-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    align-items: start;
    gap: 18px;
}

        .content-card {
            min-width: 0;
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: white;
            box-shadow: var(--shadow);
        }

        .content-card-header {
            padding: 23px 25px 19px;
            border-bottom: 1px solid var(--border);
        }

        .content-card-header h3 {
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.5;
        }

        .card-description {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .card-body {
            padding: 20px 24px 25px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .table-wrap table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .table-wrap th {
            padding: 15px 14px;
            color: #64758a;
            background: #f5f8fb;
            font-size: 12px;
            font-weight: 800;
            text-align: left;
        }

        .table-wrap td {
            padding: 17px 14px;
            border-bottom: 1px solid #edf0f4;
            color: #4c5d70;
            font-size: 12px;
        }

        .table-wrap tbody tr:last-child td {
            border-bottom: 0;
        }

        .table-wrap tbody tr:hover {
            background: #f8fafc;
        }

        .table-count {
            display: inline-flex;
            min-width: 39px;
            justify-content: center;
            padding: 5px 11px;
            border-radius: 20px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
        }

        .empty {
            padding: 30px 12px !important;
            color: #8a97a7 !important;
            text-align: center;
            white-space: normal;
        }

        /* PERINGKAT TENAGA KESEHATAN */

        .ranking-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .ranking-panel {
            min-width: 0;
            padding: 18px;
            border: 1px solid #e9eef4;
            border-radius: 9px;
            background: #fbfcfe;
        }

        .ranking-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 18px;
        }

        .ranking-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 800;
        }

        .ranking-high .ranking-icon {
            color: #21845b;
            background: #e8f6ef;
        }

        .ranking-low .ranking-icon {
            color: #bd7950;
            background: #fff2e9;
        }

        .ranking-heading h4 {
            color: var(--primary);
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 800;
        }

        .ranking-heading p {
            color: #8995a4;
            font-size: 11px;
        }

        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            list-style: none;
        }

        .ranking-item {
            display: grid;
            grid-template-columns: 27px minmax(0, 1fr) auto;
            align-items: center;
            gap: 9px;
            padding: 12px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .ranking-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .ranking-number {
            width: 25px;
            height: 25px;
            display: grid;
            place-items: center;
            border-radius: 6px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
        }

        .ranking-name {
            overflow-wrap: anywhere;
            color: #506176;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.5;
        }

        .ranking-total {
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .ranking-total small {
            color: #8b98a8;
            font-size: 10px;
            font-weight: 500;
        }

        .ranking-empty {
            padding: 20px 8px;
            color: #8a97a7;
            font-size: 12px;
            line-height: 1.8;
            text-align: center;
        }

        /* FOOTER */

        .public-footer {
            color: white;
            background: #081f37;
        }

        .footer-main {
            display: grid;
            grid-template-columns: 1.3fr .7fr;
            gap: 70px;
            padding-top: 50px;
            padding-bottom: 40px;
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .footer-logo {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            padding: 5px;
            border-radius: 9px;
            background: white;
        }

        .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .footer-brand h3 {
            margin-bottom: 5px;
            font-family: 'Manrope', sans-serif;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .footer-brand p {
            max-width: 450px;
            color: rgba(255,255,255,.7);
            font-size: 13px;
            line-height: 1.9;
        }

        .footer-links h4 {
            margin-bottom: 15px;
            color: #f0ca7a;
            font-size: 14px;
            font-weight: 800;
        }

        .footer-links a {
            display: block;
            width: fit-content;
            margin-bottom: 9px;
            color: rgba(255,255,255,.75);
            font-size: 13px;
            transition: color .2s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            padding: 18px 0;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .footer-bottom-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: rgba(255,255,255,.6);
            font-size: 12px;
        }

        /* TABLET */

        @media (max-width: 1050px) {
            .public-container,
            .nav-inner {
                width: calc(100% - 48px);
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
                font-size: 46px;
            }

            .filter-grid {
                grid-template-columns: 1fr 1.3fr;
            }

            .filter-submit {
                width: 100%;
            }

            .data-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .content-card {
                height: auto;
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

            .topbar-left {
                gap: 10px;
            }

            .topbar-right {
                display: none;
            }

            .topbar-item {
                font-size: 11px;
            }

            .nav-inner {
                min-height: 72px;
            }

            .brand-logo {
                width: 46px;
                height: 46px;
            }

            .brand-title {
                font-size: 21px;
            }

            .brand-subtitle {
                font-size: 10px;
            }

            .mobile-menu-button {
                display: block;
                flex-shrink: 0;
            }

            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 12px 22px 20px;
                border-top: 1px solid var(--border);
                background: white;
                box-shadow: 0 12px 20px rgba(15,45,75,.09);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-link {
                padding: 13px 4px;
                border-bottom: 1px solid #eef1f5;
                font-size: 14px;
            }

            .nav-link::after {
                display: none;
            }

            .nav-button {
                margin-top: 12px;
            }

            .hero {
                min-height: auto;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 35px;
                padding-top: 65px;
                padding-bottom: 75px;
            }

            .hero h1 {
                font-size: clamp(35px, 8vw, 47px);
                letter-spacing: -1px;
            }

            .hero-description {
                font-size: 14px;
            }

            .hero-side {
                max-width: 550px;
                padding: 25px;
            }

            .hero-side h3 {
                font-size: 19px;
            }

            .hero-side p {
                font-size: 13px;
            }

            .about-section {
                padding: 60px 0;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .about-visual {
                min-height: 300px;
            }

            .about-content h2 {
                font-size: 29px;
            }

            .about-content p {
                font-size: 14px;
            }

            .dashboard-section {
                padding: 60px 0;
            }

            .dashboard-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
                margin-bottom: 24px;
            }

            .dashboard-heading h2 {
                font-size: 29px;
            }

            .dashboard-heading p {
                font-size: 13px;
            }

            .dashboard-badge {
                font-size: 11px;
            }

            .filter-card {
                padding: 20px;
            }

            .filter-heading {
                font-size: 16px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .form-field label {
                font-size: 12px;
            }

            .form-field select {
                height: 47px;
                font-size: 12px;
            }

            .filter-submit {
                height: 47px;
                font-size: 12px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 13px;
                margin-bottom: 22px;
            }

            .stat-card {
                min-height: 140px;
                padding: 21px;
            }

            .stat-value {
                font-size: 32px;
            }

            .stat-label {
                font-size: 13px;
            }

            .stat-caption {
                font-size: 11px;
            }

            .data-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .content-card-header {
                padding: 20px;
            }

            .content-card-header h3 {
                font-size: 16px;
            }

            .card-description {
                font-size: 11px;
            }

            .card-body {
                padding: 15px;
            }

            .table-wrap th,
            .table-wrap td {
                font-size: 11px;
            }

            .ranking-grid {
                grid-template-columns: 1fr;
                gap: 13px;
                padding: 15px;
            }

            .ranking-panel {
                padding: 17px;
            }

            .ranking-heading h4 {
                font-size: 13px;
            }

            .ranking-name {
                font-size: 12px;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 30px;
                padding-top: 40px;
                padding-bottom: 35px;
            }

            .footer-brand h3 {
                font-size: 19px;
            }

            .footer-brand p {
                font-size: 12px;
            }

            .footer-bottom-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 6px;
                font-size: 11px;
            }
        }

        /* HP KECIL */

        @media (max-width: 420px) {
            .public-container,
            .nav-inner {
                width: calc(100% - 30px);
            }

            .hero-inner {
                padding-top: 55px;
                padding-bottom: 65px;
            }

            .hero h1 {
                font-size: 34px;
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

            .about-content h2 {
                font-size: 26px;
            }

            .about-points {
                grid-template-columns: 1fr;
            }

            .dashboard-heading h2 {
                font-size: 26px;
            }

            .filter-card {
                padding: 17px;
            }

            .stat-value {
                font-size: 30px;
            }

            .content-card-header {
                padding: 17px;
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

        /* AKSESIBILITAS */

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="public-container topbar-inner">

            <div class="topbar-left">
                <div class="topbar-item">
                    <span class="topbar-icon">◷</span>
                    <span>Portal Informasi Publik Bapelkes</span>
                </div>
            </div>

            <div class="topbar-right">
                <div class="topbar-item">
                    <span class="topbar-icon">▣</span>
                    <span>Sistem Informasi Pelatihan</span>
                </div>
            </div>

        </div>
    </div>

    {{-- NAVBAR --}}
    <header class="public-nav">
        <div class="nav-inner">

            <a href="{{ route('public.dashboard') }}" class="brand">

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
                aria-expanded="false"
            >
                ☰
            </button>

            <nav class="nav-menu" id="nav-menu">

                <a href="#beranda" class="nav-link">
                    Beranda
                </a>

                <a href="#tentang" class="nav-link">
                    Tentang SIRAPIK
                </a>

                <a href="#dashboard" class="nav-link">
                    Data Pelatihan
                </a>

                <a href="{{ route('public.training-history') }}" class="nav-button">
                    Riwayat Pelatihan
                    <span>→</span>
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
                            <span>→</span>
                        </a>

                    </div>

                </div>

                <aside class="hero-side">

                    <div class="hero-side-icon">▤</div>

                    <div class="hero-side-label">
                        Portal Informasi
                    </div>

                    <h3>
                        Informasi pelatihan lebih mudah diakses.
                    </h3>

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

                <div class="about-visual">

                    <div class="about-visual-content">

                        <div class="about-logo">
                            <img
                                src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                                alt="Logo Provinsi Kalimantan Selatan"
                            >
                        </div>

                        <strong>SIRAPIK</strong>

                        <span>
                            Bapelkes Provinsi Kalimantan Selatan
                        </span>

                    </div>

                </div>

                <div class="about-content">

                    <div class="section-label">
                        Mengenal SIRAPIK
                    </div>

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
                <div class="dashboard-heading">

                    <div>

                        <div class="section-label">
                            Pusat Informasi Data
                        </div>

                        <h2>
                            Data Pelatihan
                        </h2>

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
                    class="filter-card"
                    id="dashboard-filter"
                >

                    <div class="filter-heading">
                        <span>⌕</span>
                        Filter Data Pelatihan
                    </div>

                    <div class="filter-grid">

                        <div class="form-field">

                            <label for="year">
                                Tahun Pelatihan
                            </label>

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

                            <label for="funding_source">
                                Sumber Dana
                            </label>

                            <select name="funding_source" id="funding_source">

                                <option value="">
                                    Semua Sumber Dana
                                </option>

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
                            Terapkan Filter →
                        </button>

                    </div>

                </form>

                {{-- RINGKASAN STATISTIK --}}
                <div class="stats-grid">

                    {{-- JUMLAH PELATIHAN --}}
                    <article class="stat-card">

                        <div class="stat-top">

                            <div class="stat-label">
                                Jumlah Pelatihan
                            </div>

                            <div class="stat-icon">
                                ▤
                            </div>

                        </div>

                        <div class="stat-value">
                            {{ number_format($totalTrainings, 0, ',', '.') }}
                        </div>

                        <div class="stat-caption">
                            Jumlah pelatihan sesuai filter yang dipilih
                        </div>

                    </article>

                    {{-- JUMLAH PESERTA --}}
                    <article class="stat-card">

                        <div class="stat-top">

                            <div class="stat-label">
                                Peserta yang Sudah Dilatih
                            </div>

                            <div class="stat-icon">
                                ♙
                            </div>

                        </div>

                        <div class="stat-value">
                            {{ number_format($totalParticipants, 0, ',', '.') }}
                        </div>

                        <div class="stat-caption">
                            Jumlah peserta yang sudah dilatih per individu
                        </div>

                    </article>

                    {{-- TAHUN LAPORAN --}}
                    <article class="stat-card">

                        <div class="stat-top">

                            <div class="stat-label">
                                Tahun Laporan
                            </div>

                            <div class="stat-icon">
                                ▦
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
                    <section class="content-card">

                        <div class="content-card-header">

                            <h3>
                                Rekap Kabupaten/Kota/Mitra/Mandiri
                            </h3>

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
                                            <th>
                                                Kabupaten/Kota/Mitra/Mandiri
                                            </th>

                                            <th>
                                                Jumlah
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse ($trainingByDistrict as $row)

                                            <tr>

                                                <td>
                                                    {{ $row->district }}
                                                </td>

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
                    <section class="content-card">

                        <div class="content-card-header">

                            <h3>
                                Partisipasi Tenaga Kesehatan
                            </h3>

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

                                    <div class="ranking-icon">
                                        ↑
                                    </div>

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

                                    <div class="ranking-icon">
                                        ↓
                                    </div>

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

    {{-- JAVASCRIPT NAVBAR MOBILE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const menuButton = document.getElementById('mobile-menu-button');
            const navMenu = document.getElementById('nav-menu');

            if (menuButton && navMenu) {

                menuButton.addEventListener('click', function () {

                    const isOpen = navMenu.classList.toggle('active');

                    menuButton.setAttribute(
                        'aria-expanded',
                        isOpen ? 'true' : 'false'
                    );

                    menuButton.innerHTML = isOpen ? '×' : '☰';

                });

                navMenu.querySelectorAll('a').forEach(function (link) {

                    link.addEventListener('click', function () {

                        navMenu.classList.remove('active');

                        menuButton.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                        menuButton.innerHTML = '☰';

                    });

                });

            }

        });
    </script>

</body>
</html>