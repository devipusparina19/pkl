@extends('layouts.app')

@section('title', 'Tambah Akun · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

{{-- =========================================================
     ARTA — Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan
     Halaman: Tambah Akun (Premium Blue Editorial)
========================================================= --}}

{{-- Icon sprite (gaya Lucide, inline, tanpa dependency) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    <symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
    <symbol id="i-user-plus" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-eye-off" viewBox="0 0 24 24"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></symbol>
    <symbol id="i-settings" viewBox="0 0 24 24"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
    <symbol id="i-arrow-left" viewBox="0 0 24 24"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-loader" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></symbol>
    <symbol id="i-pulse" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></symbol>
</svg>

<style>
    /* =========================================================
       ARTA — CREATE USER
       Semua style di-scope ke .arta (tidak mengubah halaman lain)
    ========================================================= */

    .arta {
        --navy-deep: #081B33;
        --midnight: #0B2545;
        --royal: #2457D6;
        --electric: #3478F6;
        --soft: #EAF2FF;
        --ice: #F3F7FC;
        --slate: #64748B;
        --text: #14243A;
        --border: #E1E9F2;
        --cyan: #38C6E8;
        --danger: #D6455D;
        --ok: #15936B;

        --font-display: 'Sora', 'Plus Jakarta Sans', system-ui, sans-serif;
        --font-body: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;

        position: relative;
        width: 100%;
        max-width: none;
        color: var(--text);
        font-family: var(--font-body);
        font-size: 16px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    .arta *, .arta *::before, .arta *::after { box-sizing: border-box; }
    .arta a, .arta button, .arta input, .arta label { -webkit-tap-highlight-color: transparent; }

    .arta .ic {
        width: 1.15em; height: 1.15em;
        flex-shrink: 0;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    /* ---------- Scroll progress ---------- */
    .arta-progress {
        position: fixed; top: 0; left: 0; right: 0;
        height: 3px; z-index: 2000;
        background: linear-gradient(90deg, var(--cyan), var(--electric), var(--royal));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    /* ---------- Reveal ---------- */
    .arta.js [data-reveal] {
        opacity: 0;
        transform: translate3d(0, 22px, 0);
        transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1);
        transition-delay: var(--d, 0s);
        will-change: opacity, transform;
    }
    .arta.js [data-reveal].is-in { opacity: 1; transform: none; }

    /* ---------- HERO ---------- */
    .arta-hero {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        padding: clamp(28px, 5vw, 64px) clamp(20px, 4vw, 56px) clamp(70px, 8vw, 110px);
        background:
            radial-gradient(900px 420px at 85% -10%, rgba(52,120,246,.55), transparent 60%),
            radial-gradient(700px 380px at 0% 110%, rgba(56,198,232,.22), transparent 60%),
            linear-gradient(135deg, var(--navy-deep), var(--midnight) 60%, #0F3166);
        color: #fff;
    }

    .arta-orb {
        position: absolute; z-index: -1;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.14);
        animation: arta-float 14s ease-in-out infinite;
    }
    .arta-orb.o1 { width: 420px; height: 420px; top: -170px; right: -90px; }
    .arta-orb.o2 { width: 260px; height: 260px; bottom: -130px; right: 18%; animation-delay: -5s; background: rgba(56,198,232,.06); }
    .arta-orb.o3 { width: 120px; height: 120px; top: 36%; right: 8%; animation-delay: -9s; background: rgba(52,120,246,.25); border: 0; filter: blur(2px); }

    @keyframes arta-float {
        0%, 100% { transform: translate3d(0,0,0); }
        50% { transform: translate3d(-18px, 22px, 0); }
    }

    .arta-grid-bg {
        position: absolute; inset: 0; z-index: -1;
        background-image:
            linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px);
        background-size: 54px 54px;
        mask-image: radial-gradient(ellipse at 70% 30%, #000 10%, transparent 70%);
        -webkit-mask-image: radial-gradient(ellipse at 70% 30%, #000 10%, transparent 70%);
    }

    .arta-crumb {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
        margin-bottom: clamp(28px, 5vw, 56px);
        font-size: 14px; font-weight: 600;
        color: #A9C3E6;
    }
    .arta-crumb a {
        display: inline-flex; align-items: center; gap: 8px;
        color: #CFE0F7; text-decoration: none;
        padding: 7px 12px; border-radius: 999px;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.06);
        transition: background .2s ease, transform .2s ease;
    }
    .arta-crumb a:hover { background: rgba(255,255,255,.14); transform: translateY(-1px); color: #fff; }
    .arta-crumb .ic { width: 15px; height: 15px; }
    .arta-crumb [aria-current] { color: #fff; }

    .arta-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
        gap: clamp(24px, 4vw, 64px);
        align-items: end;
    }

    .arta-brand {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 22px;
        font-size: 12px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;
        color: var(--cyan);
    }
    .arta-brand-mark {
        display: grid; place-items: center;
        width: 42px; height: 42px; border-radius: 13px;
        background: linear-gradient(135deg, var(--electric), var(--cyan));
        color: #fff; font-size: 20px;
        box-shadow: 0 10px 30px rgba(52,120,246,.45);
    }

    .arta-title {
        margin: 0;
        font-family: var(--font-display);
        font-size: clamp(44px, 8.2vw, 88px);
        font-weight: 800;
        line-height: .98;
        letter-spacing: -.045em;
        color: #fff;
    }
    .arta-title .grad {
        display: block;
        background: linear-gradient(95deg, #8FB8FF 0%, var(--cyan) 60%, #B6F0FF 100%);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }

    .arta-lead {
        max-width: 560px;
        margin: 26px 0 0;
        font-size: clamp(16px, 1.5vw, 18px);
        color: #BFD2EA;
    }
    .arta-lead strong { color: #fff; font-weight: 700; }

    /* Stats */
    .arta-stats {
        display: grid; grid-template-columns: repeat(3, 1fr);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 22px;
        background: rgba(255,255,255,.06);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        overflow: hidden;
    }
    .arta-stat { padding: 22px 18px; border-right: 1px solid rgba(255,255,255,.12); }
    .arta-stat:last-child { border-right: 0; }
    .arta-stat b {
        display: block;
        font-family: var(--font-display);
        font-size: clamp(30px, 3.6vw, 46px);
        font-weight: 800; letter-spacing: -.04em; line-height: 1;
        color: #fff;
    }
    .arta-stat span { display: block; margin-top: 8px; font-size: 12px; font-weight: 600; color: #A9C3E6; line-height: 1.4; }

    /* ---------- BODY (overlap hero) ---------- */
    .arta-main {
        position: relative;
        margin-top: -52px;
        padding: 0 clamp(14px, 3vw, 56px) 56px;
    }

    .arta-alert {
        display: flex; align-items: flex-start; gap: 14px;
        margin-bottom: 20px;
        padding: 18px 20px;
        border: 1px solid #F3C5CD;
        border-radius: 18px;
        background: #FFF5F6;
        color: #8E2B3C;
        box-shadow: 0 14px 40px rgba(214,69,93,.1);
    }
    .arta-alert-ic {
        display: grid; place-items: center;
        width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
        background: #FFE1E6; color: var(--danger); font-size: 19px;
    }
    .arta-alert strong { display: block; margin-bottom: 4px; font-size: 15px; }
    .arta-alert ul { margin: 0; padding-left: 18px; font-size: 14px; line-height: 1.8; }

    .arta-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(280px, .8fr);
        gap: 22px;
        align-items: start;
    }

    /* ---------- FORM CARD ---------- */
    .arta-card {
        min-width: 0;
        border: 1px solid var(--border);
        border-radius: 26px;
        background: #fff;
        box-shadow: 0 30px 70px -30px rgba(11,37,69,.28);
        overflow: hidden;
    }

    .arta-card-head {
        display: flex; align-items: center; justify-content: space-between; gap: 16px;
        padding: clamp(20px, 3vw, 32px) clamp(20px, 3vw, 40px);
        border-bottom: 1px solid var(--border);
        background: linear-gradient(180deg, var(--ice), #fff);
    }
    .arta-card-head h2 {
        margin: 0;
        font-family: var(--font-display);
        font-size: clamp(22px, 2.4vw, 30px);
        font-weight: 800; letter-spacing: -.03em; color: var(--midnight);
    }
    .arta-card-head p { margin: 6px 0 0; font-size: 14px; color: var(--slate); }
    .arta-req { flex-shrink: 0; font-size: 12px; font-weight: 600; color: var(--slate); }
    .arta-req i { font-style: normal; color: var(--danger); font-weight: 800; }

    .arta-card-body { padding: clamp(20px, 3vw, 40px); }

    .arta-step { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
    .arta-step-n {
        display: grid; place-items: center;
        width: 42px; height: 42px; border-radius: 13px; flex-shrink: 0;
        background: var(--soft); color: var(--royal);
        font-family: var(--font-display); font-size: 14px; font-weight: 800;
    }
    .arta-step h3 {
        margin: 0;
        font-family: var(--font-display);
        font-size: 18px; font-weight: 700; letter-spacing: -.02em; color: var(--midnight);
    }
    .arta-step p { margin: 2px 0 0; font-size: 13px; color: var(--slate); }

    .arta-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 20px; }
    .arta-field { min-width: 0; }
    .arta-field.full { grid-column: 1 / -1; }

    .arta-field > label, .arta-legend {
        display: block; margin-bottom: 9px;
        font-size: 14px; font-weight: 700; color: #20364F;
    }
    .arta-field label .required, .arta-legend .required { color: var(--danger); font-weight: 800; }

    .arta-input-wrap { position: relative; }

    .arta .arta-field .form-control {
        width: 100%; min-height: 52px;
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        background: var(--ice);
        color: var(--text);
        font-family: var(--font-body); font-size: 15px; font-weight: 500;
        box-shadow: none;
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
    }
    .arta .arta-field .form-control::placeholder { color: #9AA9BB; opacity: 1; }
    .arta .arta-field .form-control:hover { border-color: #B7C8DD; }
    .arta .arta-field .form-control:focus {
        border-color: var(--electric); background: #fff;
        box-shadow: 0 0 0 4px rgba(52,120,246,.15); outline: none;
    }
    .arta .arta-field .form-control.is-invalid { border-color: var(--danger); background-image: none; background-color: #FFF8F9; }
    .arta .arta-field .form-control.is-invalid:focus { box-shadow: 0 0 0 4px rgba(214,69,93,.14); }
    .arta .arta-field .form-control.ok { border-color: var(--ok); }
    .arta .arta-field .invalid-feedback { display: block; margin-top: 7px; font-size: 12.5px; font-weight: 600; color: var(--danger); }
    .arta .arta-field .form-text { margin-top: 8px; font-size: 12.5px; color: var(--slate); }
    .arta .ac-pass { padding-right: 56px; }

    .arta-eye {
        position: absolute; top: 50%; right: 10px;
        display: grid; place-items: center;
        width: 36px; height: 36px; padding: 0;
        transform: translateY(-50%);
        border: 0; border-radius: 10px;
        background: transparent; color: var(--slate); cursor: pointer;
        transition: background .2s ease, color .2s ease;
    }
    .arta-eye:hover { background: var(--soft); color: var(--royal); }
    .arta-eye:focus-visible { outline: 2px solid var(--electric); outline-offset: 2px; }
    .arta-eye .ic { width: 19px; height: 19px; }
    .arta-eye .off { display: none; }
    .arta-eye[aria-pressed="true"] .on { display: none; }
    .arta-eye[aria-pressed="true"] .off { display: block; }

    /* Role cards */
    .arta-roles { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; border: 0; padding: 0; margin: 0; min-width: 0; }
    .arta-role { position: relative; display: block; margin: 0 !important; cursor: pointer; }
    .arta-role input { position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; cursor: pointer; margin: 0; }
    .arta-role-box {
        display: flex; gap: 14px; align-items: flex-start;
        height: 100%; padding: 18px;
        border: 1.5px solid var(--border);
        border-radius: 18px; background: var(--ice);
        transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .arta-role:hover .arta-role-box { border-color: #9DB9E3; transform: translateY(-2px); }
    .arta-role input:focus-visible + .arta-role-box { outline: 3px solid rgba(52,120,246,.35); outline-offset: 2px; }
    .arta-role input:checked + .arta-role-box {
        border-color: var(--royal); background: #fff;
        box-shadow: 0 0 0 4px rgba(36,87,214,.12), 0 14px 30px -14px rgba(36,87,214,.45);
    }
    .arta-role-ic {
        display: grid; place-items: center;
        width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
        background: var(--soft); color: var(--royal); font-size: 20px;
        transition: background .2s ease, color .2s ease;
    }
    .arta-role input:checked + .arta-role-box .arta-role-ic { background: var(--royal); color: #fff; }
    .arta-role-box strong { display: block; font-family: var(--font-display); font-size: 16px; color: var(--midnight); }
    .arta-role-box small { display: block; margin-top: 3px; font-size: 12.5px; color: var(--slate); line-height: 1.55; }
    .arta-role-check {
        position: absolute; top: 14px; right: 14px;
        display: grid; place-items: center;
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--royal); color: #fff;
        opacity: 0; transform: scale(.6);
        transition: opacity .2s ease, transform .2s ease;
    }
    .arta-role-check .ic { width: 14px; height: 14px; stroke-width: 3; }
    .arta-role input:checked ~ .arta-role-check { opacity: 1; transform: scale(1); }

    .arta-divider { display: flex; align-items: center; margin: 34px 0 30px; }
    .arta-divider::after { content: ""; flex: 1; height: 1px; background: var(--border); }

    /* Strength meter */
    .arta-meter { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-top: 12px; }
    .arta-meter i { height: 5px; border-radius: 9px; background: var(--border); overflow: hidden; position: relative; }
    .arta-meter i::after {
        content: ""; position: absolute; inset: 0;
        background: var(--c, var(--border));
        transform: scaleX(0); transform-origin: 0 50%;
        transition: transform .35s cubic-bezier(.2,.7,.2,1);
    }
    .arta-meter i.on::after { transform: scaleX(1); }
    .arta-meter-label { margin-top: 8px; font-size: 12.5px; font-weight: 600; color: var(--slate); min-height: 20px; }
    .arta-match { margin-top: 8px; display: flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 700; min-height: 20px; }
    .arta-match .ic { width: 15px; height: 15px; }
    .arta-match.good { color: var(--ok); }
    .arta-match.bad { color: var(--danger); }

    .arta-hint {
        display: flex; gap: 12px; align-items: flex-start;
        margin-top: 24px; padding: 15px 17px;
        border: 1px solid #D4E3F8; border-radius: 15px;
        background: var(--soft); color: #36527A;
        font-size: 13px; line-height: 1.7;
    }
    .arta-hint .ic { margin-top: 3px; color: var(--royal); }

    /* Actions */
    .arta-actions {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        margin-top: 32px; padding-top: 26px;
        border-top: 1px solid var(--border);
    }
    .arta-note { display: flex; align-items: center; gap: 9px; font-size: 13px; color: var(--slate); }
    .arta-note .ic { color: var(--royal); }
    .arta-buttons { display: flex; align-items: center; gap: 12px; }

    .arta .btn-a {
        position: relative;
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 52px; padding: 12px 24px;
        border: 1.5px solid transparent; border-radius: 14px;
        font-family: var(--font-body); font-size: 14px; font-weight: 700;
        text-decoration: none; cursor: pointer;
        transition: transform .2s ease, background-color .2s ease, border-color .2s ease, box-shadow .2s ease, color .2s ease;
    }
    .arta .btn-a:hover { transform: translateY(-2px); }
    .arta .btn-a:active { transform: translateY(0) scale(.98); }
    .arta .btn-a:focus-visible { outline: 3px solid rgba(52,120,246,.4); outline-offset: 3px; }

    .arta .btn-primary-a {
        background: linear-gradient(135deg, var(--royal), var(--electric));
        color: #fff;
        box-shadow: 0 12px 26px -8px rgba(36,87,214,.65);
    }
    .arta .btn-primary-a:hover { color: #fff; box-shadow: 0 18px 34px -10px rgba(36,87,214,.75); }
    .arta .btn-primary-a[disabled] { opacity: .85; cursor: progress; transform: none; }
    .arta .btn-primary-a .spin { display: none; animation: arta-spin .8s linear infinite; }
    .arta .btn-primary-a.loading .spin { display: block; }
    .arta .btn-primary-a.loading .idle { display: none; }
    @keyframes arta-spin { to { transform: rotate(360deg); } }

    .arta .btn-secondary-a { border-color: var(--border); background: #fff; color: #3C516B; }
    .arta .btn-secondary-a:hover { border-color: #9DB9E3; background: var(--ice); color: var(--midnight); }

    /* ---------- SIDE (bento) ---------- */
    .arta-side { position: sticky; top: 88px; display: grid; gap: 16px; }

    .arta-tile {
        position: relative; overflow: hidden;
        padding: 24px;
        border: 1px solid var(--border);
        border-radius: 22px; background: #fff;
    }
    .arta-tile.dark {
        border: 0; color: #fff;
        background: linear-gradient(150deg, var(--navy-deep), var(--midnight) 70%, #123A74);
        isolation: isolate;
    }
    .arta-tile.dark::before {
        content: ""; position: absolute; z-index: -1;
        top: -80px; right: -80px; width: 220px; height: 220px;
        border-radius: 50%; border: 1px solid rgba(255,255,255,.14);
    }
    .arta-tile h4 {
        margin: 0 0 6px;
        font-family: var(--font-display);
        font-size: 17px; font-weight: 700; letter-spacing: -.02em;
    }
    .arta-tile p { margin: 0; font-size: 13px; color: var(--slate); line-height: 1.7; }
    .arta-tile.dark p { color: #B6CBE6; }
    .arta-tile-label {
        display: block; margin-bottom: 14px;
        font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;
        color: var(--cyan);
    }

    .arta-prog-row { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 14px; }
    .arta-prog-num { font-family: var(--font-display); font-size: 44px; font-weight: 800; letter-spacing: -.04em; line-height: 1; }
    .arta-prog-num small { font-size: 18px; color: #8FB0D8; font-weight: 700; }
    .arta-bar { height: 8px; border-radius: 99px; background: rgba(255,255,255,.14); overflow: hidden; }
    .arta-bar i {
        display: block; height: 100%; width: 100%;
        background: linear-gradient(90deg, var(--cyan), var(--electric));
        transform: scaleX(0); transform-origin: 0 50%;
        transition: transform .5s cubic-bezier(.2,.7,.2,1);
        border-radius: 99px;
    }

    .arta-check-list { list-style: none; margin: 18px 0 0; padding: 0; display: grid; gap: 9px; }
    .arta-check-list li { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #9DB7D8; transition: color .25s ease; }
    .arta-check-list .dot {
        display: grid; place-items: center;
        width: 20px; height: 20px; border-radius: 50%;
        border: 1.5px solid rgba(255,255,255,.28);
        transition: background-color .25s ease, border-color .25s ease;
    }
    .arta-check-list .dot .ic { width: 12px; height: 12px; stroke-width: 3; opacity: 0; transform: scale(.5); transition: opacity .25s ease, transform .25s ease; }
    .arta-check-list li.done { color: #fff; }
    .arta-check-list li.done .dot { background: var(--cyan); border-color: var(--cyan); color: var(--navy-deep); }
    .arta-check-list li.done .dot .ic { opacity: 1; transform: scale(1); }

    .arta-role-line { display: flex; gap: 13px; align-items: flex-start; }
    .arta-role-line + .arta-role-line { margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--border); }
    .arta-mini-ic {
        display: grid; place-items: center;
        width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
        background: var(--soft); color: var(--royal); font-size: 17px;
    }
    .arta-role-line strong { display: block; font-size: 14px; color: var(--midnight); }

    .arta-secure { display: flex; gap: 12px; align-items: flex-start; }
    .arta-secure .ic { margin-top: 3px; color: var(--cyan); font-size: 19px; }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .arta-hero-grid { grid-template-columns: 1fr; align-items: start; }
        .arta-stats { max-width: 560px; }
    }
    @media (max-width: 991.98px) {
        .arta-layout { grid-template-columns: 1fr; }
        .arta-side { position: static; order: -1; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .arta-tile.progress-tile { grid-column: 1 / -1; }
    }
    @media (max-width: 640px) {
        .arta-main { margin-top: -36px; padding-left: 12px; padding-right: 12px; }
        .arta-card, .arta-tile { border-radius: 20px; }
        .arta-fields, .arta-roles { grid-template-columns: 1fr; gap: 18px; }
        .arta-side { grid-template-columns: 1fr; }
        .arta-card-head { flex-direction: column; align-items: flex-start; gap: 8px; }
        .arta-stats { grid-template-columns: repeat(3, 1fr); border-radius: 18px; }
        .arta-stat { padding: 16px 10px; }
        .arta-actions { flex-direction: column; align-items: stretch; }
        .arta-buttons { display: grid; grid-template-columns: 1fr 1.5fr; width: 100%; }
        .arta .btn-a { width: 100%; padding-left: 12px; padding-right: 12px; }
        .arta-note { order: 2; justify-content: center; text-align: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .arta *, .arta *::before, .arta *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
        .arta.js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

<div class="arta" id="arta">
    <div class="arta-progress" id="artaProgress" aria-hidden="true"></div>

    {{-- ================= HERO ================= --}}
    <header class="arta-hero">
        <div class="arta-grid-bg" aria-hidden="true"></div>
        <span class="arta-orb o1" aria-hidden="true"></span>
        <span class="arta-orb o2" aria-hidden="true"></span>
        <span class="arta-orb o3" aria-hidden="true"></span>

        <nav class="arta-crumb" aria-label="Breadcrumb" data-reveal>
            <a href="{{ route('super-admin.users.index') }}">
                <svg class="ic" aria-hidden="true"><use href="#i-users"/></svg>
                Manajemen Akun
            </a>
            <svg class="ic" aria-hidden="true"><use href="#i-chevron"/></svg>
            <span aria-current="page">Tambah Akun</span>
        </nav>

        <div class="arta-hero-grid">
            <div>
                <div class="arta-brand" data-reveal style="--d:.05s">
                    <span class="arta-brand-mark" aria-hidden="true">
                        <svg class="ic"><use href="#i-pulse"/></svg>
                    </span>
                    ARTA &middot; Bapelkes Kalimantan Selatan
                </div>

                <h1 class="arta-title" data-reveal style="--d:.12s">
                    Tambah Akun
                    <span class="grad">Pengguna Baru.</span>
                </h1>

                <p class="arta-lead" data-reveal style="--d:.2s">
                    <strong>ARTA</strong> &mdash; Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan.
                    Buat akun baru dan tentukan hak akses sesuai tanggung jawab
                    setiap pengguna.
                </p>
            </div>

            <div class="arta-stats" data-reveal style="--d:.28s">
                <div class="arta-stat">
                    <b><span data-count="2">0</span></b>
                    <span>Jenis akses pengguna</span>
                </div>
                <div class="arta-stat">
                    <b><span data-count="2">0</span></b>
                    <span>Langkah pengisian</span>
                </div>
                <div class="arta-stat">
                    <b><span data-count="8">0</span>+</b>
                    <span>Karakter password</span>
                </div>
            </div>
        </div>
    </header>

    {{-- ================= MAIN ================= --}}
    <div class="arta-main">

        @if ($errors->any())
            <div class="arta-alert" role="alert" data-reveal>
                <div class="arta-alert-ic"><svg class="ic" aria-hidden="true"><use href="#i-alert"/></svg></div>
                <div>
                    <strong>Periksa kembali data yang dimasukkan.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="arta-layout">

            {{-- ============ FORM ============ --}}
            <section class="arta-card" data-reveal aria-labelledby="formTitle">
                <div class="arta-card-head">
                    <div>
                        <h2 id="formTitle">Informasi Akun</h2>
                        <p>Lengkapi informasi pengguna pada formulir berikut.</p>
                    </div>
                    <div class="arta-req"><i>*</i> Wajib diisi</div>
                </div>

                <div class="arta-card-body">
                    <form action="{{ route('super-admin.users.store') }}" method="POST" id="artaForm" novalidate>
                        @csrf

                        {{-- Langkah 1 --}}
                        <div class="arta-step">
                            <div class="arta-step-n">01</div>
                            <div>
                                <h3>Identitas Pengguna</h3>
                                <p>Informasi dasar akun yang akan dibuat.</p>
                            </div>
                        </div>

                        <div class="arta-fields">
                            <div class="arta-field">
                                <label for="name">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                                       required maxlength="255" autocomplete="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="arta-field">
                                <label for="username">Username <span class="required">*</span></label>
                                <input type="text" name="username" id="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username') }}" placeholder="Masukkan username"
                                       required maxlength="255" autocomplete="username">
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="arta-field full">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" name="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="nama@instansi.go.id"
                                       required maxlength="255" autocomplete="email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="arta-field full">
                                <fieldset class="arta-roles-set" style="border:0;padding:0;margin:0;min-width:0">
                                    <legend class="arta-legend">Role Pengguna <span class="required">*</span></legend>

                                    <div class="arta-roles">
                                        <label class="arta-role">
                                            <input type="radio" name="role" value="admin"
                                                   @checked(old('role') === 'admin') required>
                                            <span class="arta-role-box">
                                                <span class="arta-role-ic"><svg class="ic" aria-hidden="true"><use href="#i-settings"/></svg></span>
                                                <span>
                                                    <strong>Admin</strong>
                                                    <small>Mengelola data operasional dan aktivitas administrasi.</small>
                                                </span>
                                            </span>
                                            <span class="arta-role-check"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>
                                        </label>

                                        <label class="arta-role">
                                            <input type="radio" name="role" value="pimpinan"
                                                   @checked(old('role') === 'pimpinan')>
                                            <span class="arta-role-box">
                                                <span class="arta-role-ic"><svg class="ic" aria-hidden="true"><use href="#i-chart"/></svg></span>
                                                <span>
                                                    <strong>Pimpinan</strong>
                                                    <small>Memantau informasi dan laporan sesuai hak akses.</small>
                                                </span>
                                            </span>
                                            <span class="arta-role-check"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>
                                        </label>
                                    </div>

                                    @error('role')
                                        <div class="invalid-feedback" style="display:block;margin-top:8px;font-size:12.5px;font-weight:600;color:#D6455D">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text" style="margin-top:10px;font-size:12.5px;color:#64748B">
                                        Pilih role berdasarkan tanggung jawab dan kewenangan pengguna.
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="arta-divider" aria-hidden="true"></div>

                        {{-- Langkah 2 --}}
                        <div class="arta-step">
                            <div class="arta-step-n">02</div>
                            <div>
                                <h3>Keamanan Akun</h3>
                                <p>Tentukan password untuk akses pengguna.</p>
                            </div>
                        </div>

                        <div class="arta-fields">
                            <div class="arta-field">
                                <label for="password">Password <span class="required">*</span></label>
                                <div class="arta-input-wrap">
                                    <input type="password" name="password" id="password"
                                           class="form-control ac-pass @error('password') is-invalid @enderror"
                                           placeholder="Minimal 8 karakter"
                                           required minlength="8" autocomplete="new-password">
                                    <button type="button" class="arta-eye" data-target="password"
                                            aria-label="Tampilkan password" aria-pressed="false">
                                        <svg class="ic on" aria-hidden="true"><use href="#i-eye"/></svg>
                                        <svg class="ic off" aria-hidden="true"><use href="#i-eye-off"/></svg>
                                    </button>
                                </div>

                                <div class="arta-meter" id="meter" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                                <div class="arta-meter-label" id="meterLabel" aria-live="polite"></div>

                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="arta-field">
                                <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                                <div class="arta-input-wrap">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control ac-pass"
                                           placeholder="Ulangi password"
                                           required minlength="8" autocomplete="new-password">
                                    <button type="button" class="arta-eye" data-target="password_confirmation"
                                            aria-label="Tampilkan konfirmasi password" aria-pressed="false">
                                        <svg class="ic on" aria-hidden="true"><use href="#i-eye"/></svg>
                                        <svg class="ic off" aria-hidden="true"><use href="#i-eye-off"/></svg>
                                    </button>
                                </div>
                                <div class="arta-match" id="matchState" aria-live="polite"></div>
                            </div>
                        </div>

                        <div class="arta-hint">
                            <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                            <span>
                                Gunakan password minimal 8 karakter. Kombinasikan huruf besar, huruf kecil,
                                angka, dan simbol agar lebih aman. Password konfirmasi harus sama.
                            </span>
                        </div>

                        <div class="arta-actions">
                            <div class="arta-note">
                                <svg class="ic" aria-hidden="true"><use href="#i-shield"/></svg>
                                Pastikan seluruh data sudah benar.
                            </div>

                            <div class="arta-buttons">
                                <a href="{{ route('super-admin.users.index') }}" class="btn-a btn-secondary-a">
                                    <svg class="ic" aria-hidden="true"><use href="#i-arrow-left"/></svg>
                                    Batal
                                </a>

                                <button type="submit" class="btn-a btn-primary-a" id="submitBtn">
                                    <svg class="ic idle" aria-hidden="true"><use href="#i-user-plus"/></svg>
                                    <svg class="ic spin" aria-hidden="true"><use href="#i-loader"/></svg>
                                    <span id="submitText">Simpan Akun</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            {{-- ============ SIDE / BENTO ============ --}}
            <aside class="arta-side" aria-label="Ringkasan">

                <div class="arta-tile dark progress-tile" data-reveal style="--d:.08s">
                    <span class="arta-tile-label">Progres pengisian</span>
                    <div class="arta-prog-row">
                        <div class="arta-prog-num"><span id="progNum">0</span><small>/6</small></div>
                        <span style="font-size:12px;font-weight:700;color:#8FB0D8" id="progPct">0%</span>
                    </div>
                    <div class="arta-bar" role="progressbar" aria-valuemin="0" aria-valuemax="6" aria-valuenow="0" id="progBar">
                        <i id="progFill"></i>
                    </div>

                    <ul class="arta-check-list" id="checkList">
                        <li data-key="name"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Nama lengkap</li>
                        <li data-key="username"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Username</li>
                        <li data-key="email"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Email valid</li>
                        <li data-key="role"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Role dipilih</li>
                        <li data-key="password"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Password min. 8 karakter</li>
                        <li data-key="confirm"><span class="dot"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>Konfirmasi cocok</li>
                    </ul>
                </div>

                <div class="arta-tile" data-reveal style="--d:.14s">
                    <span class="arta-tile-label" style="color:var(--royal)">Jenis akses</span>

                    <div class="arta-role-line">
                        <span class="arta-mini-ic"><svg class="ic" aria-hidden="true"><use href="#i-settings"/></svg></span>
                        <div>
                            <strong>Admin</strong>
                            <p>Mengelola data operasional dan aktivitas administrasi sesuai kewenangannya.</p>
                        </div>
                    </div>

                    <div class="arta-role-line">
                        <span class="arta-mini-ic"><svg class="ic" aria-hidden="true"><use href="#i-chart"/></svg></span>
                        <div>
                            <strong>Pimpinan</strong>
                            <p>Memantau informasi dan laporan sesuai hak akses yang diberikan sistem.</p>
                        </div>
                    </div>
                </div>

                <div class="arta-tile dark" data-reveal style="--d:.2s">
                    <div class="arta-secure">
                        <svg class="ic" aria-hidden="true"><use href="#i-lock"/></svg>
                        <div>
                            <h4>Keamanan data</h4>
                            <p>Pastikan setiap akun memakai identitas yang benar dan password yang tidak mudah ditebak.</p>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var root = document.getElementById('arta');
    if (!root) return;
    root.classList.add('js');

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- 1. Jaga posisi scroll (tidak lompat ke atas) ---------- */
    var KEY = 'arta:scroll:' + location.pathname;
    try { if ('scrollRestoration' in history) history.scrollRestoration = 'manual'; } catch (e) {}

    function saveScroll() {
        try { sessionStorage.setItem(KEY, String(window.scrollY || window.pageYOffset || 0)); } catch (e) {}
    }
    function restoreScroll() {
        try {
            var y = sessionStorage.getItem(KEY);
            if (y !== null) {
                sessionStorage.removeItem(KEY);
                window.scrollTo({ top: parseInt(y, 10) || 0, left: 0, behavior: 'instant' in document.documentElement.style ? 'instant' : 'auto' });
            }
        } catch (e) {}
    }
    restoreScroll();
    window.addEventListener('pageshow', restoreScroll);
    window.addEventListener('beforeunload', function () {
        /* hanya simpan bila form sedang dikirim (ditandai di submit) */
    });

    /* ---------- 2. Scroll progress bar ---------- */
    var bar = document.getElementById('artaProgress');
    var ticking = false;
    function updateProgress() {
        var h = document.documentElement;
        var max = (h.scrollHeight - h.clientHeight) || 1;
        var p = Math.min(1, Math.max(0, (window.scrollY || h.scrollTop) / max));
        bar.style.transform = 'scaleX(' + p + ')';
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(updateProgress); }
    }, { passive: true });
    updateProgress();

    /* ---------- 3. Scroll reveal ---------- */
    var reveals = root.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            });
        }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* ---------- 4. Counter ---------- */
    root.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (reduce) { el.textContent = target; return; }
        var start = null, dur = 1100;
        function tick(ts) {
            if (start === null) start = ts;
            var t = Math.min(1, (ts - start) / dur);
            el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
            if (t < 1) requestAnimationFrame(tick);
        }
        setTimeout(function () { requestAnimationFrame(tick); }, 350);
    });

    /* ---------- 5. Toggle password ---------- */
    root.querySelectorAll('.arta-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            input.focus({ preventScroll: true });
        });
    });

    /* ---------- 6. Strength meter + match + progress ---------- */
    var f = {
        name: document.getElementById('name'),
        username: document.getElementById('username'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        confirm: document.getElementById('password_confirmation')
    };
    var roles = root.querySelectorAll('input[name="role"]');
    var segs = root.querySelectorAll('#meter i');
    var meterLabel = document.getElementById('meterLabel');
    var matchEl = document.getElementById('matchState');
    var progNum = document.getElementById('progNum');
    var progPct = document.getElementById('progPct');
    var progFill = document.getElementById('progFill');
    var progBar = document.getElementById('progBar');
    var items = root.querySelectorAll('#checkList li');

    var levels = [
        { t: 'Terlalu lemah', c: '#D6455D' },
        { t: 'Lemah', c: '#E8833A' },
        { t: 'Cukup', c: '#E0B12B' },
        { t: 'Kuat', c: '#15936B' }
    ];

    function strength(v) {
        if (!v) return -1;
        var s = 0;
        if (v.length >= 8) s++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
        if (/\d/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v) || v.length >= 14) s++;
        return Math.max(0, s - 1) + (s === 4 ? 0 : 0) > 3 ? 3 : Math.max(0, s - (s > 0 ? 1 : 0));
    }

    function updateMeter() {
        var v = f.password.value;
        var lv = strength(v);
        segs.forEach(function (seg, i) {
            seg.classList.toggle('on', lv >= 0 && i <= lv);
            seg.style.setProperty('--c', lv >= 0 ? levels[lv].c : '');
        });
        meterLabel.textContent = lv >= 0 ? 'Kekuatan password: ' + levels[lv].t : '';
    }

    function updateMatch() {
        var a = f.password.value, b = f.confirm.value;
        matchEl.className = 'arta-match';
        matchEl.innerHTML = '';
        if (!b) { f.confirm.classList.remove('ok'); return; }
        if (a === b) {
            matchEl.classList.add('good');
            matchEl.innerHTML = '<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>Password cocok';
            f.confirm.classList.add('ok');
        } else {
            matchEl.classList.add('bad');
            matchEl.innerHTML = '<svg class="ic" aria-hidden="true"><use href="#i-alert"/></svg>Password belum sama';
            f.confirm.classList.remove('ok');
        }
    }

    function updateProgress2() {
        var state = {
            name: f.name.value.trim().length > 0,
            username: f.username.value.trim().length > 0,
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.value.trim()),
            role: Array.prototype.some.call(roles, function (r) { return r.checked; }),
            password: f.password.value.length >= 8,
            confirm: f.confirm.value.length >= 8 && f.confirm.value === f.password.value
        };
        var done = 0;
        items.forEach(function (li) {
            var ok = !!state[li.getAttribute('data-key')];
            li.classList.toggle('done', ok);
            if (ok) done++;
        });
        progNum.textContent = done;
        progPct.textContent = Math.round(done / 6 * 100) + '%';
        progFill.style.transform = 'scaleX(' + (done / 6) + ')';
        progBar.setAttribute('aria-valuenow', done);
    }

    function refresh() { updateMeter(); updateMatch(); updateProgress2(); }

    Object.keys(f).forEach(function (k) { f[k].addEventListener('input', refresh); });
    roles.forEach(function (r) { r.addEventListener('change', refresh); });
    refresh();

    /* Hapus status invalid saat user mengetik */
    root.querySelectorAll('.form-control.is-invalid').forEach(function (el) {
        el.addEventListener('input', function () { el.classList.remove('is-invalid'); }, { once: true });
    });

    /* ---------- 7. Submit: loading + tetap di posisi ---------- */
    var form = document.getElementById('artaForm');
    var btn = document.getElementById('submitBtn');
    var btnText = document.getElementById('submitText');
    var sending = false;

    form.addEventListener('submit', function (e) {
        if (sending) { e.preventDefault(); return; }

        if (!form.checkValidity()) {
            e.preventDefault();
            var bad = form.querySelector(':invalid');
            if (bad) {
                if (bad.classList) bad.classList.add('is-invalid');
                bad.focus({ preventScroll: true });
                if (typeof bad.reportValidity === 'function') bad.reportValidity();
            }
            return;
        }

        if (f.password.value !== f.confirm.value) {
            e.preventDefault();
            f.confirm.classList.add('is-invalid');
            f.confirm.focus({ preventScroll: true });
            return;
        }

        sending = true;
        saveScroll();
        btn.classList.add('loading');
        btn.setAttribute('aria-busy', 'true');
        btnText.textContent = 'Menyimpan...';
        /* disable setelah event berjalan agar data form tetap terkirim */
        setTimeout(function () { btn.disabled = true; }, 0);
    });

    /* Tombol Batal: simpan posisi agar tidak lompat */
    root.querySelectorAll('a.btn-secondary-a').forEach(function (a) {
        a.addEventListener('click', saveScroll);
    });

    /* Jika ada error validasi dari server, fokus ke field pertama tanpa scroll */
    var firstInvalid = root.querySelector('.form-control.is-invalid');
    if (firstInvalid) { try { firstInvalid.focus({ preventScroll: true }); } catch (e) {} }
})();
</script>

@endsection