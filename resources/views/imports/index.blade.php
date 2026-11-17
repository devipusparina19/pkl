@extends('layouts.app')

@section('title', 'Import Data Peserta Pelatihan · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

{{-- =====================================================
     IKON (gaya Lucide). Dideklarasikan sekali di sini,
     lalu dipakai berulang dengan <use href="#i-nama">.
===================================================== --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="i-upload" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></symbol>
    <symbol id="i-reset" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></symbol>
    <symbol id="i-ok" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></symbol>
    <symbol id="i-warn" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></symbol>
    <symbol id="i-loader" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></symbol>
</svg>

<style>
    /* =====================================================
       ARTA · HALAMAN IMPORT DATA PESERTA
       Semua class diawali "arta-" supaya tidak bentrok
       dengan CSS lain di website.

       Daftar isi:
       1. Warna & dasar     5. Form & input
       2. Hero              6. Tombol
       3. Hasil import      7. Loading
       4. Tabel error       8. Pratinjau
                            9. Responsif
    ===================================================== */

    /* ---------- 1. WARNA & DASAR ---------- */
    .arta {
        --navy: #081b33;   --midnight: #0b2545; --royal: #2457d6;
        --electric: #3478f6; --soft: #eaf2ff;   --ice: #f3f7fc;
        --slate: #64748b;  --ink: #14243a;      --line: #e1e9f2;
        --cyan: #38c6e8;
        --red: #991b1b;    --red-soft: #fef2f2; --red-line: #fecaca;
        --green: #166534;  --green-soft: #f0fdf4; --green-line: #bbf7d0;
        --amber: #7a4f0e;  --amber-soft: #fffaf0; --amber-line: #f0dfb9;
        --f-display: 'Sora', system-ui, sans-serif;
        --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        --ease: cubic-bezier(.2, .7, .3, 1);

        width: 100%;
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 16px;
        line-height: 1.7;
    }
    .arta *, .arta *::before, .arta *::after { box-sizing: border-box; }
    .arta input, .arta select, .arta button { font: inherit; }

    /* Gaya ikon: satu aturan untuk semua ikon */
    .arta .ic {
        display: block; flex: 0 0 auto; width: 20px; height: 20px;
        fill: none; stroke: currentColor; stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
    }

    .arta :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .6);
        outline-offset: 3px;
        border-radius: 8px;
    }

    /* Garis progress scroll di paling atas layar */
    .arta-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    /* Muncul pelan saat di-scroll (diaktifkan oleh JavaScript) */
    .arta.is-js [data-reveal] {
        opacity: 0; transform: translateY(18px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .arta.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* ---------- 2. HERO ---------- */
    .arta-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: clamp(28px, 4vw, 48px);
        padding: clamp(28px, 5vw, 72px) clamp(22px, 5vw, 72px) clamp(84px, 9vw, 128px);
        color: #fff; border-radius: 20px;
        background: linear-gradient(165deg, var(--navy), var(--midnight));
    }

    /* Garis kisi halus di latar */
    .arta-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .09;
        background-image:
            linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(165deg, #000 0%, transparent 70%);
        mask-image: linear-gradient(165deg, #000 0%, transparent 70%);
    }

    /* Garis detak jantung (ECG) yang bergerak pelan */
    .arta-ecg {
        position: absolute; left: 0; right: 0; bottom: 0; z-index: -1;
        height: 80px; overflow: hidden; opacity: .55;
        -webkit-mask-image: linear-gradient(90deg, transparent, #000 15%, #000 85%, transparent);
        mask-image: linear-gradient(90deg, transparent, #000 15%, #000 85%, transparent);
    }
    .arta-ecg svg {
        display: block; width: 200%; height: 80px;
        animation: arta-ecg 10s linear infinite;
    }
    .arta-ecg path {
        fill: none; stroke: var(--cyan); stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
        vector-effect: non-scaling-stroke;
    }
    @keyframes arta-ecg { to { transform: translateX(-50%); } }

    .arta-hero-grid {
        display: grid; align-items: end;
        gap: clamp(28px, 5vw, 80px);
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, .85fr);
    }

    .arta-eyebrow {
        display: flex; flex-wrap: wrap; align-items: center; gap: 10px 14px;
        margin-bottom: 22px; color: #bcd8ff; font-size: 14px; font-weight: 600;
    }
    .arta-eyebrow b {
        padding: 4px 12px; border-radius: 6px;
        background: var(--cyan); color: var(--navy);
        font-family: var(--f-display); font-size: 13px; font-weight: 800; letter-spacing: .08em;
    }

    .arta-hero h1 {
        margin: 0; font-family: var(--f-display);
        font-size: clamp(40px, 7.2vw, 88px);
        font-weight: 800; letter-spacing: -.045em; line-height: 1;
    }
    .arta-hero h1 span { display: block; color: #82b7ff; }

    .arta-lead {
        max-width: 54ch; margin: 24px 0 0;
        color: rgba(255,255,255,.78); font-size: clamp(16px, 1.4vw, 19px); line-height: 1.8;
    }
    .arta-hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }

    /* Panel checklist kesiapan */
    .arta-check {
        padding: 22px; border: 1px solid rgba(255,255,255,.18);
        border-radius: 14px; background: rgba(255,255,255,.05);
    }
    .arta-check-head {
        display: flex; justify-content: space-between; align-items: baseline;
        color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .arta-check-head b { color: #fff; font-family: var(--f-display); font-size: 22px; font-variant-numeric: tabular-nums; }
    .arta-check-bar { height: 4px; margin: 12px 0 8px; overflow: hidden; border-radius: 4px; background: rgba(255,255,255,.14); }
    .arta-check-bar i {
        display: block; width: 100%; height: 100%; background: var(--cyan);
        transform: scaleX(0); transform-origin: 0 50%;
        transition: transform .5s var(--ease);
    }
    .arta-check ul { margin: 0; padding: 0; list-style: none; }
    .arta-check li {
        display: flex; align-items: center; gap: 12px; padding: 11px 0;
        border-top: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.7); font-size: 14px; line-height: 1.4;
    }
    .arta-check li:first-child { border-top: 0; }
    .arta-tick {
        display: grid; place-items: center; width: 24px; height: 24px;
        border: 1.5px solid rgba(255,255,255,.35); border-radius: 50%;
        position: relative;
    }
    .arta-tick::before {
        content: ""; position: absolute; inset: -1.5px; border-radius: 50%;
        background: var(--cyan); opacity: 0; transform: scale(.4);
        transition: opacity .3s var(--ease), transform .3s var(--ease);
    }
    .arta-tick .ic { position: relative; width: 14px; height: 14px; stroke-width: 3; color: var(--navy); opacity: 0; transition: opacity .3s; }
    .arta-check li.is-done { color: #fff; }
    .arta-check li.is-done .arta-tick::before { opacity: 1; transform: none; }
    .arta-check li.is-done .arta-tick .ic { opacity: 1; }
    .arta-check li.is-opt { color: rgba(255,255,255,.55); font-size: 13px; }

    /* ---------- 3. HASIL IMPORT ---------- */
    .arta-feedback {
        margin-bottom: 36px; padding: clamp(20px, 3vw, 32px);
        border: 1px solid var(--line); border-left: 5px solid var(--green);
        border-radius: 14px; background: #fff;
        scroll-margin-top: 90px;
    }
    .arta-feedback.is-danger { border-left-color: #b91c1c; background: var(--red-soft); border-color: var(--red-line); }
    .arta-feedback.is-success { background: var(--green-soft); border-color: var(--green-line); border-left-color: var(--green); }
    .arta-feedback.is-preview { background: var(--soft); border-color: #c7dafc; border-left-color: var(--royal); }

    .arta-fb-head { display: flex; align-items: flex-start; gap: 14px; }
    .arta-fb-head .ic { width: 26px; height: 26px; margin-top: 3px; }
    .is-danger .arta-fb-head .ic { color: var(--red); }
    .is-success .arta-fb-head .ic { color: var(--green); }
    .is-preview .arta-fb-head .ic { color: var(--royal); }
    .arta-fb-title { margin: 0; font-family: var(--f-display); font-size: clamp(20px, 2.2vw, 26px); font-weight: 700; letter-spacing: -.02em; line-height: 1.25; }
    .is-danger .arta-fb-title { color: var(--red); }
    .is-success .arta-fb-title { color: var(--green); }
    .is-preview .arta-fb-title { color: var(--navy); }
    .arta-fb-body { min-width: 0; flex: 1; }
    .arta-fb-list { margin: 10px 0 0; padding-left: 20px; color: var(--red); font-size: 15px; }
    .arta-fb-list li { overflow-wrap: anywhere; }

    .arta-meta { margin-top: 8px; color: #475569; font-size: 14px; overflow-wrap: anywhere; }
    .arta-meta b { color: var(--ink); }

    .arta-pill {
        display: inline-block; margin-top: 12px; padding: 4px 12px;
        border-radius: 999px; font-size: 12px; font-weight: 800;
    }
    .arta-pill-success { color: #166534; background: #dcfce7; }
    .arta-pill-partial { color: #92400e; background: #fef3c7; }
    .arta-pill-failed  { color: #991b1b; background: #fee2e2; }

    /* Angka statistik, tersusun seperti bento */
    .arta-stats {
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1px; margin-top: 22px; overflow: hidden;
        border: 1px solid var(--line); border-radius: 14px; background: var(--line);
    }
    .arta-stat { min-width: 0; padding: 18px 20px; background: #fff; }
    .arta-stat--main { grid-column: span 2; background: var(--navy); color: #fff; }
    .arta-stat-num {
        font-family: var(--f-display); font-size: clamp(28px, 3vw, 40px);
        font-weight: 800; letter-spacing: -.04em; line-height: 1;
        font-variant-numeric: tabular-nums; color: var(--navy);
    }
    .arta-stat--main .arta-stat-num { font-size: clamp(44px, 6vw, 76px); color: #fff; }
    .arta-stat-label { margin-top: 8px; color: #40536a; font-size: 13px; font-weight: 600; line-height: 1.4; }
    .arta-stat--main .arta-stat-label { color: #bcd8ff; }

    .arta-notes { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); font-size: 14px; }
    .arta-notes p { margin: 5px 0 0; color: #64748b; font-size: 13px; }

    /* ---------- 4. TABEL ERROR ---------- */
    .arta-error-wrap { margin-top: 14px; overflow-x: auto; border: 1px solid var(--red-line); border-radius: 12px; background: #fff; }
    .arta-error-table { width: 100%; min-width: 760px; border-collapse: collapse; font-size: 13px; line-height: 1.55; }
    .arta-error-table th { padding: 12px 14px; background: #fff1f1; color: #7f1d1d; text-align: left; font-weight: 800; white-space: nowrap; }
    .arta-error-table td { padding: 12px 14px; border-top: 1px solid #fee2e2; color: #334155; vertical-align: top; overflow-wrap: anywhere; }
    .arta-error-table tr:nth-child(even) td { background: #fffafa; }
    .arta-error-row { font-weight: 800; white-space: nowrap; }
    .arta-error-value { color: #991b1b; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .arta-error-help { display: block; margin-top: 4px; color: #166534; }

    /* ---------- 5. FORM & INPUT ---------- */
    .arta-section {
        display: grid; gap: 28px clamp(32px, 5vw, 80px);
        grid-template-columns: minmax(0, .55fr) minmax(0, 1.45fr);
        padding: clamp(28px, 4vw, 48px) 0;
        border-top: 2px solid var(--navy);
        scroll-margin-top: 80px;
    }
    .arta-section-head { align-self: start; position: sticky; top: 90px; }
    .arta-num { display: block; margin-bottom: 10px; color: var(--royal); font-family: var(--f-display); font-size: 15px; font-weight: 800; }
    .arta-section-head h2 {
        margin: 0; color: var(--navy); font-family: var(--f-display);
        font-size: clamp(30px, 3.4vw, 48px); font-weight: 800; letter-spacing: -.045em; line-height: 1.08;
    }
    .arta-section-head p { margin: 12px 0 0; max-width: 34ch; color: var(--slate); font-size: 15px; line-height: 1.7; }

    .arta-grid { display: grid; gap: 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); align-content: start; }
    .arta-field { min-width: 0; }
    .arta-full { grid-column: 1 / -1; }

    .arta-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
        margin-bottom: 9px; color: #2f4259; font-size: 14px; font-weight: 700;
    }
    .arta-req { color: var(--red); font-size: 16px; line-height: 1; }
    .arta-opt { padding: 1px 8px; border-radius: 4px; background: var(--ice); color: #5b6b80; font-size: 12px; font-weight: 600; }

    .arta .arta-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid #cfdbea; border-radius: 12px;
        outline: none; background-color: #fff; color: var(--ink); font-size: 16px;
    }
    .arta .arta-control::placeholder { color: #8998ab; }
    .arta .arta-control:hover { border-color: #9db8dc; }
    .arta .arta-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.14); }
    .arta select.arta-control {
        padding-right: 44px; cursor: pointer; appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>");
        background-repeat: no-repeat; background-position: right 14px center;
    }
    .arta-help { display: block; margin-top: 8px; color: var(--slate); font-size: 13px; line-height: 1.6; }

    /* Area unggah file */
    .arta-drop {
        position: relative; display: flex; align-items: center; gap: 20px;
        padding: clamp(22px, 3vw, 36px); border: 2px dashed #9db8dc; border-radius: 16px;
        background: var(--ice);
    }
    .arta-drop:hover, .arta-drop.is-over { border-color: var(--royal); background: var(--soft); }
    .arta-drop:focus-within { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.14); }
    .arta-drop input[type="file"] {
        position: absolute; inset: 0; z-index: 2; width: 100%; height: 100%;
        margin: 0; opacity: 0; cursor: pointer;
    }
    .arta-drop-icon {
        display: grid; place-items: center; width: 60px; height: 60px;
        border-radius: 14px; background: #fff; color: var(--royal);
        box-shadow: 0 6px 18px rgba(8,27,51,.08);
        transition: transform .35s var(--ease);
    }
    .arta-drop-icon .ic { width: 28px; height: 28px; }
    .arta-drop:hover .arta-drop-icon, .arta-drop.is-over .arta-drop-icon { transform: translateY(-4px); }
    .arta-drop strong { display: block; color: var(--navy); font-family: var(--f-display); font-size: clamp(17px, 1.6vw, 20px); font-weight: 700; }
    .arta-drop span.s { display: block; margin-top: 2px; color: var(--slate); font-size: 14px; }

    .arta-selected {
        display: flex; align-items: center; gap: 12px; margin-top: 14px;
        padding: 13px 16px; border: 1px solid var(--green-line); border-radius: 12px;
        background: var(--green-soft); color: var(--green);
        font-size: 14px; font-weight: 600; overflow-wrap: anywhere;
    }
    .arta-selected[hidden] { display: none; }
    .arta-selected.is-warn { border-color: var(--red-line); background: var(--red-soft); color: var(--red); }

    .arta-template {
        display: flex; align-items: center; flex-wrap: wrap; gap: 12px 18px;
        padding: 18px 20px; border-radius: 12px; background: var(--soft);
    }
    .arta-template p { margin: 0; flex: 1 1 260px; color: #2f4259; font-size: 14px; line-height: 1.6; }
    .arta-template code {
        padding: 1px 6px; border-radius: 4px; background: #fff; color: var(--royal);
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 13px; font-weight: 600;
    }

    /* Kotak info: Kabupaten/Kota dibaca dari Excel */
    .arta-auto {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 18px 20px; border: 1px solid #c7dafc; border-radius: 12px;
        background: var(--soft); color: #2f4259;
    }
    .arta-auto .ic { width: 22px; height: 22px; margin-top: 2px; color: var(--royal); }
    .arta-auto strong { display: block; color: var(--navy); font-size: 15px; }
    .arta-auto p { margin: 4px 0 0; font-size: 14px; line-height: 1.7; }
    .arta-auto code {
        padding: 1px 6px; border-radius: 4px; background: #fff; color: var(--royal);
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 13px; font-weight: 600;
    }

    /* ---------- 6. TOMBOL ---------- */
    .arta .arta-btn {
        --bg: var(--royal); --fg: #fff; --bd: transparent; --hover: var(--navy);
        position: relative; overflow: hidden;
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 52px; padding: 12px 24px;
        border: 1.5px solid var(--bd); border-radius: 12px;
        background: var(--bg); color: var(--fg);
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: transform .25s var(--ease);
    }
    /* Lapisan warna hover: hanya opacity yang berubah */
    .arta .arta-btn::before {
        content: ""; position: absolute; inset: 0; background: var(--hover);
        opacity: 0; transition: opacity .25s;
    }
    .arta .arta-btn > * { position: relative; }
    .arta .arta-btn:hover { transform: translateY(-2px); }
    .arta .arta-btn:hover::before { opacity: 1; }
    .arta .arta-btn:active { transform: scale(.98); }
    .arta .arta-btn:disabled { opacity: .65; cursor: wait; transform: none; }
    .arta .arta-btn:disabled::before { opacity: 0; }

    .arta .arta-btn--light { --bg: #fff; --fg: var(--navy); --hover: var(--soft); }
    .arta .arta-btn--outline { --bg: transparent; --fg: #fff; --bd: rgba(255,255,255,.4); --hover: rgba(255,255,255,.12); }
    .arta .arta-btn--secondary { --bg: #fff; --fg: var(--royal); --bd: var(--royal); --hover: #dbe8ff; }
    .arta .arta-btn--ghost { --bg: transparent; --fg: #40536a; --bd: #cfdbea; --hover: var(--ice); }

    .is-spin { animation: arta-spin 1s linear infinite; }
    @keyframes arta-spin { to { transform: rotate(360deg); } }

    /* Bilah aksi di bawah form (menempel di bawah layar) */
    .arta-footer {
        position: sticky; bottom: 0; z-index: 20;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
        padding: 16px 0; border-top: 2px solid var(--navy);
        background: rgba(255,255,255,.96);
    }
    .arta-note { display: flex; align-items: flex-start; gap: 10px; max-width: 60ch; color: var(--slate); font-size: 13px; line-height: 1.7; }
    .arta-note .ic { width: 18px; height: 18px; margin-top: 2px; }
    .arta-note b { color: var(--red); }
    .arta-actions { display: flex; align-items: center; gap: 12px; }

    .arta-warn {
        display: flex; align-items: flex-start; gap: 14px; margin-top: 28px;
        padding: 18px 20px; border: 1px solid var(--amber-line); border-left: 4px solid #d9a441;
        border-radius: 12px; background: var(--amber-soft);
    }
    .arta-warn .ic { width: 22px; height: 22px; margin-top: 2px; color: var(--amber); }
    .arta-warn strong { display: block; color: var(--amber); font-size: 15px; }
    .arta-warn p { margin: 4px 0 0; color: #6f5a34; font-size: 14px; line-height: 1.7; }

    /* ---------- 7. LAYAR LOADING ---------- */
    .arta-veil {
        position: fixed; inset: 0; z-index: 100; display: grid; place-items: center;
        background: rgba(8,27,51,.78); opacity: 0; pointer-events: none;
        transition: opacity .25s;
    }
    .arta-veil.is-on { opacity: 1; pointer-events: auto; }
    .arta-veil-box { padding: 28px 36px; border-radius: 16px; background: #fff; text-align: center; max-width: 320px; margin: 16px; }
    .arta-veil-box .ic { width: 40px; height: 40px; margin: 0 auto 12px; color: var(--royal); }
    .arta-veil-box strong { display: block; color: var(--navy); font-family: var(--f-display); font-size: 18px; }
    .arta-veil-box span { display: block; margin-top: 4px; color: var(--slate); font-size: 14px; }

    /* ---------- 8. PRATINJAU & RINGKASAN WILAYAH ---------- */
    .arta-sub { margin: 26px 0 0; color: var(--navy); font-family: var(--f-display); font-size: 17px; font-weight: 700; }
    .arta-subhelp { margin: 4px 0 0; color: var(--slate); font-size: 13px; }

    .arta-dist-wrap { margin-top: 12px; overflow-x: auto; border: 1px solid var(--line); border-radius: 12px; background: #fff; }
    .arta-dist-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .arta-dist-table th { padding: 11px 14px; background: var(--ice); color: #2f4259; text-align: left; font-weight: 800; }
    .arta-dist-table td { padding: 10px 14px; border-top: 1px solid var(--line); color: #334155; }
    .arta-dist-table th:last-child, .arta-dist-table td:last-child { text-align: right; font-variant-numeric: tabular-nums; width: 1%; white-space: nowrap; }
    .arta-dist-table tfoot td { background: var(--ice); color: var(--navy); font-weight: 800; }

    .arta-ptable-wrap { margin-top: 12px; max-height: 440px; overflow: auto; border: 1px solid var(--line); border-radius: 12px; background: #fff; }
    .arta-ptable { width: 100%; min-width: 820px; border-collapse: collapse; font-size: 13px; line-height: 1.55; }
    .arta-ptable th {
        position: sticky; top: 0; z-index: 1;
        padding: 11px 14px; background: var(--ice); color: #2f4259;
        text-align: left; font-weight: 800; white-space: nowrap;
    }
    .arta-ptable td { padding: 10px 14px; border-top: 1px solid var(--line); color: #334155; vertical-align: top; overflow-wrap: anywhere; }
    .arta-ptable tr.is-error td { background: #fffafa; }
    .arta-ptable .n { font-weight: 800; white-space: nowrap; }
    .arta-ptable .mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .arta-ptable .raw { color: var(--red); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .arta-ptable .why { display: block; margin-top: 4px; color: var(--red); }

    .arta-badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 800; white-space: nowrap; }
    .arta-badge-new { color: #166534; background: #dcfce7; }
    .arta-badge-upd { color: #1e40af; background: #dbeafe; }
    .arta-badge-err { color: #991b1b; background: #fee2e2; }

    .arta-confirm {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px 20px;
        margin-top: 24px; padding-top: 20px; border-top: 1px solid #c7dafc;
    }
    .arta-confirm p { margin: 0; max-width: 60ch; color: #2f4259; font-size: 14px; line-height: 1.7; }
    .arta-confirm-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; }
    .arta-confirm-actions form { margin: 0; }

    /* ---------- 9. RESPONSIF ---------- */
    @media (max-width: 1000px) {
        .arta-hero-grid { grid-template-columns: 1fr; }
        .arta-section { grid-template-columns: 1fr; gap: 22px; }
        .arta-section-head { position: static; }
        .arta-section-head p { max-width: none; }
        .arta-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 640px) {
        .arta-hero { border-radius: 14px; }
        .arta-hero-actions .arta-btn { flex: 1 1 100%; }
        .arta-grid { grid-template-columns: 1fr; gap: 20px; }
        .arta-full { grid-column: auto; }
        .arta .arta-control { height: 54px; }
        .arta-drop { flex-direction: column; align-items: flex-start; }
        .arta-note { display: none; }
        .arta-footer { padding: 12px 0; }
        .arta-actions { width: 100%; }
        .arta-actions .arta-btn--ghost { flex: 0 0 auto; }
        .arta-actions .arta-btn--primary { flex: 1; }
        .arta-template .arta-btn { width: 100%; }
        .arta-confirm-actions, .arta-confirm-actions form, .arta-confirm-actions .arta-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .arta *, .arta *::before, .arta *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .arta.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

@php
    // Apakah ada pesan (error / pratinjau / hasil import) yang harus ditampilkan?
    $adaPesan = $errors->any() || session('import_result') || isset($preview);
@endphp

<div class="arta" id="arta" data-pesan="{{ $adaPesan ? '1' : '0' }}">

    <div class="arta-progress" id="artaProgress" aria-hidden="true"></div>

    {{-- LAYAR LOADING (muncul saat file dibaca / import diproses, posisi layar tidak berubah) --}}
    <div class="arta-veil" id="artaVeil" role="status" aria-live="polite">
        <div class="arta-veil-box">
            <svg class="ic is-spin" aria-hidden="true"><use href="#i-loader"/></svg>
            <strong id="artaVeilTitle">Memproses…</strong>
            <span>Mohon tunggu dan jangan tutup halaman ini.</span>
        </div>
    </div>

    {{-- ===================== HERO ===================== --}}
    <header class="arta-hero" data-reveal>
        <div class="arta-ecg" aria-hidden="true">
            <svg viewBox="0 0 1200 80" preserveAspectRatio="none">
                <path d="M0 40 H120 l10 -8 l10 8 H180 l8 6 l12 -38 l12 52 l10 -20 H320 l10 -8 l10 8 H600
                         M600 40 H720 l10 -8 l10 8 H780 l8 6 l12 -38 l12 52 l10 -20 H920 l10 -8 l10 8 H1200"/>
            </svg>
        </div>

        <div class="arta-hero-grid">
            <div>
                <div class="arta-eyebrow">
                    <b>ARTA</b>
                    <span>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</span>
                </div>

                <h1>Import data peserta, <span>sekali unggah.</span></h1>

                <p class="arta-lead">
                    Unggah file Excel, periksa pratinjaunya, lalu ARTA akan mencatat
                    data peserta dan riwayat pelatihannya. Kabupaten/Kota dibaca
                    otomatis dari setiap baris peserta.
                </p>

                <div class="arta-hero-actions">
                    <a href="#bagian-file" class="arta-btn arta-btn--light" id="ctaMulai">
                        <svg class="ic" aria-hidden="true"><use href="#i-arrow"/></svg>
                        <span>Mulai import</span>
                    </a>

                    {{-- Mengunduh storage/app/templates/Template_Arta.xlsx lewat controller --}}
                    <a href="{{ route('training.download-template') }}" class="arta-btn arta-btn--outline">
                        <svg class="ic" aria-hidden="true"><use href="#i-download"/></svg>
                        <span>Download Template ARTA</span>
                    </a>
                </div>
            </div>

            {{-- Checklist: terisi otomatis saat form dilengkapi --}}
            <div class="arta-check" aria-live="polite">
                <div class="arta-check-head">
                    <span id="cekLabel">Lengkapi data wajib</span>
                    <b id="cekCount">0/2</b>
                </div>
                <div class="arta-check-bar"><i id="cekBar"></i></div>
                <ul>
                    <li data-cek="file">
                        <span class="arta-tick"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>
                        File Excel dipilih
                    </li>
                    <li data-cek="dana">
                        <span class="arta-tick"><svg class="ic" aria-hidden="true"><use href="#i-check"/></svg></span>
                        Sumber dana dipilih
                    </li>
                    <li class="is-opt">
                        <span class="arta-tick"></span>
                        Kabupaten/Kota dibaca otomatis dari file Excel
                    </li>
                    <li class="is-opt">
                        <span class="arta-tick"></span>
                        Angkatan, kategori, dan JPL bersifat opsional
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- ===================== PESAN: ERROR VALIDASI, PRATINJAU & HASIL IMPORT ===================== --}}
    @if ($adaPesan)
        <div id="hasil">

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="arta-feedback is-danger" role="alert">
                    <div class="arta-fb-head">
                        <svg class="ic" aria-hidden="true"><use href="#i-alert"/></svg>
                        <div class="arta-fb-body">
                            <h2 class="arta-fb-title">Import belum dapat diproses.</h2>
                            <span style="font-size:14px;">Periksa kembali informasi berikut:</span>
                            <ul class="arta-fb-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- PRATINJAU (belum ada data yang disimpan) --}}
            @isset($preview)
                @php
                    $pvTotal   = (int) $preview['total_rows'];
                    $pvValid   = (int) $preview['valid_rows'];
                    $pvInvalid = (int) $preview['invalid_rows'];
                    $pvDistricts = $preview['district_summary'];
                    $pvUnrecognized = $preview['unrecognized_districts'];
                    $pvProblemDistrict = (int) $preview['empty_district_rows'] + array_sum($pvUnrecognized);
                @endphp

                <div class="arta-feedback is-preview" role="status">
                    <div class="arta-fb-head">
                        <svg class="ic" aria-hidden="true"><use href="#i-eye"/></svg>

                        <div class="arta-fb-body">
                            <h2 class="arta-fb-title">Pratinjau hasil pembacaan file</h2>
                            <div class="arta-meta">Belum ada data yang disimpan. Periksa tabel di bawah, lalu konfirmasi untuk menyimpan.</div>

                            <div class="arta-meta">File: <b>{{ $preview['file_name'] }}</b></div>
                            <div class="arta-meta">Pelatihan: <b>{{ $preview['training_name'] }}</b></div>

                            @if (!empty($preview['organizer']))
                                <div class="arta-meta">Kerja sama: <b>{{ $preview['organizer'] }}</b></div>
                            @endif

                            <div class="arta-meta">
                                Tanggal:
                                <b>{{ \Carbon\Carbon::parse($preview['start_date'])->format('d-m-Y') }}</b>
                                s.d.
                                <b>{{ \Carbon\Carbon::parse($preview['end_date'])->format('d-m-Y') }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="arta-stats">
                        <div class="arta-stat arta-stat--main">
                            <div class="arta-stat-num">{{ $pvValid }}</div>
                            <div class="arta-stat-label">Baris siap diimport</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ $pvInvalid }}</div>
                            <div class="arta-stat-label">Baris bermasalah (tidak diimport)</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ $pvTotal }}</div>
                            <div class="arta-stat-label">Total baris peserta dibaca</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ (int) $preview['new_rows'] }}</div>
                            <div class="arta-stat-label">Peserta baru</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ (int) $preview['update_rows'] }}</div>
                            <div class="arta-stat-label">Peserta diperbarui</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ count($pvDistricts) }}</div>
                            <div class="arta-stat-label">Kabupaten/Kota terbaca</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num">{{ $pvProblemDistrict }}</div>
                            <div class="arta-stat-label">Baris Kabupaten/Kota kosong atau tidak dikenali</div>
                        </div>
                    </div>

                    {{-- Ringkasan per Kabupaten/Kota --}}
                    <h3 class="arta-sub">Jumlah peserta per Kabupaten/Kota</h3>
                    <p class="arta-subhelp">Dihitung dari baris yang siap diimport, sesuai isi file Excel.</p>

                    @if (count($pvDistricts) > 0)
                        <div class="arta-dist-wrap">
                            <table class="arta-dist-table">
                                <thead>
                                    <tr>
                                        <th>Kabupaten/Kota</th>
                                        <th>Jumlah Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pvDistricts as $districtName => $districtCount)
                                        <tr>
                                            <td>{{ $districtName }}</td>
                                            <td>{{ $districtCount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ array_sum($pvDistricts) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="arta-notes">Belum ada baris dengan Kabupaten/Kota yang valid.</div>
                    @endif

                    {{-- Kabupaten/Kota yang tidak dikenali --}}
                    @if (count($pvUnrecognized) > 0)
                        <h3 class="arta-sub">Kabupaten/Kota tidak dikenali</h3>
                        <p class="arta-subhelp">
                            Nama berikut tidak cocok dengan Master Data ARTA. Perbaiki penulisannya di Excel,
                            atau tambahkan wilayah tersebut di Master Data, lalu unggah ulang.
                        </p>

                        <div class="arta-dist-wrap">
                            <table class="arta-dist-table">
                                <thead>
                                    <tr>
                                        <th>Tertulis di Excel</th>
                                        <th>Jumlah Baris</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pvUnrecognized as $rawName => $rawCount)
                                        <tr>
                                            <td class="arta-error-value">{{ $rawName }}</td>
                                            <td>{{ $rawCount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Detail per baris --}}
                    <h3 class="arta-sub">Detail per baris</h3>
                    <p class="arta-subhelp">Setiap peserta memakai Kabupaten/Kota dari barisnya sendiri.</p>

                    <div class="arta-ptable-wrap">
                        <table class="arta-ptable">
                            <thead>
                                <tr>
                                    <th>Baris Excel</th>
                                    <th>Nama peserta</th>
                                    <th>NIP / NIK</th>
                                    <th>Kab/Kota di Excel</th>
                                    <th>Kab/Kota terbaca</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preview['rows'] as $pvRow)
                                    <tr class="{{ $pvRow['status'] === 'ok' ? '' : 'is-error' }}">
                                        <td class="n">{{ $pvRow['row'] }}</td>
                                        <td>{{ $pvRow['name'] }}</td>
                                        <td class="mono">{{ $pvRow['identity'] }}</td>
                                        <td class="{{ $pvRow['status'] === 'ok' ? '' : 'raw' }}">
                                            {{ $pvRow['district_raw'] !== '' ? $pvRow['district_raw'] : '(kosong)' }}
                                        </td>
                                        <td>{{ $pvRow['district'] ?? '-' }}</td>
                                        <td>
                                            @if ($pvRow['status'] === 'ok')
                                                @if ($pvRow['action'] === 'update')
                                                    <span class="arta-badge arta-badge-upd">Siap · perbarui</span>
                                                @else
                                                    <span class="arta-badge arta-badge-new">Siap · baru</span>
                                                @endif
                                            @else
                                                <span class="arta-badge arta-badge-err">Ditolak</span>
                                                <span class="why">{{ $pvRow['message'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Konfirmasi --}}
                    <div class="arta-confirm">
                        <p>
                            @if ($pvValid > 0)
                                <b>{{ $pvValid }}</b> baris akan disimpan.
                                @if ($pvInvalid > 0)
                                    <b>{{ $pvInvalid }}</b> baris bermasalah akan dilewati. Anda juga bisa membatalkan,
                                    memperbaiki file Excel, lalu mengunggahnya kembali.
                                @endif
                            @else
                                Tidak ada baris yang dapat diimport. Perbaiki file Excel sesuai keterangan di atas,
                                lalu unggah kembali.
                            @endif
                        </p>

                        <div class="arta-confirm-actions">
                            <form action="{{ route('imports.cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $preview['token'] }}">
                                <button type="submit" class="arta-btn arta-btn--ghost">
                                    <svg class="ic" aria-hidden="true"><use href="#i-x"/></svg>
                                    <span>Batalkan</span>
                                </button>
                            </form>

                            <form
                                id="confirmForm"
                                action="{{ route('imports.store') }}"
                                method="POST"
                                data-veil="Menyimpan data…"
                            >
                                @csrf
                                <input type="hidden" name="token" value="{{ $preview['token'] }}">
                                <input type="hidden" name="angkatan" value="{{ $preview['params']['angkatan'] }}">
                                <input type="hidden" name="sumber_dana" value="{{ $preview['params']['sumber_dana'] }}">
                                <input type="hidden" name="training_category_id" value="{{ $preview['params']['training_category_id'] }}">
                                <input type="hidden" name="jpl" value="{{ $preview['params']['jpl'] }}">

                                <button
                                    type="submit"
                                    class="arta-btn arta-btn--primary"
                                    id="confirmButton"
                                    data-kosong="{{ $pvValid === 0 ? '1' : '0' }}"
                                    @disabled($pvValid === 0)
                                >
                                    <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
                                    <span>Import {{ $pvValid }} baris</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endisset

            {{-- HASIL IMPORT --}}
            @if (session('import_result'))
                @php
                    $result = session('import_result');

                    $importErrors = $result['errors'] ?? [];
                    $importErrors = is_array($importErrors) ? $importErrors : [];

                    $districtSummary = $result['district_summary'] ?? [];
                    $districtSummary = is_array($districtSummary) ? $districtSummary : [];

                    $createdParticipants = (int) ($result['created_participants'] ?? 0);
                    $updatedParticipants = (int) ($result['updated_participants'] ?? 0);
                    $processedParticipants = (int) ($result['processed_participants'] ?? 0);
                    $failedRows = (int) ($result['failed_rows'] ?? 0);

                    // Mengikuti angka sukses yang dikirim controller.
                    $successfulRows = (int) (
                        $result['successful_rows']
                        ?? max(0, $processedParticipants - $failedRows)
                    );

                    $resultStatus = $result['status'] ?? ($failedRows > 0 ? 'partial' : 'success');

                    $resultTitle = $resultStatus === 'failed'
                        ? 'Import tidak berhasil.'
                        : ($failedRows > 0
                            ? 'Import selesai dengan beberapa data gagal.'
                            : 'Import berhasil.');
                @endphp

                <div class="arta-feedback {{ $resultStatus === 'failed' ? 'is-danger' : 'is-success' }}" role="status">
                    <div class="arta-fb-head">
                        <svg class="ic" aria-hidden="true">
                            <use href="{{ $resultStatus === 'failed' ? '#i-alert' : '#i-ok' }}"/>
                        </svg>

                        <div class="arta-fb-body">
                            <h2 class="arta-fb-title">{{ $resultTitle }}</h2>

                            @if (!empty($result['training_name']))
                                <div class="arta-meta">Pelatihan: <b>{{ $result['training_name'] }}</b></div>
                            @endif

                            @if (!empty($result['file_name']))
                                <div class="arta-meta">File: <b>{{ $result['file_name'] }}</b></div>
                            @endif

                            <span class="arta-pill {{ $resultStatus === 'failed' ? 'arta-pill-failed' : ($failedRows > 0 ? 'arta-pill-partial' : 'arta-pill-success') }}">
                                {{ $resultStatus === 'failed' ? 'Gagal diproses' : ($failedRows > 0 ? 'Berhasil sebagian' : 'Semua data berhasil') }}
                            </span>
                        </div>
                    </div>

                    <div class="arta-stats">
                        <div class="arta-stat arta-stat--main">
                            <div class="arta-stat-num" data-count="{{ $successfulRows }}">{{ $successfulRows }}</div>
                            <div class="arta-stat-label">Baris peserta berhasil</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $failedRows }}">{{ $failedRows }}</div>
                            <div class="arta-stat-label">Baris peserta gagal</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $processedParticipants }}">{{ $processedParticipants }}</div>
                            <div class="arta-stat-label">Total baris diproses</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $createdParticipants }}">{{ $createdParticipants }}</div>
                            <div class="arta-stat-label">Peserta baru</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $updatedParticipants }}">{{ $updatedParticipants }}</div>
                            <div class="arta-stat-label">Peserta diperbarui</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $result['created_histories'] ?? 0 }}">{{ $result['created_histories'] ?? 0 }}</div>
                            <div class="arta-stat-label">Riwayat pelatihan baru</div>
                        </div>

                        <div class="arta-stat">
                            <div class="arta-stat-num" data-count="{{ $result['created_trainings'] ?? 0 }}">{{ $result['created_trainings'] ?? 0 }}</div>
                            <div class="arta-stat-label">Pelatihan baru</div>
                        </div>
                    </div>

                    {{-- Ringkasan peserta tersimpan per Kabupaten/Kota --}}
                    @if (count($districtSummary) > 0)
                        <h3 class="arta-sub">Peserta tersimpan per Kabupaten/Kota</h3>

                        <div class="arta-dist-wrap">
                            <table class="arta-dist-table">
                                <thead>
                                    <tr>
                                        <th>Kabupaten/Kota</th>
                                        <th>Jumlah Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($districtSummary as $districtName => $districtCount)
                                        <tr>
                                            <td>{{ $districtName }}</td>
                                            <td>{{ $districtCount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ array_sum($districtSummary) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                    @if (count($importErrors) > 0)
                        <div class="arta-notes">
                            <strong>Detail data yang gagal diimpor</strong>
                            <p>
                                Periksa nomor baris, kolom, dan nilai berikut. Perbaiki file Excel,
                                lalu unggah kembali baris yang bermasalah.
                            </p>

                            <div class="arta-error-wrap">
                                <table class="arta-error-table">
                                    <thead>
                                        <tr>
                                            <th>Baris Excel</th>
                                            <th>Nama peserta</th>
                                            <th>Kolom bermasalah</th>
                                            <th>Nilai</th>
                                            <th>Penyebab dan cara memperbaiki</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($importErrors as $error)
                                            @php
                                                if (is_string($error)) {
                                                    $error = [
                                                        'row' => '-',
                                                        'name' => '-',
                                                        'column' => 'Data peserta',
                                                        'value' => '-',
                                                        'message' => $error,
                                                    ];
                                                }

                                                $error = is_array($error) ? $error : [];
                                            @endphp

                                            <tr>
                                                <td class="arta-error-row">{{ $error['row'] ?? '-' }}</td>
                                                <td>{{ $error['name'] ?? '-' }}</td>
                                                <td>{{ $error['column'] ?? 'Data peserta' }}</td>
                                                <td class="arta-error-value">
                                                    {{ ($error['value'] ?? '') !== '' ? $error['value'] : '-' }}
                                                </td>
                                                <td>
                                                    {{ $error['message'] ?? 'Data tidak dapat diproses.' }}
                                                    <span class="arta-error-help">
                                                        Saran: periksa dan perbaiki nilai pada kolom tersebut,
                                                        kemudian unggah kembali file Excel.
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif ($resultStatus !== 'failed')
                        <div class="arta-notes">
                            Tidak ada baris peserta yang gagal. Seluruh baris data yang diperiksa berhasil diproses.
                        </div>
                    @endif
                </div>
            @endif

        </div>
    @endif

    {{-- ===================== FORM IMPORT ===================== --}}
    <form
        id="importForm"
        action="{{ route('imports.preview') }}"
        method="POST"
        enctype="multipart/form-data"
        data-veil="Membaca file Excel…"
    >
        @csrf

        {{-- 01 · FILE --}}
        <section class="arta-section" id="bagian-file" data-reveal aria-labelledby="artaFile">
            <div class="arta-section-head">
                <span class="arta-num">01</span>
                <h2 id="artaFile">File sumber data</h2>
                <p>Unggah file Excel yang berisi data peserta pelatihan.</p>
            </div>

            <div class="arta-grid">
                <div class="arta-field arta-full">
                    <label for="file">
                        File Excel
                        <span class="arta-req" aria-hidden="true">*</span>
                    </label>

                    <div class="arta-drop" id="dropZone">
                        <div class="arta-drop-icon" aria-hidden="true">
                            <svg class="ic"><use href="#i-upload"/></svg>
                        </div>

                        <div>
                            <strong>Pilih atau tarik file ke sini</strong>
                            <span class="s">XLSX, XLS, atau CSV · maksimal 10 MB</span>
                        </div>

                        <input
                            type="file"
                            name="file"
                            id="file"
                            accept=".xlsx,.xls,.csv"
                            aria-describedby="file-help"
                            required
                        >
                    </div>

                    <small class="arta-help" id="file-help">
                        Format yang didukung: XLSX, XLS, atau CSV. Ukuran file maksimal 10 MB.
                    </small>

                    <div id="selectedFile" class="arta-selected" hidden>
                        <svg class="ic" aria-hidden="true"><use href="#i-file"/></svg>
                        <span id="selectedFileName"></span>
                    </div>
                </div>

                {{-- Kabupaten/Kota dibaca otomatis --}}
                <div class="arta-full arta-auto">
                    <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                    <div>
                        <strong>Kabupaten/Kota dibaca otomatis dari Excel</strong>
                        <p>
                            Pastikan file memiliki kolom <code>KABUPATEN/KOTA</code> pada baris header peserta.
                            Setiap peserta disimpan dengan Kabupaten/Kota pada barisnya masing-masing,
                            jadi satu file boleh berisi banyak wilayah. Nama wilayah harus sesuai Master Data ARTA.
                        </p>
                    </div>
                </div>

                {{-- Template: diambil dari storage/app/templates/Template_Arta.xlsx --}}
                <div class="arta-full arta-template">
                    <p>
                        <b>Belum punya template?</b> Unduh <code>Template_Arta.xlsx</code>
                        terlebih dahulu, isi datanya, lalu unggah di sini.
                    </p>
                    <a href="{{ route('training.download-template') }}" class="arta-btn arta-btn--secondary">
                        <svg class="ic" aria-hidden="true"><use href="#i-download"/></svg>
                        <span>Download Template ARTA</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- 02 · PENYELENGGARAAN --}}
        <section class="arta-section" data-reveal aria-labelledby="artaPenyelenggaraan">
            <div class="arta-section-head">
                <span class="arta-num">02</span>
                <h2 id="artaPenyelenggaraan">Penyelenggaraan pelatihan</h2>
                <p>Angkatan dan sumber dana untuk seluruh data dalam file.</p>
            </div>

            <div class="arta-grid">

                {{-- ANGKATAN --}}
                <div class="arta-field">
                    <label for="angkatan">
                        Angkatan
                        <span class="arta-opt">Opsional</span>
                    </label>

                    <input
                        type="text"
                        name="angkatan"
                        id="angkatan"
                        class="arta-control"
                        value="{{ old('angkatan') }}"
                        placeholder="Contoh: 1"
                        maxlength="100"
                    >

                    <small class="arta-help">
                        Kosongkan jika pelatihan tidak memiliki informasi angkatan.
                    </small>
                </div>

                {{-- SUMBER DANA --}}
                <div class="arta-field">
                    <label for="sumber_dana">
                        Sumber Dana
                        <span class="arta-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        name="sumber_dana"
                        id="sumber_dana"
                        class="arta-control"
                        required
                    >
                        <option value="">-- Pilih sumber dana --</option>

                        @foreach ($fundingSources as $source)
                            <option
                                value="{{ $source->name }}"
                                @selected(old('sumber_dana') === $source->name)
                            >
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>

                    <small class="arta-help">
                        Pilihan mengikuti Master Data Sumber Dana ARTA.
                    </small>
                </div>

            </div>
        </section>

        {{-- 03 · KLASIFIKASI --}}
        <section class="arta-section" data-reveal aria-labelledby="artaKlasifikasi">
            <div class="arta-section-head">
                <span class="arta-num">03</span>
                <h2 id="artaKlasifikasi">Klasifikasi dan durasi</h2>
                <p>Kategori dan jumlah JPL, jika informasinya tersedia.</p>
            </div>

            <div class="arta-grid">

                {{-- KATEGORI --}}
                <div class="arta-field">
                    <label for="training_category_id">
                        Kategori Pelatihan
                        <span class="arta-opt">Opsional</span>
                    </label>

                    <select
                        name="training_category_id"
                        id="training_category_id"
                        class="arta-control"
                    >
                        <option value="">-- Pilih kategori --</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    (string) old('training_category_id', '') ===
                                    (string) $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <small class="arta-help">
                        Mengikuti Master Data Kategori Pelatihan.
                    </small>
                </div>

                {{-- JPL --}}
                <div class="arta-field">
                    <label for="jpl">
                        Jumlah JPL
                        <span class="arta-opt">Opsional</span>
                    </label>

                    <input
                        type="number"
                        name="jpl"
                        id="jpl"
                        class="arta-control"
                        value="{{ old('jpl') }}"
                        min="0"
                        step="1"
                        placeholder="Contoh: 32"
                    >

                    <small class="arta-help">
                        Isi jumlah Jam Pelajaran jika tersedia.
                    </small>
                </div>

            </div>
        </section>

        {{-- AKSI --}}
        <div class="arta-footer">

            <div class="arta-note">
                <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
                <span>
                    Kolom bertanda <b>*</b> wajib diisi. File akan dibaca dan ditampilkan
                    sebagai pratinjau terlebih dahulu; data baru disimpan setelah Anda
                    mengonfirmasi.
                </span>
            </div>

            <div class="arta-actions">
                <button type="reset" class="arta-btn arta-btn--ghost" id="resetButton">
                    <svg class="ic" aria-hidden="true"><use href="#i-reset"/></svg>
                    <span>Reset Form</span>
                </button>

                <button type="submit" class="arta-btn arta-btn--primary" id="submitButton">
                    <svg class="ic" aria-hidden="true"><use href="#i-eye"/></svg>
                    <span>Baca &amp; Pratinjau</span>
                </button>
            </div>

        </div>

    </form>

    {{-- INFORMASI PENTING --}}
    <aside class="arta-warn" data-reveal aria-label="Informasi penting">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>

        <div>
            <strong>Informasi penting</strong>
            <p>
                Peserta yang sudah terdaftar dapat diperbarui berdasarkan
                NIP atau NIK, termasuk Kabupaten/Kota sesuai baris Excel-nya.
                Baris yang Kabupaten/Kota-nya kosong atau tidak dikenali tidak
                diimport dan akan ditandai pada pratinjau. Riwayat pelatihan yang
                sama tidak dibuat berulang. Kategori Pelatihan dan JPL boleh
                dikosongkan jika informasinya belum tersedia.
            </p>
        </div>
    </aside>

</div>

<script>
(function () {

    /* =====================================================
       PENGATURAN (silakan ubah di sini)
       true  = setelah proses selesai, layar otomatis menuju
               kotak pratinjau/hasil/error supaya pesan langsung terlihat.
       false = layar tetap di posisi terakhir (persis di tempat
               tombol ditekan), pesan ada di bagian atas halaman.
    ===================================================== */
    var SCROLL_KE_HASIL = true;
    var KUNCI_SCROLL = 'arta-import-scroll-y';

    function mulaiHalamanImport() {
        var halaman = document.getElementById('arta');

        if (!halaman || halaman.dataset.siap === '1') return;
        halaman.dataset.siap = '1';

        var kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Ambil elemen penting ---------- */
        var form         = halaman.querySelector('#importForm');
        var formKonfirm  = halaman.querySelector('#confirmForm');
        var inputFile    = halaman.querySelector('#file');
        var zonaDrop     = halaman.querySelector('#dropZone');
        var kotakFile    = halaman.querySelector('#selectedFile');
        var namaFile     = halaman.querySelector('#selectedFileName');
        var pilihDana    = halaman.querySelector('#sumber_dana');
        var tombolKirim  = halaman.querySelector('#submitButton');
        var tombolKonfirm = halaman.querySelector('#confirmButton');
        var tombolReset  = halaman.querySelector('#resetButton');
        var layarLoading = halaman.querySelector('#artaVeil');
        var judulLoading = halaman.querySelector('#artaVeilTitle');
        var batang       = halaman.querySelector('#artaProgress');

        var isiAwalTombol = tombolKirim ? tombolKirim.innerHTML : '';
        var isiAwalKonfirm = tombolKonfirm ? tombolKonfirm.innerHTML : '';

        /* ---------- 1. POSISI LAYAR: JANGAN LOMPAT KE ATAS ---------- */
        // Browser biasanya mengembalikan layar ke atas setelah form dikirim.
        // Kita atur sendiri posisinya.
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        var adaPesan = halaman.dataset.pesan === '1';
        var kotakHasil = document.getElementById('hasil');
        var posisiTersimpan = null;

        try { posisiTersimpan = sessionStorage.getItem(KUNCI_SCROLL); } catch (e) {}

        if (adaPesan && SCROLL_KE_HASIL && kotakHasil) {
            kotakHasil.scrollIntoView({ block: 'start' });
        } else if (posisiTersimpan !== null) {
            window.scrollTo({ top: parseInt(posisiTersimpan, 10) || 0, behavior: 'instant' });
        }

        try { sessionStorage.removeItem(KUNCI_SCROLL); } catch (e) {}

        /* ---------- 2. MUNCUL PELAN SAAT SCROLL ---------- */
        if ('IntersectionObserver' in window && !kurangiGerak) {
            halaman.classList.add('is-js');

            var pengamat = new IntersectionObserver(function (daftar) {
                daftar.forEach(function (item) {
                    if (item.isIntersecting) {
                        item.target.classList.add('is-in');
                        pengamat.unobserve(item.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            halaman.querySelectorAll('[data-reveal]').forEach(function (el) {
                pengamat.observe(el);
            });
        }

        /* ---------- 3. ANGKA HASIL BERHITUNG NAIK ---------- */
        if (!kurangiGerak) {
            halaman.querySelectorAll('[data-count]').forEach(function (el) {
                var target = parseInt(el.dataset.count, 10) || 0;
                if (target === 0) return;

                var mulai = performance.now();

                function langkah(sekarang) {
                    var p = Math.min((sekarang - mulai) / 900, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(langkah);
                }

                el.textContent = '0';
                requestAnimationFrame(langkah);
            });
        }

        /* ---------- 4. GARIS PROGRESS SCROLL ---------- */
        var sedangMenunggu = false;

        function perbaruiProgress() {
            var maks = document.documentElement.scrollHeight - window.innerHeight;
            var rasio = maks > 0 ? window.scrollY / maks : 0;
            if (batang) batang.style.transform = 'scaleX(' + Math.min(Math.max(rasio, 0), 1) + ')';
            sedangMenunggu = false;
        }

        window.addEventListener('scroll', function () {
            if (!sedangMenunggu) {
                sedangMenunggu = true;
                requestAnimationFrame(perbaruiProgress);
            }
        }, { passive: true });
        perbaruiProgress();

        /* ---------- 5. TOMBOL "MULAI IMPORT" (geser halus) ---------- */
        var tombolMulai = halaman.querySelector('#ctaMulai');
        if (tombolMulai) {
            tombolMulai.addEventListener('click', function (e) {
                e.preventDefault();
                var tujuan = document.getElementById('bagian-file');
                if (tujuan) tujuan.scrollIntoView({ behavior: kurangiGerak ? 'auto' : 'smooth', block: 'start' });
            });
        }

        /* ---------- 6. NAMA FILE YANG DIPILIH ---------- */
        if (inputFile && kotakFile && namaFile) {
            inputFile.addEventListener('change', function () {
                var file = this.files && this.files.length ? this.files[0] : null;

                if (!file) {
                    namaFile.textContent = '';
                    kotakFile.hidden = true;
                    kotakFile.classList.remove('is-warn');
                    return;
                }

                var ukuranMB = file.size / 1024 / 1024;
                var teks = file.name + ' · ' + ukuranMB.toFixed(2) + ' MB';

                // Peringatan awal saja; pemeriksaan resmi tetap dilakukan server.
                var terlaluBesar = ukuranMB > 10;
                if (terlaluBesar) teks += ' (melebihi batas 10 MB)';

                namaFile.textContent = teks;
                kotakFile.classList.toggle('is-warn', terlaluBesar);
                kotakFile.hidden = false;
            });
        }

        /* ---------- 7. EFEK SAAT FILE DITARIK KE ZONA UNGGAH ---------- */
        if (zonaDrop) {
            ['dragenter', 'dragover'].forEach(function (nama) {
                zonaDrop.addEventListener(nama, function () { zonaDrop.classList.add('is-over'); });
            });
            ['dragleave', 'drop'].forEach(function (nama) {
                zonaDrop.addEventListener(nama, function () { zonaDrop.classList.remove('is-over'); });
            });
        }

        /* ---------- 8. CHECKLIST KESIAPAN DI HERO (2 data wajib) ---------- */
        var teksHitung = halaman.querySelector('#cekCount');
        var batangCek  = halaman.querySelector('#cekBar');
        var teksLabel  = halaman.querySelector('#cekLabel');
        var TOTAL_WAJIB = 2;

        function perbaruiChecklist() {
            var status = {
                file: !!(inputFile && inputFile.files && inputFile.files.length),
                dana: !!(pilihDana && pilihDana.value)
            };
            var jumlah = 0;

            Object.keys(status).forEach(function (kunci) {
                var baris = halaman.querySelector('[data-cek="' + kunci + '"]');
                if (baris) baris.classList.toggle('is-done', status[kunci]);
                if (status[kunci]) jumlah++;
            });

            if (teksHitung) teksHitung.textContent = jumlah + '/' + TOTAL_WAJIB;
            if (batangCek)  batangCek.style.transform = 'scaleX(' + (jumlah / TOTAL_WAJIB) + ')';
            if (teksLabel)  teksLabel.textContent = jumlah === TOTAL_WAJIB ? 'Siap dibaca' : 'Lengkapi data wajib';
        }

        if (form) {
            form.addEventListener('change', perbaruiChecklist);
            form.addEventListener('input', perbaruiChecklist);
        }
        perbaruiChecklist();

        /* ---------- 9. SAAT FORM DIKIRIM (form utama & form konfirmasi) ---------- */
        function pasangPengiriman(formEl, tombol, teksTombol) {
            if (!formEl || !tombol) return;

            formEl.addEventListener('submit', function (event) {
                // Jika ada isian wajib yang kosong, biarkan browser menampilkan pesannya.
                if (!formEl.checkValidity()) return;

                // Cegah klik ganda
                if (tombol.disabled) {
                    event.preventDefault();
                    return;
                }

                // Simpan posisi layar agar bisa dipulihkan setelah halaman dimuat ulang
                try { sessionStorage.setItem(KUNCI_SCROLL, String(window.scrollY)); } catch (e) {}

                // Tombol dinonaktifkan setelah event submit selesai,
                // supaya isi form tetap ikut terkirim.
                window.setTimeout(function () {
                    tombol.disabled = true;
                    tombol.innerHTML =
                        '<svg class="ic is-spin" aria-hidden="true"><use href="#i-loader"/></svg>' +
                        '<span>' + teksTombol + '</span>';
                }, 0);

                if (judulLoading) judulLoading.textContent = formEl.dataset.veil || 'Memproses…';

                // Layar loading bersifat fixed, jadi tidak menggeser posisi halaman
                if (layarLoading) layarLoading.classList.add('is-on');
            });
        }

        pasangPengiriman(form, tombolKirim, 'Membaca…');
        pasangPengiriman(formKonfirm, tombolKonfirm, 'Menyimpan…');

        /* ---------- 10. TOMBOL RESET ---------- */
        if (tombolReset) {
            tombolReset.addEventListener('click', function () {
                // Tunggu sebentar sampai browser selesai mengosongkan form
                window.setTimeout(function () {
                    if (namaFile) namaFile.textContent = '';
                    if (kotakFile) { kotakFile.hidden = true; kotakFile.classList.remove('is-warn'); }
                    perbaruiChecklist();
                }, 0);
            });
        }

        /* ---------- 11. SAAT KEMBALI KE HALAMAN (tombol Back) ---------- */
        window.addEventListener('pageshow', function () {
            if (tombolKirim) {
                tombolKirim.disabled = false;
                tombolKirim.innerHTML = isiAwalTombol;
            }
            if (tombolKonfirm) {
                tombolKonfirm.innerHTML = isiAwalKonfirm;
                tombolKonfirm.disabled = tombolKonfirm.dataset.kosong === '1';
            }
            if (layarLoading) layarLoading.classList.remove('is-on');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mulaiHalamanImport);
    } else {
        mulaiHalamanImport();
    }
})();
</script>

@endsection