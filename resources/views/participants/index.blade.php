@extends('layouts.app')

@section('title', 'Data Peserta · ARTA')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
.pp{
  --navy:#081b33; --midnight:#0b2545; --royal:#2457d6; --electric:#3478f6; --soft:#eaf2ff; --ice:#f3f7fc;
  --slate:#64748b; --ink:#14243a; --text:#3c4f66; --border:#e1e9f2; --line:#edf1f5; --cyan:#38c6e8;
  --danger:#c2410c; --danger-bg:#fff3ec;
  --f-display:'Sora',system-ui,sans-serif; --f-body:'Plus Jakarta Sans',system-ui,sans-serif;
  --r-sm:10px; --r:14px; --r-lg:26px; --ease:cubic-bezier(.2,.7,.3,1);

  width:100%; max-width:none; margin:0; padding:8px 0 56px; color:var(--ink);
  font:400 16px/1.7 var(--f-body); -webkit-font-smoothing:antialiased;
}
.pp *,.pp *::before,.pp *::after{box-sizing:border-box}
.pp button,.pp input,.pp select{font:inherit}
.pp a{text-decoration:none}
.pp svg{display:block;width:20px;height:20px;flex-shrink:0}
.pp :focus-visible{outline:3px solid rgba(52,120,246,.5);outline-offset:3px;border-radius:6px}
.pp-sr{position:absolute;width:1px;height:1px;margin:-1px;padding:0;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}

/* Progress bar scroll + loading (hanya transform & opacity) */
.pp-scroll,.pp-load{
  position:fixed;left:0;top:0;width:100%;height:3px;z-index:9999;
  transform-origin:0 50%;pointer-events:none
}
.pp-scroll{background:linear-gradient(90deg,var(--royal),var(--electric),var(--cyan));transform:scaleX(0)}
.pp-load{
  height:4px;opacity:0;background:linear-gradient(90deg,transparent,var(--cyan),var(--electric),transparent);
  transform:translateX(-100%);transition:opacity .2s
}
.pp-busy .pp-load{opacity:1;animation:pp-sweep 1.1s linear infinite}
@keyframes pp-sweep{from{transform:translateX(-100%)}to{transform:translateX(100%)}}

/* Header */
.pp-head{
  position:relative;display:grid;grid-template-columns:minmax(0,1fr) auto;
  align-items:end;gap:32px;margin-bottom:28px;padding:52px 52px 48px;
  overflow:hidden;border-radius:var(--r-lg);color:#fff;
  background:linear-gradient(135deg,var(--navy) 0%,var(--midnight) 100%);
  isolation:isolate;box-shadow:0 24px 60px rgba(8,27,51,.2)
}
.pp-head::before{
  content:"";position:absolute;inset:0;z-index:-3;opacity:.12;
  background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);
  background-size:64px 64px;mask-image:linear-gradient(100deg,#000 5%,transparent 75%);
  -webkit-mask-image:linear-gradient(100deg,#000 5%,transparent 75%)
}
.pp-head::after{
  content:"";position:absolute;left:0;bottom:0;width:100%;height:4px;z-index:-1;
  background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)
}
.pp-orb{position:absolute;z-index:-2;border-radius:50%;filter:blur(60px);pointer-events:none;will-change:transform}
.pp-orb.a{width:340px;height:340px;right:-80px;top:-120px;background:rgba(52,120,246,.55);animation:pp-float 14s ease-in-out infinite}
.pp-orb.b{width:240px;height:240px;right:22%;bottom:-120px;background:rgba(56,198,232,.35);animation:pp-float 18s ease-in-out -6s infinite reverse}
@keyframes pp-float{
  0%,100%{transform:translate3d(0,0,0) scale(1)}
  50%{transform:translate3d(-40px,30px,0) scale(1.15)}
}
.pp-badge{
  display:inline-flex;align-items:center;gap:10px;margin-bottom:22px;padding:9px 16px;
  border:1px solid rgba(255,255,255,.2);border-radius:999px;
  background:rgba(255,255,255,.07);font-size:14px;font-weight:600;color:#bcd8ff
}
.pp-badge svg{width:17px;height:17px;color:var(--cyan)}
.pp-badge i{
  width:8px;height:8px;border-radius:50%;background:var(--cyan);
  animation:pp-pulse 2s ease-in-out infinite
}
@keyframes pp-pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.6);opacity:.5}}
.pp-head h1{font:800 clamp(38px,5.4vw,72px)/1.04 var(--f-display);letter-spacing:-.045em}
.pp-head h1 span{
  background:linear-gradient(90deg,#fff,#9fd0ff 60%,var(--cyan));
  -webkit-background-clip:text;background-clip:text;color:transparent;-webkit-text-fill-color:transparent
}
.pp-head p{max-width:54ch;margin-top:20px;font-size:clamp(16px,1.3vw,18px);line-height:1.8;color:rgba(255,255,255,.74)}
.pp-total{
  min-width:190px;padding:22px 26px;border:1px solid rgba(255,255,255,.18);
  border-radius:var(--r);background:linear-gradient(160deg,rgba(255,255,255,.14),rgba(255,255,255,.04));
  backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px)
}
.pp-total b{display:block;font:800 clamp(44px,5vw,68px)/1 var(--f-display);letter-spacing:-.05em;font-variant-numeric:tabular-nums;min-width:3ch}
.pp-total small{display:block;margin-top:8px;font-size:14px;font-weight:600;color:#bcd8ff}

/* Notices */
.pp-notice{
  display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;padding:16px 20px;
  border:1px solid;border-radius:var(--r);font-size:15px;line-height:1.6;
  animation:pp-rise .45s var(--ease) both
}
.pp-notice svg{width:20px;height:20px;margin-top:2px}
.pp-ok{color:#166534;background:#f0fdf4;border-color:#bbf7d0}
.pp-err{color:#991b1b;background:#fef2f2;border-color:#fecaca}
@keyframes pp-rise{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}

/* Toolbar */
.pp-tools{
  display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.3fr);
  gap:16px;margin-bottom:16px
}
.pp-box{
  min-width:0;padding:26px 28px;border:1px solid var(--border);
  border-radius:var(--r-lg);background:#fff;
  transition:transform .35s var(--ease),box-shadow .35s
}
.pp-box:hover{transform:translateY(-3px);box-shadow:0 18px 40px rgba(8,27,51,.07)}
.pp-box h2{
  display:flex;align-items:center;gap:12px;
  font:700 19px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)
}
.pp-ico{
  width:42px;height:42px;display:grid;place-items:center;
  border-radius:12px;background:var(--soft);color:var(--royal);flex-shrink:0;
  transition:transform .35s var(--ease)
}
.pp-box:hover .pp-ico{transform:rotate(-8deg) scale(1.08)}
.pp-box>p{margin:8px 0 18px;font-size:14px;line-height:1.6;color:var(--slate)}
.pp-row{display:flex;align-items:center;flex-wrap:wrap;gap:10px}

/* Filter */
.pp-filter-grid{
  display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.4fr);
  gap:16px;margin-bottom:18px
}
.pp-filter-item{min-width:0}
.pp-filter-item label{display:block;margin-bottom:8px;font-size:13px;font-weight:700;color:#506176}
.pp-filter-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.pp-filter-actions .pp-btn{min-width:130px}

.pp-select,.pp-input{
  display:block;width:100%;min-height:52px;padding:10px 16px;
  border:1.5px solid var(--border);border-radius:var(--r);
  color:var(--ink);font-size:16px;background:#fff;outline:none;
  transition:border-color .25s,box-shadow .25s
}
.pp-select{
  padding-right:42px;appearance:none;-webkit-appearance:none;cursor:pointer;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center
}
.pp-select:hover,.pp-input:hover{border-color:#a6bddb}
.pp-select:focus,.pp-input:focus{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12)}
.pp-input{padding-left:48px}
.pp-input::placeholder{color:#8998ab}
.pp-search{position:relative;flex:1 1 220px;min-width:0}
.pp-search>svg{
  position:absolute;top:50%;left:16px;width:20px;height:20px;
  color:#8394aa;transform:translateY(-50%);pointer-events:none
}

/* Buttons */
.pp-btn{
  position:relative;display:inline-flex;align-items:center;justify-content:center;gap:9px;
  min-height:52px;padding:0 24px;border:1.5px solid transparent;
  border-radius:var(--r);font-size:15px;font-weight:700;
  white-space:nowrap;cursor:pointer;
  transition:background .25s,color .25s,border-color .25s,transform .25s var(--ease),box-shadow .25s
}
.pp-btn:active{transform:scale(.97)}
.pp-btn svg{width:18px;height:18px}
.pp-primary{background:var(--royal);color:#fff}
.pp-primary:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.25)}
.pp-ghost{background:#fff;color:#506176;border-color:var(--border)}
.pp-ghost:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft)}
.pp-sm{min-height:38px;padding:0 15px;border-radius:999px;font-size:13px}
.pp-danger{background:#fff;color:var(--danger);border-color:#f6d3bf}
.pp-danger:hover{background:var(--danger);color:#fff;border-color:var(--danger);box-shadow:0 8px 18px rgba(194,65,12,.22)}
.pp-btn.is-loading{pointer-events:none;opacity:.85}
.pp-btn.is-loading::after{
  content:"";width:16px;height:16px;border-radius:50%;
  border:2.5px solid currentColor;border-right-color:transparent;
  animation:pp-spin .7s linear infinite
}
@keyframes pp-spin{to{transform:rotate(360deg)}}

/* Panel + tabel */
.pp-panel{
  min-width:0;padding:32px;border:1px solid var(--border);
  border-radius:var(--r-lg);background:#fff;box-shadow:0 8px 30px rgba(8,27,51,.04)
}
.pp-panel-head{
  display:flex;align-items:flex-end;justify-content:space-between;
  flex-wrap:wrap;gap:16px;margin-bottom:24px
}
.pp-panel-head h2{font:800 clamp(26px,3vw,38px)/1.12 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.pp-panel-head p{margin-top:8px;font-size:16px;color:var(--slate)}
.pp-chip{
  display:inline-flex;align-items:center;gap:9px;padding:10px 18px;
  border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);
  font-size:14px;font-weight:700;color:var(--royal)
}
.pp-chip svg{width:16px;height:16px}
.pp-chip-wrap{display:flex;flex-wrap:wrap;gap:8px}

.pp-wrap{
  width:100%;overflow-x:auto;border:1px solid var(--border);
  border-radius:var(--r);scrollbar-width:thin;
  scrollbar-color:#b8c8d9 #f1f5f8;-webkit-overflow-scrolling:touch
}
.pp table{
  width:100%;min-width:1500px;margin:0;border-collapse:separate;
  border-spacing:0;background:#fff;text-align:left
}
.pp thead th{
  padding:18px;border-bottom:1px solid #16355f;background:var(--navy);
  font:700 12px/1.4 var(--f-body);letter-spacing:.06em;
  color:#dbe8fb;white-space:nowrap
}
.pp tbody td{
  padding:18px;border-bottom:1px solid var(--line);background:#fff;
  font-size:15px;line-height:1.6;color:var(--text);
  vertical-align:middle;transition:background .2s
}
.pp tbody tr:hover td{background:#f4f8ff}
.pp tbody tr:last-child td{border-bottom:0}
.pp thead th:first-child,.pp tbody td:first-child{position:sticky;left:0;z-index:2}
.pp thead th:first-child{z-index:3}
.pp tbody td:first-child{box-shadow:6px 0 12px -8px rgba(8,27,51,.12)}
.pp-person{display:flex;align-items:center;gap:14px;min-width:240px;font-weight:700;color:var(--ink)}
.pp-avatar{
  width:42px;height:42px;display:grid;place-items:center;flex-shrink:0;
  border-radius:13px;background:var(--soft);color:var(--royal);
  font:700 15px var(--f-display);text-transform:uppercase;
  transition:transform .3s var(--ease),background .3s,color .3s
}
.pp tbody tr:hover .pp-avatar{background:var(--royal);color:#fff;transform:scale(1.08) rotate(-4deg)}
.pp-gender{
  display:inline-flex;align-items:center;padding:4px 12px;
  border:1px solid #dce8fa;border-radius:999px;background:var(--soft);
  font-size:13px;font-weight:700;color:var(--royal)
}
.pp-actions{display:flex;align-items:center;gap:8px;white-space:nowrap}
.pp-actions form{margin:0;display:inline-flex}
.pp-empty{
  padding:56px 20px!important;text-align:center;
  font-size:16px!important;line-height:1.8;color:var(--slate)!important
}

/* Pagination */
.pp-pager{display:flex;justify-content:flex-end;margin-top:24px;overflow-x:auto}
.pp-pager nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.pp-pager nav>div:first-child{display:none}
.pp-pager svg{width:16px;height:16px}
.pp-pager p{margin:0 14px 0 0;font-size:14px;color:var(--slate)}
.pp-pager a,.pp-pager span[aria-current="page"] span,.pp-pager span[aria-disabled="true"] span{
  display:inline-flex;align-items:center;justify-content:center;
  min-width:40px;min-height:40px;padding:6px 13px;border:1px solid var(--border);
  border-radius:var(--r-sm);background:#fff;font-size:14px;font-weight:700;color:#506176;
  transition:.25s var(--ease)
}
.pp-pager a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.pp-pager span[aria-current="page"] span{color:#fff;background:var(--royal);border-color:var(--royal)}
.pp-pager span[aria-disabled="true"] span{opacity:.45}

/* Reveal */
.pp-rv{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s var(--ease)}
.pp-rv.is-in,.pp-nojs .pp-rv{opacity:1;transform:none}

/* Responsive */
@media (max-width:1100px){
  .pp-tools{grid-template-columns:1fr}
}
@media (max-width:1000px){
  .pp-head{padding:40px 32px}
  .pp-panel{padding:26px}
}

/* Tablet & HP: tabel jadi kartu, tanpa scroll samping */
@media (max-width:900px){
  .pp-wrap{border:0;overflow:visible}
  .pp table{min-width:0}
  .pp thead{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)}
  .pp table,.pp tbody,.pp tr{display:block;width:100%}
  .pp tbody tr{
    margin-bottom:14px;border:1px solid var(--border);
    border-radius:var(--r);overflow:hidden;background:#fff
  }
  .pp tbody tr:last-child{margin-bottom:0}
  .pp tbody td{
    display:grid;grid-template-columns:128px minmax(0,1fr);gap:12px;align-items:start;
    padding:12px 16px;word-break:break-word
  }
  .pp tbody td::before{
    content:attr(data-label);padding-top:2px;
    font:700 11px/1.5 var(--f-body);letter-spacing:.06em;text-transform:uppercase;color:var(--slate)
  }
  .pp tbody td:first-child{
    position:static;display:block;padding:16px;background:var(--ice);box-shadow:none
  }
  .pp tbody td:first-child::before,.pp tbody td:last-child::before{display:none}
  .pp tbody td:last-child{display:block;padding:14px 16px}
  .pp tbody tr:hover td{background:#fff}
  .pp tbody tr:hover td:first-child{background:var(--ice)}
  .pp-person{min-width:0}
  .pp-actions{flex-wrap:wrap}
  .pp-empty{display:block!important;background:#fff!important}
  .pp-empty::before{display:none!important}
}
@media (max-width:640px){
  .pp{font-size:15px;padding-bottom:32px}
  .pp-head{grid-template-columns:1fr;gap:24px;padding:34px 22px;border-radius:20px}
  .pp-total{min-width:0}
  .pp-box{padding:22px 18px;border-radius:20px}
  .pp-filter-grid{grid-template-columns:1fr;gap:12px}
  .pp-filter-actions{display:grid;grid-template-columns:1fr 1fr}
  .pp-filter-actions .pp-btn{width:100%;min-width:0}
  .pp-row .pp-btn{flex:1 1 100%}
  .pp-panel{padding:22px 14px;border-radius:20px}
  .pp-panel-head{flex-direction:column;align-items:flex-start}
  .pp tbody td{grid-template-columns:104px minmax(0,1fr)}
  .pp-pager{justify-content:flex-start}
}
@media (prefers-reduced-motion:reduce){
  .pp *,.pp *::before,.pp *::after{animation:none!important;transition-duration:.01ms!important}
  .pp-rv{opacity:1;transform:none}
  .pp-scroll{display:none}
}
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $selectedYear = $selectedYear ?? null;
    $yearFilter = $yearFilter ?? 'all';
    $districts = $districts ?? collect();
    $districtFilter = $districtFilter ?? '';

    $participantTotal = method_exists($participants, 'total')
        ? $participants->total()
        : $participants->count();
@endphp

<div class="pp pp-nojs" id="pp-root">

    {{-- Progress bar scroll + indikator loading --}}
    <div class="pp-scroll" id="pp-scroll" aria-hidden="true"></div>
    <div class="pp-load" aria-hidden="true"></div>

    {{-- HEADER HALAMAN --}}
    <header class="pp-head">
        <span class="pp-orb a" aria-hidden="true"></span>
        <span class="pp-orb b" aria-hidden="true"></span>

        <div>
            <div class="pp-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <ellipse cx="12" cy="5" rx="8" ry="3"/>
                    <path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/>
                    <path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>
                </svg>
                ARTA · Data master
                <i aria-hidden="true"></i>
            </div>

            <h1>Data <span>Peserta</span></h1>

            <p>
                Kelola dan telusuri informasi peserta pada Aplikasi Rekam
                Riwayat Pelatihan Tenaga Kesehatan, yang berasal dari hasil Impor Excel.
            </p>
        </div>

        <div class="pp-total" role="status" aria-label="Jumlah peserta sesuai filter">
            <b data-count="{{ $participantTotal }}">{{ number_format($participantTotal, 0, ',', '.') }}</b>
            <small>peserta ditemukan</small>
        </div>
    </header>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="pp-notice pp-ok" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="pp-notice pp-err" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4M12 16h.01"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- FILTER DAN PENCARIAN --}}
    <div class="pp-tools pp-rv">

        {{-- FILTER TAHUN + WILAYAH --}}
        <section class="pp-box" aria-labelledby="pp-filter-title">
            <h2 id="pp-filter-title">
                <span class="pp-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5h16M7 12h10M10 19h4"/>
                        <circle cx="8" cy="5" r="1.5" fill="currentColor"/>
                        <circle cx="15" cy="12" r="1.5" fill="currentColor"/>
                    </svg>
                </span>
                Filter Data Peserta
            </h2>

            <p>Pilih tahun pelatihan dan wilayah untuk menyaring daftar peserta.</p>

            <form method="GET" action="{{ route('participants.index') }}" data-keep-scroll>

                <div class="pp-filter-grid">

                    <div class="pp-filter-item">
                        <label for="year">Tahun Pelatihan</label>

                        <select name="year" id="year" class="pp-select">
                            <option value="all" {{ $yearFilter === 'all' ? 'selected' : '' }}>
                                Semua Tahun
                            </option>

                            @foreach ($years as $availableYear)
                                <option
                                    value="{{ $availableYear }}"
                                    {{ (string) $yearFilter === (string) $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pp-filter-item">
                        <label for="district">Kabupaten/Kota/Mitra</label>

                        <select name="district" id="district" class="pp-select">
                            <option value="">Semua Kabupaten/Kota/Mitra</option>

                            @foreach ($districts as $district)
                                <option
                                    value="{{ $district }}"
                                    {{ $districtFilter === $district ? 'selected' : '' }}>
                                    {{ $district }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Pencarian tetap dipertahankan saat filter diterapkan --}}
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="pp-filter-actions">

                    <button type="submit" class="pp-btn pp-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                        </svg>
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('participants.index', [
                            'year' => 'all',
                            'search' => $search ?: null
                        ]) }}"
                        class="pp-btn pp-ghost"
                        data-keep-scroll>

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset Filter
                    </a>

                </div>
            </form>
        </section>

        {{-- PENCARIAN PESERTA --}}
        <section class="pp-box" aria-labelledby="pp-search-title">
            <h2 id="pp-search-title">
                <span class="pp-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>
                </span>
                Cari Peserta
            </h2>

            <p>Cari berdasarkan nama, NIK, NIP, instansi, atau kabupaten/kota.</p>

            <form method="GET" action="{{ route('participants.index') }}" class="pp-row" data-keep-scroll>

                <input type="hidden" name="year" value="{{ $yearFilter }}">
                <input type="hidden" name="district" value="{{ $districtFilter }}">

                <div class="pp-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="pp-input"
                        placeholder="Cari nama, NIK, NIP, instansi, atau kabupaten/kota..."
                        aria-label="Cari peserta">
                </div>

                <button class="pp-btn pp-primary" type="submit">Cari</button>

                <a
                    class="pp-btn pp-ghost"
                    href="{{ route('participants.index', [
                        'year' => $yearFilter,
                        'district' => $districtFilter ?: null
                    ]) }}"
                    data-keep-scroll>
                    Reset
                </a>
            </form>
        </section>

    </div>

    {{-- PANEL DATA PESERTA --}}
    <section class="pp-panel pp-rv" aria-labelledby="pp-list-title">

        <div class="pp-panel-head">
            <div>
                <h2 id="pp-list-title">Daftar Peserta</h2>
                <p>Informasi identitas dan data peserta pelatihan.</p>
            </div>

            <div class="pp-chip-wrap">

                @if ($selectedYear)
                    <span class="pp-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        Tahun: {{ $selectedYear }}
                    </span>
                @else
                    <span class="pp-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Semua Tahun
                    </span>
                @endif

                @if ($districtFilter !== '')
                    <span class="pp-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 21h18"/>
                            <path d="M5 21V7l7-4 7 4v14"/>
                            <path d="M9 21v-5h6v5"/>
                        </svg>
                        {{ $districtFilter }}
                    </span>
                @endif

            </div>
        </div>

        <div class="pp-wrap">
            <table>
                <caption class="pp-sr">Daftar peserta pelatihan tenaga kesehatan</caption>
                <thead>
                    <tr>
                        <th scope="col">NAMA LENGKAP</th>
                        <th scope="col">NIK</th>
                        <th scope="col">NIP</th>
                        <th scope="col">JENIS KELAMIN</th>
                        <th scope="col">PANGKAT/GOLONGAN</th>
                        <th scope="col">PROFESI NAKES</th>
                        <th scope="col">EMAIL</th>
                        <th scope="col">NOMOR TELEPON</th>
                        <th scope="col">INSTANSI/UNIT KERJA</th>
                        <th scope="col">KABUPATEN/KOTA/MITRA/MANDIRI</th>
                        <th scope="col">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($participants as $participant)
                        <tr>
                            <td data-label="Nama Lengkap">
                                <div class="pp-person">
                                    <span class="pp-avatar" aria-hidden="true">
                                        {{ \Illuminate\Support\Str::substr($participant->name ?: '?', 0, 1) }}
                                    </span>
                                    {{ $participant->name ?: '-' }}
                                </div>
                            </td>

                            <td data-label="NIK">{{ $participant->nik ?: '-' }}</td>
                            <td data-label="NIP">{{ $participant->nip ?: '-' }}</td>

                            <td data-label="Jenis Kelamin">
                                @if ($participant->gender === 'L')
                                    <span class="pp-gender">Laki-laki</span>
                                @elseif ($participant->gender === 'P')
                                    <span class="pp-gender">Perempuan</span>
                                @else
                                    {{ $participant->gender ?: '-' }}
                                @endif
                            </td>

                            <td data-label="Pangkat/Golongan">{{ $participant->rank ?: '-' }}</td>
                            <td data-label="Profesi Nakes">{{ $participant->profession ?: '-' }}</td>
                            <td data-label="Email">{{ $participant->email ?: '-' }}</td>
                            <td data-label="Nomor Telepon">{{ $participant->phone ?: '-' }}</td>
                            <td data-label="Instansi/Unit Kerja">{{ $participant->institution ?: '-' }}</td>
                            <td data-label="Kabupaten/Kota/Mitra">{{ $participant->district ?: '-' }}</td>

                            <td data-label="Aksi">
                                <div class="pp-actions">

                                    <a
                                        class="pp-btn pp-sm pp-primary"
                                        href="{{ route('participants.show', $participant) }}">
                                        Lihat
                                    </a>

                                    <a
                                        class="pp-btn pp-sm pp-ghost"
                                        href="{{ route('participants.edit', $participant) }}">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('participants.destroy', $participant) }}"
                                        method="POST"
                                        data-keep-scroll
                                        data-confirm="Yakin ingin menghapus peserta {{ $participant->name }}? Data riwayat pelatihannya dan akun yang terhubung juga akan dihapus.">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="pp-btn pp-sm pp-danger">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="pp-empty">

                                @if ($search !== '')
                                    Data peserta tidak ditemukan untuk pencarian tersebut.

                                @elseif ($districtFilter !== '')
                                    Belum ada peserta dari wilayah {{ $districtFilter }}.

                                @elseif ($selectedYear)
                                    Belum ada peserta yang mengikuti pelatihan
                                    pada tahun {{ $selectedYear }}.

                                @else
                                    Belum ada data peserta.
                                    Silakan lakukan Impor Excel terlebih dahulu.
                                @endif

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($participants->hasPages())
            <div class="pp-pager" data-keep-scroll>
                {{ $participants->links() }}
            </div>
        @endif

    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('pp-root');
    if (!root) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var fmt = new Intl.NumberFormat('id-ID');
    var KEY = 'arta-scroll:' + location.pathname;

    root.classList.remove('pp-nojs');

    /* ---------- 1. Posisi scroll TETAP saat klik/loading ---------- */
    var restored = false;

    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

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

    function setBusy() {
        root.classList.add('pp-busy');
    }

    // Pagination & link reset
    root.querySelectorAll('a[data-keep-scroll], [data-keep-scroll] a[href]').forEach(function (a) {
        a.addEventListener('click', function (ev) {
            if (ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.button === 1) return;
            remember();
            setBusy();
            a.classList.add('is-loading');
        });
    });

    // Semua form (filter, cari, hapus)
    root.querySelectorAll('form[data-keep-scroll]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            var msg = form.getAttribute('data-confirm');

            if (msg && !window.confirm(msg)) {
                ev.preventDefault();
                return;
            }

            remember();
            setBusy();

            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                setTimeout(function () { btn.classList.add('is-loading'); }, 0);
            }
        });
    });

    // Kembali dari cache browser (tombol back): hilangkan status loading
    window.addEventListener('pageshow', function (ev) {
        if (ev.persisted) {
            root.classList.remove('pp-busy');
            root.querySelectorAll('.is-loading').forEach(function (el) {
                el.classList.remove('is-loading');
            });
        }
    });

    /* ---------- 2. Progress bar scroll ---------- */
    var bar = document.getElementById('pp-scroll');
    var ticking = false;

    function updateBar() {
        var h = document.documentElement.scrollHeight - window.innerHeight;
        var p = h > 0 ? Math.min(window.scrollY / h, 1) : 0;
        bar.style.transform = 'scaleX(' + p + ')';
        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(updateBar);
        }
    }, { passive: true });
    updateBar();

    /* ---------- 3. Counter angka ---------- */
    function count(el) {
        var target = parseInt(el.dataset.count, 10);

        if (isNaN(target) || reduce || restored || target < 2) return;

        var start = null;
        var dur = 1200;

        function step(t) {
            if (start === null) start = t;

            var p = Math.min((t - start) / dur, 1);
            el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 4))));

            if (p < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = fmt.format(target);
            }
        }

        el.textContent = '0';
        requestAnimationFrame(step);
    }

    root.querySelectorAll('[data-count]').forEach(count);

    /* ---------- 4. Scroll reveal ---------- */
    var items = root.querySelectorAll('.pp-rv');

    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (!en.isIntersecting) return;
                en.target.classList.add('is-in');
                io.unobserve(en.target);
            });
        }, { threshold: .1, rootMargin: '0px 0px -30px 0px' });

        items.forEach(function (el) { io.observe(el); });
    } else {
        items.forEach(function (el) { el.classList.add('is-in'); });
    }
});
</script>

@endsection