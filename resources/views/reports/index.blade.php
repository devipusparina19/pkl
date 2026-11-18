@extends('layouts.app')

@section('title', 'Laporan Pelatihan · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =====================================================================
   LAPORAN PELATIHAN — EXECUTIVE FRIENDLY
   Variabel dipakai halaman DAN panel (panel dipindah ke <body> oleh script)
   ===================================================================== */
.report-page,.rp-drawer{
    --navy:#081B33; --midnight:#0B2545; --royal:#2457D6; --electric:#3478F6;
    --soft:#EAF2FF; --ice:#F3F7FC; --slate:#56657A; --text:#14243A;
    --border:#E1E9F2; --cyan:#38C6E8; --ok:#0F6B4A; --ok-bg:#E8F6EF; --warn:#8A5A00; --warn-bg:#FFF6E0;
    --ease:cubic-bezier(.2,.7,.2,1);
    --display:"Sora","Segoe UI",Arial,sans-serif;
    color:var(--text);
    font-family:"Plus Jakarta Sans","Segoe UI",Arial,sans-serif;
    font-size:16px; line-height:1.65; -webkit-font-smoothing:antialiased;
}
.report-page{position:relative;width:100%;margin:0}
.report-page *,.report-page *::before,.report-page *::after,
.rp-drawer *,.rp-drawer *::before,.rp-drawer *::after{box-sizing:border-box}
.report-page [hidden],.rp-drawer [hidden]{display:none!important}
.rp-drawer[hidden]{display:none!important}
.report-page button,.report-page input,.report-page select,.rp-drawer button,.rp-drawer input{font:inherit}
.report-page a{text-decoration:none}
.report-page svg.i,.rp-drawer svg.i{width:1em;height:1em;flex:none;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.report-page :focus-visible,.rp-drawer :focus-visible{outline:3px solid var(--cyan);outline-offset:3px;border-radius:6px}
html.rp-lock{overflow:hidden}
.rp-sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

/* indikator memuat halaman (progress scroll sudah disediakan layout) */
.rp-load{position:fixed;z-index:1000;top:0;left:0;right:0;height:4px;pointer-events:none;opacity:0;background:linear-gradient(90deg,transparent,var(--cyan),var(--electric),transparent);transform:translateX(-100%);transition:opacity .2s}
.rp-busy .rp-load{opacity:1;animation:rp-sweep 1.1s linear infinite}
@keyframes rp-sweep{from{transform:translateX(-100%)}to{transform:translateX(100%)}}

.rp-js .rp-reveal{opacity:0;transform:translate3d(0,18px,0);transition:opacity .6s var(--ease),transform .6s var(--ease)}
.rp-js .rp-reveal.in{opacity:1;transform:none}

/* ---------- BUTTON ---------- */
.rp-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:0 18px;border:1.5px solid transparent;border-radius:12px;font-family:var(--display);font-size:14px;font-weight:600;white-space:nowrap;cursor:pointer;transition:transform .2s var(--ease),box-shadow .2s,background .2s,color .2s}
.rp-btn .i{font-size:18px}
.rp-btn:active{transform:scale(.97)}
.rp-btn-primary{color:#fff;background:linear-gradient(110deg,var(--royal),var(--electric));box-shadow:0 10px 22px rgba(36,87,214,.26)}
.rp-btn-primary:hover{color:#fff;transform:translateY(-2px);box-shadow:0 14px 28px rgba(36,87,214,.36)}
.rp-btn-secondary{color:var(--royal);background:#fff;border-color:#C5D6F5}
.rp-btn-secondary:hover{color:var(--royal);background:var(--soft);transform:translateY(-2px)}
.rp-btn-ghost{color:var(--slate);background:transparent}
.rp-btn-ghost:hover{color:var(--navy);background:var(--ice)}
.rp-btn-light{color:var(--navy);background:#fff}
.rp-btn-light:hover{color:var(--navy);background:var(--cyan);transform:translateY(-2px)}
.rp-btn-outline-light{color:#fff;background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.35)}
.rp-btn-outline-light:hover{color:#fff;background:rgba(255,255,255,.18);transform:translateY(-2px)}
.rp-btn[disabled]{opacity:.45;cursor:not-allowed;transform:none}
.rp-btn.is-loading{pointer-events:none;opacity:.85}
.rp-btn.is-loading::after{content:"";width:15px;height:15px;border-radius:50%;border:2.5px solid currentColor;border-right-color:transparent;animation:rp-spin .7s linear infinite}
@keyframes rp-spin{to{transform:rotate(360deg)}}

/* ---------- ANGKA ---------- */
.rp-count{display:inline-flex;align-items:baseline;gap:6px;white-space:nowrap;font-family:var(--display);font-variant-numeric:tabular-nums;line-height:1.4;color:var(--navy)}
.rp-count b{font-weight:700}
.rp-count small{font-family:"Plus Jakarta Sans",sans-serif;font-size:13px;font-weight:600;color:var(--slate)}
.rp-count.lg b{font-size:1.6rem;line-height:1.2}
.rp-count.pill{padding:3px 12px;border-radius:10px;background:var(--soft);color:var(--royal)}

/* tombol masuk ke rincian */
.rp-detail-link{display:inline-flex;align-items:center;justify-content:center;gap:6px;min-height:40px;padding:0 15px;border:1px solid #C5D6F5;border-radius:10px;color:var(--royal);background:#fff;font-size:14px;font-weight:700;white-space:nowrap;cursor:pointer;transition:background .2s,color .2s,transform .2s}
.rp-detail-link .i{font-size:15px;transition:transform .2s var(--ease)}
.rp-detail-link:hover{color:#fff;background:var(--royal);border-color:var(--royal);transform:translateY(-1px)}
.rp-detail-link:hover .i{transform:translate(2px,-2px)}

/* =====================================================================
   1. RINGKASAN EKSEKUTIF
   ===================================================================== */
.rp-head{position:relative;isolation:isolate;overflow:hidden;display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:end;gap:22px 32px;padding:clamp(24px,3.6vw,44px);border-radius:26px;color:#fff;background:linear-gradient(120deg,var(--navy) 0%,var(--midnight) 60%,#173d86 100%);animation:rp-rise .7s var(--ease) both}
.rp-head::before{content:"";position:absolute;z-index:-1;right:-90px;top:-120px;width:380px;height:380px;border-radius:50%;background:var(--royal);opacity:.42;filter:blur(64px)}
.rp-eyebrow{display:inline-flex;align-items:center;gap:10px;margin:0 0 12px;font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--cyan)}
.rp-eyebrow::before{content:"";width:26px;height:2px;background:var(--cyan)}
.rp-head h1{margin:0 0 12px;font-family:var(--display);font-weight:800;font-size:clamp(1.8rem,4vw,3rem);line-height:1.15;letter-spacing:-.035em;color:#fff}
.rp-lead{max-width:760px;margin:0;font-size:17px;line-height:1.75;color:rgba(255,255,255,.88)}
.rp-head-actions{display:flex;flex-wrap:wrap;gap:10px}

/* FILTER: selalu tampil (tidak bisa dilipat) */
.rp-filterbox{margin-top:16px;border:1px solid var(--border);border-radius:20px;background:#fff;box-shadow:0 10px 30px rgba(8,27,51,.05);overflow:hidden}
.rp-fb-head{display:flex;align-items:center;flex-wrap:wrap;gap:10px 14px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--ice)}
.rp-fb-title{display:inline-flex;align-items:center;gap:8px;margin:0;font-family:var(--display);font-size:14.5px;font-weight:700;color:var(--navy)}
.rp-fb-title .i{font-size:18px;color:var(--royal)}
.rp-fb-chips{display:flex;flex-wrap:wrap;gap:8px;flex:1;min-width:0}
.rp-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:999px;background:var(--soft);color:var(--royal);font-size:13.5px;font-weight:700}
.rp-filter{padding:clamp(16px,2.4vw,24px)}
.rp-filter-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;align-items:end}
.rp-field{min-width:0}
.rp-field label{display:block;margin-bottom:8px;font-size:14px;font-weight:700;color:var(--navy)}
.rp-field input,.rp-field select{display:block;width:100%;min-width:0;height:50px;padding:0 14px;color:var(--text);background:var(--ice);border:1.5px solid var(--border);border-radius:12px;font-size:15px;transition:border-color .2s,box-shadow .2s,background .2s}
.rp-field input::placeholder{color:#7a8aa0}
.rp-field input:hover,.rp-field select:hover{border-color:#c5d3e4}
.rp-field input:focus,.rp-field select:focus{outline:none;background:#fff;border-color:var(--electric);box-shadow:0 0 0 4px rgba(52,120,246,.16)}
.rp-filter-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center;height:50px}

/* KPI */
.rp-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-top:16px}
.rp-kpi{display:flex;flex-direction:column;gap:4px;min-width:0;padding:20px 22px;border:1px solid var(--border);border-radius:20px;background:#fff;box-shadow:0 10px 30px rgba(8,27,51,.045)}
.rp-kpi-top{display:flex;align-items:center;gap:12px;margin-bottom:6px}
.rp-kpi-ico{display:grid;place-items:center;width:42px;height:42px;flex:none;border-radius:12px;background:var(--soft);color:var(--royal);font-size:21px}
.rp-kpi-label{font-size:14.5px;font-weight:700;line-height:1.35;color:var(--slate)}
.rp-kpi-value{display:flex;align-items:baseline;gap:8px;font-family:var(--display);font-weight:800;font-size:clamp(2rem,3.2vw,2.7rem);line-height:1.15;letter-spacing:-.03em;color:var(--navy);font-variant-numeric:tabular-nums}
.rp-kpi-value small{font-family:"Plus Jakarta Sans",sans-serif;font-size:15px;font-weight:600;letter-spacing:0;color:var(--slate)}
.rp-kpi-desc{margin:0;font-size:14px;line-height:1.55;color:var(--slate)}

/* SOROTAN */
.rp-notes{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-top:14px}
.rp-note-card{min-width:0;padding:22px 24px;border:1px solid var(--border);border-radius:20px;background:#fff}
.rp-note-card h2{display:flex;align-items:center;gap:10px;margin:0 0 4px;font-family:var(--display);font-size:1.1rem;font-weight:700;color:var(--navy)}
.rp-note-card h2 .i{display:grid;place-items:center;width:32px;height:32px;padding:7px;border-radius:10px;background:var(--ok-bg);color:var(--ok)}
.rp-note-card.warn h2 .i{background:var(--warn-bg);color:var(--warn)}
.rp-note-sub{margin:0 0 12px;font-size:13.5px;color:var(--slate)}
.rp-note-list{display:grid;gap:10px;margin:0;padding:0;list-style:none}
.rp-note-list li{position:relative;padding-left:18px;font-size:15.5px;line-height:1.6;color:var(--text)}
.rp-note-list li::before{content:"";position:absolute;left:0;top:.62em;width:8px;height:8px;border-radius:50%;background:var(--royal)}
.rp-note-card.warn .rp-note-list li::before{background:#D99100}

/* NAV */
.rp-nav-wrap{position:sticky;top:calc(var(--header-height,88px) + 8px);z-index:50;display:flex;padding:16px 0 0;pointer-events:none}
.rp-nav{display:flex;gap:4px;max-width:100%;overflow-x:auto;padding:5px;border:1px solid var(--border);border-radius:999px;pointer-events:auto;background:rgba(255,255,255,.94);backdrop-filter:blur(10px);box-shadow:0 10px 30px rgba(8,27,51,.1);scrollbar-width:none}
.rp-nav::-webkit-scrollbar{display:none}
.rp-nav a{padding:9px 17px;border-radius:999px;font-size:14.5px;font-weight:600;color:var(--slate);white-space:nowrap;transition:background .2s,color .2s}
.rp-nav a:hover{color:var(--navy);background:var(--ice)}
.rp-nav a.active{color:#fff;background:var(--royal)}

/* SECTION */
.rp-section{padding-top:clamp(32px,4.6vw,56px);scroll-margin-top:calc(var(--header-height,88px) + 76px)}
.rp-section-head{margin-bottom:18px}
.rp-section-head h2{margin:0 0 6px;font-family:var(--display);font-weight:700;font-size:clamp(1.5rem,3vw,2.1rem);line-height:1.2;letter-spacing:-.03em;color:var(--navy)}
.rp-section-head p{margin:0;max-width:760px;font-size:16px;color:var(--slate)}
.rp-hint{display:inline-flex;align-items:center;gap:8px;margin-top:10px;padding:7px 14px;border-radius:999px;background:var(--soft);color:var(--royal);font-size:14px;font-weight:600}
.rp-hint .i{font-size:16px}
.rp-split{display:grid;grid-template-columns:minmax(0,5fr) minmax(0,7fr);gap:18px;align-items:stretch}

/* PANEL */
.rp-panel{min-width:0;overflow:hidden;border:1px solid var(--border);border-radius:22px;background:#fff;box-shadow:0 14px 40px rgba(8,27,51,.055)}
.rp-panel-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px clamp(18px,2.4vw,26px);border-bottom:1px solid var(--border)}
.rp-panel-top h3{margin:0;font-family:var(--display);font-size:17px;font-weight:600;color:var(--navy)}
.rp-panel-body{padding:clamp(18px,2.6vw,28px)}
.rp-panel-body h3{margin:0 0 4px;font-family:var(--display);font-size:18px;font-weight:600;color:var(--navy)}
.rp-gender-info>p{margin:0 0 16px;font-size:15px;color:var(--slate)}
.rp-note{margin:0;padding:12px clamp(18px,2.4vw,26px);border-bottom:1px solid var(--border);background:var(--ice);font-size:14.5px;line-height:1.7;color:var(--slate)}
.rp-panel-empty{padding:22px 0;font-size:15px;color:var(--slate);text-align:center}
.rp-mini-search{display:block;width:min(100%,290px);height:44px;padding:0 14px;color:var(--text);background:var(--ice);border:1.5px solid var(--border);border-radius:12px;font-size:15px;transition:border-color .2s,box-shadow .2s,background .2s}
.rp-mini-search:focus{outline:none;background:#fff;border-color:var(--electric);box-shadow:0 0 0 4px rgba(52,120,246,.16)}

/* SORT / TAB */
.rp-seg{display:inline-flex;gap:4px;padding:4px;border:1px solid var(--border);border-radius:12px;background:var(--ice)}
.rp-seg button{display:inline-flex;align-items:center;justify-content:center;gap:6px;min-height:40px;padding:0 14px;border:0;border-radius:9px;background:transparent;color:var(--slate);cursor:pointer;font-family:var(--display);font-size:13.5px;font-weight:600;white-space:nowrap}
.rp-seg button:hover{color:var(--navy);background:#fff}
.rp-seg button[aria-pressed="true"]{color:#fff;background:var(--royal);box-shadow:0 6px 14px rgba(36,87,214,.28)}
.rp-sort-note{margin:0;padding:10px clamp(18px,2.4vw,26px);border-bottom:1px solid var(--border);background:var(--ice);font-size:14px;color:var(--slate)}
.rp-sort-note strong{color:var(--navy)}

/* KEIKUTSERTAAN */
.rp-times-top{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding:16px clamp(18px,2.4vw,26px);border-bottom:1px solid var(--border);background:var(--ice)}
.rp-times-top h3{margin:0;font-family:var(--display);font-size:17px;font-weight:600;color:var(--navy)}
.rp-times{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;padding:clamp(16px,2.2vw,24px)}
.rp-time{position:relative;display:flex;flex-direction:column;align-items:flex-start;gap:6px;width:100%;padding:18px 18px 16px;border:1.5px solid var(--border);border-radius:18px;background:#fff;text-align:left;color:inherit;cursor:pointer;overflow:hidden;transition:transform .25s var(--ease),border-color .2s,box-shadow .25s,background .2s}
.rp-time:hover{transform:translateY(-3px);border-color:#b9cfec;background:#F7FAFF;box-shadow:0 16px 34px rgba(8,27,51,.11)}
.rp-time-label{font-family:var(--display);font-size:1.2rem;font-weight:800;color:var(--navy)}
.rp-time-num{display:flex;align-items:baseline;gap:6px;font-family:var(--display);font-variant-numeric:tabular-nums}
.rp-time-num b{font-size:1.9rem;font-weight:800;color:var(--royal);line-height:1.1}
.rp-time-num small{font-family:"Plus Jakarta Sans",sans-serif;font-size:14px;font-weight:600;color:var(--slate)}
.rp-time-bar{width:100%;height:6px;border-radius:99px;background:var(--soft);overflow:hidden}
.rp-time-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--royal),var(--cyan))}
.rp-time-go{display:inline-flex;align-items:center;gap:6px;margin-top:2px;font-size:14px;font-weight:700;color:var(--royal)}
.rp-time-go .i{font-size:15px;transition:transform .2s var(--ease)}
.rp-time:hover .rp-time-go .i{transform:translate(2px,-2px)}

/* GENDER */
.rp-gender-wrap{display:grid;grid-template-columns:1fr;gap:22px;justify-items:center}
.rp-chart-box{position:relative;width:100%;max-width:250px;height:250px}
.rp-gender-info{width:100%}
.rp-gender-list{display:grid;gap:10px;margin:0;padding:0;list-style:none}
.rp-gender-list li{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid var(--border);border-radius:14px;background:var(--ice)}
.rp-dot{width:14px;height:14px;border-radius:50%;flex:none}
.rp-gender-list b{display:block;font-size:15px;color:var(--navy)}
.rp-gender-list span{font-size:14px;color:var(--slate)}
.rp-gender-list em{margin-left:auto;font-style:normal;font-family:var(--display);font-weight:700;font-size:1.45rem;color:var(--navy)}

/* TABEL */
.rp-table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:thin}
.rp-table{width:100%;min-width:650px;border-collapse:collapse;text-align:left}
.rp-table thead th{padding:14px 18px;background:var(--navy);color:#fff;font-size:13px;font-weight:700;letter-spacing:.04em;white-space:nowrap}
.rp-table tbody td{padding:14px 18px;border-bottom:1px solid #EBF0F6;font-size:15px;color:#33465C;vertical-align:middle}
.rp-table tbody tr{transition:background .2s}
.rp-table tbody tr:hover{background:#F5F9FF}
.rp-table tbody tr:last-child td{border-bottom:0}
.rp-table th.c,.rp-table td.c{text-align:center}
.rp-table th.r,.rp-table td.r{text-align:right}
.rp-number{width:64px;font-weight:700;color:#5F7088!important;font-variant-numeric:tabular-nums}
.rp-participant{font-weight:700;color:var(--navy)!important}
.rp-nik{color:var(--slate)!important;font-variant-numeric:tabular-nums;white-space:nowrap}
.rp-empty{padding:44px 18px!important;text-align:center;color:var(--slate)!important}
.rp-empty strong{display:block;margin-bottom:4px;font-family:var(--display);font-size:16px;color:var(--navy)}

/* chip sumber dana */
.rp-fchips{display:flex;flex-wrap:wrap;gap:6px}
.rp-fchip{display:inline-flex;align-items:center;gap:6px;min-height:30px;padding:2px 12px;border:1px solid transparent;border-radius:999px;font-size:13.5px;font-weight:700}
.rp-fchip b{font-family:var(--display);font-variant-numeric:tabular-nums}
.rp-fchip.t0{background:#E6F7FC;color:#0A6C86;border-color:#BFE8F4}
.rp-fchip.t1{background:var(--soft);color:var(--royal);border-color:#CFE0FB}
.rp-fchip.t2{background:#E9EEFF;color:#2B3FB3;border-color:#CBD5FF}
.rp-fchip.t3{background:#E4EBF3;color:var(--navy);border-color:#CBD6E4}

/* PAGINATION LARAVEL */
.rp-pagination{padding:16px clamp(18px,2.4vw,26px);border-top:1px solid var(--border);overflow-x:auto}
.rp-pagination:empty{display:none}
.rp-pagination nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.rp-pagination nav>div:first-child{display:none}
.rp-pagination svg{width:16px;height:16px}
.rp-pagination p{margin:0 14px 0 0;font-size:14.5px;color:var(--slate)}
.rp-pagination a,.rp-pagination span[aria-current="page"] span,.rp-pagination span[aria-disabled="true"] span{display:inline-flex;align-items:center;justify-content:center;min-width:42px;min-height:42px;padding:6px 13px;border:1px solid var(--border);border-radius:10px;background:#fff;font-size:14.5px;font-weight:700;color:#506176;transition:.25s var(--ease)}
.rp-pagination a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.rp-pagination span[aria-current="page"] span{color:#fff;background:var(--royal);border-color:var(--royal)}
.rp-pagination span[aria-disabled="true"] span{opacity:.45}

/* PAGINATION LOKAL */
.rp-pager{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px clamp(18px,2.4vw,26px);border-top:1px solid var(--border);background:#fff}
.rp-pager .rp-btn{min-height:44px;padding:0 16px}
.rp-pager-info{font-size:14.5px;font-weight:600;color:var(--slate);text-align:center;font-variant-numeric:tabular-nums}

/* SUMBER DANA */
.rp-funds{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px}
.rp-fund{display:flex;flex-direction:column;gap:16px;min-width:0;padding:22px;border:1px solid var(--border);border-radius:20px;background:#fff;box-shadow:0 10px 30px rgba(8,27,51,.045);transition:transform .25s var(--ease),box-shadow .25s}
.rp-fund:hover{transform:translateY(-3px);box-shadow:0 18px 40px rgba(8,27,51,.09)}
.rp-fund.lead{border-color:#B9CFEC;background:linear-gradient(180deg,#F5F9FF,#fff)}
.rp-fund-top{display:flex;align-items:center;gap:12px}
.rp-fund-ico{display:grid;place-items:center;width:42px;height:42px;border-radius:12px;background:var(--soft);color:var(--royal);font-size:20px;flex:none}
.rp-fund h3{margin:0;font-family:var(--display);font-size:1.1rem;font-weight:700;line-height:1.3;color:var(--navy);overflow-wrap:anywhere}
.rp-fund-badge{margin-left:auto;padding:2px 10px;border-radius:999px;background:var(--royal);color:#fff;font-size:12.5px;font-weight:700;white-space:nowrap}
.rp-fund-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.rp-stat{display:flex;flex-direction:column;gap:2px;min-width:0}
.rp-stat>span{font-size:13.5px;font-weight:600;color:var(--slate)}
.rp-fund-foot{margin-top:auto;display:grid;gap:12px}
.rp-fund-bar{height:7px;border-radius:999px;background:var(--soft);overflow:hidden}
.rp-fund-bar i{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg,var(--royal),var(--cyan));transform-origin:left;animation:rp-grow .9s var(--ease) both}
.rp-fund-meta{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.rp-fund-share{margin:0;font-size:14px;color:var(--slate)}

/* WILAYAH x SUMBER DANA */
.rp-dist-tools{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding:16px clamp(18px,2.4vw,26px);border-bottom:1px solid var(--border);background:var(--ice)}
.rp-dist-tools h3{margin:0;font-family:var(--display);font-size:17px;font-weight:600;color:var(--navy)}
.rp-dist-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;padding:clamp(14px,2vw,22px)}
.rp-dist-list .rp-wide{grid-column:1/-1}
.rp-dist{min-width:0;border:1px solid var(--border);border-radius:18px;background:#fff;overflow:hidden}
.rp-dist-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:14px 18px;background:var(--ice);border-bottom:1px solid var(--border)}
.rp-dist-head h4{margin:0;display:flex;align-items:center;gap:8px;font-family:var(--display);font-size:1.02rem;font-weight:700;color:var(--navy)}
.rp-dist-head h4 .i{color:var(--royal);font-size:18px}
.rp-dist-sum{display:flex;flex-wrap:wrap;gap:6px 16px;padding:12px 18px;border-bottom:1px solid #EDF1F5;font-size:14px}
.rp-lines{margin:0;padding:0;list-style:none}
.rp-line{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:8px 12px;padding:12px 18px;border-top:1px solid #EDF1F5}
.rp-line:first-child{border-top:0}
.rp-line-nums{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:6px 14px;font-size:14px}

/* =====================================================================
   PANEL RINCIAN & DIALOG EKSPOR (dipindah ke <body>)
   ===================================================================== */
.rp-drawer{position:fixed;inset:0;z-index:9999}
.rp-drawer-backdrop{position:absolute;inset:0;background:rgba(8,27,51,.58);opacity:0;transition:opacity .3s}
.rp-drawer.is-open .rp-drawer-backdrop{opacity:1}
.rp-drawer-panel{position:absolute;top:0;right:0;bottom:0;display:flex;flex-direction:column;width:min(780px,100%);background:#fff;box-shadow:-24px 0 60px rgba(8,27,51,.28);transform:translate3d(100%,0,0);transition:transform .38s var(--ease);outline:none}
.rp-drawer.is-open .rp-drawer-panel{transform:none}
.rp-drawer-head{position:relative;display:flex;align-items:flex-start;gap:12px;padding:20px clamp(16px,3vw,28px);color:#fff;background:linear-gradient(120deg,var(--navy),var(--midnight) 65%,#173d86);flex:none}
.rp-drawer-head::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:3px;background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)}
.rp-drawer-titles{flex:1;min-width:0}
.rp-drawer-kicker{margin:0 0 4px;font-size:12.5px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--cyan)}
.rp-drawer-title{margin:0;font-family:var(--display);font-size:clamp(1.15rem,2.6vw,1.6rem);font-weight:700;line-height:1.25;letter-spacing:-.02em;color:#fff;overflow-wrap:anywhere}
.rp-drawer-sub{margin:6px 0 0;font-size:14.5px;color:rgba(255,255,255,.8);overflow-wrap:anywhere}
.rp-icon-btn{display:grid;place-items:center;width:44px;height:44px;flex:none;border:1px solid rgba(255,255,255,.25);border-radius:12px;background:rgba(255,255,255,.08);color:#fff;font-size:20px;cursor:pointer;transition:background .2s,transform .2s var(--ease)}
.rp-icon-btn:hover{background:rgba(255,255,255,.2);transform:scale(1.05)}
.rp-drawer-tabs{padding:12px clamp(16px,3vw,28px);border-bottom:1px solid var(--border);flex:none}
.rp-drawer-tabs .rp-seg{display:flex;width:100%}
.rp-drawer-tabs .rp-seg button{flex:1}
.rp-drawer-summary{display:flex;flex-wrap:wrap;gap:10px;padding:14px clamp(16px,3vw,28px);border-bottom:1px solid var(--border);background:var(--ice);flex:none}
.rp-sum-item{display:flex;align-items:baseline;gap:8px;padding:6px 14px;border:1px solid var(--border);border-radius:12px;background:#fff}
.rp-sum-item b{font-family:var(--display);font-size:1.25rem;font-weight:700;color:var(--royal);font-variant-numeric:tabular-nums}
.rp-sum-item span{font-size:14px;font-weight:600;color:var(--slate)}
.rp-drawer-note{margin:0;padding:10px clamp(16px,3vw,28px);background:#FFF8E6;border-bottom:1px solid #F3E3B0;font-size:14px;color:#7A5B00;flex:none}
.rp-drawer-tools{padding:12px clamp(16px,3vw,28px);border-bottom:1px solid var(--border);flex:none}
.rp-drawer-tools .rp-mini-search{width:100%}
.rp-drawer-body{flex:1;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding:18px clamp(16px,3vw,28px) 32px}
.rp-info{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1px;margin:0 0 22px;border:1px solid var(--border);border-radius:16px;background:var(--border);overflow:hidden}
.rp-info div{padding:12px 16px;background:#fff;min-width:0}
.rp-info dt{margin:0 0 2px;font-size:12.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--slate)}
.rp-info dd{margin:0;font-size:15px;font-weight:600;color:var(--navy);overflow-wrap:anywhere}
.rp-info .wide{grid-column:1/-1}
.rp-list-title{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:0 0 10px;font-family:var(--display);font-size:15.5px;font-weight:700;color:var(--navy)}
.rp-list-title small{font-family:"Plus Jakarta Sans",sans-serif;font-size:13.5px;font-weight:600;color:var(--slate)}
.rp-li-wrap{display:grid;gap:8px;margin:0;padding:0;list-style:none}
.rp-li{display:flex;align-items:center;gap:12px;width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:14px;background:#fff;text-align:left;cursor:pointer;color:inherit;transition:transform .2s var(--ease),border-color .2s,box-shadow .2s,background .2s}
.rp-li:hover{transform:translateX(3px);border-color:#b9cfec;background:#F7FAFF;box-shadow:0 8px 20px rgba(8,27,51,.07)}
.rp-li-date{display:flex;flex-direction:column;align-items:center;justify-content:center;width:64px;min-height:54px;flex:none;border-radius:12px;background:var(--soft);color:var(--royal);line-height:1.15;text-align:center}
.rp-li-date b{font-family:var(--display);font-size:1.25rem}
.rp-li-date small{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.rp-li-avatar{display:grid;place-items:center;width:44px;height:44px;flex:none;border-radius:13px;background:var(--soft);color:var(--royal);font-family:var(--display);font-weight:700;text-transform:uppercase}
.rp-li-main{display:flex;flex-direction:column;gap:4px;flex:1;min-width:0}
.rp-li-main strong{font-size:15px;line-height:1.4;color:var(--navy);overflow-wrap:anywhere}
.rp-li-meta{display:flex;flex-wrap:wrap;align-items:center;gap:6px 8px;font-size:13.5px;color:var(--slate)}
.rp-tag{display:inline-flex;align-items:center;padding:1px 9px;border-radius:999px;background:var(--soft);color:var(--royal);font-size:12.5px;font-weight:700}
.rp-tag.alt{background:#E4EBF3;color:var(--navy)}
.rp-li-count{display:flex;flex-direction:column;align-items:flex-end;flex:none;line-height:1.2}
.rp-li-count b{font-family:var(--display);font-size:1.15rem;color:var(--royal)}
.rp-li-count small{font-size:12.5px;font-weight:600;color:var(--slate)}
.rp-li-go{flex:none;color:#8aa0bd;font-size:18px;display:grid}
.rp-skel{display:grid;gap:10px}
.rp-skel i{display:block;height:58px;border-radius:14px;background:var(--ice);animation:rp-pulse 1.2s ease-in-out infinite}
.rp-skel i:nth-child(2){animation-delay:.15s}
.rp-skel i:nth-child(3){animation-delay:.3s}
.rp-skel i:nth-child(4){animation-delay:.45s}
@keyframes rp-pulse{0%,100%{opacity:.45}50%{opacity:1}}
.rp-state{padding:36px 10px;text-align:center;color:var(--slate)}
.rp-state strong{display:block;margin-bottom:6px;font-family:var(--display);color:var(--navy)}

/* dialog pilihan ekspor */
.rp-modal-panel{position:absolute;left:50%;top:50%;display:flex;flex-direction:column;width:min(580px,calc(100% - 28px));max-height:calc(100% - 28px);overflow:hidden;border-radius:24px;background:#fff;box-shadow:0 30px 80px rgba(8,27,51,.35);opacity:0;transform:translate(-50%,-46%) scale(.97);transition:opacity .25s var(--ease),transform .3s var(--ease);outline:none}
.rp-modal.is-open .rp-modal-panel{opacity:1;transform:translate(-50%,-50%)}
.rp-modal-head{position:relative;display:flex;align-items:flex-start;gap:12px;padding:20px clamp(18px,3vw,26px);color:#fff;background:linear-gradient(120deg,var(--navy),var(--midnight) 65%,#173d86);flex:none}
.rp-modal-head::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:3px;background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)}
.rp-modal-body{display:grid;gap:12px;padding:clamp(16px,3vw,24px);overflow-y:auto}
.rp-modal-filter{margin:0;padding:10px 14px;border-radius:12px;background:var(--ice);font-size:14px;line-height:1.6;color:var(--slate)}
.rp-modal-filter strong{color:var(--navy)}
.rp-opt{position:relative;display:flex;align-items:flex-start;gap:14px;padding:16px 18px;border:1.5px solid var(--border);border-radius:16px;background:#fff;cursor:pointer;transition:border-color .2s,background .2s,box-shadow .2s,transform .2s var(--ease)}
.rp-opt:hover{border-color:#b9cfec;background:#F7FAFF;transform:translateY(-2px)}
.rp-opt input{position:absolute;opacity:0;pointer-events:none}
.rp-opt-radio{display:grid;place-items:center;width:22px;height:22px;margin-top:2px;flex:none;border:2px solid #b9cfec;border-radius:50%;background:#fff;transition:border-color .2s,background .2s}
.rp-opt-radio::after{content:"";width:10px;height:10px;border-radius:50%;background:#fff;transform:scale(0);transition:transform .2s var(--ease)}
.rp-opt.is-selected{border-color:var(--royal);background:var(--soft);box-shadow:0 0 0 4px rgba(52,120,246,.14)}
.rp-opt.is-selected .rp-opt-radio{border-color:var(--royal);background:var(--royal)}
.rp-opt.is-selected .rp-opt-radio::after{transform:scale(1)}
.rp-opt:focus-within{outline:3px solid var(--cyan);outline-offset:3px}
.rp-opt-text{display:grid;gap:4px;min-width:0}
.rp-opt-text strong{font-family:var(--display);font-size:1.02rem;font-weight:700;color:var(--navy)}
.rp-opt-text span{font-size:14px;line-height:1.6;color:var(--slate)}
.rp-opt-tag{display:inline-flex;width:fit-content;align-items:center;gap:6px;padding:2px 10px;border-radius:999px;font-size:12.5px;font-weight:700;background:#E4EBF3;color:var(--navy)}
.rp-opt-tag.safe{background:#E6F7FC;color:#0A6C86}
.rp-modal-foot{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:10px;padding:14px clamp(16px,3vw,24px);border-top:1px solid var(--border);background:var(--ice);flex:none}

@keyframes rp-rise{from{opacity:0;transform:translate3d(0,16px,0)}to{opacity:1;transform:none}}
@keyframes rp-grow{from{transform:scaleX(0)}to{transform:scaleX(1)}}

/* =====================================================================
   RESPONSIVE
   ===================================================================== */
@media (max-width:1200px){
    .rp-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}
    .rp-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media (max-width:1100px){
    .rp-head{grid-template-columns:1fr;align-items:start}
    .rp-dist-list{grid-template-columns:1fr}
    .rp-split{grid-template-columns:1fr}
    .rp-gender-wrap{grid-template-columns:minmax(0,250px) minmax(0,1fr);align-items:center}
}
@media (max-width:760px){
    .rp-notes{grid-template-columns:1fr}
}
@media (max-width:700px){
    .rp-kpis{grid-template-columns:1fr}
    .rp-filter-grid{grid-template-columns:1fr}
    .rp-filter-actions{display:grid;grid-template-columns:1fr;height:auto}
    .rp-filter-actions .rp-btn,.rp-head-actions .rp-btn{width:100%}
    .rp-pager .rp-btn{width:auto}
    .rp-fund-stats{grid-template-columns:repeat(3,minmax(0,1fr))}
    .rp-gender-wrap{grid-template-columns:1fr}
    .rp-seg{width:100%}
    .rp-seg button{flex:1;padding:0 8px}
    .rp-mini-search{width:100%}
    .rp-pagination nav{justify-content:flex-start}
    .rp-line{grid-template-columns:1fr}
    .rp-line-nums{justify-content:flex-start}
    .rp-info{grid-template-columns:1fr}
    .rp-times{grid-template-columns:repeat(2,minmax(0,1fr))}

    .rp-drawer-panel{width:100%;transform:translate3d(0,100%,0)}
    .rp-modal-foot .rp-btn{flex:1}

    .rp-table-wrap.cards{overflow:visible}
    .rp-table-wrap.cards .rp-table{display:block;min-width:0!important}
    .rp-table-wrap.cards thead{display:none}
    .rp-table-wrap.cards tbody{display:grid;gap:12px;padding:12px}
    .rp-table-wrap.cards tbody tr{display:block;overflow:hidden;border:1px solid var(--border);border-radius:14px}
    .rp-table-wrap.cards tbody td{display:grid;grid-template-columns:118px minmax(0,1fr);gap:10px;align-items:center;padding:11px 14px;font-size:15px;border-bottom:1px solid #EDF1F5;text-align:left!important}
    .rp-table-wrap.cards tbody td[data-label]::before{content:attr(data-label);font-size:13.5px;font-weight:600;color:var(--slate)}
    .rp-table-wrap.cards tbody tr td:last-child{border-bottom:0}
    .rp-table-wrap.cards .rp-number{width:auto}
    .rp-table-wrap.cards td.rp-empty{display:block}
    .rp-table-wrap.cards .rp-detail-link{width:100%}
}
@media (max-width:420px){
    .rp-times{grid-template-columns:1fr}
    .rp-fund-stats{grid-template-columns:1fr}
}
@media (prefers-reduced-motion:reduce){
    .report-page *,.report-page *::before,.report-page *::after,.rp-drawer *{animation:none!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
    .rp-js .rp-reveal{opacity:1;transform:none}
}
</style>

@php
    /* ------------------------------------------------------------------
       DATA DARI CONTROLLER (nama variabel tidak berubah)
    ------------------------------------------------------------------ */
    $fundingRecap = collect($fundingRecap ?? []);
    $categoryRecap = collect($categoryRecap ?? []);
    $trainingRecap = collect($trainingRecap ?? []);
    $districtFundingRecap = collect($districtFundingRecap ?? []);
    $districtFundingTotals = collect($districtFundingTotals ?? []);
    $nakesByType = collect($nakesByType ?? []);

    // Sebaran jumlah keikutsertaan. Tiap item: { times: int, total_participants: int }
    $frequencyRecap = collect($frequencyRecap ?? []);

    $years = collect($years ?? []);
    $districts = collect($districts ?? []);
    $institutions = collect($institutions ?? []);
    $participants = $participants ?? collect();

    $year = $year ?? now()->year;

    /*
     * FILTER KEIKUTSERTAAN: dibaca langsung dari URL agar pilihan
     * "Semua keikutsertaan" tidak berubah menjadi 1 kali.
     */
    $rawMinimum = request()->has('minimum') ? request('minimum') : 'all';
    $isAll = !is_numeric($rawMinimum) || (int) $rawMinimum < 1;
    $minimum = $isAll ? 'all' : (int) $rawMinimum;
    $minimumLabel = $isAll ? 'Semua keikutsertaan' : $minimum . ' kali keikutsertaan';

    $search = $search ?? request('search', '');
    $districtFilter = (string) ($districtFilter ?? request('district', ''));
    $institutionFilter = (string) ($institutionFilter ?? request('institution', ''));

    $totalTrainings = (int) ($totalTrainings ?? 0);
    $totalTrainedParticipants = (int) ($totalTrainedParticipants ?? 0);
    $totalParticipations = (int) ($totalParticipations ?? 0);

    $maleCount = (int) ($maleCount ?? 0);
    $femaleCount = (int) ($femaleCount ?? 0);
    $genderTotal = (int) ($genderTotal ?? 0);
    $malePercent = (float) ($malePercent ?? 0);
    $femalePercent = (float) ($femalePercent ?? 0);

    $fundingMax = max(1, (int) $fundingRecap->max('total_trainings'));
    $fundingSum = max(1, (int) $fundingRecap->sum('total_trainings'));

    $formatNumber = fn ($value) => number_format((int) $value, 0, ',', '.');

    $hasDetailRoute = \Illuminate\Support\Facades\Route::has('reports.detail');
    $hasCategoryDetailRoute = \Illuminate\Support\Facades\Route::has('reports.category-details');
    $endpoint = $hasDetailRoute ? route('reports.detail') : '';

    // Filter aktif ikut dikirim ke panel rincian agar angka selalu sinkron.
    $baseParams = [
        'year' => $year,
        'minimum' => $minimum,
        'search' => $search,
        'district' => $districtFilter,
        'institution' => $institutionFilter,
    ];

    // Ekspor mengikuti filter aktif; parameter "type" ditambahkan oleh dialog ekspor.
    $exportParams = array_filter($baseParams, fn ($v) => $v !== '' && $v !== null);

    $excelUrl = \Illuminate\Support\Facades\Route::has('reports.export') ? route('reports.export', $exportParams) : null;
    $pdfUrl = \Illuminate\Support\Facades\Route::has('reports.pdf')
        ? route('reports.pdf', $exportParams)
        : (\Illuminate\Support\Facades\Route::has('pdf.report') ? route('pdf.report', $exportParams) : null);
    $pdfSupportsType = \Illuminate\Support\Facades\Route::has('reports.pdf');

    // Bagian sebaran hanya tampil saat "Semua keikutsertaan" dipilih.
    $showFrequency = $isAll && $frequencyRecap->isNotEmpty();
    $frequencyTotal = (int) $frequencyRecap->sum(fn ($r) => (int) data_get($r, 'total_participants', 0));

    $count = function ($value, string $unit = '', string $extra = '') use ($formatNumber) {
        $unitHtml = $unit !== '' ? '<small>' . e($unit) . '</small>' : '';

        return new \Illuminate\Support\HtmlString(
            '<span class="rp-count ' . e($extra) . '"><b>' . e($formatNumber($value)) . '</b>' . $unitHtml . '</span>'
        );
    };

    $detailBtn = function (array $payload, string $text = 'Rincian') use ($hasDetailRoute) {
        if (!$hasDetailRoute) {
            return new \Illuminate\Support\HtmlString('');
        }

        return new \Illuminate\Support\HtmlString(
            '<button type="button" class="rp-detail-link" data-detail="'
            . e(json_encode($payload, JSON_UNESCAPED_UNICODE)) . '" aria-haspopup="dialog" aria-label="'
            . e($text . ': ' . ($payload['label'] ?? '')) . '">' . e($text)
            . '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg></button>'
        );
    };

    $tone = fn ($label) => 't' . (abs(crc32((string) $label)) % 4);

    $districtGroups = $districtFundingRecap->groupBy('district');

    // Urutan wilayah: berdasarkan jumlah peserta (sama dengan Excel).
    $districtBlocks = $districtFundingTotals
        ->sortByDesc(fn ($row) => (int) $row->total_participants)
        ->values();

    // Ringkasan filter aktif untuk dialog ekspor.
    $exportFilterText = collect([
        'Tahun ' . $year,
        $minimumLabel,
        $districtFilter !== '' ? $districtFilter : null,
        $institutionFilter !== '' ? $institutionFilter : null,
        $search !== '' ? 'Kata kunci "' . $search . '"' : null,
    ])->filter()->implode(' · ');

    $filtersActive = !$isAll || $districtFilter !== '' || $institutionFilter !== '' || $search !== '';

    /* ------------------------------------------------------------------
       RINGKASAN EKSEKUTIF
       Semua kalimat di bawah disusun OTOMATIS dari data laporan di atas.
       Tidak ada angka atau kesimpulan karangan.
    ------------------------------------------------------------------ */
    $fundingTop = $fundingRecap->sortByDesc(fn ($r) => (int) data_get($r, 'total_trainings', 0))->first();
    $fundingTopShare = $fundingTop ? (int) round(((int) data_get($fundingTop, 'total_trainings', 0) / $fundingSum) * 100) : 0;
    $professionTop = $nakesByType->sortByDesc(fn ($r) => (int) data_get($r, 'total', 0))->first();
    $trainingTop = $trainingRecap->sortByDesc(fn ($r) => (int) data_get($r, 'total_trainings', 0))->first();
    $districtTop = $districtBlocks->first();
    $categoryTop = $categoryRecap
        ->filter(fn ($c) => filled(data_get($c, 'category_id')))
        ->sortByDesc(fn ($c) => (int) data_get($c, 'total_trainings', 0))
        ->first();
    $uncategorized = $categoryRecap->first(fn ($c) => blank(data_get($c, 'category_id')));

    $repeatParticipants = (int) $frequencyRecap
        ->filter(fn ($r) => (int) data_get($r, 'times', 0) >= 2)
        ->sum(fn ($r) => (int) data_get($r, 'total_participants', 0));

    $conclusion = $totalTrainings > 0
        ? 'Sesuai filter yang dipilih, tahun ' . $year . ' tercatat ' . $formatNumber($totalTrainings)
            . ' kegiatan pelatihan yang diikuti ' . $formatNumber($totalTrainedParticipants)
            . ' peserta (' . $formatNumber($totalParticipations) . ' kali keikutsertaan).'
            . ($fundingTop
                ? ' Sumber dana terbesar adalah ' . data_get($fundingTop, 'funding_source') . ' dengan ' . $fundingTopShare . '% dari seluruh kegiatan.'
                : '')
        : 'Belum ada kegiatan pelatihan yang tercatat pada tahun ' . $year . ' sesuai filter yang dipilih.';

    $achievements = [];
    if ($totalTrainings > 0) {
        $achievements[] = $formatNumber($totalTrainings) . ' kegiatan pelatihan terlaksana dan diikuti ' . $formatNumber($totalTrainedParticipants) . ' peserta.';
    }
    if ($fundingTop) {
        $achievements[] = 'Sumber dana terbesar: ' . data_get($fundingTop, 'funding_source') . ' (' . $formatNumber(data_get($fundingTop, 'total_trainings')) . ' kegiatan, ' . $fundingTopShare . '%).';
    }
    if ($trainingTop) {
        $achievements[] = 'Pelatihan yang paling sering diselenggarakan: ' . data_get($trainingTop, 'name') . ' (' . $formatNumber(data_get($trainingTop, 'total_trainings')) . ' kegiatan).';
    }
    if ($professionTop) {
        $achievements[] = 'Profesi dengan peserta terbanyak: ' . data_get($professionTop, 'name') . ' (' . $formatNumber(data_get($professionTop, 'total')) . ' peserta).';
    }
    if ($districtTop) {
        $achievements[] = 'Kabupaten/kota dengan peserta terbanyak: ' . $districtTop->district . ' (' . $formatNumber($districtTop->total_participants) . ' peserta).';
    }
    if ($categoryTop) {
        $achievements[] = 'Kategori pelatihan terbanyak: ' . data_get($categoryTop, 'category_name') . ' (' . $formatNumber(data_get($categoryTop, 'total_trainings')) . ' kegiatan).';
    }
    if ($repeatParticipants > 0) {
        $achievements[] = $formatNumber($repeatParticipants) . ' peserta mengikuti pelatihan lebih dari satu kali.';
    }

    $attention = [];
    if ($totalTrainedParticipants > 0 && $genderTotal > 0 && $genderTotal < $totalTrainedParticipants) {
        $attention[] = 'Data jenis kelamin baru tercatat untuk ' . $formatNumber($genderTotal) . ' dari ' . $formatNumber($totalTrainedParticipants) . ' peserta.';
    }
    if ($uncategorized && (int) data_get($uncategorized, 'total_trainings', 0) > 0) {
        $attention[] = $formatNumber(data_get($uncategorized, 'total_trainings')) . ' kegiatan belum memiliki kategori pelatihan.';
    }
    if ($fundingRecap->count() > 1 && $fundingTopShare >= 50) {
        $attention[] = 'Kegiatan banyak bertumpu pada satu sumber dana: ' . data_get($fundingTop, 'funding_source') . ' (' . $fundingTopShare . '%).';
    }
@endphp

<div class="rp-load" aria-hidden="true"></div>

<div class="report-page" id="reportPage"
     data-endpoint="{{ $endpoint }}"
     data-base="{{ json_encode($baseParams, JSON_UNESCAPED_UNICODE) }}">

    {{-- ============================================================
         1. RINGKASAN EKSEKUTIF
         ============================================================ --}}
    <section id="ringkasan-eksekutif" aria-labelledby="rp-title">

        <header class="rp-head">
            <div>
                <p class="rp-eyebrow">Ringkasan eksekutif · Periode {{ $year }}</p>
                <h1 id="rp-title">Laporan pelatihan tenaga kesehatan tahun {{ $year }}</h1>
                <p class="rp-lead">{{ $conclusion }}</p>
            </div>

            <div class="rp-head-actions">
                {{-- Tautan tetap berfungsi tanpa JavaScript (mengunduh ekspor lengkap).
                     Dengan JavaScript, klik membuka dialog pilihan jenis ekspor. --}}
                @if ($excelUrl)
                    <a href="{{ $excelUrl }}" class="rp-btn rp-btn-light"
                       data-export="excel" data-export-label="Excel" data-export-type-support="1"
                       aria-haspopup="dialog">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                        Unduh Excel
                    </a>
                @endif

                @if ($pdfUrl)
                    <a href="{{ $pdfUrl }}" class="rp-btn rp-btn-outline-light"
                       data-export="pdf" data-export-label="PDF" data-export-type-support="{{ $pdfSupportsType ? '1' : '0' }}"
                       aria-haspopup="dialog">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h8"/></svg>
                        Unduh PDF
                    </a>
                @endif
            </div>
        </header>

        {{-- FILTER: selalu tampil, tidak disembunyikan --}}
        <section class="rp-filterbox" id="filter" aria-labelledby="rpFilterTitle">
            <div class="rp-fb-head">
                <h2 class="rp-fb-title" id="rpFilterTitle">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                    Filter laporan
                </h2>

                <span class="rp-fb-chips" aria-label="Filter yang sedang aktif">
                    <span class="rp-chip">Tahun {{ $year }}</span>
                    <span class="rp-chip">{{ $minimumLabel }}</span>
                    @if ($districtFilter !== '') <span class="rp-chip">{{ $districtFilter }}</span> @endif
                    @if ($institutionFilter !== '') <span class="rp-chip">{{ $institutionFilter }}</span> @endif
                    @if ($search !== '') <span class="rp-chip">Kata kunci: {{ $search }}</span> @endif
                </span>
            </div>

            <div class="rp-filter">
                <form method="GET" action="{{ route('reports.index') }}" data-keep-scroll>
                    <div class="rp-filter-grid">

                        <div class="rp-field">
                            <label for="year">Tahun Pelatihan</label>
                            <select id="year" name="year" required>
                                @forelse ($years as $availableYear)
                                    <option value="{{ $availableYear }}" @selected((int) $year === (int) $availableYear)>{{ $availableYear }}</option>
                                @empty
                                    <option value="{{ $year }}" selected>{{ $year }}</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="minimum">Jumlah Keikutsertaan</label>
                            <select id="minimum" name="minimum">
                                <option value="all" @selected($isAll)>Semua keikutsertaan</option>
                                @foreach ([1, 2, 3, 4, 5, 10] as $value)
                                    <option value="{{ $value }}" @selected(!$isAll && (int) $minimum === $value)>{{ $value }} kali</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="district">Kabupaten/Kota</label>
                            <select id="district" name="district">
                                <option value="">Semua Kabupaten/Kota</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district }}" @selected($districtFilter === (string) $district)>{{ $district }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="institution">Instansi</label>
                            <select id="institution" name="institution">
                                <option value="">Semua Instansi</option>
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution }}" @selected($institutionFilter === (string) $institution)>{{ $institution }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="search">Cari Peserta</label>
                            <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Nama, NIK, atau NIP">
                        </div>

                        <div class="rp-filter-actions">
                            <button type="submit" class="rp-btn rp-btn-primary">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                Terapkan
                            </button>

                            <a href="{{ route('reports.index') }}" class="rp-btn rp-btn-ghost" data-keep-scroll>
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                Atur ulang
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        {{-- INDIKATOR UTAMA --}}
        <div class="rp-kpis" role="list" aria-label="Indikator utama">
            <div class="rp-kpi" role="listitem">
                <div class="rp-kpi-top">
                    <span class="rp-kpi-ico" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></span>
                    <span class="rp-kpi-label">Kegiatan Pelatihan</span>
                </div>
                <div class="rp-kpi-value"><span class="rp-counter" data-target="{{ $totalTrainings }}">{{ $formatNumber($totalTrainings) }}</span><small>kegiatan</small></div>
                <p class="rp-kpi-desc">Jumlah kegiatan pelatihan yang terlaksana pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-kpi" role="listitem">
                <div class="rp-kpi-top">
                    <span class="rp-kpi-ico" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg></span>
                    <span class="rp-kpi-label">Peserta Dilatih</span>
                </div>
                <div class="rp-kpi-value"><span class="rp-counter" data-target="{{ $totalTrainedParticipants }}">{{ $formatNumber($totalTrainedParticipants) }}</span><small>orang</small></div>
                <p class="rp-kpi-desc">Jumlah orang yang sudah dilatih. Satu orang dihitung satu kali.</p>
            </div>

            <div class="rp-kpi" role="listitem">
                <div class="rp-kpi-top">
                    <span class="rp-kpi-ico" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></span>
                    <span class="rp-kpi-label">Total Keikutsertaan</span>
                </div>
                <div class="rp-kpi-value"><span class="rp-counter" data-target="{{ $totalParticipations }}">{{ $formatNumber($totalParticipations) }}</span><small>kali</small></div>
                <p class="rp-kpi-desc">Jumlah seluruh keikutsertaan. Satu orang bisa ikut lebih dari satu pelatihan.</p>
            </div>

            <div class="rp-kpi" role="listitem">
                <div class="rp-kpi-top">
                    <span class="rp-kpi-ico" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></span>
                    <span class="rp-kpi-label">Jenis Pelatihan</span>
                </div>
                <div class="rp-kpi-value"><span class="rp-counter" data-target="{{ $trainingRecap->count() }}">{{ $formatNumber($trainingRecap->count()) }}</span><small>jenis</small></div>
                <p class="rp-kpi-desc">Jumlah nama pelatihan yang berbeda. Satu jenis bisa punya banyak kegiatan.</p>
            </div>
        </div>

        {{-- PENCAPAIAN & PERLU PERHATIAN --}}
        <div class="rp-notes">
            <article class="rp-note-card" aria-labelledby="rp-ach-title">
                <h2 id="rp-ach-title">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                    Pencapaian
                </h2>
                <p class="rp-note-sub">Disusun otomatis dari data laporan.</p>

                @if (count($achievements))
                    <ul class="rp-note-list">
                        @foreach ($achievements as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="rp-panel-empty" style="text-align:left;padding:6px 0">Belum ada data untuk diringkas pada filter ini.</p>
                @endif
            </article>

            <article class="rp-note-card warn" aria-labelledby="rp-att-title">
                <h2 id="rp-att-title">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                    Perlu Perhatian
                </h2>
                <p class="rp-note-sub">Catatan dari kelengkapan dan sebaran data.</p>

                @if (count($attention))
                    <ul class="rp-note-list">
                        @foreach ($attention as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="rp-panel-empty" style="text-align:left;padding:6px 0">Tidak ada catatan khusus dari data yang tersedia.</p>
                @endif
            </article>
        </div>
    </section>

    {{-- NAVIGASI --}}
    <div class="rp-nav-wrap">
        <nav class="rp-nav" aria-label="Navigasi laporan" id="rpNav">
            <a href="#ringkasan-eksekutif" class="active">Ringkasan</a>
            @if ($showFrequency)
                <a href="#keikutsertaan">Keikutsertaan</a>
            @endif
            <a href="#komposisi">Komposisi</a>
            <a href="#sumber-dana">Sumber Dana</a>
            <a href="#pelatihan">Pelatihan</a>
            <a href="#kategori">Kategori</a>
            <a href="#wilayah-dana">Wilayah</a>
            <a href="#peserta">Peserta</a>
        </nav>
    </div>

    {{-- ============================================================
         2. KEIKUTSERTAAN: hanya saat filter = "Semua keikutsertaan"
         ============================================================ --}}
    @if ($showFrequency)
        <section class="rp-section rp-reveal" id="keikutsertaan" aria-labelledby="rp-times-title">
            <div class="rp-section-head">
                <h2 id="rp-times-title">Berapa kali peserta mengikuti pelatihan?</h2>
                <p>Pilih salah satu kartu untuk melihat nama peserta yang mengikuti pelatihan sebanyak itu pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-panel">
                <div class="rp-times-top">
                    <h3>Peserta berdasarkan jumlah keikutsertaan</h3>
                    <span class="rp-count pill"><b>{{ $formatNumber($frequencyTotal) }}</b><small>peserta</small></span>
                </div>

                <div class="rp-times">
                    @foreach ($frequencyRecap as $row)
                        @php
                            $times = (int) data_get($row, 'times', 0);
                            $timesTotal = (int) data_get($row, 'total_participants', 0);
                            $timesShare = $frequencyTotal > 0 ? round($timesTotal / $frequencyTotal * 100) : 0;
                            $timesPayload = ['view' => 'participants', 'times' => $times, 'label' => $times . ' kali keikutsertaan'];
                        @endphp

                        <button type="button" class="rp-time"
                                @if ($hasDetailRoute) data-detail="{{ json_encode($timesPayload, JSON_UNESCAPED_UNICODE) }}" aria-haspopup="dialog" @endif
                                aria-label="{{ $times }} kali keikutsertaan: {{ $formatNumber($timesTotal) }} peserta ({{ $timesShare }}%)">
                            <span class="rp-time-label">{{ $times }} kali</span>
                            <span class="rp-time-num"><b>{{ $formatNumber($timesTotal) }}</b><small>peserta · {{ $timesShare }}%</small></span>
                            <span class="rp-time-bar" aria-hidden="true"><i style="width:{{ max(2, $timesShare) }}%"></i></span>
                            <span class="rp-time-go">
                                Lihat nama
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         3. KOMPOSISI PESERTA: jenis kelamin + profesi
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="komposisi" aria-labelledby="rp-sum-title">
        <div class="rp-section-head">
            <h2 id="rp-sum-title">Siapa saja pesertanya?</h2>
            <p>Komposisi peserta berdasarkan jenis kelamin dan profesi pada tahun {{ $year }}.</p>
        </div>

        <div class="rp-split">

            <div class="rp-panel">
                <div class="rp-panel-body">
                    @if ($genderTotal > 0)
                        <div class="rp-gender-wrap">
                            <div class="rp-chart-box">
                                <canvas id="reportGenderChart" role="img"
                                    aria-label="Diagram peserta laki-laki {{ $maleCount }} orang dan perempuan {{ $femaleCount }} orang"></canvas>
                            </div>

                            <div class="rp-gender-info">
                                <h3>Jenis kelamin</h3>
                                <p>Peserta dengan jenis kelamin tercatat: <strong>{{ $formatNumber($genderTotal) }} orang</strong></p>

                                <ul class="rp-gender-list">
                                    <li>
                                        <span class="rp-dot" style="background:#2457D6" aria-hidden="true"></span>
                                        <div><b>Laki-laki</b><span>{{ $formatNumber($maleCount) }} orang</span></div>
                                        <em>{{ number_format($malePercent, 1, ',', '.') }}%</em>
                                    </li>
                                    <li>
                                        <span class="rp-dot" style="background:#0E8FB3" aria-hidden="true"></span>
                                        <div><b>Perempuan</b><span>{{ $formatNumber($femaleCount) }} orang</span></div>
                                        <em>{{ number_format($femalePercent, 1, ',', '.') }}%</em>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <h3>Jenis kelamin</h3>
                        <div class="rp-panel-empty">Belum ada data jenis kelamin pada tahun {{ $year }}.</div>
                    @endif
                </div>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top">
                    <h3>Peserta berdasarkan profesi</h3>

                    @if ($nakesByType->isNotEmpty())
                        <div class="rp-seg" role="group" aria-label="Urutkan profesi" id="rpProfSort">
                            <button type="button" data-sort="default" aria-pressed="true">Awal</button>
                            <button type="button" data-sort="high" aria-pressed="false">Tertinggi</button>
                            <button type="button" data-sort="low" aria-pressed="false">Terendah</button>
                        </div>
                    @endif
                </div>

                @if ($nakesByType->isNotEmpty())
                    <p class="rp-sort-note" id="rpProfNote" aria-live="polite">Diurutkan: <strong>urutan awal</strong></p>
                @endif

                <div class="rp-table-wrap cards">
                    <table class="rp-table" style="min-width:380px">
                        <caption class="rp-sr">Jumlah peserta per profesi tahun {{ $year }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Jenis Profesi</th>
                                <th scope="col" class="r">Peserta Terlatih</th>
                            </tr>
                        </thead>
                        <tbody id="rpProfBody" data-paginate="8" data-items="tr[data-row]" data-pager-after="closest:.rp-table-wrap">
                            @forelse ($nakesByType as $index => $profession)
                                <tr data-row data-total="{{ (int) $profession->total }}">
                                    <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>
                                    <td class="rp-participant" data-label="Profesi">{{ $profession->name }}</td>
                                    <td class="r" data-label="Peserta">{{ $count($profession->total, '', 'pill') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="rp-empty">Belum ada data profesi pada tahun {{ $year }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    {{-- ============================================================
         4. SUMBER DANA
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="sumber-dana" aria-labelledby="rp-fund-title">
        <div class="rp-section-head">
            <h2 id="rp-fund-title">Dari mana pembiayaannya?</h2>
            <p>Jumlah kegiatan, peserta, dan keikutsertaan untuk setiap sumber dana tahun {{ $year }}.</p>
            @if ($hasDetailRoute)
                <span class="rp-hint">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    Tombol Rincian menampilkan daftar kegiatan dan pesertanya
                </span>
            @endif
        </div>

        @if ($fundingRecap->isEmpty())
            <div class="rp-panel"><div class="rp-panel-body"><div class="rp-panel-empty">Belum ada data sumber dana pada tahun {{ $year }}.</div></div></div>
        @else
            <div class="rp-funds">
                @foreach ($fundingRecap as $index => $funding)
                    @php
                        $fundingLabel = 'Sumber dana: ' . $funding->funding_source;
                        $share = round(((int) $funding->total_trainings / $fundingSum) * 100);
                    @endphp

                    <article class="rp-fund {{ $index === 0 ? 'lead' : '' }}">
                        <div class="rp-fund-top">
                            <span class="rp-fund-ico" aria-hidden="true">
                                <svg class="i" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </span>
                            <h3>{{ $funding->funding_source }}</h3>
                            @if ($index === 0 && $fundingRecap->count() > 1)
                                <span class="rp-fund-badge">Terbesar</span>
                            @endif
                        </div>

                        <div class="rp-fund-stats">
                            <div class="rp-stat"><span>Kegiatan</span>{{ $count($funding->total_trainings, '', 'lg') }}</div>
                            <div class="rp-stat"><span>Peserta</span>{{ $count($funding->total_participants, '', 'lg') }}</div>
                            <div class="rp-stat"><span>Keikutsertaan</span>{{ $count($funding->total_participations, '', 'lg') }}</div>
                        </div>

                        <div class="rp-fund-foot">
                            <div class="rp-fund-bar" aria-hidden="true">
                                <i style="width:{{ max(3, round(((int) $funding->total_trainings / $fundingMax) * 100)) }}%"></i>
                            </div>
                            <div class="rp-fund-meta">
                                <p class="rp-fund-share">{{ $share }}% dari seluruh kegiatan</p>
                                {{ $detailBtn(['tabs' => 1, 'view' => 'trainings', 'funding_source' => $funding->funding_source, 'label' => $fundingLabel]) }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ============================================================
         5. REKAP PER PELATIHAN
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="pelatihan" aria-labelledby="rp-train-title">
        <div class="rp-section-head">
            <h2 id="rp-train-title">Pelatihan apa saja yang diselenggarakan?</h2>
            <p>Satu jenis pelatihan bisa memiliki banyak kegiatan dengan sumber dana berbeda. Tekan Rincian untuk melihat tanggal, sumber dana, dan pesertanya.</p>
        </div>

        <div class="rp-panel">
            <div class="rp-panel-top">
                <h3>Jumlah kegiatan per nama pelatihan</h3>
                <input type="search" class="rp-mini-search" placeholder="Cari nama pelatihan"
                       aria-label="Cari nama pelatihan"
                       data-filter-target="#rpTrainBody" data-filter-items="tr[data-row]">
            </div>

            <div class="rp-table-wrap cards">
                <table class="rp-table" style="min-width:860px">
                    <caption class="rp-sr">Rekap kegiatan per nama pelatihan tahun {{ $year }}</caption>
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Pelatihan</th>
                            <th scope="col" class="c">Kegiatan</th>
                            <th scope="col">Sumber Dana</th>
                            <th scope="col" class="c">Peserta</th>
                            <th scope="col" class="c">Rincian</th>
                        </tr>
                    </thead>
                    <tbody id="rpTrainBody" data-paginate="10" data-items="tr[data-row]" data-pager-after="closest:.rp-table-wrap">
                        @forelse ($trainingRecap as $index => $training)
                            @php $trainLabel = 'Pelatihan: ' . $training->name; @endphp
                            <tr data-row data-search="{{ mb_strtolower($training->name) }}">
                                <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>
                                <td class="rp-participant" data-label="Nama Pelatihan">{{ $training->name }}</td>
                                <td class="c" data-label="Kegiatan">{{ $count($training->total_trainings) }}</td>
                                <td data-label="Sumber Dana">
                                    <div class="rp-fchips">
                                        @foreach ($training->funding as $fund)
                                            <span class="rp-fchip {{ $tone($fund->funding_source) }}">{{ $fund->funding_source }} <b>{{ $formatNumber($fund->total_trainings) }}</b></span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="c" data-label="Peserta">{{ $count($training->total_participants) }}</td>
                                <td class="c" data-label="Rincian">
                                    {{ $detailBtn(['tabs' => 1, 'view' => 'trainings', 'training_name' => $training->name, 'label' => $trainLabel]) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="rp-empty">Belum ada data pelatihan pada tahun {{ $year }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ============================================================
         6. KATEGORI
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="kategori" aria-labelledby="rp-cat-title">
        <div class="rp-section-head">
            <h2 id="rp-cat-title">Kategori pelatihan</h2>
            <p>Jumlah kegiatan dan peserta berdasarkan kategori pelatihan.</p>
        </div>

        <div class="rp-panel">
            <div class="rp-panel-top"><h3>Kegiatan berdasarkan kategori</h3></div>

            <div class="rp-table-wrap cards">
                <table class="rp-table">
                    <caption class="rp-sr">Rekap kategori pelatihan tahun {{ $year }}</caption>
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Kategori Pelatihan</th>
                            <th scope="col" class="c">Jumlah Kegiatan</th>
                            <th scope="col" class="c">Peserta</th>
                            <th scope="col" class="c">Rincian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categoryRecap as $index => $category)
                            @php
                                $catId = $category->category_id ? (string) $category->category_id : 'none';
                                $catLabel = 'Kategori: ' . $category->category_name;
                            @endphp
                            <tr>
                                <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>
                                <td class="rp-participant" data-label="Kategori">{{ $category->category_name }}</td>
                                <td class="c" data-label="Jumlah Kegiatan">{{ $count($category->total_trainings) }}</td>
                                <td class="c" data-label="Peserta">{{ $count($category->total_participants) }}</td>
                                <td class="c" data-label="Rincian">
                                    @if ($hasDetailRoute)
                                        {{ $detailBtn(['tabs' => 1, 'view' => 'trainings', 'category_id' => $catId, 'label' => $catLabel]) }}
                                    @elseif ($hasCategoryDetailRoute && $category->category_id)
                                        <a class="rp-detail-link" href="{{ route('reports.category-details', ['categoryId' => $category->category_id, 'year' => $year]) }}">Rincian</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="rp-empty">Belum ada data kategori pelatihan pada tahun {{ $year }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ============================================================
         7. WILAYAH x SUMBER DANA
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="wilayah-dana" aria-labelledby="rp-dfund-title">
        <div class="rp-section-head">
            <h2 id="rp-dfund-title">Sebaran per kabupaten/kota</h2>
            <p>Setiap kabupaten/kota menampilkan jumlah kegiatan dan peserta untuk tiap sumber dana.</p>
        </div>

        <div class="rp-panel">
            <div class="rp-dist-tools">
                <h3>Peserta per kabupaten/kota</h3>
                <input type="search" class="rp-mini-search" placeholder="Cari kabupaten/kota"
                       aria-label="Cari kabupaten/kota"
                       data-filter-target="#rpDistList" data-filter-items=".rp-dist">
            </div>

            @if ($districtBlocks->isEmpty())
                <div class="rp-panel-body"><div class="rp-panel-empty">Belum ada data wilayah berdasarkan sumber dana.</div></div>
            @else
                <div class="rp-dist-list" id="rpDistList" data-paginate="8" data-items=".rp-dist" data-pager-after="self" data-fill>
                    @foreach ($districtBlocks as $block)
                        @php
                            $distName = $block->district;
                            $distLabel = 'Kab/Kota: ' . $distName;
                            $lines = $districtGroups->get($distName, collect());
                        @endphp

                        <article class="rp-dist" data-search="{{ mb_strtolower($distName) }}">
                            <header class="rp-dist-head">
                                <h4>
                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-5h6v5"/></svg>
                                    {{ $distName }}
                                </h4>
                                {{ $detailBtn(['tabs' => 1, 'view' => 'trainings', 'scope_district' => $distName, 'label' => $distLabel]) }}
                            </header>

                            <div class="rp-dist-sum">
                                {{ $count($block->total_trainings, 'kegiatan') }}
                                {{ $count($block->total_participants, 'peserta') }}
                            </div>

                            <ul class="rp-lines">
                                @foreach ($lines as $line)
                                    <li class="rp-line">
                                        <span class="rp-fchip {{ $tone($line->funding_source) }}">{{ $line->funding_source }}</span>
                                        <div class="rp-line-nums">
                                            {{ $count($line->total_trainings, 'kegiatan') }}
                                            {{ $count($line->total_participants, 'peserta') }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         8. DAFTAR PESERTA
         ============================================================ --}}
    <section class="rp-section rp-reveal" id="peserta" aria-labelledby="rp-freq-title">
        <div class="rp-section-head">
            <h2 id="rp-freq-title">Daftar peserta</h2>
            <p>
                Jumlah keikutsertaan setiap peserta pada tahun {{ $year }} ({{ mb_strtolower($minimumLabel) }}).
                Tekan Lihat untuk mengetahui pelatihan apa saja yang diikuti.
            </p>
        </div>

        <div class="rp-panel">
            <div class="rp-panel-top">
                <h3>Peserta pelatihan</h3>

                <span class="rp-count pill">
                    <b>{{ $formatNumber(method_exists($participants, 'total') ? $participants->total() : $participants->count()) }}</b>
                    <small>peserta</small>
                </span>
            </div>

            <p class="rp-note">Satu peserta bisa mengikuti beberapa kegiatan, tetapi hanya dihitung satu kali pada jumlah peserta.</p>

            <div class="rp-table-wrap cards">
                <table class="rp-table" style="min-width:1000px">
                    <caption class="rp-sr">Data peserta dan jumlah keikutsertaan pelatihan tahun {{ $year }}</caption>

                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Peserta</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Instansi</th>
                            <th scope="col">Kabupaten/Kota</th>
                            <th scope="col" class="c">Keikutsertaan</th>
                            <th scope="col" class="c">Rincian</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($participants as $participant)
                            <tr>
                                <td class="rp-number" data-label="No.">{{ $participants->firstItem() + $loop->index }}</td>
                                <td class="rp-participant" data-label="Nama Peserta">{{ $participant->name }}</td>
                                <td class="rp-nik" data-label="NIK">{{ $participant->nik }}</td>
                                <td data-label="Instansi">{{ $participant->institution }}</td>
                                <td data-label="Kabupaten/Kota">{{ $participant->district }}</td>
                                <td class="c" data-label="Keikutsertaan">{{ $count($participant->participations_count, 'kali') }}</td>
                                <td class="c" data-label="Rincian">
                                    {{ $detailBtn(['view' => 'participant', 'participant_id' => $participant->id, 'label' => $participant->name ?: 'Peserta'], 'Lihat') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="rp-empty">
                                    <strong>Tidak ada data peserta</strong>
                                    Belum ada peserta yang memenuhi filter pada tahun {{ $year }}.

                                    @if ($search !== '')
                                        dengan kata kunci "{{ $search }}"
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rp-pagination" data-keep-scroll>
                {{ $participants->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

    {{-- PANEL RINCIAN (dipindah ke <body> oleh script agar menempel ke layar) --}}
    <div class="rp-drawer" id="rpDrawer" hidden>
        <div class="rp-drawer-backdrop" data-close></div>

        <aside class="rp-drawer-panel" role="dialog" aria-modal="true" aria-labelledby="rpDrawerTitle" tabindex="-1">
            <header class="rp-drawer-head">
                <button type="button" class="rp-icon-btn" id="rpDrawerBack" aria-label="Kembali" hidden>
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>

                <div class="rp-drawer-titles">
                    <p class="rp-drawer-kicker" id="rpDrawerKicker">Rincian</p>
                    <h2 class="rp-drawer-title" id="rpDrawerTitle">Memuat…</h2>
                    <p class="rp-drawer-sub" id="rpDrawerSub"></p>
                </div>

                <button type="button" class="rp-icon-btn" data-close aria-label="Tutup rincian">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </header>

            <div class="rp-drawer-tabs" id="rpDrawerTabs" hidden>
                <div class="rp-seg" role="group" aria-label="Jenis rincian">
                    <button type="button" data-tab="trainings" aria-pressed="true">Kegiatan</button>
                    <button type="button" data-tab="participants" aria-pressed="false">Peserta</button>
                </div>
            </div>

            <div class="rp-drawer-summary" id="rpDrawerSummary" hidden></div>
            <p class="rp-drawer-note" id="rpDrawerNote" hidden>Rincian ini mengikuti filter laporan yang sedang aktif.</p>

            <div class="rp-drawer-tools" id="rpDrawerTools" hidden>
                <input type="search" class="rp-mini-search" id="rpDrawerSearch" placeholder="Cari di daftar ini" aria-label="Cari di daftar rincian">
            </div>

            <div class="rp-drawer-body" id="rpDrawerBody" aria-live="polite"></div>
        </aside>
    </div>

    {{-- DIALOG PILIHAN JENIS EKSPOR (dipindah ke <body> oleh script) --}}
    @if ($excelUrl || $pdfUrl)
        <div class="rp-drawer rp-modal" id="rpExport" hidden>
            <div class="rp-drawer-backdrop" data-export-close></div>

            <div class="rp-modal-panel" role="dialog" aria-modal="true" aria-labelledby="rpExportTitle" tabindex="-1">
                <header class="rp-modal-head">
                    <div class="rp-drawer-titles">
                        <p class="rp-drawer-kicker" id="rpExportKicker">Unduh</p>
                        <h2 class="rp-drawer-title" id="rpExportTitle">Unduh Laporan</h2>
                        <p class="rp-drawer-sub">Pilih jenis laporan sebelum mengunduh file.</p>
                    </div>

                    <button type="button" class="rp-icon-btn" data-export-close aria-label="Tutup pilihan unduhan">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </header>

                <div class="rp-modal-body">
                    <p class="rp-modal-filter">
                        <strong>Mengikuti filter aktif:</strong> {{ $exportFilterText }}
                    </p>

                    <div role="radiogroup" aria-label="Jenis ekspor" style="display:grid;gap:12px">
                        <label class="rp-opt is-selected" data-opt="summary">
                            <input type="radio" name="rpExportType" value="summary" checked>
                            <span class="rp-opt-radio" aria-hidden="true"></span>
                            <span class="rp-opt-text">
                                <strong>Laporan Ringkasan</strong>
                                <span>Hanya berisi statistik dan rekapitulasi, termasuk jumlah peserta berdasarkan frekuensi keikutsertaan.</span>
                                <span class="rp-opt-tag safe">Tidak menampilkan identitas peserta satu per satu</span>
                            </span>
                        </label>

                        <label class="rp-opt" data-opt="full">
                            <input type="radio" name="rpExportType" value="full">
                            <span class="rp-opt-radio" aria-hidden="true"></span>
                            <span class="rp-opt-text">
                                <strong>Laporan Lengkap</strong>
                                <span>Berisi seluruh informasi laporan, termasuk daftar kegiatan dan rincian data setiap peserta (nama, NIK, instansi, jumlah keikutsertaan).</span>
                                <span class="rp-opt-tag">Memuat data pribadi peserta</span>
                            </span>
                        </label>
                    </div>
                </div>

                <footer class="rp-modal-foot">
                    <button type="button" class="rp-btn rp-btn-ghost" data-export-close>Batal</button>
                    <a href="#" class="rp-btn rp-btn-primary" id="rpExportGo">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                        <span id="rpExportGoText">Unduh</span>
                    </a>
                </footer>
            </div>
        </div>
    @endif
</div>

<script>
(function () {
    var page = document.getElementById('reportPage');
    if (!page) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var KEY = 'arta-report-scroll';
    var restored = false;

    /* ---------- POSISI SCROLL TETAP (filter, pagination, reset) ---------- */
    try {
        var saved = sessionStorage.getItem(KEY);
        if (saved !== null) {
            sessionStorage.removeItem(KEY);
            window.scrollTo(0, parseInt(saved, 10) || 0);
            restored = true;
        }
    } catch (e) {}

    function remember() {
        try { sessionStorage.setItem(KEY, String(window.scrollY)); } catch (e) {}
    }

    function setBusy() { page.classList.add('rp-busy'); }

    page.querySelectorAll('a[data-keep-scroll], [data-keep-scroll] a[href]').forEach(function (a) {
        a.addEventListener('click', function (ev) {
            if (ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.button === 1) return;
            remember();
            setBusy();
            a.classList.add('is-loading');
        });
    });

    page.querySelectorAll('form[data-keep-scroll]').forEach(function (form) {
        form.addEventListener('submit', function () {
            remember();
            setBusy();
            var btn = form.querySelector('button[type="submit"]');
            if (btn) setTimeout(function () { btn.classList.add('is-loading'); }, 0);
        });
    });

    window.addEventListener('pageshow', function (ev) {
        if (ev.persisted) {
            page.classList.remove('rp-busy');
            page.querySelectorAll('.is-loading').forEach(function (el) { el.classList.remove('is-loading'); });
        }
    });

    /* ---------- PAGINATION LOKAL (mendukung pencarian & isi penuh) ---------- */
    document.querySelectorAll('[data-paginate]').forEach(function (box) {
        var per = parseInt(box.getAttribute('data-paginate'), 10) || 8;
        var selector = box.getAttribute('data-items');
        var fill = box.hasAttribute('data-fill');
        var current = 1;

        function all() { return Array.prototype.slice.call(box.querySelectorAll(selector)); }
        function visible() { return all().filter(function (el) { return !el.hasAttribute('data-nomatch'); }); }

        var nav = document.createElement('div');
        nav.className = 'rp-pager';
        nav.innerHTML =
            '<button type="button" class="rp-btn rp-btn-secondary" data-prev>Sebelumnya</button>' +
            '<span class="rp-pager-info" aria-live="polite"></span>' +
            '<button type="button" class="rp-btn rp-btn-primary" data-next>Selanjutnya</button>';

        var anchor = box;
        var rule = box.getAttribute('data-pager-after') || 'self';
        if (rule.indexOf('closest:') === 0) anchor = box.closest(rule.slice(8)) || box;
        anchor.parentNode.insertBefore(nav, anchor.nextSibling);

        var prev = nav.querySelector('[data-prev]');
        var next = nav.querySelector('[data-next]');
        var info = nav.querySelector('.rp-pager-info');

        function show() {
            var list = visible();
            var pages = Math.max(1, Math.ceil(list.length / per));
            if (current > pages) current = pages;

            var start = (current - 1) * per;
            var end = start + per;

            all().forEach(function (el) { el.hidden = true; el.classList.remove('rp-wide'); });
            list.forEach(function (el, i) { el.hidden = !(i >= start && i < end); });

            if (fill) {
                var onPage = list.slice(start, end);
                if (onPage.length % 2 === 1) onPage[onPage.length - 1].classList.add('rp-wide');
            }

            var filtered = all().some(function (el) { return el.hasAttribute('data-nomatch'); });
            nav.hidden = list.length <= per && !(filtered && list.length === 0);

            info.textContent = list.length
                ? 'Halaman ' + current + ' dari ' + pages + ' · ' + (start + 1) + '–' + Math.min(end, list.length) + ' dari ' + list.length
                : 'Tidak ada hasil';

            prev.disabled = current === 1;
            next.disabled = current === pages;
        }

        prev.addEventListener('click', function () { if (current > 1) { current--; show(); } });
        next.addEventListener('click', function () { current++; show(); });

        box.addEventListener('rp:reorder', function () { current = 1; show(); });
        box.addEventListener('rp:refilter', function () { current = 1; show(); });

        show();
    });

    /* ---------- KOTAK CARI PADA DAFTAR ---------- */
    document.querySelectorAll('[data-filter-target]').forEach(function (input) {
        var target = document.querySelector(input.getAttribute('data-filter-target'));
        var sel = input.getAttribute('data-filter-items');
        if (!target) return;

        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();

            target.querySelectorAll(sel).forEach(function (el) {
                var hay = (el.getAttribute('data-search') || el.textContent).toLowerCase();
                if (q && hay.indexOf(q) === -1) el.setAttribute('data-nomatch', '');
                else el.removeAttribute('data-nomatch');
            });

            target.dispatchEvent(new CustomEvent('rp:refilter'));
        });
    });

    /* ---------- URUTKAN PROFESI ---------- */
    var sortBox = document.getElementById('rpProfSort');
    var profBody = document.getElementById('rpProfBody');
    var profNote = document.getElementById('rpProfNote');

    if (sortBox && profBody) {
        var rows = Array.prototype.slice.call(profBody.querySelectorAll('tr[data-row]'));
        rows.forEach(function (row, index) { row.setAttribute('data-order', index); });

        var labels = {
            'default': 'urutan awal',
            'high': 'jumlah peserta tertinggi ke terendah',
            'low': 'jumlah peserta terendah ke tertinggi'
        };

        var buttons = Array.prototype.slice.call(sortBox.querySelectorAll('button[data-sort]'));

        function applySort(mode) {
            var sorted = rows.slice().sort(function (a, b) {
                var ta = Number(a.getAttribute('data-total')) || 0;
                var tb = Number(b.getAttribute('data-total')) || 0;

                if (mode === 'high' && ta !== tb) return tb - ta;
                if (mode === 'low' && ta !== tb) return ta - tb;

                return Number(a.getAttribute('data-order')) - Number(b.getAttribute('data-order'));
            });

            sorted.forEach(function (row, index) {
                profBody.appendChild(row);
                var number = row.querySelector('.rp-number');
                if (number) number.textContent = index + 1;
            });

            buttons.forEach(function (button) {
                button.setAttribute('aria-pressed', button.getAttribute('data-sort') === mode ? 'true' : 'false');
            });

            if (profNote) {
                var strong = document.createElement('strong');
                strong.textContent = labels[mode];
                profNote.textContent = 'Diurutkan: ';
                profNote.appendChild(strong);
            }

            profBody.dispatchEvent(new CustomEvent('rp:reorder'));
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () { applySort(button.getAttribute('data-sort')); });
        });
    }

    /* ---------- ANGKA INDIKATOR UTAMA ---------- */
    if (!reduce && !restored) {
        var formatter = new Intl.NumberFormat('id-ID');

        page.querySelectorAll('.rp-counter').forEach(function (element) {
            var target = parseInt(element.getAttribute('data-target'), 10) || 0;
            if (target < 1) return;

            var start = null;
            var duration = 900;
            element.textContent = '0';

            requestAnimationFrame(function step(time) {
                if (start === null) start = time;
                var progress = Math.min((time - start) / duration, 1);
                element.textContent = formatter.format(Math.round(target * (1 - Math.pow(1 - progress, 3))));

                if (progress < 1) requestAnimationFrame(step);
                else element.textContent = formatter.format(target);
            });
        });
    }

    /* ---------- SCROLL REVEAL ---------- */
    if (!reduce && 'IntersectionObserver' in window) {
        page.classList.add('rp-js');

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.06 });

        page.querySelectorAll('.rp-reveal').forEach(function (element) { observer.observe(element); });
    }

    /* ---------- NAV AKTIF ---------- */
    var links = Array.prototype.slice.call(document.querySelectorAll('#rpNav a'));

    if ('IntersectionObserver' in window) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    links.forEach(function (link) {
                        link.classList.toggle('active', link.getAttribute('href') === '#' + entry.target.id);
                    });
                }
            });
        }, { rootMargin: '-35% 0px -55% 0px' });

        links.forEach(function (link) {
            var section = document.querySelector(link.getAttribute('href'));
            if (section) spy.observe(section);
        });
    }
})();

/* ======================================================================
   PANEL RINCIAN
   ====================================================================== */
(function () {
    var root = document.getElementById('reportPage');
    var drawer = document.getElementById('rpDrawer');
    if (!root || !drawer) return;

    var endpoint = root.getAttribute('data-endpoint');
    if (!endpoint) return;

    document.body.appendChild(drawer);

    var base = {};
    try { base = JSON.parse(root.getAttribute('data-base') || '{}'); } catch (e) {}

    var panel = drawer.querySelector('.rp-drawer-panel');
    var elKicker = document.getElementById('rpDrawerKicker');
    var elTitle = document.getElementById('rpDrawerTitle');
    var elSub = document.getElementById('rpDrawerSub');
    var elTabs = document.getElementById('rpDrawerTabs');
    var elSummary = document.getElementById('rpDrawerSummary');
    var elNote = document.getElementById('rpDrawerNote');
    var elTools = document.getElementById('rpDrawerTools');
    var elSearch = document.getElementById('rpDrawerSearch');
    var elBody = document.getElementById('rpDrawerBody');
    var btnBack = document.getElementById('rpDrawerBack');
    var tabButtons = Array.prototype.slice.call(elTabs.querySelectorAll('button[data-tab]'));

    // "all" = tanpa pembatasan, jadi bukan filter aktif.
    var hasMinimum = base.minimum !== undefined && base.minimum !== null && base.minimum !== '' && base.minimum !== 'all';
    var filtersActive = !!(base.search || base.district || base.institution || hasMinimum);
    var stack = [];
    var cache = {};
    var token = 0;
    var lastTrigger = null;
    var closeTimer = null;

    var KICKERS = {
        trainings: 'Daftar kegiatan',
        participants: 'Daftar peserta',
        training: 'Info pelatihan',
        participant: 'Info peserta'
    };

    var ICON_GO = '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>';

    function el(tag, cls, text) {
        var node = document.createElement(tag);
        if (cls) node.className = cls;
        if (text !== undefined && text !== null) node.textContent = text;
        return node;
    }

    var nf = new Intl.NumberFormat('id-ID');
    var fmtLong = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    var fmtMonth = new Intl.DateTimeFormat('id-ID', { month: 'short' });

    function toDate(iso) {
        if (!iso) return null;
        var d = new Date(iso + 'T00:00:00');
        return isNaN(d.getTime()) ? null : d;
    }

    function dateRange(start, end) {
        var a = toDate(start);
        if (!a) return '';
        var b = toDate(end);
        if (!b || end === start) return fmtLong.format(a);
        return fmtLong.format(a) + ' – ' + fmtLong.format(b);
    }

    function openDrawer() {
        clearTimeout(closeTimer);
        if (drawer.hidden) {
            drawer.hidden = false;
            var sbw = window.innerWidth - document.documentElement.clientWidth;
            document.documentElement.classList.add('rp-lock');
            if (sbw > 0) document.body.style.paddingRight = sbw + 'px';
        }
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                drawer.classList.add('is-open');
                panel.focus({ preventScroll: true });
            });
        });
    }

    function closeDrawer() {
        token++;
        drawer.classList.remove('is-open');
        closeTimer = setTimeout(function () {
            drawer.hidden = true;
            document.documentElement.classList.remove('rp-lock');
            document.body.style.paddingRight = '';
            stack = [];
            if (lastTrigger && lastTrigger.focus) lastTrigger.focus({ preventScroll: true });
        }, 340);
    }

    function push(payload) {
        stack.push({ payload: payload, data: null });
        load();
    }

    function buildQuery(payload) {
        var params = Object.assign({}, base, payload);
        var qs = new URLSearchParams();
        Object.keys(params).forEach(function (key) {
            if (key === 'tabs') return;
            var value = params[key];
            if (value !== null && value !== undefined && value !== '') qs.set(key, value);
        });
        return qs.toString();
    }

    function load() {
        var entry = stack[stack.length - 1];
        if (!entry) return;

        var query = buildQuery(entry.payload);

        if (cache[query]) {
            entry.data = cache[query];
            render();
            return;
        }

        renderLoading(entry);
        var my = ++token;

        fetch(endpoint + '?' + query, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function (res) {
                if (!res.ok) {
                    return res.json().catch(function () { return {}; }).then(function (j) {
                        throw new Error(j.message || 'Rincian gagal dimuat (kode ' + res.status + ').');
                    });
                }
                return res.json();
            })
            .then(function (data) {
                cache[query] = data;
                if (my !== token) return;
                entry.data = data;
                render();
            })
            .catch(function (err) {
                if (my !== token) return;
                renderError(err.message);
            });
    }

    function setHeader(kicker, title, sub, entry) {
        elKicker.textContent = kicker;
        elTitle.textContent = title;
        elSub.textContent = sub || '';
        btnBack.hidden = stack.length < 2;

        var hasTabs = !!(entry && entry.payload.tabs);
        elTabs.hidden = !hasTabs;
        if (hasTabs) {
            tabButtons.forEach(function (b) {
                b.setAttribute('aria-pressed', b.getAttribute('data-tab') === entry.payload.view ? 'true' : 'false');
            });
        }
    }

    function renderLoading(entry) {
        setHeader(KICKERS[entry.payload.view] || 'Rincian', 'Memuat rincian…', entry.payload.label || '', entry);
        elSummary.hidden = true;
        elNote.hidden = true;
        elTools.hidden = true;
        elBody.textContent = '';

        var skel = el('div', 'rp-skel');
        for (var i = 0; i < 5; i++) skel.appendChild(el('i'));
        elBody.appendChild(skel);
    }

    function renderError(message) {
        elBody.textContent = '';
        var box = el('div', 'rp-state');
        box.appendChild(el('strong', '', 'Tidak dapat memuat rincian'));
        box.appendChild(el('p', '', message));

        var retry = el('button', 'rp-btn rp-btn-primary', 'Coba lagi');
        retry.type = 'button';
        retry.addEventListener('click', load);
        box.appendChild(retry);
        elBody.appendChild(box);
    }

    function trainingItem(r, showCount) {
        var b = el('button', 'rp-li');
        b.type = 'button';
        b.setAttribute('data-q', [r.name, r.funding_source, r.category].join(' ').toLowerCase());

        var d = toDate(r.start_date);
        var dateBox = el('span', 'rp-li-date');
        if (d) {
            dateBox.appendChild(el('b', '', String(d.getDate())));
            dateBox.appendChild(el('small', '', fmtMonth.format(d) + ' ' + d.getFullYear()));
        } else {
            dateBox.appendChild(el('small', '', 'Tanpa tanggal'));
        }

        var main = el('span', 'rp-li-main');
        main.appendChild(el('strong', '', r.name));

        var meta = el('span', 'rp-li-meta');
        if (r.funding_source) meta.appendChild(el('span', 'rp-tag', r.funding_source));
        if (r.category) meta.appendChild(el('span', 'rp-tag alt', r.category));
        var range = dateRange(r.start_date, r.end_date);
        if (range) meta.appendChild(el('span', '', range));
        main.appendChild(meta);

        b.appendChild(dateBox);
        b.appendChild(main);

        if (showCount) {
            var count = el('span', 'rp-li-count');
            count.appendChild(el('b', '', nf.format(r.participants)));
            count.appendChild(el('small', '', 'peserta'));
            b.appendChild(count);
        }

        var go = el('span', 'rp-li-go');
        go.innerHTML = ICON_GO;
        b.appendChild(go);

        b.addEventListener('click', function () {
            push({ view: 'training', training_id: r.id, label: r.name });
        });

        return b;
    }

    function participantItem(r, showCount) {
        var b = el('button', 'rp-li');
        b.type = 'button';
        b.setAttribute('data-q', [r.name, r.nik, r.nip, r.institution, r.district, r.profession].join(' ').toLowerCase());

        b.appendChild(el('span', 'rp-li-avatar', (r.name || '?').charAt(0)));

        var main = el('span', 'rp-li-main');
        main.appendChild(el('strong', '', r.name));

        var meta = el('span', 'rp-li-meta');
        if (r.nik) meta.appendChild(el('span', '', 'NIK ' + r.nik));
        if (r.profession) meta.appendChild(el('span', 'rp-tag alt', r.profession));
        if (r.institution) meta.appendChild(el('span', '', r.institution));
        if (r.district) meta.appendChild(el('span', '', r.district));
        main.appendChild(meta);
        b.appendChild(main);

        if (showCount) {
            var count = el('span', 'rp-li-count');
            count.appendChild(el('b', '', nf.format(r.participations) + '×'));
            count.appendChild(el('small', '', 'ikut'));
            b.appendChild(count);
        }

        var go = el('span', 'rp-li-go');
        go.innerHTML = ICON_GO;
        b.appendChild(go);

        b.addEventListener('click', function () {
            push({ view: 'participant', participant_id: r.id, label: r.name });
        });

        return b;
    }

    function render() {
        var entry = stack[stack.length - 1];
        if (!entry || !entry.data) return;

        var data = entry.data;
        setHeader(KICKERS[data.view] || 'Rincian', data.title, data.subtitle, entry);

        elSummary.textContent = '';
        (data.summary || []).forEach(function (item) {
            var box = el('div', 'rp-sum-item');
            box.appendChild(el('b', '', nf.format(item.value)));
            box.appendChild(el('span', '', item.label));
            elSummary.appendChild(box);
        });
        elSummary.hidden = !(data.summary && data.summary.length);
        elNote.hidden = !filtersActive;

        elBody.textContent = '';
        elBody.scrollTop = 0;

        // Keterangan yang kosong tidak ditampilkan (tanpa tanda "-").
        var infoItems = (data.info || []).filter(function (item) { return item.value; });
        if (infoItems.length) {
            var dl = el('dl', 'rp-info');
            infoItems.forEach(function (item) {
                var row = el('div', String(item.value).length > 60 ? 'wide' : '');
                row.appendChild(el('dt', '', item.label));
                row.appendChild(el('dd', '', item.value));
                dl.appendChild(row);
            });
            elBody.appendChild(dl);
        }

        var rows = data.rows || [];
        var title = el('h3', 'rp-list-title');
        title.appendChild(el('span', '', data.list_title || 'Daftar'));
        title.appendChild(el('small', '', nf.format(data.total || rows.length) + ' data'));
        elBody.appendChild(title);

        if (!rows.length) {
            var empty = el('div', 'rp-state');
            empty.appendChild(el('strong', '', 'Belum ada data'));
            empty.appendChild(el('p', '', 'Tidak ada data yang cocok dengan pilihan ini.'));
            elBody.appendChild(empty);
            elTools.hidden = true;
            return;
        }

        var list = el('div', 'rp-li-wrap');
        rows.forEach(function (r) {
            var item;

            if (data.view === 'trainings' || data.view === 'participant') {
                item = trainingItem(r, data.view === 'trainings');
            } else {
                item = participantItem(r, data.view === 'participants');
            }

            list.appendChild(item);
        });
        elBody.appendChild(list);

        if (data.truncated) {
            elBody.appendChild(el('p', 'rp-state', 'Menampilkan ' + nf.format(rows.length) + ' data pertama dari ' + nf.format(data.total) + '. Persempit filter untuk melihat sisanya.'));
        }

        elSearch.value = '';
        elTools.hidden = rows.length < 8;
    }

    elSearch.addEventListener('input', function () {
        var q = elSearch.value.trim().toLowerCase();
        elBody.querySelectorAll('.rp-li').forEach(function (item) {
            item.hidden = !!q && (item.getAttribute('data-q') || '').indexOf(q) === -1;
        });
    });

    tabButtons.forEach(function (b) {
        b.addEventListener('click', function () {
            var entry = stack[stack.length - 1];
            var view = b.getAttribute('data-tab');
            if (!entry || !entry.payload.tabs || entry.payload.view === view) return;

            entry.payload = Object.assign({}, entry.payload, { view: view });
            entry.data = null;
            load();
        });
    });

    root.addEventListener('click', function (ev) {
        var trigger = ev.target.closest('[data-detail]');
        if (!trigger) return;

        var payload;
        try { payload = JSON.parse(trigger.getAttribute('data-detail')); } catch (e) { return; }

        ev.preventDefault();
        lastTrigger = trigger;
        stack = [];
        openDrawer();
        push(payload);
    });

    drawer.addEventListener('click', function (ev) {
        if (ev.target.closest('[data-close]')) closeDrawer();
    });

    btnBack.addEventListener('click', function () {
        if (stack.length > 1) {
            token++;
            stack.pop();
            render();
        }
    });

    document.addEventListener('keydown', function (ev) {
        if (drawer.hidden) return;

        if (ev.key === 'Escape') {
            ev.preventDefault();
            closeDrawer();
            return;
        }

        if (ev.key === 'Tab') {
            var focusable = Array.prototype.slice.call(
                panel.querySelectorAll('button:not([hidden]):not([disabled]), input:not([hidden])')
            ).filter(function (n) { return n.offsetParent !== null; });

            if (!focusable.length) return;

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (ev.shiftKey && (document.activeElement === first || document.activeElement === panel)) {
                ev.preventDefault();
                last.focus();
            } else if (!ev.shiftKey && document.activeElement === last) {
                ev.preventDefault();
                first.focus();
            }
        }
    });
})();

/* ======================================================================
   DIALOG PILIHAN JENIS EKSPOR
   Tombol unduh membuka dialog; tautan unduhan dibangun dari URL ekspor
   (yang sudah memuat filter aktif) + type=...
   ====================================================================== */
(function () {
    var root = document.getElementById('reportPage');
    var modal = document.getElementById('rpExport');
    if (!root || !modal) return;

    document.body.appendChild(modal);

    var panel = modal.querySelector('.rp-modal-panel');
    var kicker = document.getElementById('rpExportKicker');
    var go = document.getElementById('rpExportGo');
    var goText = document.getElementById('rpExportGoText');
    var options = Array.prototype.slice.call(modal.querySelectorAll('.rp-opt'));
    var triggers = Array.prototype.slice.call(root.querySelectorAll('[data-export]'));

    var baseUrl = '';
    var format = '';
    var supportsType = true;
    var lastTrigger = null;
    var closeTimer = null;

    function selectedType() {
        var checked = modal.querySelector('input[name="rpExportType"]:checked');
        return checked ? checked.value : 'summary';
    }

    function buildUrl() {
        if (!supportsType) return baseUrl;
        return baseUrl + (baseUrl.indexOf('?') === -1 ? '?' : '&') + 'type=' + encodeURIComponent(selectedType());
    }

    function sync() {
        var type = selectedType();

        options.forEach(function (opt) {
            opt.classList.toggle('is-selected', opt.getAttribute('data-opt') === type);
        });

        go.setAttribute('href', buildUrl());
        goText.textContent = 'Unduh ' + (type === 'summary' ? 'Ringkasan' : 'Lengkap') + ' (' + format + ')';
    }

    function openModal(trigger) {
        clearTimeout(closeTimer);

        lastTrigger = trigger;
        baseUrl = trigger.getAttribute('href') || '';
        format = trigger.getAttribute('data-export-label') || '';
        supportsType = trigger.getAttribute('data-export-type-support') !== '0';
        kicker.textContent = 'Unduh ' + format;

        // Jika route PDF lama tidak mengenal "type", paksa pilihan lengkap
        // agar hasil unduhan tidak menyesatkan.
        var inputs = modal.querySelectorAll('input[name="rpExportType"]');
        Array.prototype.forEach.call(inputs, function (input) {
            input.disabled = !supportsType && input.value === 'summary';
            if (!supportsType) input.checked = input.value === 'full';
        });
        if (supportsType) inputs[0].checked = true;

        sync();

        if (modal.hidden) {
            modal.hidden = false;
            var sbw = window.innerWidth - document.documentElement.clientWidth;
            document.documentElement.classList.add('rp-lock');
            if (sbw > 0) document.body.style.paddingRight = sbw + 'px';
        }

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                modal.classList.add('is-open');
                panel.focus({ preventScroll: true });
            });
        });
    }

    function closeModal() {
        modal.classList.remove('is-open');
        closeTimer = setTimeout(function () {
            modal.hidden = true;
            document.documentElement.classList.remove('rp-lock');
            document.body.style.paddingRight = '';
            if (lastTrigger && lastTrigger.focus) lastTrigger.focus({ preventScroll: true });
        }, 300);
    }

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function (ev) {
            if (ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.button === 1) return;
            ev.preventDefault();
            openModal(trigger);
        });
    });

    modal.addEventListener('change', sync);

    modal.addEventListener('click', function (ev) {
        if (ev.target.closest('[data-export-close]')) closeModal();
    });

    // Setelah unduhan dimulai, tutup dialog (halaman tidak berpindah karena file diunduh).
    go.addEventListener('click', function () {
        setTimeout(closeModal, 400);
    });

    document.addEventListener('keydown', function (ev) {
        if (modal.hidden) return;

        if (ev.key === 'Escape') {
            ev.preventDefault();
            closeModal();
            return;
        }

        if (ev.key === 'Tab') {
            var focusable = Array.prototype.slice.call(
                panel.querySelectorAll('button:not([disabled]), a[href], input:not([disabled])')
            ).filter(function (n) { return n.type !== 'radio' || n.checked; });

            if (!focusable.length) return;

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (ev.shiftKey && (document.activeElement === first || document.activeElement === panel)) {
                ev.preventDefault();
                last.focus();
            } else if (!ev.shiftKey && document.activeElement === last) {
                ev.preventDefault();
                first.focus();
            }
        }
    });
})();
</script>

{{-- DIAGRAM DONAT JENIS KELAMIN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('reportGenderChart');
    if (!canvas || typeof Chart === 'undefined') return;

    var male = Number(@json($maleCount));
    var female = Number(@json($femaleCount));
    var total = male + female;
    if (total <= 0) return;

    // Total peserta di tengah donat
    var centerText = {
        id: 'centerText',
        afterDraw: function (chart) {
            var area = chart.chartArea;
            var ctx = chart.ctx;
            var cx = (area.left + area.right) / 2;
            var cy = (area.top + area.bottom) / 2;

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#081B33';
            ctx.font = '800 28px "Sora","Segoe UI",Arial,sans-serif';
            ctx.fillText(total.toLocaleString('id-ID'), cx, cy - 8);
            ctx.fillStyle = '#56657A';
            ctx.font = '600 13px "Plus Jakarta Sans","Segoe UI",Arial,sans-serif';
            ctx.fillText('peserta', cx, cy + 16);
            ctx.restore();
        }
    };

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [male, female],
                backgroundColor: ['#2457D6', '#0E8FB3'],
                borderColor: '#FFFFFF',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '64%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var value = Number(context.raw) || 0;
                            var percentage = (value / total * 100).toFixed(1).replace('.', ',');
                            return context.label + ': ' + value.toLocaleString('id-ID') + ' peserta (' + percentage + '%)';
                        }
                    }
                }
            }
        },
        plugins: [centerText]
    });
});
</script>

@endsection