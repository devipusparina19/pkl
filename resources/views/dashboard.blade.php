@extends('layouts.app')

@section('title', 'Dashboard Admin SIRAPIK')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =========================================================
   SIRAPIK ADMIN DASHBOARD — Premium Blue Editorial
   Semua style di-scope ke .da agar tidak bentrok dengan layout.
========================================================= */
.da{
  --navy:#081b33; --midnight:#0b2545; --royal:#2457d6; --electric:#3478f6;
  --soft:#eaf2ff; --ice:#f3f7fc; --slate:#64748b; --ink:#14243a; --text:#3c4f66;
  --border:#e1e9f2; --line:#edf1f5; --cyan:#38c6e8;
  --f-display:'Sora',system-ui,sans-serif; --f-body:'Plus Jakarta Sans',system-ui,sans-serif;
  --r-sm:10px; --r:14px; --r-lg:26px;
  --shadow:0 18px 46px rgba(8,27,51,.08);
  --ease:cubic-bezier(.2,.7,.3,1);

  width:100%; padding:8px 0 56px; color:var(--ink);
  font:400 16px/1.7 var(--f-body); -webkit-font-smoothing:antialiased;
}
.da *,.da *::before,.da *::after{box-sizing:border-box}
.da button,.da input,.da select{font:inherit}
.da a{text-decoration:none}
.da svg{display:block;width:20px;height:20px;flex-shrink:0}
.da :focus-visible{outline:3px solid rgba(52,120,246,.5);outline-offset:3px;border-radius:6px}

/* ---------- Page header ---------- */
.da-head{position:relative;display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:end;gap:32px;
  min-height:300px;margin-bottom:56px;padding:56px 56px 52px;overflow:hidden;border-radius:var(--r-lg);
  color:#fff;background:var(--navy);isolation:isolate;box-shadow:0 24px 60px rgba(8,27,51,.22)}
.da-head::before{content:"";position:absolute;inset:0;z-index:-2;opacity:.12;
  background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);
  background-size:64px 64px;mask-image:linear-gradient(100deg,#000 5%,transparent 75%);-webkit-mask-image:linear-gradient(100deg,#000 5%,transparent 75%)}
.da-head::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:4px;z-index:-1;
  background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)}
.da-year{position:absolute;z-index:-1;right:-14px;bottom:-.2em;font:800 clamp(150px,24vw,330px)/1 var(--f-display);letter-spacing:-.06em;
  color:transparent;-webkit-text-stroke:1.5px rgba(130,183,255,.2);user-select:none;pointer-events:none}
.da-head h1{max-width:14ch;font:800 clamp(38px,5.6vw,76px)/1.04 var(--f-display);letter-spacing:-.045em}
.da-head p{max-width:56ch;margin-top:22px;font-size:clamp(16px,1.3vw,18px);line-height:1.8;color:rgba(255,255,255,.74)}
.da-badge{display:inline-flex;align-items:center;gap:10px;margin-bottom:24px;padding:9px 16px;border:1px solid rgba(255,255,255,.2);border-radius:999px;
  background:rgba(255,255,255,.07);font-size:14px;font-weight:600;color:#bcd8ff}
.da-dot{width:9px;height:9px;border-radius:50%;background:#54d69b;box-shadow:0 0 0 4px rgba(84,214,155,.18);animation:da-pulse 2.6s ease-in-out infinite}
@keyframes da-pulse{50%{box-shadow:0 0 0 8px rgba(84,214,155,0)}}

/* ---------- Section heading ---------- */
.da-kicker{display:inline-flex;align-items:center;gap:12px;margin-bottom:12px;font-size:15px;font-weight:700;color:var(--royal)}
.da-kicker::before{content:"";width:32px;height:2px;background:var(--cyan)}
.da-title{font:800 clamp(28px,3.4vw,44px)/1.12 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.da-sub{margin-top:12px;max-width:62ch;font-size:17px;line-height:1.8;color:var(--slate)}
.da-intro{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:28px}
.da-period{flex-shrink:0;padding:10px 18px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);font-size:14px;font-weight:700;color:var(--royal)}

/* ---------- Bento stats ---------- */
.da-bento{display:grid;grid-template-columns:1.4fr 1fr;gap:16px;margin-bottom:16px}
.da-stat{position:relative;min-width:0;overflow:hidden;padding:32px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;
  transition:transform .35s var(--ease),box-shadow .35s}
.da-stat:hover{transform:translateY(-5px);box-shadow:var(--shadow)}
.da-stat-top{display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:16px;font-weight:700;color:var(--slate)}
.da-ico{width:50px;height:50px;display:grid;place-items:center;border-radius:15px;background:var(--soft);color:var(--royal)}
.da-ico svg{width:24px;height:24px}
.da-val{margin-top:28px;font:800 clamp(52px,6vw,88px)/1 var(--f-display);letter-spacing:-.05em;color:var(--navy);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.da-cap{max-width:38ch;margin-top:16px;padding-top:14px;border-top:1px solid var(--line);font-size:14px;line-height:1.7;color:var(--slate)}
.da-stat.feature{background:var(--navy);border-color:var(--navy);color:#fff}
.da-stat.feature .da-stat-top{color:rgba(255,255,255,.75)}
.da-stat.feature .da-ico{background:rgba(255,255,255,.1);color:var(--cyan)}
.da-stat.feature .da-val{color:#fff;font-size:clamp(64px,8vw,116px)}
.da-stat.feature .da-cap{color:rgba(255,255,255,.65);border-color:rgba(255,255,255,.15)}
.da-stat.feature::after{content:"";position:absolute;right:-100px;bottom:-140px;width:340px;height:340px;border-radius:50%;
  border:1px solid rgba(130,183,255,.25);box-shadow:0 0 0 46px rgba(130,183,255,.05)}

/* ---------- Gender ---------- */
.da-gender{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);align-items:center;gap:48px;margin-bottom:72px;padding:40px;
  border:1px solid var(--border);border-radius:var(--r-lg);background:var(--ice)}
.da-gender h3{font:700 clamp(24px,2.4vw,32px)/1.2 var(--f-display);letter-spacing:-.035em;color:var(--navy)}
.da-gender p{margin-top:14px;max-width:44ch;font-size:16px;line-height:1.8;color:var(--slate)}
.da-gtotal{display:inline-flex;align-items:baseline;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid var(--border);font-size:15px;color:var(--slate)}
.da-gtotal strong{font:800 30px/1 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.da-chart{width:100%;max-width:320px;height:300px;margin:0 auto 20px}
.da-chart canvas{display:block;width:100%!important;height:100%!important}
.da-legend{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.da-leg{display:flex;align-items:center;gap:14px;min-width:0;padding:16px 18px;border:1px solid var(--border);border-radius:var(--r);background:#fff}
.da-leg i{width:14px;height:14px;border-radius:50%;flex-shrink:0}
.da-leg small{display:block;font-size:13px;font-weight:600;color:var(--slate)}
.da-leg b{display:block;margin-top:2px;font:700 18px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)}

/* ---------- Empty year ---------- */
.da-empty-year{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:260px;margin-bottom:72px;padding:40px 24px;
  border:1.5px dashed #c8d6e4;border-radius:var(--r-lg);background:#fff;text-align:center}
.da-empty-year .da-ico{width:60px;height:60px;margin-bottom:18px}
.da-empty-year h3{font:700 24px/1.3 var(--f-display);letter-spacing:-.03em;color:var(--navy)}
.da-empty-year p{max-width:52ch;margin-top:10px;font-size:16px;line-height:1.8;color:var(--slate)}

/* ---------- Panels ---------- */
.da-panel{min-width:0;margin-bottom:56px;padding:40px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;box-shadow:0 8px 30px rgba(8,27,51,.04)}
.da-panel-head{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:28px}
.da-tag{display:inline-flex;align-items:center;gap:9px;flex-shrink:0;padding:9px 16px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);
  font-size:13px;font-weight:700;color:var(--royal);white-space:nowrap}
.da-tag::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--royal)}

/* ---------- Filter ---------- */
.da-filter{display:flex;align-items:flex-end;flex-wrap:wrap;gap:14px;margin-bottom:28px;padding:22px;border:1px solid var(--border);border-radius:var(--r);background:var(--ice)}
.da-field{display:flex;flex:1 1 210px;flex-direction:column;gap:8px;min-width:160px}
.da-field label{font-size:14px;font-weight:700;color:#40536a}
.da-field select{width:100%;min-height:54px;padding:10px 42px 10px 16px;border:1.5px solid var(--border);border-radius:var(--r);color:var(--ink);font-size:16px;outline:none;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center;
  appearance:none;-webkit-appearance:none;cursor:pointer;transition:border-color .25s,box-shadow .25s}
.da-field select:hover{border-color:#a6bddb}
.da-field select:focus{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12)}
.da-btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:54px;padding:0 26px;border:1.5px solid transparent;border-radius:var(--r);
  font-size:16px;font-weight:700;cursor:pointer;transition:background .25s,color .25s,border-color .25s,transform .25s var(--ease),box-shadow .25s}
.da-btn:active{transform:scale(.97)}
.da-btn svg{width:18px;height:18px}
.da-btn-primary{background:var(--royal);color:#fff}
.da-btn-primary:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.25)}
.da-btn-ghost{background:#fff;color:#506176;border-color:var(--border)}
.da-btn-ghost:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft)}

/* ---------- Table ---------- */
.da-table-wrap{width:100%;overflow-x:auto;border:1px solid var(--border);border-radius:var(--r);scrollbar-width:thin;scrollbar-color:#b8c8d9 #f1f5f8}
.da table{width:100%;min-width:560px;margin:0;border-collapse:separate;border-spacing:0;background:#fff;text-align:left}
.da thead th{padding:18px 20px;border-bottom:1px solid var(--border);background:var(--ice);font:700 13px/1.3 var(--f-body);letter-spacing:.02em;color:#506176;white-space:nowrap}
.da tbody td{padding:18px 20px;border-bottom:1px solid var(--line);font-size:15px;color:var(--text);vertical-align:middle}
.da tbody tr{transition:background .2s}
.da tbody tr:hover{background:#f7faff}
.da tbody tr:last-child td{border-bottom:0}
.da tbody td:first-child{font-weight:700;color:var(--ink)}
.da-count{display:flex;align-items:center;gap:14px;min-width:150px}
.da-pill{display:inline-grid;place-items:center;min-width:46px;min-height:34px;padding:0 12px;border:1px solid #dce8fa;border-radius:999px;background:var(--soft);
  font:700 14px var(--f-display);color:var(--royal);font-variant-numeric:tabular-nums}
.da-bar{flex:1;height:6px;min-width:60px;border-radius:99px;background:var(--soft);overflow:hidden}
.da-bar i{display:block;width:var(--w);height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--royal),var(--cyan));
  transform:scaleX(0);transform-origin:left;transition:transform 1.1s var(--ease)}
.da-in .da-bar i,.da-nojs .da-bar i{transform:scaleX(1)}
.da-person{display:flex;align-items:center;gap:14px;min-width:200px;font-weight:700;color:var(--ink)}
.da-avatar{width:40px;height:40px;display:grid;place-items:center;flex-shrink:0;border-radius:12px;background:var(--soft);color:var(--royal)}
.da-avatar svg{width:20px;height:20px}
.da-empty{padding:40px 20px!important;text-align:center;font-size:15px;line-height:1.8;color:var(--slate)!important;font-weight:500!important}
.da-hint{padding:36px 20px;border:1.5px dashed #c8d6e4;border-radius:var(--r);text-align:center;font-size:16px;line-height:1.8;color:var(--slate)}
.da-hint strong{color:var(--navy)}

/* ---------- Chips + note ---------- */
.da-chips{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px}
.da-chip{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);font-size:14px;font-weight:600;color:#40536a}
.da-chip strong{font-weight:800;color:var(--royal)}
.da-note{display:flex;align-items:flex-start;gap:14px;margin-top:24px;padding:18px 22px;border-left:4px solid var(--royal);border-radius:0 var(--r) var(--r) 0;background:var(--ice);font-size:14px;line-height:1.8;color:var(--text)}
.da-note svg{margin-top:3px;color:var(--royal)}
.da-note strong{color:var(--navy)}

/* ---------- Pagination (Laravel) ---------- */
.da-pager{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-top:24px}
.da-pager>p{font-size:14px;color:var(--slate)}
.da-pager nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.da-pager nav svg{width:16px;height:16px}
.da-pager a,.da-pager span[aria-current="page"] span,.da-pager span[aria-disabled="true"] span{display:inline-flex;align-items:center;justify-content:center;min-width:40px;min-height:40px;padding:6px 13px;
  border:1px solid var(--border);border-radius:var(--r-sm);background:#fff;font-size:14px;font-weight:700;color:#506176;transition:.25s var(--ease)}
.da-pager a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.da-pager span[aria-current="page"] span{color:#fff;background:var(--royal);border-color:var(--royal)}
.da-pager span[aria-disabled="true"] span{opacity:.45}

/* ---------- Reveal ---------- */
.da-rv{opacity:0;transform:translateY(20px);transition:opacity .7s ease,transform .7s var(--ease)}
.da-in.da-rv,.da-nojs .da-rv{opacity:1;transform:none}

/* ---------- Responsive ---------- */
@media (max-width:1000px){
  .da-head{padding:44px 36px;min-height:260px}
  .da-bento{grid-template-columns:1fr}
  .da-gender{grid-template-columns:1fr;gap:28px;padding:32px}
  .da-panel{padding:32px}
}
@media (max-width:640px){
  .da{font-size:15px;padding-bottom:32px}
  .da-head{grid-template-columns:1fr;min-height:auto;margin-bottom:40px;padding:36px 24px 40px;border-radius:20px}
  .da-intro,.da-panel-head{flex-direction:column;align-items:flex-start;gap:14px}
  .da-sub{font-size:15px}
  .da-stat{padding:24px}
  .da-gender{margin-bottom:48px;padding:24px 18px;border-radius:20px}
  .da-chart{max-width:270px;height:260px}
  .da-legend{grid-template-columns:1fr}
  .da-panel{margin-bottom:36px;padding:24px 16px;border-radius:20px}
  .da-filter{flex-direction:column;align-items:stretch;padding:16px}
  .da-field{flex:1 1 auto;min-width:100%}
  .da-btn{width:100%}
  .da thead th,.da tbody td{padding:15px 14px}
  .da-pager{flex-direction:column;align-items:flex-start}
  .da-pager nav{justify-content:flex-start}
  .da-note{padding:16px}
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

    {{-- HEADER --}}
    <header class="da-head">
        <div class="da-year" aria-hidden="true">{{ $year }}</div>

        <div>
            <div class="da-badge"><span class="da-dot"></span> Dashboard Admin SIRAPIK</div>
            <h1>Pusat kendali data pelatihan.</h1>
            <p>
                Pantau perkembangan, evaluasi keikutsertaan, serta telusuri
                data peserta berdasarkan tahun dan jenis profesi.
            </p>
        </div>
    </header>


    {{-- RINGKASAN DATA --}}
    <section aria-labelledby="summary-title">

        <div class="da-intro da-rv">
            <div>
                <div class="da-kicker">Ringkasan data</div>
                <h2 class="da-title" id="summary-title">Gambaran Umum SIRAPIK</h2>
                <p class="da-sub">Informasi peserta dan pelatihan pada tahun {{ $year }}.</p>
            </div>
            <div class="da-period">Periode {{ $year }}</div>
        </div>

        @if ($hasYearData)

            <div class="da-bento">

                <article class="da-stat feature da-rv">
                    <div class="da-stat-top">
                        Peserta Terlatih Tahun {{ $year }}
                        <span class="da-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="10" cy="7" r="4"/><path d="M2 21v-2a8 8 0 0 1 16 0v2"/><path d="M19 8a3 3 0 0 1 0 6"/><path d="M22 21v-2a5 5 0 0 0-3-4.58"/>
                            </svg>
                        </span>
                    </div>
                    <div class="da-val" data-count="{{ $totalParticipants }}">{{ number_format($totalParticipants, 0, ',', '.') }}</div>
                    <p class="da-cap">Jumlah peserta unik yang mengikuti pelatihan pada tahun terpilih.</p>
                </article>

                <article class="da-stat da-rv" style="transition-delay:.1s">
                    <div class="da-stat-top">
                        Total Pelatihan Tahun {{ $year }}
                        <span class="da-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8M8 11h7"/>
                            </svg>
                        </span>
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
                    </div>

                    <div>
                        <div class="da-chart">
                            <canvas id="genderPieChart" role="img"
                                    aria-label="Diagram lingkaran jenis kelamin peserta tahun {{ $year }}"></canvas>
                        </div>

                        <div class="da-legend">
                            <div class="da-leg">
                                <i style="background:#2457d6"></i>
                                <div>
                                    <small>Laki-laki</small>
                                    <b>{{ number_format($malePercent, 1, ',', '.') }}% ({{ number_format($maleCount, 0, ',', '.') }})</b>
                                </div>
                            </div>

                            <div class="da-leg">
                                <i style="background:#38c6e8"></i>
                                <div>
                                    <small>Perempuan</small>
                                    <b>{{ number_format($femalePercent, 1, ',', '.') }}% ({{ number_format($femaleCount, 0, ',', '.') }})</b>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

            @endif

        @else

            <div class="da-empty-year da-rv">
                <div class="da-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/>
                    </svg>
                </div>
                <h3>Data Tahun {{ $year }} Belum Tersedia</h3>
                <p>
                    Belum terdapat data peserta maupun pelatihan pada tahun ini.
                    Ringkasan dan diagram akan ditampilkan setelah data tersedia.
                </p>
            </div>

        @endif

    </section>


    {{-- KEIKUTSERTAAN PESERTA --}}
    <section class="da-panel da-rv" aria-labelledby="frequent-title">

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

        <form method="GET" action="{{ url()->current() }}" class="da-filter">

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

            <button type="submit" class="da-btn da-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>
                Terapkan Filter
            </button>

            <a href="{{ url()->current() }}" class="da-btn da-btn-ghost">Reset</a>

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
                            <td>{{ $participant->name ?? '-' }}</td>
                            <td>{{ $participant->nip ?? '-' }}</td>
                            <td>
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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/>
            </svg>
            <div>
                <strong>Catatan:</strong>
                Jumlah pelatihan dihitung berdasarkan riwayat keikutsertaan
                peserta pada tahun yang dipilih. Gunakan pilihan urutan untuk
                melihat peserta dengan jumlah pelatihan terendah atau tertinggi.
            </div>
        </div>

    </section>


    {{-- PESERTA BERDASARKAN PROFESI --}}
    <section class="da-panel da-rv" aria-labelledby="profession-title">

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

        <form method="GET" action="{{ url()->current() }}" class="da-filter">

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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>
                Tampilkan Peserta
            </button>

            <a href="{{ url()->current() }}" class="da-btn da-btn-ghost">Reset</a>

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
                                <td>{{ $trainedParticipantStart + $index }}</td>

                                <td>
                                    <div class="da-person">
                                        <span class="da-avatar" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                            </svg>
                                        </span>
                                        {{ $participant->name ?? '-' }}
                                    </div>
                                </td>

                                <td>{{ $participant->nip ?? '-' }}</td>
                                <td>{{ $participant->profession ?? '-' }}</td>

                                <td>
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
                Pilih tahun dan jenis profesi, kemudian klik
                <strong>Tampilkan Peserta</strong> untuk melihat daftar peserta.
            </div>

        @endif

    </section>

</div>


{{-- CHART.JS — PIE CHART JENIS KELAMIN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var root = document.getElementById('da-root');
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Reveal, bar, dan counter ---------- */
    root.classList.remove('da-nojs');

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
                if (!en.isIntersecting) return;
                en.target.classList.add('da-in');
                if (en.target.hasAttribute('data-count')) count(en.target);
                io.unobserve(en.target);
            });
        }, { threshold: .12, rootMargin: '0px 0px -30px 0px' });

        items.forEach(function (el) { io.observe(el); });
    } else {
        items.forEach(function (el) { el.classList.add('da-in'); });
    }

    /* ---------- Pie chart ---------- */
    var canvas = document.getElementById('genderPieChart');
    if (!canvas || typeof Chart === 'undefined') return;

    var maleCount = Number(@json($maleCount));
    var femaleCount = Number(@json($femaleCount));
    var total = maleCount + femaleCount;

    if (total <= 0) return;

    new Chart(canvas.getContext('2d'), {
        type: 'pie',

        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [maleCount, femaleCount],
                backgroundColor: ['#2457D6', '#38C6E8'],
                hoverBackgroundColor: ['#1B45B0', '#1FB0D3'],
                borderColor: '#F3F7FC',
                borderWidth: 4,
                hoverOffset: 12
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            animation: reduce ? false : {
                duration: 900,
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

});
</script>

@endsection