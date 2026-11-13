@extends('layouts.app')

@section('title', 'Laporan Pelatihan · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* Variabel & dasar: dipakai halaman DAN panel rincian (panel dipindah ke <body>) */
.report-page,.rp-drawer{
    --navy:#081B33; --midnight:#0B2545; --royal:#2457D6; --electric:#3478F6;
    --soft:#EAF2FF; --ice:#F3F7FC; --slate:#64748B; --text:#14243A;
    --border:#E1E9F2; --cyan:#38C6E8; --ease:cubic-bezier(.2,.7,.2,1);
    color:var(--text);
    font-family:"Plus Jakarta Sans","Segoe UI",Arial,sans-serif;
    font-size:16px; line-height:1.65; -webkit-font-smoothing:antialiased;
}
.report-page{position:relative; width:100%; max-width:none; margin:0}
.report-page *,.report-page *::before,.report-page *::after,
.rp-drawer *,.rp-drawer *::before,.rp-drawer *::after{box-sizing:border-box}
.report-page [hidden],.rp-drawer [hidden]{display:none!important}
.rp-drawer[hidden]{display:none!important}
.report-page button,.report-page input,.report-page select,.rp-drawer button,.rp-drawer input{font:inherit}
.report-page a{text-decoration:none}
.report-page svg.i,.rp-drawer svg.i{width:1em;height:1em;flex:none;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.report-page :focus-visible,.rp-drawer :focus-visible{outline:3px solid var(--cyan);outline-offset:3px;border-radius:6px}
html.rp-lock{overflow:hidden}

.rp-shell{width:100%}
.rp-sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

/* progress scroll + loading */
.rp-progress,.rp-load{position:fixed;z-index:1000;top:0;left:0;right:0;pointer-events:none;transform-origin:left}
.rp-progress{height:3px;background:linear-gradient(90deg,var(--royal),var(--cyan));transform:scaleX(0)}
.rp-load{height:4px;opacity:0;background:linear-gradient(90deg,transparent,var(--cyan),var(--electric),transparent);transform:translateX(-100%);transition:opacity .2s}
.rp-busy .rp-load{opacity:1;animation:rp-sweep 1.1s linear infinite}
@keyframes rp-sweep{from{transform:translateX(-100%)}to{transform:translateX(100%)}}

.rp-js .rp-reveal{opacity:0;transform:translate3d(0,22px,0);transition:opacity .7s var(--ease),transform .7s var(--ease)}
.rp-js .rp-reveal.in{opacity:1;transform:none}

/* HERO */
.rp-hero{
    position:relative;isolation:isolate;overflow:hidden;display:grid;
    grid-template-columns:minmax(0,1.2fr) minmax(0,.8fr);align-items:end;
    gap:clamp(24px,4vw,64px);padding:clamp(30px,5vw,64px);border-radius:28px;color:#fff;
    background:linear-gradient(120deg,var(--navy) 0%,var(--midnight) 60%,#173d86 100%);
    animation:rp-rise .8s var(--ease) both;
}
.rp-hero::before{content:"";position:absolute;z-index:-1;right:-90px;top:-110px;width:400px;height:400px;border-radius:50%;background:var(--royal);opacity:.45;filter:blur(60px);animation:rp-drift 24s ease-in-out infinite alternate}
.rp-hero::after{content:"";position:absolute;z-index:-1;left:30%;bottom:-120px;width:260px;height:260px;border-radius:50%;background:var(--cyan);opacity:.14;filter:blur(60px);animation:rp-drift 30s ease-in-out -8s infinite alternate-reverse}
.rp-eyebrow{display:inline-flex;align-items:center;gap:8px;margin-bottom:18px;font-size:13px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--cyan)}
.rp-eyebrow::before{content:"";width:28px;height:2px;background:var(--cyan)}
.rp-hero h1{margin:0 0 18px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-weight:800;font-size:clamp(2.5rem,6.6vw,5.2rem);line-height:1.03;letter-spacing:-.04em}
.rp-hero h1 span{display:block;color:var(--cyan)}
.rp-hero p{max-width:560px;margin:0 0 24px;font-size:16px;line-height:1.8;color:rgba(255,255,255,.84)}
.rp-hero-cta{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px}
.rp-chips{display:flex;flex-wrap:wrap;gap:10px}
.rp-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 15px;border:1px solid rgba(255,255,255,.22);border-radius:999px;background:rgba(255,255,255,.08);font-size:13.5px;font-weight:600;color:#E3EEFB}
.rp-chip .i{font-size:15px;color:var(--cyan)}
.rp-hero-stats{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));border-left:1px solid rgba(255,255,255,.2);padding-left:clamp(20px,3vw,40px)}
.rp-hero-stat{padding:16px 12px 16px 0;min-width:0}
.rp-hero-stat:nth-child(even){border-left:1px solid rgba(255,255,255,.16);padding-left:20px}
.rp-hero-stat small{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;color:rgba(255,255,255,.8)}
.rp-hero-stat small .i{font-size:17px;color:var(--cyan)}
.rp-hero-stat strong{display:block;margin:4px 0 2px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-weight:800;font-size:clamp(2rem,3.8vw,3.2rem);line-height:1.15;letter-spacing:-.04em;font-variant-numeric:tabular-nums;min-width:2ch}
.rp-hero-stat span{font-size:13px;color:rgba(255,255,255,.7)}

/* NAV */
.rp-nav-wrap{position:sticky;top:12px;z-index:50;display:flex;justify-content:flex-start;padding:16px 0 0;pointer-events:none}
.rp-nav{display:flex;gap:4px;max-width:100%;overflow-x:auto;padding:5px;border:1px solid var(--border);border-radius:999px;pointer-events:auto;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);box-shadow:0 10px 30px rgba(8,27,51,.1);scrollbar-width:none}
.rp-nav::-webkit-scrollbar{display:none}
.rp-nav a{padding:8px 16px;border-radius:999px;font-size:13.5px;font-weight:600;color:var(--slate);white-space:nowrap;transition:background .2s,color .2s}
.rp-nav a:hover{color:var(--navy);background:var(--ice)}
.rp-nav a.active{color:#fff;background:var(--royal)}

/* SECTION */
.rp-section{padding-top:clamp(36px,5vw,64px);scroll-margin-top:100px}
.rp-section-head{margin-bottom:22px}
.rp-section-head h2{margin:0 0 8px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-weight:700;font-size:clamp(1.6rem,3.6vw,2.5rem);line-height:1.15;letter-spacing:-.03em;color:var(--navy)}
.rp-section-head p{margin:0;max-width:720px;font-size:16px;color:var(--slate)}
.rp-hint{display:inline-flex;align-items:center;gap:8px;margin-top:12px;padding:7px 14px;border-radius:999px;background:var(--soft);color:var(--royal);font-size:13.5px;font-weight:600}
.rp-hint .i{font-size:16px}
.rp-stack{display:grid;gap:20px}

/* FILTER */
.rp-filter{padding:clamp(18px,2.6vw,28px);border:1px solid var(--border);border-radius:22px;background:#fff;box-shadow:0 14px 36px rgba(8,27,51,.05)}
.rp-filter-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;align-items:end}
.rp-field{min-width:0}
.rp-field label{display:block;margin-bottom:8px;font-size:13.5px;font-weight:700;color:var(--navy)}
.rp-field input,.rp-field select{display:block;width:100%;min-width:0;height:50px;padding:0 14px;color:var(--text);background:var(--ice);border:1.5px solid var(--border);border-radius:12px;font-size:15px;transition:border-color .2s,box-shadow .2s,background .2s}
.rp-field input::placeholder{color:#7a8aa0}
.rp-field input:hover,.rp-field select:hover{border-color:#c5d3e4}
.rp-field input:focus,.rp-field select:focus{outline:none;background:#fff;border-color:var(--electric);box-shadow:0 0 0 4px rgba(52,120,246,.16)}
.rp-filter-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}

/* BUTTON */
.rp-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:0 15px;border:1.5px solid transparent;border-radius:12px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:13px;font-weight:600;white-space:nowrap;cursor:pointer;transition:transform .2s var(--ease),box-shadow .2s,background .2s,color .2s}
.rp-btn .i{font-size:17px}
.rp-btn:active{transform:scale(.97)}
.rp-btn-primary{color:#fff;background:linear-gradient(110deg,var(--royal),var(--electric));box-shadow:0 10px 22px rgba(36,87,214,.28)}
.rp-btn-primary:hover{color:#fff;transform:translateY(-2px);box-shadow:0 14px 28px rgba(36,87,214,.38)}
.rp-btn-secondary{color:var(--royal);background:#fff;border-color:#C5D6F5}
.rp-btn-secondary:hover{color:var(--royal);background:var(--soft);transform:translateY(-2px)}
.rp-btn-ghost{color:var(--slate);background:transparent}
.rp-btn-ghost:hover{color:var(--navy);background:var(--ice)}
.rp-btn-light{color:var(--navy);background:#fff}
.rp-btn-light:hover{color:var(--navy);background:var(--cyan);transform:translateY(-2px)}
.rp-btn-outline-light{color:#fff;background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.3)}
.rp-btn-outline-light:hover{color:#fff;background:rgba(255,255,255,.18);transform:translateY(-2px)}
.rp-btn[disabled]{opacity:.45;cursor:not-allowed;transform:none}
.rp-btn.is-loading{pointer-events:none;opacity:.85}
.rp-btn.is-loading::after{content:"";width:15px;height:15px;border-radius:50%;border:2.5px solid currentColor;border-right-color:transparent;animation:rp-spin .7s linear infinite}
@keyframes rp-spin{to{transform:rotate(360deg)}}

/* ANGKA YANG BISA DIKLIK */
.rp-count,.rp-num{display:inline-flex;align-items:baseline;gap:6px;min-height:32px;padding:3px 11px;border-radius:10px;background:var(--soft);color:var(--royal);font-family:"Sora","Segoe UI",Arial,sans-serif;font-variant-numeric:tabular-nums;line-height:1.4}
.rp-count b,.rp-num b{font-weight:700}
.rp-count small,.rp-num small{font-family:"Plus Jakarta Sans",sans-serif;font-size:11.5px;font-weight:600;color:var(--slate)}
.rp-num{align-items:center;border:1px solid #CFE0FB;cursor:pointer;transition:transform .2s var(--ease),background .2s,color .2s,box-shadow .2s}
.rp-num .rp-go{font-size:13px;opacity:.6;transition:transform .2s var(--ease)}
.rp-num:hover{color:#fff;background:var(--royal);border-color:var(--royal);transform:translateY(-2px);box-shadow:0 8px 18px rgba(36,87,214,.28)}
.rp-num:hover small{color:rgba(255,255,255,.85)}
.rp-num:hover .rp-go{opacity:1;transform:translate(2px,-2px)}
.rp-num:active{transform:scale(.97)}
.rp-num.lg b,.rp-count.lg b{font-size:1.6rem;line-height:1.2}

/* PANEL */
.rp-panel{min-width:0;overflow:hidden;border:1px solid var(--border);border-radius:22px;background:#fff;box-shadow:0 18px 44px rgba(8,27,51,.07)}
.rp-panel-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;padding:18px clamp(18px,2.6vw,26px);border-bottom:1px solid var(--border)}
.rp-panel-top h3{margin:0;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:17px;font-weight:600;color:var(--navy)}
.rp-panel-body{padding:clamp(18px,2.6vw,28px)}
.rp-panel-body h3{margin:0 0 4px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:18px;font-weight:600;color:var(--navy)}
.rp-panel-body>p,.rp-gender-info>p{margin:0 0 18px;font-size:14.5px;color:var(--slate)}
.rp-note{margin:0;padding:14px clamp(18px,2.6vw,26px);border-bottom:1px solid var(--border);background:var(--ice);font-size:14px;line-height:1.75;color:var(--slate)}
.rp-panel-empty{padding:22px 0;font-size:14.5px;color:var(--slate);text-align:center}
.rp-mini-search{display:block;width:min(100%,280px);height:42px;padding:0 14px;color:var(--text);background:var(--ice);border:1.5px solid var(--border);border-radius:12px;font-size:14px;transition:border-color .2s,box-shadow .2s,background .2s}
.rp-mini-search:focus{outline:none;background:#fff;border-color:var(--electric);box-shadow:0 0 0 4px rgba(52,120,246,.16)}

/* SORT */
.rp-seg{display:inline-flex;gap:4px;padding:4px;border:1px solid var(--border);border-radius:12px;background:var(--ice)}
.rp-seg button{display:inline-flex;align-items:center;justify-content:center;gap:6px;min-height:38px;padding:0 14px;border:0;border-radius:9px;background:transparent;color:var(--slate);cursor:pointer;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:13px;font-weight:600;white-space:nowrap}
.rp-seg button:hover{color:var(--navy);background:#fff}
.rp-seg button[aria-pressed="true"]{color:#fff;background:var(--royal);box-shadow:0 6px 14px rgba(36,87,214,.28)}
.rp-sort-note{margin:0;padding:12px clamp(18px,2.6vw,26px);border-bottom:1px solid var(--border);background:var(--ice);font-size:13.5px;color:var(--slate)}
.rp-sort-note strong{color:var(--navy)}

/* GENDER (selebar panel, tanpa ruang kosong) */
.rp-gender-wrap{display:grid;grid-template-columns:minmax(0,300px) minmax(0,1fr);gap:clamp(20px,4vw,48px);align-items:center}
.rp-chart-box{position:relative;width:100%;max-width:300px;height:300px;margin:0 auto}
.rp-gender-list{display:grid;gap:10px;margin:0;padding:0;list-style:none}
.rp-gender-list li{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid var(--border);border-radius:14px;background:var(--ice)}
.rp-dot{width:12px;height:12px;border-radius:50%;flex:none}
.rp-gender-list b{display:block;font-size:14.5px;color:var(--navy)}
.rp-gender-list span{font-size:13.5px;color:var(--slate)}
.rp-gender-list em{margin-left:auto;font-style:normal;font-family:"Sora","Segoe UI",Arial,sans-serif;font-weight:700;font-size:1.5rem;color:var(--navy)}

/* TABEL */
.rp-table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:thin}
.rp-table{width:100%;min-width:650px;border-collapse:collapse;text-align:left}
.rp-table thead th{padding:14px 18px;background:var(--navy);color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;white-space:nowrap}
.rp-table tbody td{padding:14px 18px;border-bottom:1px solid #EBF0F6;font-size:14.5px;color:#33465C;vertical-align:middle}
.rp-table tbody tr{transition:background .2s}
.rp-table tbody tr:hover{background:#F5F9FF}
.rp-table tbody tr:last-child td{border-bottom:0}
.rp-number{width:64px;font-weight:700;color:#5F7088!important;font-variant-numeric:tabular-nums}
.rp-participant{font-weight:700;color:var(--navy)!important}
.rp-nik{color:var(--slate)!important;font-variant-numeric:tabular-nums;white-space:nowrap}
.rp-empty{padding:44px 18px!important;text-align:center;color:var(--slate)!important}
.rp-empty strong{display:block;margin-bottom:4px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:16px;color:var(--navy)}
.rp-detail-link{display:inline-flex;align-items:center;justify-content:center;gap:6px;min-height:36px;padding:0 12px;border:1px solid #C5D6F5;border-radius:9px;color:var(--royal);background:#fff;font-size:12.5px;font-weight:700;white-space:nowrap;cursor:pointer;transition:background .2s,transform .2s}
.rp-detail-link:hover{color:var(--royal);background:var(--soft);transform:translateY(-1px)}

/* chip sumber dana */
.rp-fchips{display:flex;flex-wrap:wrap;gap:6px}
.rp-fchip{display:inline-flex;align-items:center;gap:6px;min-height:30px;padding:2px 11px;border:1px solid transparent;border-radius:999px;font-size:12.5px;font-weight:700;cursor:pointer;transition:transform .2s var(--ease),box-shadow .2s;font-family:inherit}
.rp-fchip b{font-family:"Sora","Segoe UI",Arial,sans-serif;font-variant-numeric:tabular-nums}
.rp-fchip:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(8,27,51,.14)}
span.rp-fchip{cursor:default}
span.rp-fchip:hover{transform:none;box-shadow:none}
.rp-fchip.t0{background:#E6F7FC;color:#0A6C86;border-color:#BFE8F4}
.rp-fchip.t1{background:var(--soft);color:var(--royal);border-color:#CFE0FB}
.rp-fchip.t2{background:#E9EEFF;color:#2B3FB3;border-color:#CBD5FF}
.rp-fchip.t3{background:#E4EBF3;color:var(--navy);border-color:#CBD6E4}

/* PAGINATION LARAVEL */
.rp-pagination{padding:16px clamp(18px,2.6vw,26px);border-top:1px solid var(--border);overflow-x:auto}
.rp-pagination:empty{display:none}
.rp-pagination nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.rp-pagination nav>div:first-child{display:none}
.rp-pagination svg{width:16px;height:16px}
.rp-pagination p{margin:0 14px 0 0;font-size:14px;color:var(--slate)}
.rp-pagination a,.rp-pagination span[aria-current="page"] span,.rp-pagination span[aria-disabled="true"] span{display:inline-flex;align-items:center;justify-content:center;min-width:40px;min-height:40px;padding:6px 13px;border:1px solid var(--border);border-radius:10px;background:#fff;font-size:14px;font-weight:700;color:#506176;transition:.25s var(--ease)}
.rp-pagination a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.rp-pagination span[aria-current="page"] span{color:#fff;background:var(--royal);border-color:var(--royal)}
.rp-pagination span[aria-disabled="true"] span{opacity:.45}

/* PAGINATION LOKAL */
.rp-pager{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px clamp(18px,2.6vw,26px);border-top:1px solid var(--border);background:#fff}
.rp-pager .rp-btn{min-height:42px;padding:0 16px}
.rp-pager-info{font-size:13.5px;font-weight:600;color:var(--slate);text-align:center;font-variant-numeric:tabular-nums}

/* BENTO SUMBER DANA (selalu mengisi baris penuh) */
.rp-bento{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.rp-fund{position:relative;display:flex;flex-direction:column;gap:18px;min-width:0;padding:24px;border:1px solid var(--border);border-radius:22px;background:#fff;overflow:hidden;transition:transform .3s var(--ease),box-shadow .3s}
.rp-fund.span-1{grid-column:span 1}
.rp-fund.span-2{grid-column:span 2}
.rp-fund.span-3{grid-column:span 3}
.rp-fund.span-4{grid-column:span 4}
.rp-fund:hover{transform:translateY(-4px);box-shadow:0 22px 48px rgba(8,27,51,.1)}
.rp-fund-top{display:flex;align-items:center;gap:12px}
.rp-fund-ico{display:grid;place-items:center;width:42px;height:42px;border-radius:12px;background:var(--soft);color:var(--royal);font-size:20px;flex:none}
.rp-fund h3{margin:0;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:1.05rem;font-weight:700;line-height:1.3;color:var(--navy);overflow-wrap:anywhere}
.rp-fund-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.rp-stat{display:flex;flex-direction:column;align-items:flex-start;gap:6px;min-width:0}
.rp-stat>span{font-size:12.5px;font-weight:600;color:var(--slate)}
.rp-fund-foot{margin-top:auto;display:grid;gap:8px}
.rp-fund-bar{height:6px;border-radius:999px;background:var(--soft);overflow:hidden}
.rp-fund-bar i{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg,var(--royal),var(--cyan));transform-origin:left;animation:rp-grow .9s var(--ease) both}
.rp-fund-share{margin:0;font-size:12.5px;color:var(--slate)}
.rp-fund.lead{color:#fff;border-color:transparent;background:linear-gradient(135deg,var(--navy),var(--midnight) 70%,#173d86)}
.rp-fund.lead::after{content:"";position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:var(--electric);opacity:.35;filter:blur(50px);pointer-events:none}
.rp-fund.lead h3{color:#fff;font-size:1.35rem}
.rp-fund.lead .rp-fund-ico{background:rgba(255,255,255,.12);color:var(--cyan)}
.rp-fund.lead .rp-stat>span,.rp-fund.lead .rp-fund-share{color:rgba(255,255,255,.75)}
.rp-fund.lead .rp-fund-bar{background:rgba(255,255,255,.16)}
.rp-fund.lead .rp-num,.rp-fund.lead .rp-count{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.24);color:#fff}
.rp-fund.lead .rp-num:hover{background:var(--cyan);border-color:var(--cyan);color:var(--navy)}
.rp-fund.lead .rp-num small,.rp-fund.lead .rp-count small{color:rgba(255,255,255,.75)}

/* WILAYAH x SUMBER DANA */
.rp-dist-tools{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding:16px clamp(18px,2.6vw,26px);border-bottom:1px solid var(--border);background:var(--ice)}
.rp-dist-tools h3{margin:0;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:17px;font-weight:600;color:var(--navy)}
.rp-dist-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;padding:clamp(14px,2vw,22px)}
.rp-dist-list .rp-wide{grid-column:1/-1}
.rp-dist{min-width:0;border:1px solid var(--border);border-radius:18px;background:#fff;overflow:hidden;transition:box-shadow .3s,transform .3s var(--ease)}
.rp-dist:hover{box-shadow:0 14px 32px rgba(8,27,51,.08);transform:translateY(-2px)}
.rp-dist-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:14px 18px;background:var(--ice);border-bottom:1px solid var(--border)}
.rp-dist-head h4{margin:0;display:flex;align-items:center;gap:8px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:1rem;font-weight:700;color:var(--navy)}
.rp-dist-head h4 .i{color:var(--royal);font-size:18px}
.rp-dist-totals{display:flex;flex-wrap:wrap;gap:6px}
.rp-lines{margin:0;padding:0;list-style:none}
.rp-line{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:8px 12px;padding:12px 18px;border-top:1px solid #EDF1F5}
.rp-line:first-child{border-top:0}
.rp-line-name{min-width:0}
.rp-line-nums{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:6px}

/* DRAWER RINCIAN */
.rp-drawer{position:fixed;inset:0;z-index:9999}
.rp-drawer-backdrop{position:absolute;inset:0;background:rgba(8,27,51,.58);opacity:0;transition:opacity .3s}
.rp-drawer.is-open .rp-drawer-backdrop{opacity:1}
.rp-drawer-panel{position:absolute;top:0;right:0;bottom:0;display:flex;flex-direction:column;width:min(780px,100%);background:#fff;box-shadow:-24px 0 60px rgba(8,27,51,.28);transform:translate3d(100%,0,0);transition:transform .38s var(--ease);outline:none}
.rp-drawer.is-open .rp-drawer-panel{transform:none}
.rp-drawer-head{position:relative;display:flex;align-items:flex-start;gap:12px;padding:20px clamp(16px,3vw,28px);color:#fff;background:linear-gradient(120deg,var(--navy),var(--midnight) 65%,#173d86);flex:none}
.rp-drawer-head::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:3px;background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)}
.rp-drawer-titles{flex:1;min-width:0}
.rp-drawer-kicker{margin:0 0 4px;font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--cyan)}
.rp-drawer-title{margin:0;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:clamp(1.15rem,2.6vw,1.6rem);font-weight:700;line-height:1.25;letter-spacing:-.02em;overflow-wrap:anywhere}
.rp-drawer-sub{margin:6px 0 0;font-size:14px;color:rgba(255,255,255,.78);overflow-wrap:anywhere}
.rp-icon-btn{display:grid;place-items:center;width:42px;height:42px;flex:none;border:1px solid rgba(255,255,255,.25);border-radius:12px;background:rgba(255,255,255,.08);color:#fff;font-size:20px;cursor:pointer;transition:background .2s,transform .2s var(--ease)}
.rp-icon-btn:hover{background:rgba(255,255,255,.2);transform:scale(1.05)}
.rp-drawer-summary{display:flex;flex-wrap:wrap;gap:10px;padding:14px clamp(16px,3vw,28px);border-bottom:1px solid var(--border);background:var(--ice);flex:none}
.rp-sum-item{display:flex;align-items:baseline;gap:8px;padding:6px 14px;border:1px solid var(--border);border-radius:12px;background:#fff}
.rp-sum-item b{font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:1.25rem;font-weight:700;color:var(--royal);font-variant-numeric:tabular-nums}
.rp-sum-item span{font-size:13px;font-weight:600;color:var(--slate)}
.rp-drawer-note{margin:0;padding:10px clamp(16px,3vw,28px);background:#FFF8E6;border-bottom:1px solid #F3E3B0;font-size:13px;color:#7A5B00;flex:none}
.rp-drawer-tools{padding:12px clamp(16px,3vw,28px);border-bottom:1px solid var(--border);flex:none}
.rp-drawer-tools .rp-mini-search{width:100%}
.rp-drawer-body{flex:1;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding:18px clamp(16px,3vw,28px) 32px}
.rp-info{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1px;margin:0 0 22px;border:1px solid var(--border);border-radius:16px;background:var(--border);overflow:hidden}
.rp-info div{padding:12px 16px;background:#fff;min-width:0}
.rp-info dt{margin:0 0 2px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--slate)}
.rp-info dd{margin:0;font-size:14.5px;font-weight:600;color:var(--navy);overflow-wrap:anywhere}
.rp-info .wide{grid-column:1/-1}
.rp-list-title{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:0 0 10px;font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:15px;font-weight:700;color:var(--navy)}
.rp-list-title small{font-family:"Plus Jakarta Sans",sans-serif;font-size:12.5px;font-weight:600;color:var(--slate)}
.rp-li-wrap{display:grid;gap:8px;margin:0;padding:0;list-style:none}
.rp-li{display:flex;align-items:center;gap:12px;width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:14px;background:#fff;text-align:left;cursor:pointer;color:inherit;transition:transform .2s var(--ease),border-color .2s,box-shadow .2s,background .2s}
.rp-li:hover{transform:translateX(3px);border-color:#b9cfec;background:#F7FAFF;box-shadow:0 8px 20px rgba(8,27,51,.07)}
.rp-li-date{display:flex;flex-direction:column;align-items:center;justify-content:center;width:62px;min-height:54px;flex:none;border-radius:12px;background:var(--soft);color:var(--royal);line-height:1.15;text-align:center}
.rp-li-date b{font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:1.25rem}
.rp-li-date small{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.rp-li-avatar{display:grid;place-items:center;width:44px;height:44px;flex:none;border-radius:13px;background:var(--soft);color:var(--royal);font-family:"Sora","Segoe UI",Arial,sans-serif;font-weight:700;text-transform:uppercase}
.rp-li-main{display:flex;flex-direction:column;gap:4px;flex:1;min-width:0}
.rp-li-main strong{font-size:14.5px;line-height:1.4;color:var(--navy);overflow-wrap:anywhere}
.rp-li-meta{display:flex;flex-wrap:wrap;align-items:center;gap:6px 8px;font-size:12.5px;color:var(--slate)}
.rp-tag{display:inline-flex;align-items:center;padding:1px 9px;border-radius:999px;background:var(--soft);color:var(--royal);font-size:12px;font-weight:700}
.rp-tag.alt{background:#E4EBF3;color:var(--navy)}
.rp-li-count{display:flex;flex-direction:column;align-items:flex-end;flex:none;line-height:1.2}
.rp-li-count b{font-family:"Sora","Segoe UI",Arial,sans-serif;font-size:1.15rem;color:var(--royal)}
.rp-li-count small{font-size:11.5px;font-weight:600;color:var(--slate)}
.rp-li-go{flex:none;color:#8aa0bd;font-size:18px;display:grid}
.rp-skel{display:grid;gap:10px}
.rp-skel i{display:block;height:58px;border-radius:14px;background:var(--ice);animation:rp-pulse 1.2s ease-in-out infinite}
.rp-skel i:nth-child(2){animation-delay:.15s}
.rp-skel i:nth-child(3){animation-delay:.3s}
.rp-skel i:nth-child(4){animation-delay:.45s}
@keyframes rp-pulse{0%,100%{opacity:.45}50%{opacity:1}}
.rp-state{padding:36px 10px;text-align:center;color:var(--slate)}
.rp-state strong{display:block;margin-bottom:6px;font-family:"Sora","Segoe UI",Arial,sans-serif;color:var(--navy)}

@keyframes rp-rise{from{opacity:0;transform:translate3d(0,18px,0)}to{opacity:1;transform:none}}
@keyframes rp-drift{to{transform:translate3d(-40px,40px,0)}}
@keyframes rp-grow{from{transform:scaleX(0)}to{transform:scaleX(1)}}

/* RESPONSIVE */
@media (max-width:1200px){
    .rp-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
    .rp-bento{grid-template-columns:repeat(2,minmax(0,1fr))}
    .rp-fund.span-1,.rp-fund.span-2,.rp-fund.span-3,.rp-fund.span-4{grid-column:span 1}
    .rp-fund.lead{grid-column:1/-1}
    .rp-fund:last-child:nth-child(even){grid-column:1/-1}
}
@media (max-width:1100px){
    .rp-hero{grid-template-columns:1fr;align-items:start}
    .rp-hero-stats{border-left:0;padding-left:0;border-top:1px solid rgba(255,255,255,.2);padding-top:8px}
    .rp-dist-list{grid-template-columns:1fr}
}
@media (max-width:700px){
    .rp-hero-stats{grid-template-columns:1fr}
    .rp-hero-stat:nth-child(even){padding-left:0;border-left:0;border-top:1px solid rgba(255,255,255,.16)}
    .rp-filter-grid{grid-template-columns:1fr}
    .rp-filter-actions{display:grid;grid-template-columns:1fr}
    .rp-filter-actions .rp-btn,.rp-hero-cta .rp-btn,.rp-detail-link{width:100%}
    .rp-pager .rp-btn{width:auto}
    .rp-bento{grid-template-columns:1fr}
    .rp-fund.span-1,.rp-fund.span-2,.rp-fund.span-3,.rp-fund.span-4,.rp-fund.lead,.rp-fund:last-child:nth-child(even){grid-column:auto}
    .rp-fund-stats{grid-template-columns:repeat(3,minmax(0,1fr))}
    .rp-gender-wrap{grid-template-columns:1fr}
    .rp-seg{width:100%}
    .rp-seg button{flex:1;padding:0 8px}
    .rp-mini-search{width:100%}
    .rp-pagination nav{justify-content:flex-start}
    .rp-line{grid-template-columns:1fr}
    .rp-line-nums{justify-content:flex-start}
    .rp-info{grid-template-columns:1fr}

    .rp-drawer-panel{width:100%;transform:translate3d(0,100%,0)}

    .rp-table-wrap.cards{overflow:visible}
    .rp-table-wrap.cards .rp-table{display:block;min-width:0!important}
    .rp-table-wrap.cards thead{display:none}
    .rp-table-wrap.cards tbody{display:grid;gap:12px;padding:12px}
    .rp-table-wrap.cards tbody tr{display:block;overflow:hidden;border:1px solid var(--border);border-radius:14px}
    .rp-table-wrap.cards tbody td{display:grid;grid-template-columns:118px minmax(0,1fr);gap:10px;align-items:center;padding:11px 14px;font-size:14.5px;border-bottom:1px solid #EDF1F5}
    .rp-table-wrap.cards tbody td[data-label]::before{content:attr(data-label);font-size:13px;font-weight:600;color:var(--slate)}
    .rp-table-wrap.cards tbody tr td:last-child{border-bottom:0}
    .rp-table-wrap.cards .rp-number{width:auto}
    .rp-table-wrap.cards td.rp-empty{display:block}
}
@media (prefers-reduced-motion:reduce){
    .report-page *,.report-page *::before,.report-page *::after,.rp-drawer *{animation:none!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
    .rp-js .rp-reveal{opacity:1;transform:none}
    .rp-progress{display:none}
}
</style>

@php
    $fundingRecap = collect($fundingRecap ?? []);
    $categoryRecap = collect($categoryRecap ?? []);
    $trainingRecap = collect($trainingRecap ?? []);
    $districtFundingRecap = collect($districtFundingRecap ?? []);
    $districtFundingTotals = collect($districtFundingTotals ?? []);
    $nakesByType = collect($nakesByType ?? []);

    $years = collect($years ?? []);
    $districts = collect($districts ?? []);
    $institutions = collect($institutions ?? []);
    $participants = $participants ?? collect();

    $year = $year ?? now()->year;
    $minimum = $minimum ?? 1;

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
    $baseParams = [
        'year' => $year,
        'search' => $search,
        'district' => $districtFilter,
        'institution' => $institutionFilter,
    ];

    // Ekspor mencakup seluruh isi laporan sesuai filter aktif.
    $exportParams = array_filter([
        'year' => $year,
        'minimum' => $minimum,
        'search' => $search,
        'district' => $districtFilter,
        'institution' => $institutionFilter,
    ], fn ($v) => $v !== '' && $v !== null);

    $excelUrl = \Illuminate\Support\Facades\Route::has('reports.export') ? route('reports.export', $exportParams) : null;
    $pdfUrl = \Illuminate\Support\Facades\Route::has('reports.pdf')
        ? route('reports.pdf', $exportParams)
        : (\Illuminate\Support\Facades\Route::has('pdf.report') ? route('pdf.report', $exportParams) : null);

    // Lebar tiap kartu sumber dana agar setiap baris selalu penuh.
    $fundCount = $fundingRecap->count();
    if ($fundCount <= 1) {
        $spans = [4];
    } elseif ($fundCount === 2) {
        $spans = [2, 2];
    } else {
        $spans = [2, 1, 1];
        $rest = $fundCount - 3;
        while ($rest > 0) {
            $row = min(4, $rest);
            $spans = array_merge($spans, match ($row) {
                1 => [4],
                2 => [2, 2],
                3 => [2, 1, 1],
                default => [1, 1, 1, 1],
            });
            $rest -= $row;
        }
    }

    $numBtn = function ($value, string $unit, array $payload, bool $showUnit = false, string $extra = '') use ($hasDetailRoute, $formatNumber) {
        $text = $formatNumber($value);
        $unitHtml = $showUnit ? '<small>' . e($unit) . '</small>' : '';

        if (!$hasDetailRoute || (int) $value < 1) {
            return new \Illuminate\Support\HtmlString(
                '<span class="rp-count ' . e($extra) . '"><b>' . e($text) . '</b>' . $unitHtml . '</span>'
            );
        }

        return new \Illuminate\Support\HtmlString(
            '<button type="button" class="rp-num ' . e($extra) . '" data-detail="'
            . e(json_encode($payload, JSON_UNESCAPED_UNICODE)) . '" aria-haspopup="dialog" aria-label="'
            . e($text . ' ' . $unit . ', buka rincian') . '"><b>' . e($text) . '</b>' . $unitHtml
            . '<svg class="i rp-go" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg></button>'
        );
    };

    $tone = fn ($label) => 't' . (abs(crc32((string) $label)) % 4);
    $chipBtn = function (string $label, $count, array $payload) use ($hasDetailRoute, $formatNumber, $tone) {
        $inner = e($label) . ' <b>' . e($formatNumber($count)) . '</b>';

        if (!$hasDetailRoute) {
            return new \Illuminate\Support\HtmlString('<span class="rp-fchip ' . $tone($label) . '">' . $inner . '</span>');
        }

        return new \Illuminate\Support\HtmlString(
            '<button type="button" class="rp-fchip ' . $tone($label) . '" data-detail="'
            . e(json_encode($payload, JSON_UNESCAPED_UNICODE)) . '" aria-haspopup="dialog" aria-label="'
            . e($label . ': ' . $formatNumber($count) . ' kegiatan, buka rincian') . '">' . $inner . '</button>'
        );
    };

    $districtGroups = $districtFundingRecap->groupBy('district');
    $districtBlocks = $districtFundingTotals
        ->sortByDesc(fn ($row) => (int) $row->total_participations)
        ->values();
@endphp

<div class="rp-progress" id="rpProgress" aria-hidden="true"></div>
<div class="rp-load" aria-hidden="true"></div>

<div class="report-page" id="reportPage"
     data-endpoint="{{ $endpoint }}"
     data-base="{{ json_encode($baseParams, JSON_UNESCAPED_UNICODE) }}">
    <div class="rp-shell">

        {{-- HERO --}}
        <section class="rp-hero" aria-labelledby="rp-title">
            <div>
                <div class="rp-eyebrow">ARTA · Laporan Pelatihan</div>

                <h1 id="rp-title">
                    Laporan riwayat
                    <span>pelatihan.</span>
                </h1>

                <p>
                    Pantau frekuensi keikutsertaan, rincian sumber dana, kegiatan per pelatihan,
                    dan rekap wilayah. Klik angka mana saja untuk melihat siapa saja pesertanya.
                </p>

                <div class="rp-hero-cta">
                    @if ($excelUrl)
                        <a href="{{ $excelUrl }}" class="rp-btn rp-btn-light">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                            Ekspor Excel
                        </a>
                    @endif

                    @if ($pdfUrl)
                        <a href="{{ $pdfUrl }}" class="rp-btn rp-btn-outline-light">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h8"/></svg>
                            Ekspor PDF
                        </a>
                    @endif
                </div>

                <div class="rp-chips">
                    <span class="rp-chip">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4m8-4v4M3 10h18"/></svg>
                        Tahun {{ $year }}
                    </span>

                    <span class="rp-chip">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
                        Minimal {{ $minimum }} pelatihan per peserta
                    </span>

                    <span class="rp-chip">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                        Ekspor memuat seluruh isi laporan
                    </span>
                </div>
            </div>

            <div class="rp-hero-stats">
                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
                        Peserta sudah dilatih
                    </small>
                    <strong class="rp-counter" data-target="{{ $totalTrainedParticipants }}">{{ $formatNumber($totalTrainedParticipants) }}</strong>
                    <span>Jumlah peserta unik</span>
                </div>

                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        Total kegiatan pelatihan
                    </small>
                    <strong class="rp-counter" data-target="{{ $totalTrainings }}">{{ $formatNumber($totalTrainings) }}</strong>
                    <span>Jumlah kegiatan tahun {{ $year }}</span>
                </div>

                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h18v16H3z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg>
                        Total keikutsertaan
                    </small>
                    <strong class="rp-counter" data-target="{{ $totalParticipations }}">{{ $formatNumber($totalParticipations) }}</strong>
                    <span>Total riwayat peserta tahun {{ $year }}</span>
                </div>
            </div>
        </section>

        {{-- NAVIGASI --}}
        <div class="rp-nav-wrap">
            <nav class="rp-nav" aria-label="Navigasi laporan" id="rpNav">
                <a href="#filter" class="active">Filter</a>
                <a href="#ringkasan">Ringkasan</a>
                <a href="#sumber-dana">Sumber Dana</a>
                <a href="#pelatihan">Pelatihan</a>
                <a href="#kategori">Kategori</a>
                <a href="#wilayah-dana">Wilayah &amp; Dana</a>
                <a href="#peserta">Peserta</a>
            </nav>
        </div>

        {{-- FILTER --}}
        <section class="rp-section rp-reveal" id="filter" aria-labelledby="rp-filter-title">
            <div class="rp-section-head">
                <h2 id="rp-filter-title">Filter laporan</h2>
                <p>Pilih tahun pelatihan, jumlah keikutsertaan, kabupaten/kota, instansi, atau kata kunci peserta.</p>
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
                            <label for="minimum">Keikutsertaan</label>
                            <select id="minimum" name="minimum">
                                @foreach ([1, 2, 3, 4, 5, 10] as $value)
                                    <option value="{{ $value }}" @selected((int) $minimum === $value)> {{ $value }}  kali</option>
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
                            <label for="search">Pencarian Peserta</label>
                            <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, atau NIP">
                        </div>

                        <div class="rp-filter-actions">
                            <button type="submit" class="rp-btn rp-btn-primary">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                                Terapkan Filter
                            </button>

                            <a href="{{ route('reports.index') }}" class="rp-btn rp-btn-ghost" data-keep-scroll>
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                Reset Filter
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        {{-- RINGKASAN --}}
        <section class="rp-section rp-reveal" id="ringkasan" aria-labelledby="rp-sum-title">
            <div class="rp-section-head">
                <h2 id="rp-sum-title">Ringkasan peserta</h2>
                <p>Komposisi peserta berdasarkan jenis kelamin dan profesi pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-stack">

                <div class="rp-panel">
                    <div class="rp-panel-body">
                        @if ($genderTotal > 0)
                            <div class="rp-gender-wrap">
                                <div class="rp-chart-box">
                                    <canvas id="reportGenderChart" role="img"
                                        aria-label="Diagram peserta laki-laki {{ $maleCount }} orang dan perempuan {{ $femaleCount }} orang"></canvas>
                                </div>

                                <div class="rp-gender-info">
                                    <h3>Jenis kelamin peserta</h3>
                                    <p>
                                        Total peserta dengan jenis kelamin tercatat:
                                        <strong>{{ $formatNumber($genderTotal) }} orang</strong>
                                    </p>

                                    <ul class="rp-gender-list">
                                        <li>
                                            <span class="rp-dot" style="background:#2457D6" aria-hidden="true"></span>
                                            <div>
                                                <b>Laki-laki</b>
                                                <span>{{ $formatNumber($maleCount) }} orang</span>
                                            </div>
                                            <em>{{ number_format($malePercent,1,',','.') }}%</em>
                                        </li>

                                        <li>
                                            <span class="rp-dot" style="background:#0E8FB3" aria-hidden="true"></span>
                                            <div>
                                                <b>Perempuan</b>
                                                <span>{{ $formatNumber($femaleCount) }} orang</span>
                                            </div>
                                            <em>{{ number_format($femalePercent,1,',','.') }}%</em>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <h3>Jenis kelamin peserta</h3>
                            <div class="rp-panel-empty">Belum ada data jenis kelamin pada tahun {{ $year }}.</div>
                        @endif
                    </div>
                </div>

                <div class="rp-panel">
                    <div class="rp-panel-top">
                        <h3>Total peserta berdasarkan profesi</h3>

                        @if ($nakesByType->isNotEmpty())
                            <div class="rp-seg" role="group" aria-label="Urutkan profesi" id="rpProfSort">
                                <button type="button" data-sort="default" aria-pressed="true">Urutan awal</button>
                                <button type="button" data-sort="high" aria-pressed="false">Tertinggi</button>
                                <button type="button" data-sort="low" aria-pressed="false">Terendah</button>
                            </div>
                        @endif
                    </div>

                    @if ($nakesByType->isNotEmpty())
                        <p class="rp-sort-note" id="rpProfNote" aria-live="polite">Diurutkan: <strong>urutan awal</strong></p>
                    @endif

                    <div class="rp-table-wrap cards">
                        <table class="rp-table">
                            <caption class="rp-sr">Jumlah peserta unik per profesi tahun {{ $year }}</caption>
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Jenis Profesi</th>
                                    <th scope="col">Peserta Terlatih</th>
                                </tr>
                            </thead>
                            <tbody id="rpProfBody" data-paginate="8" data-items="tr[data-row]" data-pager-after="closest:.rp-table-wrap">
                                @forelse ($nakesByType as $index => $profession)
                                    <tr data-row data-total="{{ (int) $profession->total }}">
                                        <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>
                                        <td class="rp-participant" data-label="Profesi">{{ $profession->name }}</td>
                                        <td data-label="Peserta">
                                            <span class="rp-count"><b>{{ $formatNumber($profession->total) }}</b></span>
                                        </td>
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

        {{-- RINCIAN SUMBER DANA (BENTO) --}}
        <section class="rp-section rp-reveal" id="sumber-dana" aria-labelledby="rp-fund-title">
            <div class="rp-section-head">
                <h2 id="rp-fund-title">Rincian sumber dana</h2>
                <p>
                    Jumlah kegiatan, peserta unik, dan keikutsertaan untuk setiap sumber pembiayaan
                    (APBD, APBN, mitra, dan lainnya) tahun {{ $year }}.
                </p>
                @if ($hasDetailRoute)
                    <span class="rp-hint">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        Klik angka untuk melihat daftar kegiatan dan pesertanya
                    </span>
                @endif
            </div>

            @if ($fundingRecap->isEmpty())
                <div class="rp-panel"><div class="rp-panel-body"><div class="rp-panel-empty">Belum ada data sumber dana pada tahun {{ $year }}.</div></div></div>
            @else
                <div class="rp-bento">
                    @foreach ($fundingRecap as $index => $funding)
                        @php
                            $fundingLabel = 'Sumber dana: ' . $funding->funding_source;
                            $share = round(((int) $funding->total_trainings / $fundingSum) * 100);
                        @endphp

                        <article class="rp-fund span-{{ $spans[$index] ?? 1 }} {{ $index === 0 ? 'lead' : '' }}">
                            <div class="rp-fund-top">
                                <span class="rp-fund-ico" aria-hidden="true">
                                    <svg class="i" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </span>
                                <h3>{{ $funding->funding_source }}</h3>
                            </div>

                            <div class="rp-fund-stats">
                                <div class="rp-stat">
                                    <span>Kegiatan</span>
                                    {{ $numBtn($funding->total_trainings, 'kegiatan', ['view' => 'trainings', 'funding_source' => $funding->funding_source, 'label' => $fundingLabel], false, 'lg') }}
                                </div>
                                <div class="rp-stat">
                                    <span>Peserta unik</span>
                                    {{ $numBtn($funding->total_participants, 'peserta unik', ['view' => 'participants', 'funding_source' => $funding->funding_source, 'label' => $fundingLabel], false, 'lg') }}
                                </div>
                                <div class="rp-stat">
                                    <span>Keikutsertaan</span>
                                    {{ $numBtn($funding->total_participations, 'keikutsertaan', ['view' => 'participants', 'funding_source' => $funding->funding_source, 'label' => $fundingLabel], false, 'lg') }}
                                </div>
                            </div>

                            <div class="rp-fund-foot">
                                <div class="rp-fund-bar" aria-hidden="true">
                                    <i style="width:{{ max(3, round(((int) $funding->total_trainings / $fundingMax) * 100)) }}%"></i>
                                </div>
                                <p class="rp-fund-share">{{ $share }}% dari seluruh kegiatan pada rekap ini</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- REKAP PER PELATIHAN --}}
        <section class="rp-section rp-reveal" id="pelatihan" aria-labelledby="rp-train-title">
            <div class="rp-section-head">
                <h2 id="rp-train-title">Rekap per pelatihan</h2>
                <p>
                    Satu jenis pelatihan bisa punya banyak kegiatan dengan sumber dana berbeda.
                    Klik jumlah kegiatan untuk melihat tanggal, sumber dana, dan pesertanya.
                </p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top">
                    <h3>Jumlah kegiatan per nama pelatihan</h3>
                    <input type="search" class="rp-mini-search" placeholder="Cari nama pelatihan"
                           aria-label="Cari nama pelatihan"
                           data-filter-target="#rpTrainBody" data-filter-items="tr[data-row]">
                </div>

                <div class="rp-table-wrap cards">
                    <table class="rp-table" style="min-width:900px">
                        <caption class="rp-sr">Rekap kegiatan per nama pelatihan tahun {{ $year }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Pelatihan</th>
                                <th scope="col">Kegiatan</th>
                                <th scope="col">Sumber Dana</th>
                                <th scope="col">Peserta Unik</th>
                                <th scope="col">Keikutsertaan</th>
                            </tr>
                        </thead>
                        <tbody id="rpTrainBody" data-paginate="10" data-items="tr[data-row]" data-pager-after="closest:.rp-table-wrap">
                            @forelse ($trainingRecap as $index => $training)
                                @php $trainLabel = 'Pelatihan: ' . $training->name; @endphp
                                <tr data-row data-search="{{ mb_strtolower($training->name) }}">
                                    <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>

                                    <td class="rp-participant" data-label="Nama Pelatihan">{{ $training->name }}</td>

                                    <td data-label="Kegiatan">
                                        {{ $numBtn($training->total_trainings, 'kegiatan', ['view' => 'trainings', 'training_name' => $training->name, 'label' => $trainLabel], true) }}
                                    </td>

                                    <td data-label="Sumber Dana">
                                        <div class="rp-fchips">
                                            @foreach ($training->funding as $fund)
                                                {{ $chipBtn($fund->funding_source, $fund->total_trainings, [
                                                    'view' => 'trainings',
                                                    'training_name' => $training->name,
                                                    'funding_source' => $fund->funding_source,
                                                    'label' => $trainLabel . ' · ' . $fund->funding_source,
                                                ]) }}
                                            @endforeach
                                        </div>
                                    </td>

                                    <td data-label="Peserta Unik">
                                        {{ $numBtn($training->total_participants, 'peserta unik', ['view' => 'participants', 'training_name' => $training->name, 'label' => $trainLabel]) }}
                                    </td>

                                    <td data-label="Keikutsertaan">
                                        {{ $numBtn($training->total_participations, 'keikutsertaan', ['view' => 'participants', 'training_name' => $training->name, 'label' => $trainLabel]) }}
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

        {{-- KATEGORI --}}
        <section class="rp-section rp-reveal" id="kategori">
            <div class="rp-section-head">
                <h2>Rekap kategori pelatihan</h2>
                <p>Jumlah kegiatan, peserta unik, dan keikutsertaan berdasarkan kategori pelatihan.</p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top"><h3>Kegiatan berdasarkan kategori</h3></div>

                <div class="rp-table-wrap cards">
                    <table class="rp-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kategori Pelatihan</th>
                                <th>Jumlah Kegiatan</th>
                                <th>Peserta Unik</th>
                                <th>Keikutsertaan</th>
                                <th>Rincian</th>
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
                                    <td data-label="Jumlah Kegiatan">
                                        {{ $numBtn($category->total_trainings, 'kegiatan', ['view' => 'trainings', 'category_id' => $catId, 'label' => $catLabel]) }}
                                    </td>
                                    <td data-label="Peserta Unik">
                                        {{ $numBtn($category->total_participants, 'peserta unik', ['view' => 'participants', 'category_id' => $catId, 'label' => $catLabel]) }}
                                    </td>
                                    <td data-label="Keikutsertaan">
                                        {{ $numBtn($category->total_participations, 'keikutsertaan', ['view' => 'participants', 'category_id' => $catId, 'label' => $catLabel]) }}
                                    </td>
                                    <td data-label="Rincian">
                                        @if ($hasDetailRoute)
                                            <button type="button" class="rp-detail-link"
                                                data-detail="{{ json_encode(['view' => 'trainings', 'category_id' => $catId, 'label' => $catLabel], JSON_UNESCAPED_UNICODE) }}"
                                                aria-haspopup="dialog">Lihat kegiatan</button>
                                        @elseif ($hasCategoryDetailRoute && $category->category_id)
                                            <a class="rp-detail-link" href="{{ route('reports.category-details', ['categoryId' => $category->category_id, 'year' => $year]) }}">Lihat kegiatan</a>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="rp-empty">Belum ada data kategori pelatihan pada tahun {{ $year }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- WILAYAH x SUMBER DANA --}}
        <section class="rp-section rp-reveal" id="wilayah-dana" aria-labelledby="rp-dfund-title">
            <div class="rp-section-head">
                <h2 id="rp-dfund-title">Rekap wilayah berdasarkan sumber dana</h2>
                <p>
                    Setiap kabupaten/kota menampilkan rincian per sumber dana,
                    misalnya APBD sekian, APBN sekian, dan mitra sekian.
                </p>
            </div>

            <div class="rp-panel">
                <div class="rp-dist-tools">
                    <h3>Keikutsertaan per kabupaten/kota</h3>
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
                                    <div class="rp-dist-totals">
                                        {{ $numBtn($block->total_trainings, 'kegiatan', ['view' => 'trainings', 'scope_district' => $distName, 'label' => $distLabel], true) }}
                                        {{ $numBtn($block->total_participations, 'ikut', ['view' => 'participants', 'scope_district' => $distName, 'label' => $distLabel], true) }}
                                    </div>
                                </header>

                                <ul class="rp-lines">
                                    @foreach ($lines as $line)
                                        @php $lineLabel = $distLabel . ' · ' . $line->funding_source; @endphp
                                        <li class="rp-line">
                                            <div class="rp-line-name">
                                                <span class="rp-fchip {{ $tone($line->funding_source) }}">{{ $line->funding_source }}</span>
                                            </div>
                                            <div class="rp-line-nums">
                                                {{ $numBtn($line->total_trainings, 'kegiatan', ['view' => 'trainings', 'scope_district' => $distName, 'funding_source' => $line->funding_source, 'label' => $lineLabel], true) }}
                                                {{ $numBtn($line->total_participants, 'peserta', ['view' => 'participants', 'scope_district' => $distName, 'funding_source' => $line->funding_source, 'label' => $lineLabel], true) }}
                                                {{ $numBtn($line->total_participations, 'ikut', ['view' => 'participants', 'scope_district' => $distName, 'funding_source' => $line->funding_source, 'label' => $lineLabel], true) }}
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

        {{-- DAFTAR PESERTA --}}
        <section class="rp-section rp-reveal" id="peserta" aria-labelledby="rp-freq-title">
            <div class="rp-section-head">
                <h2 id="rp-freq-title">Jumlah pelatihan per peserta</h2>
                <p>
                    Jumlah keikutsertaan setiap peserta pada tahun {{ $year }} sesuai filter.
                    Klik angka keikutsertaan untuk melihat pelatihan apa saja yang diikuti.
                </p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top">
                    <h3>Daftar peserta</h3>

                    <span class="rp-count">
                        <b>{{ $formatNumber(method_exists($participants, 'total') ? $participants->total() : $participants->count()) }}</b>
                        <small>peserta</small>
                    </span>
                </div>

                <p class="rp-note">
                    Daftar peserta menampilkan nama, NIK, instansi, Kabupaten/Kota, dan jumlah keikutsertaan.
                    Satu peserta dapat mengikuti beberapa kegiatan dan dihitung satu kali pada peserta unik.
                </p>

                <div class="rp-table-wrap cards">
                    <table class="rp-table" style="min-width:950px">
                        <caption class="rp-sr">Data peserta dan jumlah keikutsertaan pelatihan tahun {{ $year }}</caption>

                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Peserta</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Instansi</th>
                                <th scope="col">Kabupaten/Kota</th>
                                <th scope="col">Jumlah Keikutsertaan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($participants as $participant)
                                <tr>
                                    <td class="rp-number" data-label="No.">{{ $participants->firstItem() + $loop->index }}</td>
                                    <td class="rp-participant" data-label="Nama Peserta">{{ $participant->name ?: '-' }}</td>
                                    <td class="rp-nik" data-label="NIK">{{ $participant->nik ?: '-' }}</td>
                                    <td data-label="Instansi">{{ $participant->institution ?: '-' }}</td>
                                    <td data-label="Kabupaten/Kota">{{ $participant->district ?: '-' }}</td>
                                    <td data-label="Jumlah Keikutsertaan">
                                        {{ $numBtn($participant->participations_count, 'keikutsertaan', ['view' => 'participant', 'participant_id' => $participant->id, 'label' => $participant->name ?: 'Peserta'], true) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="rp-empty">
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

    </div>

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

            <div class="rp-drawer-summary" id="rpDrawerSummary" hidden></div>
            <p class="rp-drawer-note" id="rpDrawerNote" hidden>Rincian ini mengikuti filter laporan yang sedang aktif.</p>

            <div class="rp-drawer-tools" id="rpDrawerTools" hidden>
                <input type="search" class="rp-mini-search" id="rpDrawerSearch" placeholder="Cari di daftar ini" aria-label="Cari di daftar rincian">
            </div>

            <div class="rp-drawer-body" id="rpDrawerBody" aria-live="polite"></div>
        </aside>
    </div>
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

    /* ---------- ANGKA HERO ---------- */
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
        }, { threshold: 0.08 });

        page.querySelectorAll('.rp-reveal').forEach(function (element) { observer.observe(element); });
    }

    /* ---------- PROGRESS BAR ---------- */
    var bar = document.getElementById('rpProgress');
    var ticking = false;

    function updateBar() {
        var height = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.transform = 'scaleX(' + (height > 0 ? Math.min(window.scrollY / height, 1) : 0) + ')';
        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(updateBar); }
    }, { passive: true });
    updateBar();

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
   PANEL RINCIAN (klik angka -> daftar kegiatan / peserta / info)
   ====================================================================== */
(function () {
    var root = document.getElementById('reportPage');
    var drawer = document.getElementById('rpDrawer');
    if (!root || !drawer) return;

    var endpoint = root.getAttribute('data-endpoint');
    if (!endpoint) return;

    // Pindahkan ke <body>: position:fixed tidak lagi terpotong oleh area konten layout.
    document.body.appendChild(drawer);

    var base = {};
    try { base = JSON.parse(root.getAttribute('data-base') || '{}'); } catch (e) {}

    var panel = drawer.querySelector('.rp-drawer-panel');
    var elKicker = document.getElementById('rpDrawerKicker');
    var elTitle = document.getElementById('rpDrawerTitle');
    var elSub = document.getElementById('rpDrawerSub');
    var elSummary = document.getElementById('rpDrawerSummary');
    var elNote = document.getElementById('rpDrawerNote');
    var elTools = document.getElementById('rpDrawerTools');
    var elSearch = document.getElementById('rpDrawerSearch');
    var elBody = document.getElementById('rpDrawerBody');
    var btnBack = document.getElementById('rpDrawerBack');

    var filtersActive = !!(base.search || base.district || base.institution);
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

    /* ----- buka / tutup ----- */
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

    /* ----- ambil data ----- */
    function push(payload) {
        stack.push({ payload: payload, data: null });
        load();
    }

    function buildQuery(payload) {
        var params = Object.assign({}, base, payload);
        var qs = new URLSearchParams();
        Object.keys(params).forEach(function (key) {
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

    /* ----- tampilan ----- */
    function setHeader(kicker, title, sub) {
        elKicker.textContent = kicker;
        elTitle.textContent = title;
        elSub.textContent = sub || '';
        btnBack.hidden = stack.length < 2;
    }

    function renderLoading(entry) {
        setHeader(KICKERS[entry.payload.view] || 'Rincian', 'Memuat rincian…', entry.payload.label || '');
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
        b.setAttribute('data-q', [r.name, r.funding_source, r.category, r.location].join(' ').toLowerCase());

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
        if (r.location) meta.appendChild(el('span', '', r.location));
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
        setHeader(KICKERS[data.view] || 'Rincian', data.title, data.subtitle);

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

        if (data.info && data.info.length) {
            var dl = el('dl', 'rp-info');
            data.info.forEach(function (item) {
                var row = el('div', (item.value || '').length > 60 ? 'wide' : '');
                row.appendChild(el('dt', '', item.label));
                row.appendChild(el('dd', '', item.value || '-'));
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
            empty.appendChild(el('p', '', 'Tidak ada data yang cocok dengan angka ini.'));
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

    /* ----- cari di dalam daftar rincian ----- */
    elSearch.addEventListener('input', function () {
        var q = elSearch.value.trim().toLowerCase();
        elBody.querySelectorAll('.rp-li').forEach(function (item) {
            item.hidden = !!q && (item.getAttribute('data-q') || '').indexOf(q) === -1;
        });
    });

    /* ----- event ----- */
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
</script>

{{-- CHART JENIS KELAMIN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('reportGenderChart');
    if (!canvas || typeof Chart === 'undefined') return;

    var male = Number(@json($maleCount));
    var female = Number(@json($femaleCount));
    var total = male + female;
    if (total <= 0) return;

    var sliceLabels = {
        id: 'sliceLabels',
        afterDatasetsDraw: function (chart) {
            var ctx = chart.ctx;
            var meta = chart.getDatasetMeta(0);
            var data = chart.data.datasets[0].data;

            ctx.save();
            ctx.font = '700 15px "Sora","Segoe UI",Arial,sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#FFFFFF';

            meta.data.forEach(function (arc, index) {
                if (!data[index]) return;
                var percentage = (data[index] / total * 100).toFixed(1).replace('.', ',') + '%';
                var position = arc.tooltipPosition();
                ctx.fillText(percentage, position.x, position.y);
            });

            ctx.restore();
        }
    };

    new Chart(canvas, {
        type: 'pie',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [male, female],
                backgroundColor: ['#2457D6', '#0E8FB3'],
                borderColor: '#FFFFFF',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var value = Number(context.raw) || 0;
                            var percentage = (value / total * 100).toFixed(1);
                            return context.label + ': ' + value.toLocaleString('id-ID') + ' peserta (' + percentage + '%)';
                        }
                    }
                }
            }
        },
        plugins: [sliceLabels]
    });
});
</script>

@endsection