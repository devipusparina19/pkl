<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#081b33">
<meta name="description" content="ARTA - Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan, Bapelkes Provinsi Kalimantan Selatan.">
<title>ARTA — Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<!-- Chart.js untuk diagram pie -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root{
  --navy:#081b33; --midnight:#0b2545; --royal:#2457d6; --electric:#3478f6;
  --soft:#eaf2ff; --ice:#f3f7fc; --white:#fff; --slate:#56657a; --ink:#14243a;
  --border:#e1e9f2; --cyan:#38c6e8; --green:#16845b; --green-bg:#eaf7f0;
  --amber:#a35a22; --amber-bg:#fff3e9;
  --f-display:'Sora',system-ui,sans-serif;
  --f-body:'Plus Jakarta Sans',system-ui,sans-serif;
  --gutter:clamp(18px,4vw,80px);
  --s1:8px; --s2:16px; --s3:24px; --s4:40px; --s5:64px; --s6:112px;
  --r-sm:8px; --r:14px; --r-lg:28px;
  --shadow:0 18px 50px rgba(8,27,51,.09);
  --shadow-lg:0 30px 80px rgba(8,27,51,.28);
  --ease:cubic-bezier(.2,.7,.3,1);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;scroll-padding-top:96px;-webkit-text-size-adjust:100%}
body{min-width:320px;overflow-x:clip;color:var(--ink);background:var(--white);
  font:400 17px/1.7 var(--f-body);-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
button,select{font:inherit;cursor:pointer}
img{display:block;max-width:100%}
::selection{background:var(--royal);color:#fff}

/* Container penuh layar: mengikuti lebar layar, bukan kotak sempit di tengah */
.container{width:100%;max-width:1920px;margin-inline:auto;padding-inline:var(--gutter)}

.icon{width:20px;height:20px;flex-shrink:0;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
:focus-visible{outline:3px solid rgba(52,120,246,.6);outline-offset:3px;border-radius:6px}

.sr-only{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap}
.skip{position:absolute;left:16px;top:-60px;z-index:300;padding:12px 18px;border-radius:10px;background:var(--royal);color:#fff;font-weight:700;transition:top .2s}
.skip:focus{top:12px}

/* SCROLL PROGRESS */
.progress{position:fixed;inset:0 0 auto;height:3px;z-index:200;background:linear-gradient(90deg,var(--electric),var(--cyan));
  transform:scaleX(0);transform-origin:left;will-change:transform}

/* TOPBAR + HEADER */
.topbar{background:var(--navy);color:rgba(255,255,255,.75);font-size:14px}
.topbar-inner{min-height:40px;display:flex;align-items:center;justify-content:space-between;gap:20px}
.topbar span{display:flex;align-items:center;gap:8px}
.topbar .icon{width:16px;height:16px;color:var(--cyan)}

.site-header{position:sticky;top:0;z-index:100;background:rgba(255,255,255,.88);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid transparent;
  transition:border-color .3s,box-shadow .3s}
.site-header.scrolled{border-color:var(--border);box-shadow:0 10px 30px rgba(8,27,51,.07)}
.nav-inner{min-height:84px;display:flex;align-items:center;justify-content:space-between;gap:var(--s4);transition:min-height .3s var(--ease)}
.site-header.scrolled .nav-inner{min-height:68px}

.brand{display:flex;align-items:center;gap:14px;min-width:0}
.brand-logo{width:50px;height:50px;padding:5px;border:1px solid var(--border);border-radius:var(--r);background:#fff;flex-shrink:0}
.brand-logo img{width:100%;height:100%;object-fit:contain}
.brand-title{font:800 26px/1.1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.brand-sub{margin-top:3px;font-size:12px;font-weight:600;color:var(--slate);line-height:1.3}

.nav-menu{display:flex;align-items:center;gap:var(--s1)}
.nav-link{position:relative;padding:10px 16px;font-size:15px;font-weight:600;color:#43546a;border-radius:999px;transition:color .25s,background .25s}
.nav-link:hover{color:var(--royal);background:var(--ice)}
.nav-link.active{color:var(--royal);background:var(--soft)}
.nav-cta{margin-left:var(--s1);min-height:46px;display:inline-flex;align-items:center;gap:10px;padding:0 22px;border-radius:999px;
  background:var(--navy);color:#fff;font-size:15px;font-weight:700;transition:background .25s,transform .25s var(--ease),box-shadow .25s}
.nav-cta:hover{background:var(--royal);transform:translateY(-2px);box-shadow:0 10px 24px rgba(36,87,214,.28)}
.nav-cta .icon,.btn .icon{width:18px;height:18px;transition:transform .25s var(--ease)}
.nav-cta:hover .icon,.btn:hover .icon{transform:translateX(4px)}

.menu-toggle{display:none;width:46px;height:46px;place-items:center;border:1px solid var(--border);border-radius:12px;background:#fff;color:var(--navy)}

/* BUTTONS */
.btn{min-height:56px;display:inline-flex;align-items:center;justify-content:center;gap:12px;padding:0 28px;border-radius:999px;
  font-size:16px;font-weight:700;border:1.5px solid transparent;transition:transform .25s var(--ease),background .25s,color .25s,border-color .25s,box-shadow .25s}
.btn:active{transform:scale(.97)}
.btn-primary{background:#fff;color:var(--navy)}
.btn-primary:hover{background:var(--cyan);transform:translateY(-3px);box-shadow:0 14px 30px rgba(56,198,232,.3)}
.btn-ghost{color:#fff;border-color:rgba(255,255,255,.35)}
.btn-ghost:hover{border-color:#fff;background:rgba(255,255,255,.08)}

/* HERO */
.hero{position:relative;overflow:hidden;isolation:isolate;color:#fff;background:var(--navy)}
.hero-bg{position:absolute;inset:0;z-index:-5;
  background:linear-gradient(100deg,rgba(5,22,42,.98) 0%,rgba(8,34,63,.93) 55%,rgba(9,39,70,.8) 100%),
  url("{{ asset('images/background-sirapik.jpg') }}") center/cover no-repeat}
.hero-grid{position:absolute;inset:0;z-index:-4;opacity:.12;
  background-image:linear-gradient(rgba(255,255,255,.4) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.4) 1px,transparent 1px);
  background-size:72px 72px;mask-image:linear-gradient(100deg,#000 10%,transparent 80%);-webkit-mask-image:linear-gradient(100deg,#000 10%,transparent 80%);
  animation:grid-drift 40s linear infinite}
@keyframes grid-drift{to{background-position:72px 72px}}

/* orb ambient (hanya transform) */
.orb{position:absolute;z-index:-3;border-radius:50%;pointer-events:none;will-change:transform}
.orb.o1{width:min(52vw,720px);aspect-ratio:1;left:-12%;top:-30%;
  background:radial-gradient(circle,rgba(52,120,246,.5),transparent 65%);animation:float1 18s ease-in-out infinite alternate}
.orb.o2{width:min(40vw,560px);aspect-ratio:1;right:-8%;bottom:-30%;
  background:radial-gradient(circle,rgba(56,198,232,.38),transparent 65%);animation:float2 22s ease-in-out infinite alternate}
@keyframes float1{to{transform:translate3d(8vw,6vh,0) scale(1.15)}}
@keyframes float2{to{transform:translate3d(-9vw,-7vh,0) scale(1.2)}}

.hero-year{position:absolute;z-index:-2;right:-1%;bottom:-.18em;font:800 clamp(200px,30vw,520px)/1 var(--f-display);letter-spacing:-.06em;
  color:transparent;-webkit-text-stroke:1.5px rgba(130,183,255,.16);user-select:none;pointer-events:none}

.hero-inner{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(320px,.7fr);align-items:center;gap:var(--s5);padding-block:112px 120px}
.hero-tag{display:inline-flex;align-items:center;gap:10px;margin-bottom:var(--s3);padding:8px 16px 8px 10px;border-radius:999px;
  border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.07);font-size:14px;font-weight:600;color:#cfe3ff}
.hero-tag .icon{width:18px;height:18px;color:var(--cyan)}
.hero h1{font:800 clamp(44px,6.6vw,88px)/1.04 var(--f-display);letter-spacing:-.045em}
.hero h1 .ln{display:block;overflow:hidden;padding-bottom:.08em}
.hero h1 .ln>span{display:block;transform:translateY(110%);opacity:0;animation:rise .9s var(--ease) forwards}
.hero h1 .ln:nth-child(2)>span{animation-delay:.12s;color:#82b7ff}
.hero h1 .ln:nth-child(3)>span{animation-delay:.24s;color:#82b7ff}
@keyframes rise{to{transform:none;opacity:1}}
.hero-fade{opacity:0;transform:translateY(16px);animation:fadeup .9s var(--ease) forwards}
.hero-fade.f1{animation-delay:.4s}.hero-fade.f2{animation-delay:.55s}.hero-fade.f3{animation-delay:.7s}
@keyframes fadeup{to{opacity:1;transform:none}}

.hero-lead{max-width:60ch;margin-top:var(--s3);font-size:clamp(16px,1.4vw,19px);line-height:1.8;color:rgba(255,255,255,.78)}
.hero-actions{display:flex;flex-wrap:wrap;gap:var(--s2);margin-top:var(--s4)}
.hero-trust{display:flex;align-items:center;gap:10px;margin-top:var(--s3);font-size:15px;color:rgba(255,255,255,.7)}
.hero-trust .icon{color:var(--cyan)}

.plate{position:relative;padding:var(--s4) 36px 32px;border-radius:var(--r-lg);border:1px solid rgba(255,255,255,.18);
  background:linear-gradient(160deg,rgba(255,255,255,.14),rgba(255,255,255,.04));box-shadow:var(--shadow-lg);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);transform:rotate(1.6deg);transition:transform .35s var(--ease);will-change:transform}
.plate:hover{transform:rotate(0) translateY(-6px)}
.plate-head{display:flex;align-items:center;gap:14px;font-size:14px;font-weight:600;color:rgba(255,255,255,.8)}
.plate-logo{width:48px;height:48px;padding:6px;border-radius:12px;background:#fff}
.plate-logo img{width:100%;height:100%;object-fit:contain}
.plate-num{margin-top:var(--s4);font:800 clamp(64px,7vw,104px)/.95 var(--f-display);letter-spacing:-.05em;font-variant-numeric:tabular-nums}
.plate-cap{margin-top:12px;font-size:17px;font-weight:600;color:#bcd8ff}
.plate-rule{height:1px;margin-block:var(--s3);background:rgba(255,255,255,.18)}
.plate-row{display:flex;justify-content:space-between;gap:var(--s2)}
.plate-row b{display:block;font:700 28px/1.2 var(--f-display);letter-spacing:-.03em;font-variant-numeric:tabular-nums}
.plate-row small{font-size:13px;color:rgba(255,255,255,.7)}
.plate-note{margin-top:var(--s3);display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,.75)}
.dot{width:9px;height:9px;border-radius:50%;background:#54d69b;box-shadow:0 0 0 4px rgba(84,214,155,.18);animation:pulse 2.6s ease-in-out infinite;flex-shrink:0}
@keyframes pulse{50%{box-shadow:0 0 0 8px rgba(84,214,155,0)}}

/* TICKER */
.ticker{overflow:hidden;background:var(--royal);color:#fff;border-block:1px solid rgba(255,255,255,.12)}
.ticker-track{display:flex;width:max-content;animation:marquee 38s linear infinite}
.ticker:hover .ticker-track{animation-play-state:paused}
.ticker-set{display:flex;align-items:center;flex-shrink:0}
.ticker-set span{display:flex;align-items:center;gap:28px;padding:18px 0 18px 28px;font:700 15px/1 var(--f-display);letter-spacing:.02em;white-space:nowrap}
.ticker-set span::after{content:"";width:8px;height:8px;border-radius:50%;background:var(--cyan)}
@keyframes marquee{to{transform:translateX(-50%)}}

/* SECTION HEADS */
.kicker{display:inline-flex;align-items:center;gap:12px;margin-bottom:var(--s2);font-size:15px;font-weight:700;color:var(--royal)}
.kicker::before{content:"";width:32px;height:2px;background:var(--cyan)}
.title{font:800 clamp(34px,4.6vw,60px)/1.1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.lead{font-size:18px;line-height:1.8;color:var(--slate)}

/* ABOUT */
.about{padding-block:var(--s6)}
.about-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:var(--s5);align-items:start}
.about-side{position:sticky;top:130px}
.about-side .title{max-width:14ch}
.about-body p{font-size:19px;line-height:1.85;color:#3c4f66;max-width:66ch}
.about-body p+p{margin-top:var(--s2);color:var(--slate);font-size:17px}
.about-list{margin-top:var(--s4);list-style:none;border-top:1px solid var(--border)}
.about-list li{display:flex;align-items:center;gap:20px;padding:22px 0;border-bottom:1px solid var(--border);
  font:700 clamp(20px,2.2vw,28px)/1.3 var(--f-display);letter-spacing:-.025em;color:var(--navy);transition:transform .35s var(--ease),color .25s}
.about-list li:hover{transform:translateX(14px);color:var(--royal)}
.about-list .tick{width:36px;height:36px;display:grid;place-items:center;border-radius:50%;background:var(--soft);color:var(--royal);flex-shrink:0}
.about-list .tick .icon{width:18px;height:18px}

/* TIMELINE */
.flow{padding-block:var(--s5) var(--s6);background:var(--navy);color:#fff;position:relative;overflow:hidden}
.flow::after{content:"";position:absolute;right:-200px;top:-200px;width:560px;height:560px;border-radius:50%;border:1px solid rgba(255,255,255,.08);box-shadow:0 0 0 80px rgba(255,255,255,.02)}
.flow .kicker{color:#82b7ff}
.flow .title{color:#fff;max-width:18ch}
.flow-line{position:relative;margin-top:var(--s5)}
.flow-rail{position:absolute;left:0;right:0;top:27px;height:2px;background:rgba(255,255,255,.14)}
.flow-rail i{display:block;height:100%;background:linear-gradient(90deg,var(--electric),var(--cyan));transform:scaleX(0);transform-origin:left;transition:transform 1.6s var(--ease) .2s}
.flow-line.in .flow-rail i{transform:scaleX(1)}
.flow-steps{position:relative;display:grid;grid-template-columns:repeat(4,1fr);gap:var(--s3);list-style:none}
.flow-step .num{width:56px;height:56px;display:grid;place-items:center;border-radius:50%;background:var(--midnight);border:2px solid var(--cyan);
  font:800 18px var(--f-display);color:#fff;transition:transform .35s var(--ease),background .25s}
.flow-step:hover .num{transform:scale(1.12);background:var(--royal)}
.flow-step h3{margin-top:var(--s3);font:700 22px/1.3 var(--f-display);letter-spacing:-.02em}
.flow-step p{margin-top:8px;max-width:30ch;font-size:15px;line-height:1.75;color:rgba(255,255,255,.7)}
.flow-step a{display:inline-flex;align-items:center;gap:8px;margin-top:12px;font-weight:700;font-size:15px;color:var(--cyan)}
.flow-step a .icon{width:16px;height:16px;transition:transform .25s var(--ease)}
.flow-step a:hover .icon{transform:translateX(4px)}

/* DASHBOARD */
.dash{position:relative;padding-block:var(--s6);background:var(--ice)}
.dash::before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,var(--navy) 0 30%,var(--royal) 30% 75%,var(--cyan) 75%)}
.dash-head{display:flex;align-items:flex-end;justify-content:space-between;gap:var(--s3);margin-bottom:var(--s4)}
.dash-head .lead{max-width:64ch;margin-top:var(--s2)}
.live{display:inline-flex;align-items:center;gap:10px;padding:10px 18px;border:1px solid #d4e3f5;border-radius:999px;background:#fff;font-size:14px;font-weight:700;color:var(--royal);white-space:nowrap}

/* FILTER */
.filter{position:relative;margin-bottom:var(--s3);padding:var(--s3) 28px 28px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;box-shadow:var(--shadow)}
.filter-top{display:flex;align-items:center;justify-content:space-between;gap:var(--s2);margin-bottom:var(--s3)}
.filter-title{display:flex;align-items:center;gap:12px;font:700 20px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)}
.filter-ico{width:42px;height:42px;display:grid;place-items:center;border-radius:12px;background:var(--soft);color:var(--royal)}
.filter-reset{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;font-size:14px;font-weight:700;color:var(--slate);transition:background .25s,color .25s}
.filter-reset:hover{background:var(--ice);color:var(--royal)}
.filter-reset .icon{width:16px;height:16px}
.filter-grid{display:grid;grid-template-columns:.8fr 1.5fr 1fr auto;gap:var(--s2);align-items:end}
.field label{display:block;margin-bottom:8px;font-size:14px;font-weight:700;color:#40536a}
.field select{width:100%;height:56px;padding:0 40px 0 16px;border:1.5px solid var(--border);border-radius:var(--r);color:var(--ink);font-size:16px;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center;
  appearance:none;-webkit-appearance:none;transition:border-color .25s,box-shadow .25s}
.field select:hover{border-color:#a6bddb}
.field select:focus-visible{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12);outline:none}
.btn-apply{min-height:56px;min-width:200px;padding:0 28px;border-radius:var(--r);background:var(--royal);color:#fff;border:0;display:inline-flex;align-items:center;justify-content:center;gap:10px;
  font-size:16px;font-weight:700;transition:background .25s,transform .25s var(--ease),box-shadow .25s}
.btn-apply:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.25)}
.btn-apply:active{transform:scale(.97)}
.btn-apply .icon{width:18px;height:18px;transition:transform .25s var(--ease)}
.btn-apply:hover .icon.go{transform:translateX(4px)}
.btn-apply .spin{display:none;animation:spin .8s linear infinite}
.filter.is-loading .btn-apply{pointer-events:none;background:var(--navy)}
.filter.is-loading .btn-apply .go{display:none}
.filter.is-loading .btn-apply .spin{display:block}
@keyframes spin{to{transform:rotate(360deg)}}

/* BENTO STATS */
.bento{display:grid;grid-template-columns:1.35fr 1fr 1fr;gap:var(--s2);margin-bottom:var(--s2)}
.stat{position:relative;overflow:hidden;isolation:isolate;padding:30px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;transition:transform .35s var(--ease),box-shadow .35s}
.stat:hover{transform:translateY(-5px);box-shadow:var(--shadow)}
/* spotlight mengikuti kursor: hanya transform + opacity */
.stat::before{content:"";position:absolute;z-index:-1;left:0;top:0;width:340px;height:340px;margin:-170px 0 0 -170px;border-radius:50%;
  background:radial-gradient(circle,rgba(52,120,246,.16),transparent 65%);opacity:0;transform:translate3d(var(--mx,50%),var(--my,50%),0);transition:opacity .3s;pointer-events:none}
.stat:hover::before{opacity:1}
.stat-top{display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:16px;font-weight:700;color:var(--slate)}
.stat-ico{width:48px;height:48px;display:grid;place-items:center;border-radius:14px;background:var(--soft);color:var(--royal)}
.stat-val{margin-top:var(--s3);font:800 clamp(44px,5vw,72px)/1 var(--f-display);letter-spacing:-.05em;color:var(--navy);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.stat-cap{margin-top:12px;font-size:14px;line-height:1.7;color:var(--slate);max-width:34ch}
.stat.feature{background:var(--navy);border-color:var(--navy);color:#fff}
.stat.feature .stat-top{color:rgba(255,255,255,.8)}
.stat.feature .stat-ico{background:rgba(255,255,255,.1);color:var(--cyan)}
.stat.feature .stat-val{color:#fff;font-size:clamp(56px,6.4vw,92px)}
.stat.feature .stat-cap{color:rgba(255,255,255,.7)}
.stat.feature::after{content:"";position:absolute;z-index:-1;right:-90px;bottom:-130px;width:320px;height:320px;border-radius:50%;border:1px solid rgba(130,183,255,.25);box-shadow:0 0 0 44px rgba(130,183,255,.05);animation:spin 60s linear infinite}

/* DATA PANELS */
.panels{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.3fr);
  grid-template-areas:"gen nak" "wil wil";gap:var(--s2);align-items:stretch}
.stack{display:contents}
.panels>.panel:first-child{grid-area:wil}
.stack>.panel:nth-child(1){grid-area:gen}
.stack>.panel:nth-child(2){grid-area:nak}

/* panel mengisi tinggi yang sama, isi tidak menyisakan ruang kosong */
.panels .panel{display:flex;flex-direction:column}
.panels .gender-content,.panels .rank-wrap{flex:1}
.panels .gender-content{align-content:center}

/* rekap wilayah: daftar jadi beberapa kolom */
.panels>.panel:first-child .rows{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));column-gap:56px}
.panel{min-width:0;overflow:hidden;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff}
.panel-head{padding:28px 30px 22px;border-bottom:1px solid var(--border)}
.panel-head h3{font:700 22px/1.35 var(--f-display);letter-spacing:-.025em;color:var(--navy)}
.panel-head p{margin-top:8px;font-size:15px;line-height:1.7;color:var(--slate)}
.panel-body{padding:12px 30px 26px}

.rows{list-style:none}
.row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:6px 16px;align-items:center;padding:16px 0;border-bottom:1px solid #edf1f5}
.row:last-child{border-bottom:0}
.row-name{display:flex;align-items:center;font-size:15px;font-weight:600;color:#3c4f66;overflow-wrap:anywhere}
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
.pos{display:inline-grid;place-items:center;width:26px;height:26px;margin-right:10px;border-radius:8px;background:var(--soft);color:var(--royal);font:700 12px var(--f-display);flex-shrink:0}

/* DIAGRAM JENIS KELAMIN */
.gender-content{padding:30px;display:grid;grid-template-columns:minmax(220px,.85fr) minmax(240px,1fr);align-items:center;gap:40px}
.gender-chart-wrap{width:100%;max-width:340px;aspect-ratio:1/1;margin:0 auto;position:relative}
.gender-chart-wrap canvas{width:100%!important;height:100%!important}
.gender-summary{display:flex;flex-direction:column;gap:18px;min-width:0}
.gender-total{padding-bottom:18px;border-bottom:1px solid var(--border)}
.gender-total strong{display:block;font:800 42px/1.2 var(--f-display);letter-spacing:-.04em;color:var(--navy);font-variant-numeric:tabular-nums}
.gender-total span{font-size:14px;color:var(--slate)}
.gender-legend{display:flex;flex-direction:column;gap:14px}
.gender-legend-item{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 18px;border:1px solid var(--border);border-radius:14px;background:#fff;
  transition:transform .3s var(--ease),box-shadow .3s}
.gender-legend-item:hover{transform:translateX(6px);box-shadow:var(--shadow)}
.gender-legend-label{display:flex;align-items:center;gap:12px;font-size:15px;font-weight:700;color:#3c4f66}
.gender-color{width:13px;height:13px;flex-shrink:0;border-radius:50%}
.gender-legend-value{text-align:right}
.gender-legend-value strong{display:block;font:700 20px/1.3 var(--f-display);color:var(--navy);font-variant-numeric:tabular-nums}
.gender-legend-value small{font-size:13px;color:var(--slate)}
.gender-note{font-size:13px;line-height:1.7;color:var(--slate)}

/* FOOTER */
.footer{position:relative;overflow:hidden;background:var(--navy);color:#fff}
.footer::before{content:"";position:absolute;top:-240px;right:-140px;width:520px;height:520px;border-radius:50%;border:1px solid rgba(255,255,255,.08);box-shadow:0 0 0 70px rgba(255,255,255,.02)}
.footer-main{position:relative;display:grid;grid-template-columns:1.4fr .6fr;gap:var(--s5);padding-block:var(--s5) var(--s4)}
.footer-brand{display:flex;gap:18px;align-items:flex-start}
.footer-logo{width:52px;height:52px;padding:6px;border-radius:12px;background:#fff;flex-shrink:0}
.footer-logo img{width:100%;height:100%;object-fit:contain}
.footer-brand h3{font:800 26px/1.2 var(--f-display);letter-spacing:-.03em;margin-bottom:8px}
.footer-brand p{max-width:60ch;font-size:15px;line-height:1.85;color:rgba(255,255,255,.7)}
.footer-links h4{margin-bottom:var(--s2);font:700 16px var(--f-display);color:#82b7ff}
.footer-links a{display:block;width:fit-content;margin-bottom:10px;font-size:15px;color:rgba(255,255,255,.78);transition:color .25s,transform .25s var(--ease)}
.footer-links a:hover{color:#fff;transform:translateX(5px)}
.footer-bottom{position:relative;border-top:1px solid rgba(255,255,255,.1)}
.footer-bottom .container{display:flex;justify-content:space-between;gap:var(--s2);padding-block:22px;font-size:14px;color:rgba(255,255,255,.65)}

/* REVEAL */
.reveal{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s var(--ease)}
.reveal.in,.no-js .reveal{opacity:1;transform:none}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}

/* RESPONSIVE */
@media (max-width:1100px){
  .nav-link{padding:10px 12px}
  .hero-inner{grid-template-columns:1fr;padding-block:88px}
  .plate{max-width:520px}
  .about-grid{grid-template-columns:1fr;gap:var(--s4)}
  .about-side{position:static}
  .flow-steps{grid-template-columns:1fr 1fr;row-gap:var(--s4)}
  .flow-rail{display:none}
  .filter-grid{grid-template-columns:1fr 1fr}
  .btn-apply{grid-column:1/-1}
  .bento{grid-template-columns:1fr 1fr}
  .stat.feature{grid-column:1/-1}
  .panels{grid-template-columns:1fr;grid-template-areas:"gen" "nak" "wil"}
}
@media (max-width:760px){
  body{font-size:16px}
  :root{--gutter:16px}
  .topbar-inner{justify-content:center}.topbar .hide-m{display:none}
  .nav-inner,.site-header.scrolled .nav-inner{min-height:68px}
  .brand-title{font-size:22px}.brand-sub{font-size:11px;max-width:190px}
  .menu-toggle{display:grid}
  .nav-menu{position:absolute;top:100%;left:0;right:0;display:none;flex-direction:column;align-items:stretch;gap:0;padding:12px var(--gutter) 20px;
    background:#fff;border-top:1px solid var(--border);box-shadow:0 20px 30px rgba(8,27,51,.12)}
  .nav-menu.open{display:flex}
  .nav-link{padding:15px 12px;border-radius:12px;font-size:16px}
  .nav-cta{margin:12px 0 0;min-height:52px;justify-content:center}
  .hero-inner{padding-block:56px 64px;gap:var(--s4)}
  .hero-actions .btn{width:100%}
  .plate{padding:28px 22px 24px;max-width:none}
  .about,.dash{padding-block:72px}
  .about-body p{font-size:17px}
  .flow-steps{grid-template-columns:1fr}
  .dash-head{flex-direction:column;align-items:flex-start}
  .filter{padding:22px 16px}
  .filter-grid{grid-template-columns:1fr}
  .bento{grid-template-columns:1fr}
  .stat{padding:24px}
  .panel-head,.panel-body{padding-inline:18px}
  .rank-wrap{grid-template-columns:1fr}
  .rank{padding:22px 18px}
  .rank+.rank{border-left:0;border-top:1px solid var(--border)}
  .gender-content{grid-template-columns:1fr;gap:24px;padding:24px 18px}
  .gender-chart-wrap{max-width:280px}
  .gender-total strong{font-size:36px}
  .footer-main{grid-template-columns:1fr;gap:var(--s4)}
  .footer-bottom .container{flex-direction:column;gap:4px}
}

@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
  .reveal,.hero-fade{opacity:1;transform:none}
  .hero h1 .ln>span{opacity:1;transform:none}
  .bar i,.flow-rail i{transform:scaleX(1)}
}
</style>
</head>

<body class="no-js">
@php
    $maxDistrict = max(1, (int) collect($trainingByDistrict)->max('total'));
    $maxTop      = max(1, (int) collect($topNakes ?? [])->max('total'));
    $maxBottom   = max(1, (int) collect($bottomNakes ?? [])->max('total'));

    $maleCount = (int) ($genderStats['L'] ?? 0);
    $femaleCount = (int) ($genderStats['P'] ?? 0);
    $genderTotal = $maleCount + $femaleCount;

    $malePercentage = $genderTotal > 0 ? round(($maleCount / $genderTotal) * 100, 1) : 0;
    $femalePercentage = $genderTotal > 0 ? round(($femaleCount / $genderTotal) * 100, 1) : 0;
@endphp

<!-- SISTEM IKON (gaya Lucide, satu sumber) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M10 9H8M16 13H8M16 17H8"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    <symbol id="i-list" viewBox="0 0 24 24"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2M12 11h4M12 16h4M8 11h.01M8 16h.01"/></symbol>
    <symbol id="i-cal" viewBox="0 0 24 24"><path d="M8 2v4M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/></symbol>
    <symbol id="i-sliders" viewBox="0 0 24 24"><path d="M21 4h-7M10 4H3M21 12h-9M8 12H3M21 20h-5M12 20H3M14 2v4M8 10v4M16 18v4"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-up" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="M12 5v14M19 12l-7 7-7-7"/></symbol>
    <symbol id="i-reset" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8M3 3v5h5"/></symbol>
    <symbol id="i-loader" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></symbol>
    <symbol id="i-history" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8M3 3v5h5M12 7v5l4 2"/></symbol>
  </defs>
</svg>

<a href="#dashboard" class="skip">Lewati ke data pelatihan</a>
<div class="progress" id="progress" aria-hidden="true"></div>

<!-- TOPBAR -->
<div class="topbar">
  <div class="container topbar-inner">
    <span><svg class="icon"><use href="#i-clock"/></svg>Portal Informasi Publik Bapelkes</span>
    <span class="hide-m"><svg class="icon"><use href="#i-file"/></svg>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</span>
  </div>
</div>

<!-- HEADER -->
<header class="site-header" id="site-header">
  <div class="container nav-inner">
    <a href="{{ route('public.dashboard') }}" class="brand" aria-label="ARTA Beranda">
      <div class="brand-logo">
        <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}" alt="Logo Provinsi Kalimantan Selatan">
      </div>
      <div>
        <div class="brand-title">ARTA</div>
        <div class="brand-sub">Bapelkes Provinsi Kalimantan Selatan</div>
      </div>
    </a>

    <button type="button" class="menu-toggle" id="menu-toggle" aria-label="Buka menu navigasi" aria-controls="nav-menu" aria-expanded="false">
      <svg class="icon" id="ico-open"><use href="#i-menu"/></svg>
      <svg class="icon" id="ico-close" style="display:none"><use href="#i-x"/></svg>
    </button>

    <nav class="nav-menu" id="nav-menu" aria-label="Navigasi utama">
      <a href="#beranda" class="nav-link active">Beranda</a>
      <a href="#tentang" class="nav-link">Tentang ARTA</a>
      <a href="#alur" class="nav-link">Alur</a>
      <a href="#dashboard" class="nav-link">Data Pelatihan</a>
      <a href="{{ route('public.training-history') }}" class="nav-cta">
        Riwayat Pelatihan
        <svg class="icon"><use href="#i-arrow"/></svg>
      </a>
    </nav>
  </div>
</header>

<main>

<!-- HERO -->
<section class="hero" id="beranda">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="orb o1"></div>
  <div class="orb o2"></div>
  <div class="hero-year" aria-hidden="true">{{ $year }}</div>

  <div class="container hero-inner">
    <div>
      <div class="hero-tag hero-fade f1">
        <svg class="icon"><use href="#i-shield"/></svg>
        Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan
      </div>

      <h1>
        <span class="ln"><span>Rekam Jejak</span></span>
        <span class="ln"><span>Pelatihan Nakes</span></span>
        <span class="ln"><span>Dalam Satu Sistem.</span></span>
      </h1>

      <p class="hero-lead hero-fade f2">
        ARTA menyajikan rekam riwayat dan ringkasan data pelatihan tenaga kesehatan
        Bapelkes Provinsi Kalimantan Selatan. Temukan data berdasarkan tahun,
        kabupaten/kota atau mitra asal peserta, serta sumber dana
        dalam satu aplikasi terintegrasi.
      </p>

      <div class="hero-actions hero-fade f3">
        <a href="#dashboard" class="btn btn-primary">
          Jelajahi Data Pelatihan
          <svg class="icon"><use href="#i-arrow"/></svg>
        </a>
        <a href="#tentang" class="btn btn-ghost">Kenali ARTA</a>
      </div>

      <div class="hero-trust hero-fade f3">
        <svg class="icon"><use href="#i-shield"/></svg>
        Informasi publik dan mudah diakses
      </div>
    </div>

    <aside class="plate" id="plate" aria-label="Ringkasan data pelatihan">
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

<!-- TICKER -->
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    @for ($i = 0; $i < 2; $i++)
      <div class="ticker-set">
        <span>Rekam Riwayat Pelatihan</span>
        <span>Tenaga Kesehatan</span>
        <span>Sebaran Wilayah Peserta</span>
        <span>Sumber Dana</span>
        <span>Bapelkes Kalimantan Selatan</span>
        <span>Data Terintegrasi</span>
      </div>
    @endfor
  </div>
</div>

<!-- ABOUT -->
<section class="about" id="tentang">
  <div class="container about-grid">
    <div class="about-side reveal">
      <div class="kicker">Mengenal ARTA</div>
      <h2 class="title">Merekam riwayat pelatihan tenaga kesehatan.</h2>
    </div>

    <div class="about-body reveal d1">
      <p>
        ARTA adalah Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan yang
        menyajikan informasi pelatihan Bapelkes Provinsi Kalimantan Selatan.
      </p>
      <p>
        Informasi yang tersedia mencakup ringkasan pelatihan, jumlah peserta,
        rekap berdasarkan kabupaten/kota atau mitra asal peserta, serta
        partisipasi tenaga kesehatan, sehingga gambaran data pelatihan
        lebih mudah dipahami.
      </p>

      <ul class="about-list">
        @foreach (['Rekam riwayat pelatihan', 'Ringkasan statistik', 'Filter data pelatihan', 'Rekap partisipasi'] as $point)
          <li>
            <span class="tick"><svg class="icon"><use href="#i-check"/></svg></span>
            {{ $point }}
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>

<!-- TIMELINE ALUR -->
<section class="flow" id="alur">
  <div class="container">
    <div class="reveal">
      <div class="kicker">Alur Membaca Data</div>
      <h2 class="title">Empat langkah mudah memahami data.</h2>
    </div>

    <div class="flow-line reveal d1" id="flow-line">
      <div class="flow-rail" aria-hidden="true"><i></i></div>
      <ol class="flow-steps">
        <li class="flow-step">
          <div class="num">1</div>
          <h3>Pilih Tahun</h3>
          <p>Tentukan tahun pelatihan yang ingin kamu lihat.</p>
        </li>
        <li class="flow-step">
          <div class="num">2</div>
          <h3>Saring Data</h3>
          <p>Pilih wilayah asal peserta atau sumber dana pelatihan.</p>
        </li>
        <li class="flow-step">
          <div class="num">3</div>
          <h3>Baca Ringkasan</h3>
          <p>Lihat total peserta, jumlah pelatihan, sebaran wilayah, dan profil peserta.</p>
        </li>
        <li class="flow-step">
          <div class="num">4</div>
          <h3>Telusuri Riwayat</h3>
          <p>Buka halaman riwayat untuk melihat rincian pelatihan.</p>
          <a href="{{ route('public.training-history') }}">Buka riwayat <svg class="icon"><use href="#i-arrow"/></svg></a>
        </li>
      </ol>
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

    <!-- FILTER (tanpa #dashboard di action agar halaman tidak lompat) -->
    <form method="GET" action="{{ route('public.dashboard') }}" class="filter reveal" id="dashboard-filter" aria-busy="false">
      <div class="filter-top">
        <div class="filter-title">
          <span class="filter-ico"><svg class="icon"><use href="#i-sliders"/></svg></span>
          Filter Data Pelatihan
        </div>
        <a href="{{ route('public.dashboard') }}" class="filter-reset" data-keep-scroll>
          <svg class="icon"><use href="#i-reset"/></svg> Atur ulang
        </a>
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
          <label for="district">Asal Kabupaten/Kota/Mitra/Mandiri Peserta</label>
          <select name="district" id="district">
            <option value="">Semua Wilayah Peserta</option>
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
          <span class="btn-label">Terapkan Filter</span>
          <svg class="icon go"><use href="#i-arrow"/></svg>
          <svg class="icon spin"><use href="#i-loader"/></svg>
        </button>
      </div>
    </form>

    <!-- BENTO STATS -->
    <div class="bento">
      <article class="stat feature reveal">
        <div class="stat-top">
          Peserta yang Sudah Dilatih
          <span class="stat-ico"><svg class="icon"><use href="#i-users"/></svg></span>
        </div>
        <div class="stat-val" data-count="{{ (int) $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</div>
        <p class="stat-cap">Jumlah peserta yang sudah dilatih per individu</p>
      </article>

      <article class="stat reveal d1">
        <div class="stat-top">
          Jumlah Pelatihan
          <span class="stat-ico"><svg class="icon"><use href="#i-list"/></svg></span>
        </div>
        <div class="stat-val" data-count="{{ (int) $totalTrainings }}">{{ number_format($totalTrainings, 0, ',', '.') }}</div>
        <p class="stat-cap">Jumlah pelatihan sesuai filter yang dipilih</p>
      </article>

      <article class="stat reveal d2">
        <div class="stat-top">
          Tahun Laporan
          <span class="stat-ico"><svg class="icon"><use href="#i-cal"/></svg></span>
        </div>
        <div class="stat-val">{{ $year }}</div>
        <p class="stat-cap">Tahun pelaksanaan pelatihan yang sedang ditampilkan</p>
      </article>
    </div>

    <!-- DATA PANELS -->
    <div class="panels">

      <!-- REKAP WILAYAH -->
      <section class="panel reveal">
        <div class="panel-head">
          <h3>Rekap Peserta Berdasarkan Wilayah</h3>
          <p>
            Jumlah peserta unik berdasarkan Kabupaten/Kota/Mitra/Mandiri
            yang tercatat pada data peserta. Wilayah mengikuti data asal
            atau wilayah kerja peserta terbaru.
          </p>
        </div>

        <div class="panel-body">
          @if (collect($trainingByDistrict)->isNotEmpty())
            <ul class="rows" data-bars>
              @foreach ($trainingByDistrict as $row)
                <li class="row">
                  <span class="row-name">{{ $row->district }}</span>
                  <span class="row-total">
                    {{ number_format((int) $row->total, 0, ',', '.') }}
                    <small>org</small>
                  </span>
                  <span class="bar">
                    <i style="--w: {{ round(((int) $row->total / $maxDistrict) * 100) }}%"></i>
                  </span>
                </li>
              @endforeach
            </ul>
          @else
            <div class="empty">Belum ada data peserta untuk filter ini.</div>
          @endif
        </div>
      </section>

      <div class="stack">

        <!-- DIAGRAM PIE JENIS KELAMIN -->
        <section class="panel reveal d1">
          <div class="panel-head">
            <h3>Profil Peserta Berdasarkan Jenis Kelamin</h3>
            <p>
              Perbandingan jumlah peserta laki-laki dan perempuan yang mengikuti
              pelatihan pada tahun {{ $year }} sesuai filter yang dipilih.
            </p>
          </div>

          @if ($genderTotal > 0)
          <div class="gender-content">
            <div class="gender-chart-wrap" id="gender-wrap">
              <canvas id="genderChart" role="img"
                      aria-label="Diagram pie perbandingan peserta laki-laki dan perempuan"></canvas>
            </div>

            <div class="gender-summary">
              <div class="gender-total">
                <strong>{{ number_format($genderTotal, 0, ',', '.') }}</strong>
                <span>Total peserta dengan data jenis kelamin</span>
              </div>

              <div class="gender-legend">
                <div class="gender-legend-item">
                  <div class="gender-legend-label">
                    <span class="gender-color" style="background:#2457D6"></span>
                    Laki-laki
                  </div>
                  <div class="gender-legend-value">
                    <strong>{{ number_format($maleCount, 0, ',', '.') }}</strong>
                    <small>{{ number_format($malePercentage, 1, ',', '.') }}%</small>
                  </div>
                </div>

                <div class="gender-legend-item">
                  <div class="gender-legend-label">
                    <span class="gender-color" style="background:#38C6E8"></span>
                    Perempuan
                  </div>
                  <div class="gender-legend-value">
                    <strong>{{ number_format($femaleCount, 0, ',', '.') }}</strong>
                    <small>{{ number_format($femalePercentage, 1, ',', '.') }}%</small>
                  </div>
                </div>
              </div>

              <p class="gender-note">
                Data dihitung berdasarkan peserta unik yang mengikuti pelatihan.
                Peserta tanpa data jenis kelamin tidak dimasukkan dalam diagram.
              </p>
            </div>
          </div>
          @else
            <div class="empty">Belum ada data jenis kelamin peserta untuk filter ini.</div>
          @endif
        </section>

        <!-- PARTISIPASI TENAGA KESEHATAN -->
        <section class="panel reveal d2">
          <div class="panel-head">
            <h3>Partisipasi Tenaga Kesehatan</h3>
            <p>Peringkat jenis tenaga kesehatan berdasarkan jumlah peserta unik yang mengikuti pelatihan sesuai filter yang dipilih.</p>
          </div>

          <div class="rank-wrap">
            <div class="rank high">
              <div class="rank-title">
                <span class="rank-ico"><svg class="icon"><use href="#i-up"/></svg></span>
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
                <span class="rank-ico"><svg class="icon"><use href="#i-down"/></svg></span>
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
        <h3>ARTA</h3>
        <p>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan, Bapelkes Provinsi Kalimantan Selatan. Menyediakan informasi dan ringkasan data pelatihan melalui satu aplikasi yang mudah diakses.</p>
      </div>
    </div>

    <div class="footer-links">
      <h4>Navigasi</h4>
      <a href="#beranda">Beranda</a>
      <a href="#tentang">Tentang ARTA</a>
      <a href="#alur">Alur Membaca Data</a>
      <a href="#dashboard">Data Pelatihan</a>
      <a href="{{ route('public.training-history') }}">Riwayat Pelatihan</a>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <span>© {{ date('Y') }} ARTA. Hak Cipta Dilindungi.</span>
      <span>Bapelkes Provinsi Kalimantan Selatan</span>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.remove('no-js');

  var root = document.documentElement;
  var header = document.getElementById('site-header');
  var progress = document.getElementById('progress');
  var btn = document.getElementById('menu-toggle');
  var menu = document.getElementById('nav-menu');
  var icoOpen = document.getElementById('ico-open');
  var icoClose = document.getElementById('ico-close');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  /* HEADER + SCROLL PROGRESS */
  var ticking = false;
  function onScroll() {
    header.classList.toggle('scrolled', window.scrollY > 15);
    var max = root.scrollHeight - window.innerHeight;
    progress.style.transform = 'scaleX(' + (max > 0 ? window.scrollY / max : 0) + ')';
    ticking = false;
  }
  onScroll();
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
  }, { passive: true });

  /* =========================================================
     POSISI SCROLL TETAP (tidak lompat ke atas saat filter / loading)
     ========================================================= */
  var SK = 'arta:scroll';
  var saved = null;
  var userMoved = false;

  try {
    var raw = sessionStorage.getItem(SK);
    if (raw) {
      var d = JSON.parse(raw);
      if (d && d.path === location.pathname && Date.now() - d.t < 20000) saved = d;
      sessionStorage.removeItem(SK);
    }
  } catch (e) {}

  function jumpTo(y) {
    root.style.scrollBehavior = 'auto';
    window.scrollTo(0, y);
    root.style.scrollBehavior = '';
    onScroll();
  }

  if (saved) {
    jumpTo(saved.y);
    ['wheel', 'touchstart', 'keydown', 'mousedown'].forEach(function (ev) {
      window.addEventListener(ev, function () { userMoved = true; }, { passive: true, once: true });
    });
    /* font & gambar bisa mengubah tinggi halaman: koreksi sekali setelah load */
    window.addEventListener('load', function () {
      if (!userMoved && Math.abs(window.scrollY - saved.y) > 4) jumpTo(saved.y);
    });
  }

  function saveScroll() {
    try {
      sessionStorage.setItem(SK, JSON.stringify({ y: window.scrollY, path: location.pathname, t: Date.now() }));
    } catch (e) {}
  }

  /* FILTER: simpan posisi + tampilkan loading */
  var form = document.getElementById('dashboard-filter');
  if (form) {
    form.addEventListener('submit', function () {
      saveScroll();
      form.classList.add('is-loading');
      form.setAttribute('aria-busy', 'true');
      form.querySelector('.btn-label').textContent = 'Memuat data…';
    });
  }
  document.querySelectorAll('[data-keep-scroll]').forEach(function (a) {
    a.addEventListener('click', saveScroll);
  });

  /* tombol Back / bfcache: pastikan loading hilang */
  window.addEventListener('pageshow', function (e) {
    if (e.persisted && form) {
      form.classList.remove('is-loading');
      form.setAttribute('aria-busy', 'false');
      form.querySelector('.btn-label').textContent = 'Terapkan Filter';
    }
  });

  /* MOBILE MENU */
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

  /* COUNTER */
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

  /* DIAGRAM PIE (digambar saat terlihat) */
  var genderCanvas = document.getElementById('genderChart');
  var chartStarted = false;
  function startChart() {
    if (chartStarted || !genderCanvas || typeof Chart === 'undefined') return;
    chartStarted = true;

    var maleCount = {{ $maleCount }};
    var femaleCount = {{ $femaleCount }};
    var genderTotal = maleCount + femaleCount;

    new Chart(genderCanvas, {
      type: 'pie',
      data: {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [{
          data: [maleCount, femaleCount],
          backgroundColor: ['#2457D6', '#38C6E8'],
          borderColor: '#ffffff',
          borderWidth: 4,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1,
        plugins: {
          legend: { display: false },
          tooltip: {
            enabled: true,
            backgroundColor: '#081b33',
            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
            padding: 12,
            cornerRadius: 10,
            callbacks: {
              label: function (context) {
                var value = Number(context.raw || 0);
                var percentage = genderTotal > 0 ? ((value / genderTotal) * 100).toFixed(1) : '0.0';
                return ' ' + value.toLocaleString('id-ID') + ' peserta (' + percentage.replace('.', ',') + '%)';
              }
            }
          }
        },
        animation: reduce ? false : { animateRotate: true, animateScale: true, duration: 1100 }
      }
    });
  }

  /* REVEAL + BARS + COUNTERS + CHART + TIMELINE */
  var items = document.querySelectorAll('.reveal, [data-bars], [data-count], #flow-line');
  var chartWrap = document.getElementById('gender-wrap');

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

    if (chartWrap) {
      var co = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) { startChart(); co.disconnect(); }
      }, { threshold: .3 });
      co.observe(chartWrap);
    }
  } else {
    items.forEach(function (el) { el.classList.add('in'); });
    startChart();
  }

  /* ACTIVE NAV */
  var links = document.querySelectorAll('.nav-link[href^="#"]');
  if ('IntersectionObserver' in window) {
    var so = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        links.forEach(function (l) {
          l.classList.toggle('active', l.getAttribute('href') === '#' + en.target.id);
        });
      });
    }, { rootMargin: '-25% 0px -60% 0px' });
    document.querySelectorAll('#beranda, #tentang, #alur, #dashboard').forEach(function (s) { so.observe(s); });
  }

  /* TILT KARTU HERO (desktop, pointer halus) */
  var plate = document.getElementById('plate');
  if (plate && finePointer && !reduce) {
    plate.addEventListener('mousemove', function (e) {
      var r = plate.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width - .5;
      var py = (e.clientY - r.top) / r.height - .5;
      plate.style.transform = 'perspective(1000px) rotateY(' + (px * 9) + 'deg) rotateX(' + (-py * 9) + 'deg) translateY(-6px)';
    });
    plate.addEventListener('mouseleave', function () { plate.style.transform = ''; });
  }

  /* SPOTLIGHT KARTU STATISTIK */
  if (finePointer && !reduce) {
    document.querySelectorAll('.stat').forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        var r = card.getBoundingClientRect();
        card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
        card.style.setProperty('--my', (e.clientY - r.top) + 'px');
      });
    });
  }
});
</script>

</body>
</html>