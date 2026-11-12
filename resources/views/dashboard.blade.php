@extends('layouts.app')

@section('title', 'Dashboard Admin ARTA')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

{{-- ICON SPRITE (Lucide, inline - tanpa dependency tambahan) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
        <symbol id="i-filter" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></symbol>
        <symbol id="i-reset" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></symbol>
        <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></symbol>
        <symbol id="i-down" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></symbol>
        <symbol id="i-pie" viewBox="0 0 24 24"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
        <symbol id="i-steth" viewBox="0 0 24 24"><path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/></symbol>
        <symbol id="i-cal" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
        <symbol id="i-dash" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></symbol>
        <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></symbol>
    </defs>
</svg>

<style>
/* =========================================================
   ARTA ADMIN DASHBOARD — Premium Blue Editorial
   Semua style di-scope ke .da supaya tidak bentrok dengan layout.
   Animasi hanya memakai transform & opacity.
========================================================= */
.da{
  --navy:#081b33; --midnight:#0b2545; --royal:#2457d6; --electric:#3478f6;
  --soft:#eaf2ff; --ice:#f3f7fc; --slate:#64748b; --ink:#14243a; --text:#3c4f66;
  --border:#e1e9f2; --line:#edf1f5; --cyan:#38c6e8;
  --f-display:'Sora',system-ui,sans-serif; --f-body:'Plus Jakarta Sans',system-ui,sans-serif;
  --r-sm:10px; --r:14px; --r-lg:28px;
  --shadow:0 18px 46px rgba(8,27,51,.1);
  --ease:cubic-bezier(.2,.7,.3,1);
  --da-top:12px;               /* jarak sticky nav dari atas (ubah kalau navbar layout sticky) */
  --gutter:clamp(16px,3vw,40px);

  position:relative; width:100%; padding:8px 0 64px; color:var(--ink);
  font:400 16px/1.7 var(--f-body); -webkit-font-smoothing:antialiased;
}
.da *,.da *::before,.da *::after{box-sizing:border-box}
.da button,.da input,.da select{font:inherit}
.da a{text-decoration:none;color:inherit}
.da svg.ic{display:block;width:20px;height:20px;flex-shrink:0;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.da :focus-visible{outline:3px solid rgba(52,120,246,.55);outline-offset:3px;border-radius:8px}
.da section[id]{scroll-margin-top:96px}

/* ---------- Scroll progress + loader ---------- */
.da-progress,.da-loader{position:fixed;left:0;top:0;width:100%;height:3px;z-index:9999;pointer-events:none}
.da-progress i{display:block;width:100%;height:100%;background:linear-gradient(90deg,var(--royal),var(--cyan));transform:scaleX(0);transform-origin:left}
.da-loader{opacity:0;transition:opacity .2s;overflow:hidden;background:rgba(36,87,214,.15)}
.da-loader i{display:block;width:40%;height:100%;background:var(--royal);transform:translateX(-100%)}
.da-loader.on{opacity:1}
.da-loader.on i{animation:da-load 1.1s var(--ease) infinite}
@keyframes da-load{to{transform:translateX(260%)}}

/* ---------- Sticky section nav ---------- */
.da-nav{position:sticky;top:var(--da-top);z-index:50;display:flex;align-items:center;justify-content:space-between;gap:12px;
  margin-bottom:16px;padding:8px 8px 8px 18px;border:1px solid rgba(225,233,242,.9);border-radius:999px;
  background:rgba(255,255,255,.82);-webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px);
  box-shadow:0 8px 30px rgba(8,27,51,.07);transition:box-shadow .3s,transform .3s var(--ease)}
.da-nav.stuck{box-shadow:0 14px 40px rgba(8,27,51,.14)}
.da-brand{display:flex;align-items:center;gap:10px;font:800 17px/1 var(--f-display);letter-spacing:-.02em;color:var(--navy)}
.da-brand .logo{width:34px;height:34px;display:grid;place-items:center;border-radius:11px;background:var(--royal);color:#fff}
.da-brand .logo svg.ic{width:18px;height:18px}
.da-brand small{font:600 12px var(--f-body);letter-spacing:0;color:var(--slate)}
.da-links{display:flex;gap:4px}
.da-links a{position:relative;display:inline-flex;align-items:center;gap:8px;min-height:42px;padding:0 16px;border-radius:999px;font-size:14px;font-weight:700;color:#506176;transition:color .25s,background .25s}
.da-links a svg.ic{width:16px;height:16px}
.da-links a:hover{color:var(--royal);background:var(--soft)}
.da-links a.active{color:#fff;background:var(--royal)}

/* ---------- Hero ---------- */
.da-head{position:relative;display:grid;grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr);align-items:center;gap:clamp(24px,4vw,64px);
  min-height:min(80vh,720px);margin-bottom:0;padding:clamp(36px,6vw,88px) clamp(24px,5vw,80px);overflow:hidden;border-radius:var(--r-lg) var(--r-lg) 0 0;
  color:#fff;background:var(--navy);isolation:isolate}
.da-grid{position:absolute;inset:0;z-index:-3;opacity:.1;pointer-events:none;
  background-image:linear-gradient(rgba(255,255,255,.6) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.6) 1px,transparent 1px);
  background-size:64px 64px;-webkit-mask-image:linear-gradient(100deg,#000 5%,transparent 70%);mask-image:linear-gradient(100deg,#000 5%,transparent 70%)}
.da-glow{position:absolute;z-index:-2;border-radius:50%;pointer-events:none;will-change:transform}
.da-glow.g1{width:620px;height:620px;right:-120px;top:-180px;background:radial-gradient(circle,rgba(52,120,246,.55),transparent 65%);animation:da-drift 18s ease-in-out infinite alternate}
.da-glow.g2{width:520px;height:520px;left:-160px;bottom:-240px;background:radial-gradient(circle,rgba(56,198,232,.3),transparent 65%);animation:da-drift 22s ease-in-out infinite alternate-reverse}
@keyframes da-drift{to{transform:translate3d(-60px,40px,0) scale(1.12)}}
.da-ghost{position:absolute;z-index:-1;left:-10px;bottom:-.17em;font:800 clamp(140px,26vw,400px)/1 var(--f-display);letter-spacing:-.06em;
  color:transparent;-webkit-text-stroke:1.5px rgba(130,183,255,.16);user-select:none;pointer-events:none;white-space:nowrap}
.da-badge{display:inline-flex;align-items:center;gap:10px;margin-bottom:26px;padding:9px 18px 9px 14px;border:1px solid rgba(255,255,255,.2);border-radius:999px;
  background:rgba(255,255,255,.07);font-size:14px;font-weight:600;color:#bcd8ff}
.da-dot{width:9px;height:9px;border-radius:50%;background:#54d69b;box-shadow:0 0 0 4px rgba(84,214,155,.18);animation:da-pulse 2.6s ease-in-out infinite}
@keyframes da-pulse{50%{box-shadow:0 0 0 9px rgba(84,214,155,0)}}
.da-head h1{max-width:15ch;font:800 clamp(40px,6vw,88px)/1.03 var(--f-display);letter-spacing:-.045em}
.da-head h1 em{font-style:normal;background:linear-gradient(90deg,#8fc0ff,var(--cyan));-webkit-background-clip:text;background-clip:text;color:transparent}
.da-head p{max-width:54ch;margin-top:24px;font-size:clamp(16px,1.3vw,19px);line-height:1.8;color:rgba(255,255,255,.76)}
.da-full{margin-top:14px;font:600 14px/1.5 var(--f-body);letter-spacing:.02em;color:#8fb6ea}
.da-cta{display:flex;flex-wrap:wrap;gap:12px;margin-top:34px}
.da-head .da-btn-ghost{background:rgba(255,255,255,.08);color:#fff;border-color:rgba(255,255,255,.25)}
.da-head .da-btn-ghost:hover{background:rgba(255,255,255,.16);color:#fff;border-color:rgba(255,255,255,.45)}
.da-head .da-btn-primary{background:#fff;color:var(--navy)}
.da-head .da-btn-primary:hover{background:var(--cyan);color:var(--navy)}

/* hero visual (orbit) */
.da-orbit{position:relative;width:100%;max-width:460px;aspect-ratio:1;margin-inline:auto}
.da-par{position:absolute;inset:0;transition:transform .25s var(--ease);transform:translate3d(calc(var(--px,0)*var(--k,10)*1px),calc(var(--py,0)*var(--k,10)*1px),0)}
.da-ring{position:absolute;border-radius:50%;border:1.5px dashed rgba(143,192,255,.35);animation:da-spin 40s linear infinite}
.da-ring.r1{inset:0}
.da-ring.r2{inset:13%;border-style:solid;border-color:rgba(143,192,255,.2);animation-duration:60s;animation-direction:reverse}
.da-ring.r3{inset:27%;border-color:rgba(56,198,232,.45);animation-duration:30s}
.da-ring.r1::after,.da-ring.r3::after{content:"";position:absolute;top:-6px;left:50%;width:12px;height:12px;margin-left:-6px;border-radius:50%;background:var(--cyan);box-shadow:0 0 18px var(--cyan)}
@keyframes da-spin{to{transform:rotate(360deg)}}
.da-core{position:absolute;inset:36%;display:grid;place-items:center;border-radius:50%;color:#fff;
  background:radial-gradient(circle at 30% 25%,var(--electric),var(--royal) 60%,#173b96);box-shadow:0 0 60px rgba(52,120,246,.55)}
.da-core svg.ic{width:38%;height:38%;stroke-width:1.6}
.da-core::before,.da-core::after{content:"";position:absolute;inset:0;border-radius:50%;border:2px solid rgba(56,198,232,.6);animation:da-ping 3.2s var(--ease) infinite}
.da-core::after{animation-delay:1.6s}
@keyframes da-ping{from{transform:scale(1);opacity:.8}to{transform:scale(2.1);opacity:0}}
.da-floatbox{position:absolute;z-index:2}
.da-floatbox.f1{top:6%;right:-2%}.da-floatbox.f2{bottom:10%;left:-4%}.da-floatbox.f3{bottom:-2%;right:8%}
.da-fcard{display:flex;align-items:center;gap:12px;padding:12px 18px 12px 12px;border:1px solid rgba(255,255,255,.2);border-radius:18px;
  background:rgba(8,27,51,.55);-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px);box-shadow:0 16px 40px rgba(0,0,0,.25);
  animation:da-bob 6s ease-in-out infinite}
.f2 .da-fcard{animation-delay:-2s}.f3 .da-fcard{animation-delay:-4s}
@keyframes da-bob{50%{transform:translate3d(0,-12px,0)}}
.da-fcard .fi{width:40px;height:40px;display:grid;place-items:center;border-radius:12px;background:rgba(56,198,232,.16);color:var(--cyan)}
.da-fcard small{display:block;font-size:12px;font-weight:600;color:#9dc1ef;line-height:1.3}
.da-fcard b{display:block;font:800 22px/1.2 var(--f-display);letter-spacing:-.03em;font-variant-numeric:tabular-nums}

/* ---------- Ribbon marquee ---------- */
.da-ribbon{position:relative;overflow:hidden;margin-bottom:64px;border-radius:0 0 var(--r-lg) var(--r-lg);background:var(--royal);color:#fff}
.da-ribbon::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:4px;background:linear-gradient(90deg,var(--navy) 0 30%,var(--electric) 30% 70%,var(--cyan) 70%)}
.da-track{display:flex;width:max-content;padding:18px 0;animation:da-marquee 36s linear infinite}
.da-ribbon:hover .da-track{animation-play-state:paused}
.da-track span{display:inline-flex;align-items:center;gap:28px;padding-right:28px;font:700 clamp(15px,1.6vw,20px)/1 var(--f-display);letter-spacing:-.01em;white-space:nowrap}
.da-track span i{width:8px;height:8px;border-radius:50%;background:var(--cyan)}
@keyframes da-marquee{to{transform:translateX(-50%)}}

/* ---------- Section heading ---------- */
.da-kicker{display:inline-flex;align-items:center;gap:12px;margin-bottom:12px;font-size:15px;font-weight:700;color:var(--royal)}
.da-kicker::before{content:"";width:32px;height:2px;background:var(--cyan)}
.da-title{font:800 clamp(28px,3.8vw,52px)/1.1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.da-sub{margin-top:14px;max-width:64ch;font-size:clamp(15px,1.2vw,17px);line-height:1.8;color:var(--slate)}
.da-intro{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:32px}
.da-period{display:inline-flex;align-items:center;gap:10px;flex-shrink:0;padding:11px 20px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);font-size:14px;font-weight:700;color:var(--royal)}
.da-period svg.ic{width:16px;height:16px}

/* ---------- Bento stats ---------- */
.da-bento{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:16px;margin-bottom:16px}
.da-stat{position:relative;grid-column:span 5;min-width:0;overflow:hidden;padding:clamp(24px,3vw,40px);border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;
  transition:transform .35s var(--ease),box-shadow .35s}
.da-stat:hover{transform:translateY(-6px);box-shadow:var(--shadow)}
.da-stat-top{display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:16px;font-weight:700;color:var(--slate)}
.da-ico{width:52px;height:52px;display:grid;place-items:center;flex-shrink:0;border-radius:16px;background:var(--soft);color:var(--royal)}
.da-ico svg.ic{width:24px;height:24px}
.da-val{margin-top:clamp(20px,3vw,40px);font:800 clamp(56px,7vw,104px)/1 var(--f-display);letter-spacing:-.05em;color:var(--navy);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.da-cap{max-width:40ch;margin-top:18px;padding-top:16px;border-top:1px solid var(--line);font-size:14px;line-height:1.7;color:var(--slate)}
.da-stat.feature{grid-column:span 7;background:var(--navy);border-color:var(--navy);color:#fff}
.da-stat.feature .da-stat-top{color:rgba(255,255,255,.75)}
.da-stat.feature .da-ico{background:rgba(255,255,255,.1);color:var(--cyan)}
.da-stat.feature .da-val{color:#fff;font-size:clamp(68px,10vw,148px)}
.da-stat.feature .da-cap{color:rgba(255,255,255,.65);border-color:rgba(255,255,255,.15)}
.da-stat.feature::after{content:"";position:absolute;right:-110px;bottom:-150px;width:380px;height:380px;border-radius:50%;
  border:1px solid rgba(130,183,255,.25);box-shadow:0 0 0 52px rgba(130,183,255,.05),0 0 0 104px rgba(130,183,255,.03);pointer-events:none}

/* ---------- Gender ---------- */
.da-gender{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);align-items:center;gap:clamp(28px,5vw,72px);margin-bottom:72px;padding:clamp(24px,4vw,56px);
  border:1px solid var(--border);border-radius:var(--r-lg);background:var(--ice)}
.da-gender h3{font:700 clamp(24px,2.8vw,38px)/1.2 var(--f-display);letter-spacing:-.035em;color:var(--navy)}
.da-gender p{margin-top:14px;max-width:46ch;font-size:16px;line-height:1.8;color:var(--slate)}
.da-gtotal{display:flex;align-items:baseline;gap:12px;margin-top:26px;padding-top:20px;border-top:1px solid var(--border);font-size:15px;color:var(--slate)}
.da-gtotal strong{font:800 clamp(32px,3.4vw,44px)/1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.da-legend{display:grid;gap:12px;margin-top:26px}
.da-leg{display:grid;grid-template-columns:auto minmax(0,1fr) auto;align-items:center;gap:16px;padding:18px 20px;border:1px solid var(--border);border-radius:var(--r);background:#fff;
  cursor:default;transition:transform .3s var(--ease),box-shadow .3s,border-color .3s}
.da-leg:hover,.da-leg.hot{transform:translateX(6px);border-color:#b9cfec;box-shadow:0 10px 28px rgba(8,27,51,.08)}
.da-leg i{width:16px;height:16px;border-radius:50%}
.da-leg small{display:block;font-size:13px;font-weight:600;color:var(--slate)}
.da-leg b{display:block;font:700 15px/1.4 var(--f-display);color:var(--navy)}
.da-leg .pct{font:800 clamp(24px,2.6vw,32px)/1 var(--f-display);letter-spacing:-.04em;color:var(--navy);font-variant-numeric:tabular-nums}
.da-leg .da-bar{grid-column:1/-1;margin-top:-2px}
.da-stage{position:relative;display:grid;place-items:center;padding:clamp(16px,3vw,36px);border-radius:var(--r-lg);background:#fff;border:1px solid var(--border);box-shadow:0 20px 50px rgba(8,27,51,.06)}
.da-chart{position:relative;width:100%;max-width:440px;aspect-ratio:1}
.da-chart canvas{display:block;width:100%!important;height:100%!important}

/* ---------- Empty year ---------- */
.da-empty-year{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:280px;margin-bottom:72px;padding:40px 24px;
  border:1.5px dashed #c8d6e4;border-radius:var(--r-lg);background:#fff;text-align:center}
.da-empty-year .da-ico{width:64px;height:64px;margin-bottom:18px}
.da-empty-year h3{font:700 clamp(22px,2.4vw,28px)/1.3 var(--f-display);letter-spacing:-.03em;color:var(--navy)}
.da-empty-year p{max-width:52ch;margin-top:10px;font-size:16px;line-height:1.8;color:var(--slate)}

/* ---------- Panels ---------- */
.da-panel{min-width:0;margin-bottom:56px;padding:clamp(20px,3.4vw,48px);border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;box-shadow:0 8px 30px rgba(8,27,51,.04)}
.da-panel-head{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:30px}
.da-tag{display:inline-flex;align-items:center;gap:9px;flex-shrink:0;padding:9px 16px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);
  font-size:13px;font-weight:700;color:var(--royal);white-space:nowrap}
.da-tag::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--royal)}

/* ---------- Filter ---------- */
.da-filter{display:flex;align-items:flex-end;flex-wrap:wrap;gap:14px;margin-bottom:28px;padding:clamp(16px,2vw,24px);border:1px solid var(--border);border-radius:var(--r);background:var(--ice)}
.da-field{display:flex;flex:1 1 220px;flex-direction:column;gap:8px;min-width:160px}
.da-field label{font-size:14px;font-weight:700;color:#40536a}
.da-field select{width:100%;min-height:54px;padding:10px 42px 10px 16px;border:1.5px solid var(--border);border-radius:var(--r);color:var(--ink);font-size:16px;outline:none;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center;
  appearance:none;-webkit-appearance:none;cursor:pointer;transition:border-color .25s,box-shadow .25s}
.da-field select:hover{border-color:#a6bddb}
.da-field select:focus{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12)}

/* ---------- Buttons ---------- */
.da-btn{position:relative;display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:54px;padding:0 28px;border:1.5px solid transparent;border-radius:var(--r);
  font-size:16px;font-weight:700;cursor:pointer;transition:background .25s,color .25s,border-color .25s,transform .25s var(--ease),box-shadow .25s}
.da-btn:active{transform:scale(.97)}
.da-btn svg.ic{width:18px;height:18px}
.da-btn-primary{background:var(--royal);color:#fff}
.da-btn-primary:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.28)}
.da-btn-ghost{background:#fff;color:#506176;border-color:var(--border)}
.da-btn-ghost:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.da-spin{display:none;width:18px;height:18px;border:2.5px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:da-rot .8s linear infinite}
@keyframes da-rot{to{transform:rotate(360deg)}}
.da-btn.is-loading{pointer-events:none;opacity:.9}
.da-btn.is-loading .da-spin{display:inline-block}
.da-btn.is-loading svg.ic{display:none}
.da-table-wrap.is-loading{opacity:.5;pointer-events:none;transition:opacity .25s}

/* ---------- Table ---------- */
.da-table-wrap{width:100%;overflow-x:auto;border:1px solid var(--border);border-radius:var(--r);scrollbar-width:thin;scrollbar-color:#b8c8d9 #f1f5f8;transition:opacity .25s}
.da table{width:100%;min-width:560px;margin:0;border-collapse:separate;border-spacing:0;background:#fff;text-align:left}
.da thead th{padding:18px 22px;border-bottom:1px solid var(--border);background:var(--ice);font:700 13px/1.3 var(--f-body);letter-spacing:.03em;color:#506176;white-space:nowrap}
.da tbody td{padding:18px 22px;border-bottom:1px solid var(--line);font-size:15px;color:var(--text);vertical-align:middle}
.da tbody tr{transition:background .2s}
.da tbody tr:hover{background:#f7faff}
.da tbody tr:last-child td{border-bottom:0}
.da tbody td:first-child{font-weight:700;color:var(--ink)}
.da-count{display:flex;align-items:center;gap:14px;min-width:170px}
.da-pill{display:inline-grid;place-items:center;min-width:46px;min-height:34px;padding:0 12px;border:1px solid #dce8fa;border-radius:999px;background:var(--soft);
  font:700 14px var(--f-display);color:var(--royal);font-variant-numeric:tabular-nums}
.da-bar{flex:1;height:7px;min-width:60px;border-radius:99px;background:var(--soft);overflow:hidden}
.da-bar i{display:block;width:var(--w);height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--royal),var(--cyan));
  transform:scaleX(0);transform-origin:left;transition:transform 1.1s var(--ease)}
.da-in .da-bar i,.da-nojs .da-bar i{transform:scaleX(1)}
.da-person{display:flex;align-items:center;gap:14px;min-width:200px;font-weight:700;color:var(--ink)}
.da-avatar{width:40px;height:40px;display:grid;place-items:center;flex-shrink:0;border-radius:12px;background:var(--soft);color:var(--royal)}
.da-avatar svg.ic{width:20px;height:20px}
.da-empty{padding:44px 20px!important;text-align:center;font-size:15px;line-height:1.8;color:var(--slate)!important;font-weight:500!important}
.da-hint{display:flex;flex-direction:column;align-items:center;gap:14px;padding:44px 20px;border:1.5px dashed #c8d6e4;border-radius:var(--r);text-align:center;font-size:16px;line-height:1.8;color:var(--slate)}
.da-hint strong{color:var(--navy)}
.da-hint .da-ico{width:56px;height:56px}

/* ---------- Chips + note ---------- */
.da-chips{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px}
.da-chip{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);font-size:14px;font-weight:600;color:#40536a}
.da-chip strong{font-weight:800;color:var(--royal)}
.da-note{display:flex;align-items:flex-start;gap:14px;margin-top:24px;padding:18px 22px;border-left:4px solid var(--royal);border-radius:0 var(--r) var(--r) 0;background:var(--ice);font-size:14px;line-height:1.8;color:var(--text)}
.da-note svg.ic{margin-top:3px;color:var(--royal)}
.da-note strong{color:var(--navy)}

/* ---------- Pagination (Laravel tailwind / bootstrap) ---------- */
.da-pager{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-top:24px}
.da-pager>p{font-size:14px;color:var(--slate)}
.da-pager nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.da-pager nav p{display:none}
.da-pager nav>div,.da-pager nav [class*="inline-flex"],.da-pager ul.pagination{display:flex;flex-wrap:wrap;gap:6px;margin:0;padding:0;list-style:none}
.da-pager nav svg{width:16px;height:16px}
.da-pager a,.da-pager span[aria-current="page"] span,.da-pager span[aria-disabled="true"] span,.da-pager .page-link{display:inline-flex;align-items:center;justify-content:center;min-width:42px;min-height:42px;padding:6px 14px;
  border:1px solid var(--border);border-radius:var(--r-sm);background:#fff;font-size:14px;font-weight:700;color:#506176;transition:.25s var(--ease)}
.da-pager a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.da-pager span[aria-current="page"] span,.da-pager .active .page-link{color:#fff;background:var(--royal);border-color:var(--royal)}
.da-pager span[aria-disabled="true"] span,.da-pager .disabled .page-link{opacity:.45}

/* ---------- Reveal ---------- */
.da-rv{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s var(--ease);transition-delay:calc(var(--d,0)*90ms)}
.da-in.da-rv,.da-nojs .da-rv{opacity:1;transform:none}

/* ---------- Responsive ---------- */
@media (max-width:1100px){
  .da-head{grid-template-columns:1fr;min-height:auto}
  .da-orbit{max-width:360px;margin-top:8px}
  .da-bento{grid-template-columns:1fr}
  .da-stat,.da-stat.feature{grid-column:auto}
  .da-gender{grid-template-columns:1fr}
}
@media (max-width:640px){
  .da{font-size:15px;padding-bottom:36px}
  .da-nav{padding:6px 6px 6px 14px}
  .da-brand small{display:none}
  .da-links a{padding:0 12px;min-height:40px}
  .da-links a svg.ic{display:none}
  .da-head{padding:32px 20px 40px;border-radius:20px 20px 0 0}
  .da-orbit{max-width:290px}
  .da-fcard{padding:9px 14px 9px 9px}.da-fcard b{font-size:18px}.da-fcard .fi{width:34px;height:34px}
  .da-ribbon{margin-bottom:44px;border-radius:0 0 20px 20px}
  .da-intro,.da-panel-head{flex-direction:column;align-items:flex-start;gap:14px}
  .da-stat{border-radius:22px}
  .da-gender{margin-bottom:48px;border-radius:22px}
  .da-leg:hover,.da-leg.hot{transform:none}
  .da-panel{margin-bottom:36px;padding:20px 14px;border-radius:22px}
  .da-filter{flex-direction:column;align-items:stretch}
  .da-field{flex:1 1 auto;min-width:100%}
  .da-btn{width:100%}
  .da-cta .da-btn{flex:1 1 100%}
  .da-pager{flex-direction:column;align-items:flex-start}
  .da-pager nav{justify-content:flex-start}
  .da-note{padding:16px}

  /* tabel -> kartu di HP, tanpa scroll horizontal */
  .da-table-wrap{border:0;overflow:visible}
  .da table,.da tbody,.da tr,.da td{display:block;min-width:0;width:100%}
  .da thead{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)}
  .da tbody tr{margin-bottom:12px;padding:10px 16px;border:1px solid var(--border);border-radius:var(--r);background:#fff}
  .da tbody td{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:10px 0;border-bottom:1px dashed var(--line);text-align:right}
  .da tbody td:last-child{border-bottom:0}
  .da tbody td::before{content:attr(data-label);flex-shrink:0;font-size:12px;font-weight:700;letter-spacing:.03em;color:var(--slate);text-align:left}
  .da tbody td.da-empty{display:block;text-align:center}
  .da tbody td.da-empty::before{content:none}
  .da-person{min-width:0}
  .da-count{min-width:0;flex:1;justify-content:flex-end}
}
@media (prefers-reduced-motion:reduce){
  .da *,.da *::before,.da *::after{animation:none!important;transition-duration:.01ms!important}
  .da-rv{opacity:1;transform:none}
  .da-bar i{transform:scaleX(1)}
}
</style>

@php
    /* ---------- DATA DEFAULTS (logika asli dipertahankan) ---------- */
    $totalParticipants = (int) ($totalParticipants ?? 0);
    $totalTrainings = (int) ($totalTrainings ?? 0);

    $year = $year ?? now()->year;
    $years = $years ?? collect([$year]);

    $sort = $sort ?? 'desc';
    $selectedProfession = $selectedProfession ?? '';

    $frequentParticipants = $frequentParticipants ?? collect();
    $professionOptions = $professionOptions ?? collect();
    $trainedParticipants = $trainedParticipants ?? collect();

    $genderStats = $genderStats ?? ['L' => 0, 'P' => 0];

    $maleCount = (int) ($genderStats['L'] ?? 0);
    $femaleCount = (int) ($genderStats['P'] ?? 0);
    $genderTotal = $maleCount + $femaleCount;

    $malePercent = $genderTotal > 0 ? round(($maleCount / $genderTotal) * 100, 1) : 0;
    $femalePercent = $genderTotal > 0 ? round(($femaleCount / $genderTotal) * 100, 1) : 0;

    $hasYearData = $totalParticipants > 0 || $totalTrainings > 0;

    $isFrequentPaginated = $frequentParticipants instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $isTrainedPaginated = $trainedParticipants instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $trainedParticipantTotal = $isTrainedPaginated ? $trainedParticipants->total() : $trainedParticipants->count();
    $trainedParticipantStart = method_exists($trainedParticipants, 'firstItem') ? ($trainedParticipants->firstItem() ?? 1) : 1;

    /* Nilai maksimum untuk bar proporsional (hanya tampilan) */
    $freqItems = $isFrequentPaginated ? $frequentParticipants->getCollection() : collect($frequentParticipants);
    $trainedItems = $isTrainedPaginated ? $trainedParticipants->getCollection() : collect($trainedParticipants);
    $maxFreq = max(1, (int) $freqItems->max('participations_count'));
    $maxTrained = max(1, (int) $trainedItems->max('participations_count'));
@endphp

<div class="da da-nojs" id="da-root">
    {{-- Hilangkan mode no-JS sebelum cat pertama agar tidak berkedip --}}
    <script>document.getElementById('da-root').classList.remove('da-nojs');</script>

    <div class="da-progress" aria-hidden="true"><i id="da-progress"></i></div>
    <div class="da-loader" id="da-loader" aria-hidden="true"><i></i></div>

    {{-- NAV STICKY --}}
    <nav class="da-nav" id="da-nav" aria-label="Navigasi dashboard">
        <div class="da-brand">
            <span class="logo" aria-hidden="true"><svg class="ic"><use href="#i-steth"/></svg></span>
            <span>ARTA <small>Dashboard Admin</small></span>
        </div>
        <div class="da-links">
            <a href="#ringkasan" data-spy="ringkasan"><svg class="ic" aria-hidden="true"><use href="#i-dash"/></svg>Ringkasan</a>
            <a href="#keikutsertaan" data-spy="keikutsertaan"><svg class="ic" aria-hidden="true"><use href="#i-users"/></svg>Peserta</a>
            <a href="#profesi" data-spy="profesi"><svg class="ic" aria-hidden="true"><use href="#i-steth"/></svg>Profesi</a>
        </div>
    </nav>

    {{-- HERO --}}
    <header class="da-head" id="da-hero">
        <div class="da-grid" aria-hidden="true"></div>
        <div class="da-glow g1" aria-hidden="true"></div>
        <div class="da-glow g2" aria-hidden="true"></div>
        <div class="da-ghost" aria-hidden="true">ARTA</div>

        <div>
            <div class="da-badge"><span class="da-dot"></span> Dashboard Admin ARTA</div>
            <h1>Rekam jejak pelatihan, <em>jelas</em> dalam satu layar.</h1>
            <p class="da-full">Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</p>
            <p>
                Pantau perkembangan, evaluasi keikutsertaan, serta telusuri
                data peserta berdasarkan tahun dan jenis profesi.
            </p>
            <div class="da-cta">
                <a href="#ringkasan" class="da-btn da-btn-primary">
                    <svg class="ic" aria-hidden="true"><use href="#i-down"/></svg>
                    Lihat Ringkasan
                </a>
                <a href="#keikutsertaan" class="da-btn da-btn-ghost">Telusuri Peserta</a>
            </div>
        </div>

        <div class="da-orbit" aria-hidden="true">
            <div class="da-par" style="--k:6"><div class="da-ring r1"></div><div class="da-ring r2"></div><div class="da-ring r3"></div></div>
            <div class="da-par" style="--k:12"><div class="da-core"><svg class="ic"><use href="#i-steth"/></svg></div></div>

            <div class="da-floatbox f1"><div class="da-par" style="position:static;--k:22">
                <div class="da-fcard"><span class="fi"><svg class="ic"><use href="#i-users"/></svg></span>
                    <div><small>Peserta {{ $year }}</small><b data-count="{{ $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</b></div></div>
            </div></div>

            <div class="da-floatbox f2"><div class="da-par" style="position:static;--k:-18">
                <div class="da-fcard"><span class="fi"><svg class="ic"><use href="#i-book"/></svg></span>
                    <div><small>Pelatihan {{ $year }}</small><b data-count="{{ $totalTrainings }}">{{ number_format($totalTrainings, 0, ',', '.') }}</b></div></div>
            </div></div>

            <div class="da-floatbox f3"><div class="da-par" style="position:static;--k:14">
                <div class="da-fcard"><span class="fi"><svg class="ic"><use href="#i-cal"/></svg></span>
                    <div><small>Periode</small><b>{{ $year }}</b></div></div>
            </div></div>
        </div>
    </header>

    {{-- RIBBON --}}
    <div class="da-ribbon" aria-hidden="true">
        <div class="da-track">
            @for ($r = 0; $r < 2; $r++)
                <span>Rekam <i></i> Riwayat <i></i> Pelatihan <i></i> Tenaga Kesehatan <i></i> Rekam <i></i> Riwayat <i></i> Pelatihan <i></i> Tenaga Kesehatan <i></i></span>
            @endfor
        </div>
    </div>


    {{-- RINGKASAN DATA --}}
    <section id="ringkasan" aria-labelledby="summary-title">

        <div class="da-intro da-rv">
            <div>
                <div class="da-kicker">Ringkasan data</div>
                <h2 class="da-title" id="summary-title">Gambaran Umum ARTA</h2>
                <p class="da-sub">Informasi peserta dan pelatihan pada tahun {{ $year }}.</p>
            </div>
            <div class="da-period"><svg class="ic" aria-hidden="true"><use href="#i-cal"/></svg>Periode {{ $year }}</div>
        </div>

        @if ($hasYearData)

            <div class="da-bento">

                <article class="da-stat feature da-rv">
                    <div class="da-stat-top">
                        Peserta Terlatih Tahun {{ $year }}
                        <span class="da-ico" aria-hidden="true"><svg class="ic"><use href="#i-users"/></svg></span>
                    </div>
                    <div class="da-val" data-count="{{ $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</div>
                    <p class="da-cap">Jumlah peserta unik yang mengikuti pelatihan pada tahun terpilih.</p>
                </article>

                <article class="da-stat da-rv" style="--d:1">
                    <div class="da-stat-top">
                        Total Pelatihan Tahun {{ $year }}
                        <span class="da-ico" aria-hidden="true"><svg class="ic"><use href="#i-book"/></svg></span>
                    </div>
                    <div class="da-val" data-count="{{ $totalTrainings }}">{{ number_format($totalTrainings, 0, ',', '.') }}</div>
                    <p class="da-cap">Jumlah program pelatihan yang dilaksanakan pada tahun terpilih.</p>
                </article>

            </div>


            {{-- JENIS KELAMIN --}}
            @if ($genderTotal > 0)

                <section class="da-gender da-rv" aria-labelledby="gender-title">

                    <div>
                        <div class="da-kicker">Komposisi peserta</div>
                        <h3 id="gender-title">Profil Peserta Berdasarkan Jenis Kelamin</h3>
                        <p>
                            Perbandingan jumlah peserta laki-laki dan perempuan
                            yang mengikuti pelatihan pada tahun {{ $year }}.
                        </p>
                        <div class="da-gtotal">
                            <strong>{{ number_format($genderTotal, 0, ',', '.') }}</strong>
                            orang total peserta
                        </div>

                        <div class="da-legend">
                            <div class="da-leg" data-idx="0">
                                <i style="background:#2457d6"></i>
                                <div>
                                    <small>Laki-laki</small>
                                    <b>{{ number_format($maleCount, 0, ',', '.') }} peserta</b>
                                </div>
                                <span class="pct">{{ number_format($malePercent, 1, ',', '.') }}%</span>
                                <span class="da-bar"><i style="--w: {{ $malePercent }}%"></i></span>
                            </div>

                            <div class="da-leg" data-idx="1">
                                <i style="background:#38c6e8"></i>
                                <div>
                                    <small>Perempuan</small>
                                    <b>{{ number_format($femaleCount, 0, ',', '.') }} peserta</b>
                                </div>
                                <span class="pct">{{ number_format($femalePercent, 1, ',', '.') }}%</span>
                                <span class="da-bar"><i style="--w: {{ $femalePercent }}%"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="da-stage">
                        <div class="da-chart">
                            <canvas id="genderPieChart" role="img"
                                    aria-label="Diagram lingkaran jenis kelamin peserta tahun {{ $year }}"></canvas>
                        </div>
                    </div>

                </section>

            @endif

        @else

            <div class="da-empty-year da-rv">
                <div class="da-ico" aria-hidden="true"><svg class="ic"><use href="#i-alert"/></svg></div>
                <h3>Data Tahun {{ $year }} Belum Tersedia</h3>
                <p>
                    Belum terdapat data peserta maupun pelatihan pada tahun ini.
                    Ringkasan dan diagram akan ditampilkan setelah data tersedia.
                </p>
            </div>

        @endif

    </section>


    {{-- KEIKUTSERTAAN PESERTA --}}
    <section class="da-panel da-rv" id="keikutsertaan" aria-labelledby="frequent-title">

        <div class="da-panel-head">
            <div>
                <div class="da-kicker">Pemantauan peserta</div>
                <h2 class="da-title" id="frequent-title">Jumlah Keikutsertaan Peserta dalam Pelatihan</h2>
                <p class="da-sub">
                    Telusuri jumlah pelatihan yang diikuti setiap peserta
                    berdasarkan tahun dan urutan jumlah keikutsertaan.
                </p>
            </div>
            <div class="da-tag">Data peserta</div>
        </div>

        <form method="GET" action="{{ url()->current() }}#keikutsertaan" class="da-filter">

            <div class="da-field">
                <label for="participant-year">Tahun Pelatihan</label>
                <select id="participant-year" name="year">
                    @foreach ($years as $availableYear)
                        <option value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="da-field">
                <label for="participant-sort">Urutan Jumlah Pelatihan</label>
                <select id="participant-sort" name="sort">
                    <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Jumlah Terendah</option>
                    <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Jumlah Tertinggi</option>
                </select>
            </div>

            {{-- pertahankan pilihan profesi saat filter peserta diterapkan --}}
            @if ($selectedProfession !== '')
                <input type="hidden" name="profession" value="{{ $selectedProfession }}">
            @endif

            <button type="submit" class="da-btn da-btn-primary">
                <svg class="ic" aria-hidden="true"><use href="#i-filter"/></svg>
                <span class="da-spin" aria-hidden="true"></span>
                Terapkan Filter
            </button>

            <a href="{{ url()->current() }}" class="da-btn da-btn-ghost" data-keep>
                <svg class="ic" aria-hidden="true"><use href="#i-reset"/></svg>
                Reset
            </a>

        </form>

        <div class="da-table-wrap" data-bars>
            <table id="participantTable">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>NIP</th>
                        <th>Jumlah Pelatihan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($frequentParticipants as $participant)
                        @php $freqCount = (int) ($participant->participations_count ?? 0); @endphp
                        <tr>
                            <td data-label="Nama">{{ $participant->name ?? '-' }}</td>
                            <td data-label="NIP">{{ $participant->nip ?? '-' }}</td>
                            <td data-label="Pelatihan">
                                <div class="da-count">
                                    <span class="da-pill">{{ number_format($freqCount, 0, ',', '.') }}</span>
                                    <span class="da-bar"><i style="--w: {{ round(($freqCount / $maxFreq) * 100) }}%"></i></span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="da-empty">Belum ada data peserta untuk tahun {{ $year }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($isFrequentPaginated)
            <div class="da-pager">
                <p>
                    Menampilkan {{ $frequentParticipants->firstItem() ?? 0 }}
                    sampai {{ $frequentParticipants->lastItem() ?? 0 }}
                    dari {{ $frequentParticipants->total() }} peserta
                </p>
                {{ $frequentParticipants->onEachSide(1)->links() }}
            </div>
        @endif

        <div class="da-note">
            <svg class="ic" aria-hidden="true"><use href="#i-info"/></svg>
            <div>
                <strong>Catatan:</strong>
                Jumlah pelatihan dihitung berdasarkan riwayat keikutsertaan
                peserta pada tahun yang dipilih. Gunakan pilihan urutan untuk
                melihat peserta dengan jumlah pelatihan terendah atau tertinggi.
            </div>
        </div>

    </section>


    {{-- PESERTA BERDASARKAN PROFESI --}}
    <section class="da-panel da-rv" id="profesi" aria-labelledby="profession-title">

        <div class="da-panel-head">
            <div>
                <div class="da-kicker">Data peserta terlatih</div>
                <h2 class="da-title" id="profession-title">Peserta Terlatih Berdasarkan Jenis Profesi</h2>
                <p class="da-sub">
                    Temukan daftar peserta berdasarkan kategori profesi
                    dan tahun pelaksanaan pelatihan.
                </p>
            </div>
            <div class="da-tag">Data profesi</div>
        </div>

        <form method="GET" action="{{ url()->current() }}#profesi" class="da-filter">

            <div class="da-field">
                <label for="profession-year">Tahun Pelatihan</label>
                <select id="profession-year" name="year" required>
                    @foreach ($years as $availableYear)
                        <option value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="da-field">
                <label for="profession">Jenis Profesi</label>
                <select id="profession" name="profession" required>
                    <option value="">-- Pilih Profesi --</option>
                    @foreach ($professionOptions as $professionOption)
                        <option value="{{ $professionOption }}"
                            {{ mb_strtolower(trim((string) $selectedProfession)) === mb_strtolower(trim((string) $professionOption)) ? 'selected' : '' }}>
                            {{ $professionOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="sort" value="{{ $sort }}">

            <button type="submit" class="da-btn da-btn-primary">
                <svg class="ic" aria-hidden="true"><use href="#i-filter"/></svg>
                <span class="da-spin" aria-hidden="true"></span>
                Tampilkan Peserta
            </button>

            <a href="{{ url()->current() }}" class="da-btn da-btn-ghost" data-keep>
                <svg class="ic" aria-hidden="true"><use href="#i-reset"/></svg>
                Reset
            </a>

        </form>

        @if ($selectedProfession !== '')

            <div class="da-chips">
                <span class="da-chip">Profesi: <strong>{{ $selectedProfession }}</strong></span>
                <span class="da-chip">Tahun: <strong>{{ $year }}</strong></span>
                <span class="da-chip">Jumlah peserta: <strong>{{ number_format($trainedParticipantTotal, 0, ',', '.') }}</strong></span>
            </div>

            <div class="da-table-wrap" data-bars>
                <table id="professionTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Peserta</th>
                            <th>NIP</th>
                            <th>Profesi</th>
                            <th>Jumlah Pelatihan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($trainedParticipants as $index => $participant)
                            @php $trainedCount = (int) ($participant->participations_count ?? 0); @endphp
                            <tr>
                                <td data-label="No.">{{ $trainedParticipantStart + $index }}</td>

                                <td data-label="Nama">
                                    <div class="da-person">
                                        <span class="da-avatar" aria-hidden="true"><svg class="ic"><use href="#i-user"/></svg></span>
                                        {{ $participant->name ?? '-' }}
                                    </div>
                                </td>

                                <td data-label="NIP">{{ $participant->nip ?? '-' }}</td>
                                <td data-label="Profesi">{{ $participant->profession ?? '-' }}</td>

                                <td data-label="Pelatihan">
                                    <div class="da-count">
                                        <span class="da-pill">{{ number_format($trainedCount, 0, ',', '.') }}</span>
                                        <span class="da-bar"><i style="--w: {{ round(($trainedCount / $maxTrained) * 100) }}%"></i></span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="da-empty">
                                    Tidak ditemukan peserta dengan profesi
                                    "{{ $selectedProfession }}" pada tahun {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($isTrainedPaginated)
                <div class="da-pager">
                    <p>
                        Menampilkan {{ $trainedParticipants->firstItem() ?? 0 }}
                        sampai {{ $trainedParticipants->lastItem() ?? 0 }}
                        dari {{ $trainedParticipants->total() }} peserta
                    </p>
                    {{ $trainedParticipants->onEachSide(1)->links() }}
                </div>
            @endif

        @else

            <div class="da-hint">
                <span class="da-ico" aria-hidden="true"><svg class="ic"><use href="#i-steth"/></svg></span>
                <div>
                    Pilih tahun dan jenis profesi, kemudian klik
                    <strong>Tampilkan Peserta</strong> untuk melihat daftar peserta.
                </div>
            </div>

        @endif

    </section>

</div>


{{-- CHART.JS — PIE CHART JENIS KELAMIN (pie penuh) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var root = document.getElementById('da-root');
    if (!root) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var coarse = window.matchMedia('(pointer: coarse)').matches;
    var KEY = 'arta:scroll';

    /* ======================================================
       1. SCROLL CONTAINER + POSISI SCROLL (tidak lompat ke atas)
    ====================================================== */
    function findScroller() {
        var el = root.parentElement;
        while (el && el !== document.body && el !== document.documentElement) {
            var oy = getComputedStyle(el).overflowY;
            if ((oy === 'auto' || oy === 'scroll') && el.scrollHeight > el.clientHeight + 1) return el;
            el = el.parentElement;
        }
        return null;
    }
    var sc = findScroller();
    var scEvt = sc || window;

    function getY() { return sc ? sc.scrollTop : (window.pageYOffset || 0); }
    function setY(y) {
        var o = { top: y, left: 0, behavior: 'instant' };
        if (sc) sc.scrollTo(o); else window.scrollTo(o);
    }

    if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

    function saveScroll() {
        try { sessionStorage.setItem(KEY, JSON.stringify({ y: getY(), u: location.pathname })); } catch (e) {}
    }

    var saved = null;
    try {
        saved = JSON.parse(sessionStorage.getItem(KEY));
        sessionStorage.removeItem(KEY);
    } catch (e) {}

    if (saved && saved.u === location.pathname && typeof saved.y === 'number') {
        var userMoved = false;
        ['wheel', 'touchstart', 'keydown', 'mousedown'].forEach(function (ev) {
            window.addEventListener(ev, function () { userMoved = true; }, { once: true, passive: true });
        });
        var restore = function () { if (!userMoved) setY(saved.y); };
        restore();
        window.addEventListener('load', restore);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(restore);
    }

    /* ======================================================
       2. LOADING STATE (tombol + tabel + bar atas)
    ====================================================== */
    var loader = document.getElementById('da-loader');
    function loaderOn() { loader.classList.add('on'); }
    function loaderOff() {
        loader.classList.remove('on');
        root.querySelectorAll('.is-loading').forEach(function (n) { n.classList.remove('is-loading'); });
    }
    window.addEventListener('pageshow', function (e) { if (e.persisted) loaderOff(); });

    root.addEventListener('submit', function (e) {
        var f = e.target;
        if (!f.classList || !f.classList.contains('da-filter')) return;
        saveScroll();
        loaderOn();
        var btn = f.querySelector('.da-btn-primary');
        if (btn) btn.classList.add('is-loading');
        var panel = f.closest('.da-panel');
        var wrap = panel && panel.querySelector('.da-table-wrap');
        if (wrap) wrap.classList.add('is-loading');
    });

    root.addEventListener('click', function (e) {
        var a = e.target.closest ? e.target.closest('a') : null;
        if (!a) return;

        // anchor internal: scroll halus, URL tidak diubah
        var href = a.getAttribute('href') || '';
        if (href.charAt(0) === '#' && href.length > 1) {
            var target = document.getElementById(href.slice(1));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
            }
            return;
        }

        // pagination & tombol reset: simpan posisi scroll
        if (a.closest('.da-pager') || a.hasAttribute('data-keep')) {
            if (a.target === '_blank' || e.metaKey || e.ctrlKey || e.shiftKey) return;
            saveScroll();
            loaderOn();
            var panel = a.closest('.da-panel');
            var wrap = panel && panel.querySelector('.da-table-wrap');
            if (wrap) wrap.classList.add('is-loading');
        }
    });

    /* ======================================================
       3. REVEAL, BAR, COUNTER
    ====================================================== */
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

    var items = root.querySelectorAll('.da-rv, [data-bars], [data-count]');

    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                // elemen yang sudah terlewat (di atas layar) juga langsung ditampilkan
                if (!en.isIntersecting && en.boundingClientRect.top >= 0) return;
                en.target.classList.add('da-in');
                if (en.target.hasAttribute('data-count')) count(en.target);
                io.unobserve(en.target);
            });
        }, { root: sc, threshold: .1, rootMargin: '0px 0px -30px 0px' });
        items.forEach(function (el) { io.observe(el); });
    } else {
        items.forEach(function (el) { el.classList.add('da-in'); });
    }

    /* ======================================================
       4. PROGRESS BAR SCROLL + NAV AKTIF
    ====================================================== */
    var bar = document.getElementById('da-progress');
    var nav = document.getElementById('da-nav');
    var ticking = false;

    function onScroll() {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(function () {
            var max = sc ? (sc.scrollHeight - sc.clientHeight)
                         : (document.documentElement.scrollHeight - window.innerHeight);
            var p = max > 0 ? Math.min(Math.max(getY() / max, 0), 1) : 0;
            bar.style.transform = 'scaleX(' + p + ')';
            nav.classList.toggle('stuck', getY() > 40);
            ticking = false;
        });
    }
    scEvt.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    var spyLinks = root.querySelectorAll('[data-spy]');
    function setActive(id) {
        spyLinks.forEach(function (l) {
            var on = l.getAttribute('data-spy') === id;
            l.classList.toggle('active', on);
            if (on) l.setAttribute('aria-current', 'true'); else l.removeAttribute('aria-current');
        });
    }
    if ('IntersectionObserver' in window) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) setActive(en.target.id); });
        }, { root: sc, rootMargin: '-30% 0px -60% 0px', threshold: 0 });
        ['ringkasan', 'keikutsertaan', 'profesi'].forEach(function (id) {
            var s = document.getElementById(id);
            if (s) spy.observe(s);
        });
    }

    /* ======================================================
       5. PARALLAX HERO (mouse, hanya desktop)
    ====================================================== */
    var hero = document.getElementById('da-hero');
    if (hero && !reduce && !coarse) {
        var raf = null;
        hero.addEventListener('mousemove', function (e) {
            if (raf) return;
            raf = requestAnimationFrame(function () {
                var r = hero.getBoundingClientRect();
                hero.style.setProperty('--px', ((e.clientX - r.left) / r.width - .5).toFixed(3));
                hero.style.setProperty('--py', ((e.clientY - r.top) / r.height - .5).toFixed(3));
                raf = null;
            });
        });
        hero.addEventListener('mouseleave', function () {
            hero.style.setProperty('--px', 0);
            hero.style.setProperty('--py', 0);
        });
    }

    /* ======================================================
       6. PIE CHART JENIS KELAMIN (pie penuh)
    ====================================================== */
    var canvas = document.getElementById('genderPieChart');
    if (!canvas || typeof Chart === 'undefined') return;

    var maleCount = Number(@json($maleCount));
    var femaleCount = Number(@json($femaleCount));
    var total = maleCount + femaleCount;
    if (total <= 0) return;

    var labelColors = ['#FFFFFF', '#081B33'];

    // persentase di dalam irisan
    var pctLabels = {
        id: 'pctLabels',
        afterDatasetsDraw: function (chart) {
            var ctx = chart.ctx;
            chart.getDatasetMeta(0).data.forEach(function (arc, i) {
                var v = Number(chart.data.datasets[0].data[i]) || 0;
                var pct = v / total * 100;
                if (pct < 4) return;
                var pos = arc.tooltipPosition();
                ctx.save();
                ctx.fillStyle = labelColors[i] || '#fff';
                ctx.font = '800 16px "Plus Jakarta Sans", system-ui, sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(pct.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%', pos.x, pos.y);
                ctx.restore();
            });
        }
    };

    var pie = new Chart(canvas.getContext('2d'), {
        type: 'pie',
        plugins: [pctLabels],

        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [maleCount, femaleCount],
                backgroundColor: ['#2457D6', '#38C6E8'],
                hoverBackgroundColor: ['#1B45B0', '#1FB0D3'],
                borderColor: '#FFFFFF',
                borderWidth: 4,
                hoverOffset: 14
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 14 },

            animation: reduce ? false : {
                duration: 1100,
                easing: 'easeOutQuart',
                animateRotate: true,
                animateScale: true
            },

            plugins: {
                legend: { display: false },

                tooltip: {
                    backgroundColor: '#081B33',
                    titleColor: '#FFFFFF',
                    bodyColor: '#FFFFFF',
                    titleFont: { family: 'Plus Jakarta Sans', size: 14, weight: '700' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                    padding: 13,
                    cornerRadius: 10,
                    displayColors: true,

                    callbacks: {
                        label: function (context) {
                            var value = Number(context.raw) || 0;
                            var percentage = ((value / total) * 100).toLocaleString('id-ID', {
                                minimumFractionDigits: 1,
                                maximumFractionDigits: 1
                            });

                            return context.label + ': ' +
                                value.toLocaleString('id-ID') +
                                ' peserta (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // hover pada legenda menyorot irisan pie
    root.querySelectorAll('.da-leg[data-idx]').forEach(function (leg) {
        var idx = Number(leg.dataset.idx);
        function on() {
            leg.classList.add('hot');
            var act = [{ datasetIndex: 0, index: idx }];
            pie.setActiveElements(act);
            if (pie.tooltip) pie.tooltip.setActiveElements(act, { x: 0, y: 0 });
            pie.update();
        }
        function off() {
            leg.classList.remove('hot');
            pie.setActiveElements([]);
            if (pie.tooltip) pie.tooltip.setActiveElements([], { x: 0, y: 0 });
            pie.update();
        }
        leg.addEventListener('mouseenter', on);
        leg.addEventListener('mouseleave', off);
    });

});
</script>

@endsection