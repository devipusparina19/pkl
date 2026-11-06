@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =========================================================
   SIRAPIK — DATA PESERTA (Premium Blue Editorial)
   Semua style di-scope ke .pp agar tidak bentrok dengan layout.
========================================================= */
.pp{
  --navy:#081b33; --royal:#2457d6; --electric:#3478f6; --soft:#eaf2ff; --ice:#f3f7fc;
  --slate:#64748b; --ink:#14243a; --text:#3c4f66; --border:#e1e9f2; --line:#edf1f5; --cyan:#38c6e8;
  --danger:#c2410c; --danger-bg:#fff3ec;
  --f-display:'Sora',system-ui,sans-serif; --f-body:'Plus Jakarta Sans',system-ui,sans-serif;
  --r-sm:10px; --r:14px; --r-lg:26px; --ease:cubic-bezier(.2,.7,.3,1);

  width:100%; padding:8px 0 56px; color:var(--ink);
  font:400 16px/1.7 var(--f-body); -webkit-font-smoothing:antialiased;
}
.pp *,.pp *::before,.pp *::after{box-sizing:border-box}
.pp button,.pp input,.pp select{font:inherit}
.pp a{text-decoration:none}
.pp svg{display:block;width:20px;height:20px;flex-shrink:0}
.pp :focus-visible{outline:3px solid rgba(52,120,246,.5);outline-offset:3px;border-radius:6px}

/* ---------- Header ---------- */
.pp-head{position:relative;display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:end;gap:32px;margin-bottom:28px;
  padding:52px 52px 48px;overflow:hidden;border-radius:var(--r-lg);color:#fff;background:var(--navy);isolation:isolate;box-shadow:0 24px 60px rgba(8,27,51,.2)}
.pp-head::before{content:"";position:absolute;inset:0;z-index:-2;opacity:.12;
  background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);
  background-size:64px 64px;mask-image:linear-gradient(100deg,#000 5%,transparent 75%);-webkit-mask-image:linear-gradient(100deg,#000 5%,transparent 75%)}
.pp-head::after{content:"";position:absolute;left:0;bottom:0;width:100%;height:4px;z-index:-1;background:linear-gradient(90deg,var(--royal) 0 30%,var(--electric) 30% 75%,var(--cyan) 75%)}
.pp-badge{display:inline-flex;align-items:center;gap:10px;margin-bottom:22px;padding:9px 16px;border:1px solid rgba(255,255,255,.2);border-radius:999px;
  background:rgba(255,255,255,.07);font-size:14px;font-weight:600;color:#bcd8ff}
.pp-badge svg{width:17px;height:17px;color:var(--cyan)}
.pp-head h1{font:800 clamp(38px,5.4vw,72px)/1.04 var(--f-display);letter-spacing:-.045em}
.pp-head p{max-width:54ch;margin-top:20px;font-size:clamp(16px,1.3vw,18px);line-height:1.8;color:rgba(255,255,255,.74)}
.pp-total{min-width:190px;padding:22px 26px;border:1px solid rgba(255,255,255,.18);border-radius:var(--r);background:linear-gradient(160deg,rgba(255,255,255,.14),rgba(255,255,255,.04));
  backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px)}
.pp-total b{display:block;font:800 clamp(44px,5vw,68px)/1 var(--f-display);letter-spacing:-.05em;font-variant-numeric:tabular-nums}
.pp-total small{display:block;margin-top:8px;font-size:14px;font-weight:600;color:#bcd8ff}

/* ---------- Notices ---------- */
.pp-notice{display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;padding:16px 20px;border:1px solid;border-radius:var(--r);font-size:15px;line-height:1.6;animation:pp-in .4s var(--ease) both}
.pp-notice svg{width:20px;height:20px;margin-top:2px}
.pp-ok{color:#166534;background:#f0fdf4;border-color:#bbf7d0}
.pp-err{color:#991b1b;background:#fef2f2;border-color:#fecaca}
@keyframes pp-in{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}

/* ---------- Toolbar (filter tahun + cari) ---------- */
.pp-tools{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.3fr);gap:16px;margin-bottom:16px}
.pp-box{min-width:0;padding:26px 28px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff}
.pp-box h2{display:flex;align-items:center;gap:12px;font:700 19px/1.3 var(--f-display);letter-spacing:-.02em;color:var(--navy)}
.pp-box h2 label{cursor:pointer}
.pp-ico{width:42px;height:42px;display:grid;place-items:center;border-radius:12px;background:var(--soft);color:var(--royal)}
.pp-box>p{margin:8px 0 18px;font-size:14px;line-height:1.6;color:var(--slate)}
.pp-row{display:flex;align-items:center;flex-wrap:wrap;gap:10px}

.pp-select,.pp-input{display:block;width:100%;min-height:52px;padding:10px 16px;border:1.5px solid var(--border);border-radius:var(--r);color:var(--ink);font-size:16px;background:#fff;outline:none;
  transition:border-color .25s,box-shadow .25s}
.pp-select{padding-right:42px;appearance:none;-webkit-appearance:none;cursor:pointer;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center}
.pp-select:hover,.pp-input:hover{border-color:#a6bddb}
.pp-select:focus,.pp-input:focus{border-color:var(--royal);box-shadow:0 0 0 4px rgba(36,87,214,.12)}
.pp-input{padding-left:48px}
.pp-input::placeholder{color:#8998ab}
.pp-sel-wrap{flex:1 1 180px;min-width:150px}
.pp-search{position:relative;flex:1 1 220px;min-width:0}
.pp-search>svg{position:absolute;top:50%;left:16px;width:20px;height:20px;color:#8394aa;transform:translateY(-50%);pointer-events:none}

/* ---------- Buttons ---------- */
.pp-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:52px;padding:0 24px;border:1.5px solid transparent;border-radius:var(--r);
  font-size:15px;font-weight:700;white-space:nowrap;cursor:pointer;transition:background .25s,color .25s,border-color .25s,transform .25s var(--ease),box-shadow .25s}
.pp-btn:active{transform:scale(.97)}
.pp-btn svg{width:18px;height:18px}
.pp-primary{background:var(--royal);color:#fff}
.pp-primary:hover{background:var(--navy);transform:translateY(-2px);box-shadow:0 12px 26px rgba(36,87,214,.25)}
.pp-ghost{background:#fff;color:#506176;border-color:var(--border)}
.pp-ghost:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft)}
.pp-sm{min-height:38px;padding:0 15px;border-radius:999px;font-size:13px}
.pp-sm svg{width:15px;height:15px}
.pp-danger{background:#fff;color:var(--danger);border-color:#f6d3bf}
.pp-danger:hover{background:var(--danger);color:#fff;border-color:var(--danger);box-shadow:0 8px 18px rgba(194,65,12,.22)}

/* ---------- Panel + table ---------- */
.pp-panel{min-width:0;padding:32px;border:1px solid var(--border);border-radius:var(--r-lg);background:#fff;box-shadow:0 8px 30px rgba(8,27,51,.04)}
.pp-panel-head{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px}
.pp-panel-head h2{font:800 clamp(26px,3vw,38px)/1.12 var(--f-display);letter-spacing:-.04em;color:var(--navy)}
.pp-panel-head p{margin-top:8px;font-size:16px;color:var(--slate)}
.pp-chip{display:inline-flex;align-items:center;gap:9px;padding:10px 18px;border:1px solid #d4e3f5;border-radius:999px;background:var(--soft);font-size:14px;font-weight:700;color:var(--royal)}
.pp-chip svg{width:16px;height:16px}

.pp-wrap{width:100%;overflow-x:auto;border:1px solid var(--border);border-radius:var(--r);scrollbar-width:thin;scrollbar-color:#b8c8d9 #f1f5f8;-webkit-overflow-scrolling:touch}
.pp table{width:100%;min-width:1500px;margin:0;border-collapse:separate;border-spacing:0;background:#fff;text-align:left}
.pp thead th{padding:18px 18px;border-bottom:1px solid #16355f;background:var(--navy);font:700 12px/1.4 var(--f-body);letter-spacing:.06em;color:#dbe8fb;white-space:nowrap}
.pp tbody td{padding:18px;border-bottom:1px solid var(--line);background:#fff;font-size:15px;line-height:1.6;color:var(--text);vertical-align:middle;transition:background .2s}
.pp tbody tr:hover td{background:#f4f8ff}
.pp tbody tr:last-child td{border-bottom:0}
.pp thead th:first-child,.pp tbody td:first-child{position:sticky;left:0;z-index:2}
.pp thead th:first-child{z-index:3}
.pp tbody td:first-child{box-shadow:6px 0 12px -8px rgba(8,27,51,.12)}
.pp-person{display:flex;align-items:center;gap:14px;min-width:240px;font-weight:700;color:var(--ink)}
.pp-avatar{width:42px;height:42px;display:grid;place-items:center;flex-shrink:0;border-radius:13px;background:var(--soft);color:var(--royal);font:700 15px var(--f-display);text-transform:uppercase}
.pp-gender{display:inline-flex;align-items:center;padding:4px 12px;border:1px solid #dce8fa;border-radius:999px;background:var(--soft);font-size:13px;font-weight:700;color:var(--royal)}
.pp-actions{display:flex;align-items:center;gap:8px;white-space:nowrap}
.pp-actions form{margin:0;display:inline-flex}
.pp-empty{padding:56px 20px!important;text-align:center;font-size:16px!important;line-height:1.8;color:var(--slate)!important}

/* ---------- Pagination (Laravel) ---------- */
.pp-pager{display:flex;justify-content:flex-end;margin-top:24px;overflow-x:auto}
.pp-pager nav{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:6px}
.pp-pager nav>div:first-child{display:none}
.pp-pager svg{width:16px;height:16px}
.pp-pager p{margin:0 14px 0 0;font-size:14px;color:var(--slate)}
.pp-pager a,.pp-pager span[aria-current="page"] span,.pp-pager span[aria-disabled="true"] span{display:inline-flex;align-items:center;justify-content:center;min-width:40px;min-height:40px;padding:6px 13px;
  border:1px solid var(--border);border-radius:var(--r-sm);background:#fff;font-size:14px;font-weight:700;color:#506176;transition:.25s var(--ease)}
.pp-pager a:hover{color:var(--royal);border-color:#b9cfec;background:var(--soft);transform:translateY(-2px)}
.pp-pager span[aria-current="page"] span{color:#fff;background:var(--royal);border-color:var(--royal)}
.pp-pager span[aria-disabled="true"] span{opacity:.45}

/* ---------- Reveal ---------- */
.pp-rv{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s var(--ease)}
.pp-in.pp-rv,.pp-nojs .pp-rv{opacity:1;transform:none}

/* ---------- Responsive ---------- */
@media (max-width:1000px){
  .pp-head{padding:40px 32px}
  .pp-tools{grid-template-columns:1fr}
  .pp-panel{padding:26px}
}
@media (max-width:640px){
  .pp{font-size:15px;padding-bottom:32px}
  .pp-head{grid-template-columns:1fr;gap:24px;padding:34px 22px;border-radius:20px}
  .pp-total{min-width:0}
  .pp-box{padding:22px 18px;border-radius:20px}
  .pp-row .pp-btn,.pp-sel-wrap{flex:1 1 100%}
  .pp-panel{padding:22px 14px;border-radius:20px}
  .pp-panel-head{flex-direction:column;align-items:flex-start}
  .pp thead th,.pp tbody td{padding:15px 14px}
  .pp-pager{justify-content:flex-start}
}
@media (prefers-reduced-motion:reduce){
  .pp *,.pp *::before,.pp *::after{animation:none!important;transition-duration:.01ms!important}
  .pp-rv{opacity:1;transform:none}
}
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $selectedYear = $selectedYear ?? null;
    $yearFilter = $yearFilter ?? 'all';

    /* Hanya tampilan: total peserta dari paginator (jika tersedia) */
    $participantTotal = method_exists($participants, 'total') ? $participants->total() : $participants->count();
@endphp

<div class="pp pp-nojs" id="pp-root">

    {{-- HEADER HALAMAN --}}
    <header class="pp-head">
        <div>
            <div class="pp-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>
                </svg>
                Data master
            </div>

            <h1>Data Peserta</h1>

            <p>
                Kelola dan telusuri informasi peserta yang berasal
                dari hasil Impor Excel.
            </p>
        </div>

        <div class="pp-total" aria-label="Jumlah peserta sesuai filter">
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
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- FILTER TAHUN + PENCARIAN --}}
    <div class="pp-tools pp-rv">

        <section class="pp-box">
            <h2>
                <span class="pp-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M8 14h2M14 14h2M8 17h2"/>
                    </svg>
                </span>
                <label for="year">Filter Tahun Pelatihan</label>
            </h2>
            <p>Pilih tahun untuk menyaring data peserta.</p>

            <form method="GET" action="{{ route('participants.index') }}" class="pp-row">

                @if ($search !== '')
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif

                <div class="pp-sel-wrap">
                    <select name="year" id="year" class="pp-select" aria-label="Pilih tahun pelatihan">
                        <option value="all" {{ $yearFilter === 'all' ? 'selected' : '' }}>Semua Tahun</option>

                        @foreach ($years as $availableYear)
                            <option value="{{ $availableYear }}"
                                {{ (string) $yearFilter === (string) $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="pp-btn pp-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                    </svg>
                    Terapkan
                </button>

                <a href="{{ route('participants.index', ['year' => 'all', 'search' => $search ?: null]) }}" class="pp-btn pp-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>
                    </svg>
                    Reset
                </a>

            </form>
        </section>

        <section class="pp-box">
            <h2>
                <span class="pp-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                    </svg>
                </span>
                Cari Peserta
            </h2>
            <p>Cari berdasarkan nama, NIK, NIP, instansi, atau kabupaten/kota.</p>

            <form method="GET" action="{{ route('participants.index') }}" class="pp-row">

                <input type="hidden" name="year" value="{{ $yearFilter }}">

                <div class="pp-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
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

                <a class="pp-btn pp-ghost" href="{{ route('participants.index', ['year' => $yearFilter]) }}">Reset</a>

            </form>
        </section>

    </div>

    {{-- PANEL DATA PESERTA --}}
    <section class="pp-panel pp-rv">

        <div class="pp-panel-head">
            <div>
                <h2>Daftar Peserta</h2>
                <p>Informasi identitas dan data peserta pelatihan.</p>
            </div>

            @if ($selectedYear)
                <span class="pp-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Tahun Pelatihan: {{ $selectedYear }}
                </span>
            @else
                <span class="pp-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/>
                    </svg>
                    Menampilkan Semua Tahun
                </span>
            @endif
        </div>

        <div class="pp-wrap">
            <table>

                <thead>
                    <tr>
                        <th>NAMA LENGKAP</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>JENIS KELAMIN</th>
                        <th>PANGKAT/GOLONGAN</th>
                        <th>PROFESI NAKES</th>
                        <th>EMAIL</th>
                        <th>NOMOR TELEPON</th>
                        <th>INSTANSI/UNIT KERJA</th>
                        <th>KABUPATEN/KOTA/MITRA/MANDIRI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participants as $participant)

                        <tr>

                            <td>
                                <div class="pp-person">
                                    <span class="pp-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::substr($participant->name ?: '?', 0, 1) }}</span>
                                    {{ $participant->name ?: '-' }}
                                </div>
                            </td>

                            <td>{{ $participant->nik ?: '-' }}</td>

                            <td>{{ $participant->nip ?: '-' }}</td>

                            <td>
                                @if ($participant->gender === 'L')
                                    <span class="pp-gender">Laki-laki</span>
                                @elseif ($participant->gender === 'P')
                                    <span class="pp-gender">Perempuan</span>
                                @else
                                    {{ $participant->gender ?: '-' }}
                                @endif
                            </td>

                            <td>{{ $participant->rank ?: '-' }}</td>

                            <td>{{ $participant->profession ?: '-' }}</td>

                            <td>{{ $participant->email ?: '-' }}</td>

                            <td>{{ $participant->phone ?: '-' }}</td>

                            <td>{{ $participant->institution ?: '-' }}</td>

                            <td>{{ $participant->district ?: '-' }}</td>

                            <td>
                                <div class="pp-actions">

                                    <a class="pp-btn pp-sm pp-primary" href="{{ route('participants.show', $participant) }}">
                                        Lihat
                                    </a>

                                    <a class="pp-btn pp-sm pp-ghost" href="{{ route('participants.edit', $participant) }}">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('participants.destroy', $participant) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus peserta {{ addslashes($participant->name) }}? Data riwayat pelatihannya dan akun yang terhubung juga akan dihapus.');">
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

                                    Data peserta tidak ditemukan

                                    @if ($selectedYear)
                                        pada tahun {{ $selectedYear }}.
                                    @else
                                        untuk pencarian tersebut.
                                    @endif

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
            <div class="pp-pager">
                {{ $participants->links() }}
            </div>
        @endif

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var root = document.getElementById('pp-root');
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var fmt = new Intl.NumberFormat('id-ID');

    root.classList.remove('pp-nojs');

    function count(el) {
        var target = parseInt(el.dataset.count, 10);
        if (isNaN(target) || reduce || target < 2) return;
        var start = null, dur = 1200;
        function step(t) {
            if (start === null) start = t;
            var p = Math.min((t - start) / dur, 1);
            el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 4))));
            if (p < 1) requestAnimationFrame(step); else el.textContent = fmt.format(target);
        }
        el.textContent = '0';
        requestAnimationFrame(step);
    }

    var items = root.querySelectorAll('.pp-rv');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (!en.isIntersecting) return;
                en.target.classList.add('pp-in');
                io.unobserve(en.target);
            });
        }, { threshold: .1, rootMargin: '0px 0px -30px 0px' });
        items.forEach(function (el) { io.observe(el); });
    } else {
        items.forEach(function (el) { el.classList.add('pp-in'); });
    }

    root.querySelectorAll('[data-count]').forEach(count);
});
</script>

@endsection