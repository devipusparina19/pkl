<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#081b33">
<meta name="description" content="SIRAPIK - Sistem Informasi Pelatihan Bapelkes Provinsi Kalimantan Selatan.">
<title>SIRAPIK — Bapelkes Kalimantan Selatan</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ============ DESIGN TOKENS ============ */
:root{
  --navy:#081b33; --midnight:#0b2545; --royal:#2457d6; --electric:#3478f6;
  --soft:#eaf2ff; --ice:#f3f7fc; --white:#fff; --slate:#64748b; --ink:#14243a;
  --border:#e1e9f2; --cyan:#38c6e8; --green:#16845b; --green-bg:#eaf7f0;
  --amber:#b2692f; --amber-bg:#fff3e9;

  --f-display:'Sora',system-ui,sans-serif;
  --f-body:'Plus Jakarta Sans',system-ui,sans-serif;

  --s1:8px; --s2:16px; --s3:24px; --s4:40px; --s5:64px; --s6:112px;
  --r-sm:8px; --r:14px; --r-lg:28px;
  --shadow:0 18px 50px rgba(8,27,51,.09);
  --shadow-lg:0 30px 80px rgba(8,27,51,.28);
  --ease:cubic-bezier(.2,.7,.3,1);
  --container:1360px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;scroll-padding-top:96px}
body{min-width:320px;overflow-x:hidden;color:var(--ink);background:var(--white);
  font:400 17px/1.7 var(--f-body);-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
button,select{font:inherit;cursor:pointer}
img{display:block;max-width:100%}
::selection{background:var(--royal);color:#fff}
.container{width:min(var(--container),calc(100% - 80px));margin-inline:auto}
.icon{width:20px;height:20px;flex-shrink:0;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
:focus-visible{outline:3px solid rgba(52,120,246,.55);outline-offset:3px;border-radius:6px}

/* ============ SCROLL PROGRESS ============ */
.progress{position:fixed;inset:0 0 auto;height:3px;z-index:200;background:linear-gradient(90deg,var(--electric),var(--cyan));
  transform:scaleX(0);transform-origin:left;will-change:transform}

/* ============ TOPBAR + HEADER ============ */
.topbar{background:var(--navy);color:rgba(255,255,255,.7);font-size:14px}
.topbar-inner{min-height:40px;display:flex;align-items:center;justify-content:space-between;gap:20px}
.topbar span{display:flex;align-items:center;gap:8px}
.topbar .icon{width:16px;height:16px;color:var(--cyan)}

.site-header{position:sticky;top:0;z-index:100;background:rgba(255,255,255,.9);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid transparent;
  transition:border-color .3s,box-shadow .3s}
.site-header.scrolled{border-color:var(--border);box-shadow:0 10px 30px rgba(8,27,51,.07)}
.nav-inner{min-height:84px;display:flex;align-items:center;justify-content:space-between;gap:var(--s4);transition:min-height .3s var(--ease)}
.site-header.scrolled .nav-inner{min-height:70px}

.brand{display:flex;align-items:center;gap:14px}
.brand-logo{width:50px;height:50px;padding:5px;border:1px solid var(--border);border-radius:var(--r);background:#fff}
.brand-logo img{width:100%;height:100%;object-fit:contain}
.brand-title{font:800 24px/1.1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.brand-sub{margin-top:4px;font-size:12px;font-weight:600;color:var(--slate);line-height:1.3}

.nav-menu{display:flex;align-items:center;gap:var(--s1)}
.nav-link{position:relative;padding:10px 16px;font-size:15px;font-weight:600;color:#506176;border-radius:999px;transition:color .25s,background .25s}
.nav-link:hover{color:var(--royal);background:var(--ice)}
.nav-link.active{color:var(--royal);background:var(--soft)}
.nav-cta{margin-left:var(--s1);min-height:46px;display:inline-flex;align-items:center;gap:10px;padding:0 22px;border-radius:999px;
  background:var(--navy);color:#fff;font-size:15px;font-weight:700;transition:background .25s,transform .25s var(--ease),box-shadow .25s}
.nav-cta:hover{background:var(--royal);transform:translateY(-2px);box-shadow:0 10px 24px rgba(36,87,214,.28)}
.nav-cta .icon,.btn .icon{width:18px;height:18px;transition:transform .25s var(--ease)}
.nav-cta:hover .icon,.btn:hover .icon{transform:translateX(4px)}

.menu-toggle{display:none;width:46px;height:46px;place-items:center;border:1px solid var(--border);border-radius:12px;background:#fff;color:var(--navy)}

/* ============ BUTTONS ============ */
.btn{min-height:56px;display:inline-flex;align-items:center;justify-content:center;gap:12px;padding:0 28px;border-radius:999px;
  font-size:16px;font-weight:700;border:1.5px solid transparent;transition:transform .25s var(--ease),background .25s,color .25s,border-color .25s,box-shadow .25s}
.btn:active{transform:scale(.97)}
.btn-primary{background:#fff;color:var(--navy)}
.btn-primary:hover{background:var(--cyan);transform:translateY(-3px);box-shadow:0 14px 30px rgba(56,198,232,.3)}
.btn-ghost{color:#fff;border-color:rgba(255,255,255,.3)}
.btn-ghost:hover{border-color:#fff;background:rgba(255,255,255,.08)}

/* ============ HERO ============ */
.hero{position:relative;overflow:hidden;isolation:isolate;color:#fff;background:var(--navy)}
.hero-bg{position:absolute;inset:0;z-index:-3;
  background:linear-gradient(100deg,rgba(5,22,42,.98) 0%,rgba(8,34,63,.93) 55%,rgba(9,39,70,.8) 100%),
  url("{{ asset('images/background-sirapik.jpg') }}") center/cover no-repeat}
.hero-grid{position:absolute;inset:0;z-index:-2;opacity:.12;
  background-image:linear-gradient(rgba(255,255,255,.4) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.4) 1px,transparent 1px);
  background-size:72px 72px;mask-image:linear-gradient(100deg,#000 10%,transparent 80%);-webkit-mask-image:linear-gradient(100deg,#000 10%,transparent 80%);
  animation:grid-drift 40s linear infinite}
@keyframes grid-drift{to{background-position:72px 72px}}
.hero-year{position:absolute;z-index:-1;right:-1%;bottom:-.18em;font:800 clamp(200px,30vw,460px)/1 var(--f-display);letter-spacing:-.06em;
  color:transparent;-webkit-text-stroke:1.5px rgba(130,183,255,.16);user-select:none;pointer-events:none}

.hero-inner{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(320px,.7fr);align-items:center;gap:var(--s5);padding-block:112px 120px}
.hero h1{font:800 clamp(44px,7.2vw,96px)/1.02 var(--f-display);letter-spacing:-.045em;max-width:12ch;max-width:none}
.hero h1 span{display:block;color:#82b7ff}
.hero-lead{max-width:56ch;margin-top:var(--s3);font-size:clamp(16px,1.4vw,19px);line-height:1.8;color:rgba(255,255,255,.74)}
.hero-actions{display:flex;flex-wrap:wrap;gap:var(--s2);margin-top:var(--s4)}
.hero-trust{display:flex;align-items:center;gap:10px;margin-top:var(--s3);font-size:15px;color:rgba(255,255,255,.62)}
.hero-trust .icon{color:var(--cyan)}

/* Signature element: live data plate */
.plate{position:relative;padding:var(--s4) 36px 32px;border-radius:var(--r-lg);border:1px solid rgba(255,255,255,.18);
  background:linear-gradient(160deg,rgba(255,255,255,.14),rgba(255,255,255,.04));box-shadow:var(--shadow-lg);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);transform:rotate(1.6deg);transition:transform .6s var(--ease)}
.plate:hover{transform:rotate(0) translateY(-6px)}
.plate-head{display:flex;align-items:center;gap:14px;font-size:14px;font-weight:600;color:rgba(255,255,255,.75)}
.plate-logo{width:48px;height:48px;padding:6px;border-radius:12px;background:#fff}
.plate-logo img{width:100%;height:100%;object-fit:contain}
.plate-num{margin-top:var(--s4);font:800 clamp(64px,7vw,104px)/.95 var(--f-display);letter-spacing:-.05em;font-variant-numeric:tabular-nums}
.plate-cap{margin-top:12px;font-size:17px;font-weight:600;color:#bcd8ff}
.plate-rule{height:1px;margin-block:var(--s3);background:rgba(255,255,255,.18)}
.plate-row{display:flex;justify-content:space-between;gap:var(--s2)}
.plate-row b{display:block;font:700 28px/1.2 var(--f-display);letter-spacing:-.03em}
.plate-row small{font-size:13px;color:rgba(255,255,255,.62)}
.plate-note{margin-top:var(--s3);display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,.7)}
.dot{width:9px;height:9px;border-radius:50%;background:#54d69b;box-shadow:0 0 0 4px rgba(84,214,155,.18);animation:pulse 2.6s ease-in-out infinite}
@keyframes pulse{50%{box-shadow:0 0 0 8px rgba(84,214,155,0)}}

/* ============ SECTION HEADS ============ */
.kicker{display:inline-flex;align-items:center;gap:12px;margin-bottom:var(--s2);font-size:15px;font-weight:700;color:var(--royal)}
.kicker::before{content:"";width:32px;height:2px;background:var(--cyan)}
.title{font:800 clamp(34px,4.6vw,60px)/1.1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.lead{font-size:18px;line-height:1.8;color:var(--slate)}

/* ============ ABOUT (editorial, no cards) ============ */
.about{padding-block:var(--s6)}
.about-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:var(--s5);align-items:start}
.about-side{position:sticky;top:130px}
.about-side .title{max-width:13ch}
.about-body p{font-size:19px;line-height:1.85;color:#3c4f66;max-width:62ch}
.about-body p+p{margin-top:var(--s2);color:var(--slate);font-size:17px}
.about-list{margin-top:var(--s4);list-style:none;border-top:1px solid var(--border)}
.about-list li{display:flex;align-items:center;gap:20px;padding:22px 0;border-bottom:1px solid var(--border);
  font:700 clamp(20px,2.2vw,28px)/1.3 var(--f-display);letter-spacing:-.025em;color:var(--navy);transition:padding .35s var(--ease),color .25s}
.about-list li:hover{padding-left:14px;color:var(--royal)}
.about-list .tick{width:36px;height:36px;display:grid;place-items:center;border-radius:50%;background:var(--soft);color:var(--royal);flex-shrink:0}
.about-list .tick .icon{width:18px;height:18px}

/* ============ DASHBOARD ============ */
.dash{position:relative;padding-block:var(--s6);background:var(--ice)}
.dash::before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,var(--navy) 0 30%,var(--royal) 30% 75%,var(--cyan) 75%)}
.dash-head{display:flex;align-items:flex-end;justify-content:space-between;gap:var(--s3);margin-bottom:var(--s4)}
.dash-head .lead{max-width:60ch;margin-top:var(--s2)}
.live{display:inline-flex;align-items:center;gap:10px;padding:10px 18px;border:1px solid #d4e3f5;border-radius:999px;background:#fff;font-size:14px;font-weight:700;color:var(--royal);white-space:nowrap}

/* Filter */
.filter{margin-bottom:var(--s3);padding:var(--s3) 28px 28px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;box-shadow:var(--shadow)}
.filter-title{display:flex;align-items:center;gap:12px;margin-bottom:var(--s3);font:700 20px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)}
.filter-ico{width:42px;height:42px;display:grid;place-items:center;border-radius:12px;background:var(--soft);color:var(--royal)}
.filter-grid{display:grid;grid-template-columns:.8fr 1.5fr 1fr auto;gap:var(--s2);align-items:end}
.field label{display:block;margin-bottom:8px;font-size:14px;font-weight:700;color:#40536a}
.field select{width:100%;height:56px;padding:0 40px 0 16px;border:1.5px solid var(--border);border-radius:var(--r);color:var(--ink);font-size:16px;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center;
  appearance:none;-webkit-appearance:none;transition:border-color .25s,box-shadow .25s}
.field select:hover{border-color:#a6bddb}
.field select:focus-visible{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12);outline:none}
.btn-apply{min-height:56px;padding:0 28px;border-radius:var(--r);background:var(--royal);color:#fff;border:0;display:inline-flex;align-items:center;justify-content:center;gap:10px;
  font-size:16px;font-weight:700;transition:background .25s,transform .25s var(--ease),box-shadow .25s}
.btn-apply:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.25)}
.btn-apply:active{transform:scale(.97)}
.btn-apply .icon{width:18px;height:18px;transition:transform .25s var(--ease)}
.btn-apply:hover .icon{transform:translateX(4px)}

/* Bento stats */
.bento{display:grid;grid-template-columns:1.35fr 1fr 1fr;grid-template-rows:auto;gap:var(--s2);margin-bottom:var(--s3)}
.stat{position:relative;overflow:hidden;padding:30px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;transition:transform .35s var(--ease),box-shadow .35s}
.stat:hover{transform:translateY(-5px);box-shadow:var(--shadow)}
.stat-top{display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:16px;font-weight:700;color:var(--slate)}
.stat-ico{width:48px;height:48px;display:grid;place-items:center;border-radius:14px;background:var(--soft);color:var(--royal)}
.stat-val{margin-top:var(--s3);font:800 clamp(44px,5vw,72px)/1 var(--f-display);letter-spacing:-.05em;color:var(--navy);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.stat-cap{margin-top:12px;font-size:14px;line-height:1.7;color:var(--slate);max-width:34ch}
.stat.feature{background:var(--navy);border-color:var(--navy);color:#fff}
.stat.feature .stat-top{color:rgba(255,255,255,.75)}
.stat.feature .stat-ico{background:rgba(255,255,255,.1);color:var(--cyan)}
.stat.feature .stat-val{color:#fff;font-size:clamp(56px,6.4vw,92px)}
.stat.feature .stat-cap{color:rgba(255,255,255,.65)}
.stat.feature::after{content:"";position:absolute;right:-90px;bottom:-130px;width:320px;height:320px;border-radius:50%;border:1px solid rgba(130,183,255,.25);box-shadow:0 0 0 44px rgba(130,183,255,.05)}

/* Data panels */
.panels{display:grid;grid-template-columns:.95fr 1.2fr;gap:var(--s2);align-items:start}
.panel{min-width:0;overflow:hidden;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff}
.panel-head{padding:28px 30px 22px;border-bottom:1px solid var(--border)}
.panel-head h3{font:700 22px/1.35 var(--f-display);letter-spacing:-.025em;color:var(--navy)}
.panel-head p{margin-top:8px;font-size:15px;line-height:1.7;color:var(--slate)}
.panel-body{padding:12px 30px 26px}

.rows{list-style:none}
.row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:6px 16px;align-items:center;padding:16px 0;border-bottom:1px solid #edf1f5}
.row:last-child{border-bottom:0}
.row-name{font-size:15px;font-weight:600;color:#3c4f66;overflow-wrap:anywhere}
.row-total{font:700 18px/1 var(--f-display);letter-spacing:-.02em;color:var(--navy);font-variant-numeric:tabular-nums}
.row-total small{font:500 12px var(--f-body);color:var(--slate);letter-spacing:0}
.bar{grid-column:1/-1;height:6px;border-radius:99px;background:var(--soft);overflow:hidden}
.bar i{display:block;height:100%;width:var(--w);border-radius:inherit;background:linear-gradient(90deg,var(--royal),var(--cyan));
  transform:scaleX(0);transform-origin:left;transition:transform 1.1s var(--ease)}
.in .bar i,.no-js .bar i{transform:scaleX(1)}
.low .bar{background:var(--amber-bg)}
.low .bar i{background:linear-gradient(90deg,#d99058,#f0c08e)}
.empty{padding:36px 8px;text-align:center;font-size:15px;line-height:1.8;color:var(--slate)}

.rank-wrap{display:grid;grid-template-columns:1fr 1fr}
.rank{padding:26px 30px 28px}
.rank+.rank{border-left:1px solid var(--border)}
.rank-title{display:flex;align-items:center;gap:14px;margin-bottom:6px}
.rank-ico{width:44px;height:44px;display:grid;place-items:center;border-radius:12px;background:var(--green-bg);color:var(--green)}
.low .rank-ico{background:var(--amber-bg);color:var(--amber)}
.rank-title h4{font:700 18px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)}
.rank-title p{font-size:13px;color:var(--slate)}
.pos{display:inline-grid;place-items:center;width:26px;height:26px;margin-right:10px;border-radius:8px;background:var(--soft);color:var(--royal);font:700 12px var(--f-display)}
.row-name{display:flex;align-items:center}

/* ============ FOOTER ============ */
.footer{position:relative;overflow:hidden;background:var(--navy);color:#fff}
.footer::before{content:"";position:absolute;top:-240px;right:-140px;width:520px;height:520px;border-radius:50%;border:1px solid rgba(255,255,255,.08);box-shadow:0 0 0 70px rgba(255,255,255,.02)}
.footer-main{position:relative;display:grid;grid-template-columns:1.4fr .6fr;gap:var(--s5);padding-block:var(--s5) var(--s4)}
.footer-brand{display:flex;gap:18px;align-items:flex-start}
.footer-logo{width:52px;height:52px;padding:6px;border-radius:12px;background:#fff;flex-shrink:0}
.footer-logo img{width:100%;height:100%;object-fit:contain}
.footer-brand h3{font:800 26px/1.2 var(--f-display);letter-spacing:-.03em;margin-bottom:8px}
.footer-brand p{max-width:56ch;font-size:15px;line-height:1.85;color:rgba(255,255,255,.62)}
.footer-links h4{margin-bottom:var(--s2);font:700 16px var(--f-display);color:#82b7ff}
.footer-links a{display:block;width:fit-content;margin-bottom:10px;font-size:15px;color:rgba(255,255,255,.72);transition:color .25s,transform .25s var(--ease)}
.footer-links a:hover{color:#fff;transform:translateX(5px)}
.footer-bottom{position:relative;border-top:1px solid rgba(255,255,255,.1)}
.footer-bottom .container{display:flex;justify-content:space-between;gap:var(--s2);padding-block:22px;font-size:14px;color:rgba(255,255,255,.55)}

/* ============ REVEAL ============ */
.reveal{opacity:0;transform:translateY(20px);transition:opacity .7s ease,transform .7s var(--ease)}
.reveal.in,.no-js .reveal{opacity:1;transform:none}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}

/* ============ RESPONSIVE ============ */
@media (max-width:1100px){
  .container{width:calc(100% - 56px)}
  .nav-link{padding:10px 12px}
  .hero-inner{grid-template-columns:1fr;padding-block:88px}
  .plate{max-width:460px}
  .about-grid{grid-template-columns:1fr;gap:var(--s4)}
  .about-side{position:static}
  .filter-grid{grid-template-columns:1fr 1fr}
  .btn-apply{grid-column:1/-1}
  .bento{grid-template-columns:1fr 1fr}
  .stat.feature{grid-column:1/-1}
  .panels{grid-template-columns:1fr}
}
@media (max-width:760px){
  body{font-size:16px}
  .container{width:calc(100% - 36px)}
  .topbar-inner{justify-content:center}.topbar .hide-m{display:none}
  .nav-inner,.site-header.scrolled .nav-inner{min-height:70px}
  .brand-title{font-size:21px}.brand-sub{font-size:11px;max-width:200px}
  .menu-toggle{display:grid}
  .nav-menu{position:absolute;top:100%;left:0;right:0;display:none;flex-direction:column;align-items:stretch;gap:0;padding:12px 18px 20px;
    background:#fff;border-top:1px solid var(--border);box-shadow:0 20px 30px rgba(8,27,51,.12)}
  .nav-menu.open{display:flex}
  .nav-link{padding:15px 12px;border-radius:12px;font-size:16px}
  .nav-cta{margin:12px 0 0;min-height:52px;justify-content:center}
  .hero-inner{padding-block:64px 72px;gap:var(--s4)}
  .hero-actions .btn{width:100%}
  .plate{padding:28px 24px 24px}
  .about,.dash{padding-block:72px}
  .about-body p{font-size:17px}
  .dash-head{flex-direction:column;align-items:flex-start}
  .filter{padding:22px 18px}
  .filter-grid{grid-template-columns:1fr}
  .bento{grid-template-columns:1fr}
  .stat{padding:24px}
  .panel-head,.panel-body{padding-inline:20px}
  .rank-wrap{grid-template-columns:1fr}
  .rank{padding:22px 20px}
  .rank+.rank{border-left:0;border-top:1px solid var(--border)}
  .footer-main{grid-template-columns:1fr;gap:var(--s4)}
  .footer-bottom .container{flex-direction:column;gap:4px}
}

@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
  .reveal{opacity:1;transform:none}
  .bar i{transform:scaleX(1)}
}
</style>
</head>

<body class="no-js">
@php
    $maxDistrict = max(1, (int) collect($trainingByDistrict)->max('total'));
    $maxTop      = max(1, (int) collect($topNakes ?? [])->max('total'));
    $maxBottom   = max(1, (int) collect($bottomNakes ?? [])->max('total'));
@endphp

<div class="progress" id="progress" aria-hidden="true"></div>

<!-- TOPBAR -->
<div class="topbar">
  <div class="container topbar-inner">
    <span>
      <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
      Portal Informasi Publik Bapelkes
    </span>
    <span class="hide-m">
      <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg>
      Sistem Informasi Pelatihan Kesehatan
    </span>
  </div>
</div>

<!-- HEADER -->
<header class="site-header" id="site-header">
  <div class="container nav-inner">

    <a href="{{ route('public.dashboard') }}" class="brand" aria-label="SIRAPIK Beranda">
      <div class="brand-logo">
        <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}" alt="Logo Provinsi Kalimantan Selatan">
      </div>
      <div>
        <div class="brand-title">SIRAPIK</div>
        <div class="brand-sub">Bapelkes Provinsi Kalimantan Selatan</div>
      </div>
    </a>

    <button type="button" class="menu-toggle" id="menu-toggle" aria-label="Buka menu navigasi" aria-controls="nav-menu" aria-expanded="false">
      <svg class="icon" id="ico-open" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      <svg class="icon" id="ico-close" viewBox="0 0 24 24" aria-hidden="true" style="display:none"><path d="m6 6 12 12M18 6 6 18"/></svg>
    </button>

    <nav class="nav-menu" id="nav-menu" aria-label="Navigasi utama">
      <a href="#beranda" class="nav-link active">Beranda</a>
      <a href="#tentang" class="nav-link">Tentang SIRAPIK</a>
      <a href="#dashboard" class="nav-link">Data Pelatihan</a>
      <a href="{{ route('public.training-history') }}" class="nav-cta">
        Riwayat Pelatihan
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </nav>

  </div>
</header>

<main>

<!-- HERO -->
<section class="hero" id="beranda">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-year" aria-hidden="true">{{ $year }}</div>

  <div class="container hero-inner">

    <div>
      <h1>
        Informasi Pelatihan Kesehatan
        <span>Dalam Satu Sistem.</span>
      </h1>

      <p class="hero-lead">
        SIRAPIK menyajikan informasi dan ringkasan data pelatihan Bapelkes
        Provinsi Kalimantan Selatan. Temukan data berdasarkan tahun,
        kabupaten/kota atau mitra, serta sumber dana dalam satu sistem terintegrasi.
      </p>

      <div class="hero-actions">
        <a href="#dashboard" class="btn btn-primary">
          Jelajahi Data Pelatihan
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="#tentang" class="btn btn-ghost">Kenali SIRAPIK</a>
      </div>

      <div class="hero-trust">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/></svg>
        Informasi publik dan mudah diakses
      </div>
    </div>

    <aside class="plate" aria-label="Ringkasan data pelatihan">
      <div class="plate-head">
        <div class="plate-logo">
          <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}" alt="Logo Provinsi Kalimantan Selatan">
        </div>
        Data pelatihan tahun {{ $year }}
      </div>

      <div class="plate-num" data-count="{{ (int) $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</div>
      <div class="plate-cap">peserta sudah dilatih</div>

      <div class="plate-rule"></div>

      <div class="plate-row">
        <div>
          <b>{{ number_format($totalTrainings, 0, ',', '.') }}</b>
          <small>pelatihan terlaksana</small>
        </div>
        <div>
          <b>{{ $year }}</b>
          <small>tahun laporan</small>
        </div>
      </div>

      <div class="plate-note"><span class="dot"></span> Mengikuti filter pada bagian Data Pelatihan</div>
    </aside>

  </div>
</section>

<!-- ABOUT -->
<section class="about" id="tentang">
  <div class="container about-grid">

    <div class="about-side reveal">
      <div class="kicker">Mengenal SIRAPIK</div>
      <h2 class="title">Mendukung pengelolaan informasi pelatihan kesehatan.</h2>
    </div>

    <div class="about-body reveal d1">
      <p>
        SIRAPIK adalah Sistem Informasi Pelatihan yang menyajikan informasi
        pelatihan Bapelkes Provinsi Kalimantan Selatan.
      </p>
      <p>
        Informasi yang tersedia mencakup ringkasan pelatihan, jumlah peserta,
        rekap berdasarkan kabupaten/kota atau mitra, serta partisipasi tenaga
        kesehatan, sehingga gambaran data pelatihan lebih mudah dipahami.
      </p>

      <ul class="about-list">
        @foreach (['Informasi pelatihan', 'Ringkasan statistik', 'Filter data pelatihan', 'Rekap partisipasi'] as $point)
          <li>
            <span class="tick"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg></span>
            {{ $point }}
          </li>
        @endforeach
      </ul>
    </div>

  </div>
</section>

<!-- DASHBOARD -->
<section class="dash" id="dashboard">
  <div class="container">

    <div class="dash-head reveal">
      <div>
        <div class="kicker">Pusat Informasi Data</div>
        <h2 class="title">Data Pelatihan</h2>
        <p class="lead">
          Jelajahi informasi pelatihan, jumlah peserta, distribusi wilayah,
          serta partisipasi tenaga kesehatan. Gunakan filter untuk melihat data sesuai kebutuhan.
        </p>
      </div>
      <div class="live"><span class="dot"></span> Data Pelatihan</div>
    </div>

    <!-- FILTER -->
    <form method="GET" action="{{ route('public.dashboard') }}#dashboard" class="filter reveal" id="dashboard-filter">
      <div class="filter-title">
        <span class="filter-ico">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16M7 12h10M10 19h4"/><circle cx="8" cy="5" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
        </span>
        Filter Data Pelatihan
      </div>

      <div class="filter-grid">
        <div class="field">
          <label for="year">Tahun Pelatihan</label>
          <select name="year" id="year">
            @forelse ($years as $availableYear)
              <option value="{{ $availableYear }}" @selected((int) $year === (int) $availableYear)>{{ $availableYear }}</option>
            @empty
              <option value="{{ $year }}">{{ $year }}</option>
            @endforelse
          </select>
        </div>

        <div class="field">
          <label for="district">Kabupaten/Kota/Mitra/Mandiri</label>
          <select name="district" id="district">
            <option value="">Semua Kabupaten/Kota/Mitra/Mandiri</option>
            @foreach ($districts as $item)
              <option value="{{ $item }}" @selected($district === $item)>{{ $item }}</option>
            @endforeach
          </select>
        </div>

        <div class="field">
          <label for="funding_source">Sumber Dana</label>
          <select name="funding_source" id="funding_source">
            <option value="">Semua Sumber Dana</option>
            @foreach ($fundingSources as $source)
              <option value="{{ $source }}" @selected($fundingSource === $source)>{{ $source }}</option>
            @endforeach
          </select>
        </div>

        <button class="btn-apply" type="submit">
          Terapkan Filter
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
      </div>
    </form>

    <!-- BENTO STATS -->
    <div class="bento">

      <article class="stat feature reveal">
        <div class="stat-top">
          Peserta yang Sudah Dilatih
          <span class="stat-ico"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 20v-1.5a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4V20"/><circle cx="9.5" cy="7" r="4"/><path d="M17 11a4 4 0 0 0 0-8M21 20v-1.5a4 4 0 0 0-3-3.87"/></svg></span>
        </div>
        <div class="stat-val" data-count="{{ (int) $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</div>
        <p class="stat-cap">Jumlah peserta yang sudah dilatih per individu</p>
      </article>

      <article class="stat reveal d1">
        <div class="stat-top">
          Jumlah Pelatihan
          <span class="stat-ico"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg></span>
        </div>
        <div class="stat-val" data-count="{{ (int) $totalTrainings }}">{{ number_format($totalTrainings, 0, ',', '.') }}</div>
        <p class="stat-cap">Jumlah pelatihan sesuai filter yang dipilih</p>
      </article>

      <article class="stat reveal d2">
        <div class="stat-top">
          Tahun Laporan
          <span class="stat-ico"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg></span>
        </div>
        <div class="stat-val">{{ $year }}</div>
        <p class="stat-cap">Tahun pelaksanaan pelatihan yang sedang ditampilkan</p>
      </article>

    </div>

    <!-- DATA PANELS -->
    <div class="panels">

      <section class="panel reveal">
        <div class="panel-head">
          <h3>Rekap Kabupaten/Kota/Mitra/Mandiri</h3>
          <p>Jumlah pelatihan berdasarkan wilayah atau mitra yang tercatat dalam sistem.</p>
        </div>
        <div class="panel-body">
          @if (collect($trainingByDistrict)->isNotEmpty())
            <ul class="rows" data-bars>
              @foreach ($trainingByDistrict as $row)
                <li class="row">
                  <span class="row-name">{{ $row->district }}</span>
                  <span class="row-total">{{ number_format($row->total, 0, ',', '.') }}</span>
                  <span class="bar"><i style="--w: {{ round(($row->total / $maxDistrict) * 100) }}%"></i></span>
                </li>
              @endforeach
            </ul>
          @else
            <div class="empty">Belum ada data untuk filter ini.</div>
          @endif
        </div>
      </section>

      <section class="panel reveal d1">
        <div class="panel-head">
          <h3>Partisipasi Tenaga Kesehatan</h3>
          <p>Peringkat jenis tenaga kesehatan berdasarkan jumlah peserta unik yang mengikuti pelatihan sesuai filter yang dipilih.</p>
        </div>

        <div class="rank-wrap">

          <div class="rank high">
            <div class="rank-title">
              <span class="rank-ico"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span>
              <div><h4>5 Tertinggi</h4><p>Peserta terbanyak</p></div>
            </div>

            @if (isset($topNakes) && $topNakes->isNotEmpty())
              <ol class="rows" data-bars>
                @foreach ($topNakes as $nakes)
                  <li class="row">
                    <span class="row-name"><span class="pos">{{ $loop->iteration }}</span>{{ $nakes->name }}</span>
                    <span class="row-total">{{ number_format((int) $nakes->total, 0, ',', '.') }} <small>org</small></span>
                    <span class="bar"><i style="--w: {{ round(((int) $nakes->total / $maxTop) * 100) }}%"></i></span>
                  </li>
                @endforeach
              </ol>
            @else
              <div class="empty">Belum ada data peserta untuk ditampilkan pada filter ini.</div>
            @endif
          </div>

          <div class="rank low">
            <div class="rank-title">
              <span class="rank-ico"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg></span>
              <div><h4>5 Terendah</h4><p>Peserta paling sedikit</p></div>
            </div>

            @if (isset($bottomNakes) && $bottomNakes->isNotEmpty())
              <ol class="rows" data-bars>
                @foreach ($bottomNakes as $nakes)
                  <li class="row">
                    <span class="row-name"><span class="pos">{{ $loop->iteration }}</span>{{ $nakes->name }}</span>
                    <span class="row-total">{{ number_format((int) $nakes->total, 0, ',', '.') }} <small>org</small></span>
                    <span class="bar"><i style="--w: {{ round(((int) $nakes->total / $maxBottom) * 100) }}%"></i></span>
                  </li>
                @endforeach
              </ol>
            @else
              <div class="empty">Belum ada data peserta untuk ditampilkan pada filter ini.</div>
            @endif
          </div>

        </div>
      </section>

    </div>
  </div>
</section>

</main>

<!-- FOOTER -->
<footer class="footer">
  <div class="container footer-main">
    <div class="footer-brand">
      <div class="footer-logo">
        <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}" alt="Logo Provinsi Kalimantan Selatan" loading="lazy">
      </div>
      <div>
        <h3>SIRAPIK</h3>
        <p>Sistem Informasi Pelatihan Bapelkes Provinsi Kalimantan Selatan. Menyediakan informasi dan ringkasan data pelatihan melalui satu sistem informasi yang mudah diakses.</p>
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
    <div class="container">
      <span>© {{ date('Y') }} SIRAPIK. Hak Cipta Dilindungi.</span>
      <span>Bapelkes Provinsi Kalimantan Selatan</span>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.remove('no-js');

  var header = document.getElementById('site-header');
  var progress = document.getElementById('progress');
  var btn = document.getElementById('menu-toggle');
  var menu = document.getElementById('nav-menu');
  var icoOpen = document.getElementById('ico-open');
  var icoClose = document.getElementById('ico-close');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Header state + scroll progress */
  var ticking = false;
  function onScroll() {
    header.classList.toggle('scrolled', window.scrollY > 15);
    var max = document.documentElement.scrollHeight - window.innerHeight;
    progress.style.transform = 'scaleX(' + (max > 0 ? window.scrollY / max : 0) + ')';
    ticking = false;
  }
  onScroll();
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
  }, { passive: true });

  /* Mobile menu */
  function setMenu(open) {
    menu.classList.toggle('open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    icoOpen.style.display = open ? 'none' : 'inline-block';
    icoClose.style.display = open ? 'inline-block' : 'none';
  }
  btn.addEventListener('click', function () { setMenu(btn.getAttribute('aria-expanded') !== 'true'); });
  menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
  window.addEventListener('resize', function () { if (window.innerWidth > 760) setMenu(false); });

  /* Counter */
  var fmt = new Intl.NumberFormat('id-ID');
  function count(el) {
    var target = parseInt(el.dataset.count, 10);
    if (isNaN(target) || reduce || target < 2) return;
    var start = null, dur = 1400;
    function step(t) {
      if (start === null) start = t;
      var p = Math.min((t - start) / dur, 1);
      el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 4))));
      if (p < 1) requestAnimationFrame(step); else el.textContent = fmt.format(target);
    }
    el.textContent = '0';
    requestAnimationFrame(step);
  }

  /* Reveal + bars + counters */
  var items = document.querySelectorAll('.reveal, [data-bars], [data-count]');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        en.target.classList.add('in');
        if (en.target.hasAttribute('data-count')) count(en.target);
        io.unobserve(en.target);
      });
    }, { threshold: .15, rootMargin: '0px 0px -30px 0px' });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('in'); });
  }

  /* Active nav */
  var links = document.querySelectorAll('.nav-link[href^="#"]');
  if ('IntersectionObserver' in window) {
    var so = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        links.forEach(function (l) { l.classList.toggle('active', l.getAttribute('href') === '#' + en.target.id); });
      });
    }, { rootMargin: '-25% 0px -60% 0px' });
    document.querySelectorAll('#beranda, #tentang, #dashboard').forEach(function (s) { so.observe(s); });
  }
});
</script>

</body>
</html>