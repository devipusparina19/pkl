{{-- Ganti 'layouts.app' bila nama file layout Anda berbeda --}}
@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@push('styles')
<style>
    /* =========================================================
       PENGATURAN AKUN — Premium Blue, mudah dipahami, web + HP
       Memakai variabel warna layout; ada cadangan bila tidak ada.
    ========================================================= */
    .acc {
        --a-blue: var(--blue, #2457d6);
        --a-electric: var(--electric, #3b82f6);
        --a-navy: var(--navy, #081b33);
        --a-soft: var(--blue-soft, #eaf1ff);
        --a-border: var(--border, #dce5f2);
        --a-muted: var(--muted, #5b6b82);
        --a-ok: #15803d;
        --a-err: #b91c1c;
        --a-radius: 22px;
        width: 100%;
    }
    .acc *, .acc *::before, .acc *::after { box-sizing: border-box; }
    .acc [hidden] { display: none !important; }
    .acc svg.i { width: 20px; height: 20px; flex: 0 0 20px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* ---------- Banner atas ---------- */
    .acc-hero {
        position: relative; overflow: hidden; padding: 30px 34px; margin-bottom: 20px; color: #fff;
        background: linear-gradient(135deg, var(--a-navy) 0%, var(--a-blue) 62%, var(--a-electric) 100%);
        border-radius: var(--a-radius); box-shadow: 0 18px 40px rgba(8, 27, 51, .18);
    }
    .acc-hero::before, .acc-hero::after { content: ""; position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .08); pointer-events: none; }
    .acc-hero::before { width: 260px; height: 260px; top: -110px; right: -60px; }
    .acc-hero::after { width: 160px; height: 160px; bottom: -90px; right: 180px; }
    .acc-hero > * { position: relative; z-index: 1; }
    .acc-eyebrow { margin: 0 0 6px; font-size: 13px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; opacity: .8; }
    .acc-hero h1 { margin: 0 0 8px; color: #fff; font-family: var(--font-display, inherit); font-size: clamp(24px, 4vw, 34px); line-height: 1.2; overflow-wrap: anywhere; }
    .acc-hero p.lead { margin: 0; max-width: 620px; font-size: 16px; line-height: 1.6; opacity: .92; }
    .acc-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 18px; }
    .acc-chip { display: inline-flex; align-items: center; gap: 8px; max-width: 100%; padding: 7px 14px; background: rgba(255, 255, 255, .16); border: 1px solid rgba(255, 255, 255, .22); border-radius: 999px; font-size: 13.5px; font-weight: 600; }
    .acc-chip svg.i { width: 16px; height: 16px; flex-basis: 16px; }
    .acc-chip span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ---------- Tab (kartu pilihan) ---------- */
    .acc-tabs { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-bottom: 22px; }
    .acc-tab {
        display: flex; align-items: center; gap: 14px; min-height: 68px; padding: 12px 18px; text-align: left; cursor: pointer;
        color: var(--a-navy); background: #fff; border: 2px solid var(--a-border); border-radius: 18px;
        font: inherit; transition: border-color .2s, box-shadow .2s, transform .2s, background .2s;
    }
    .acc-tab:hover { border-color: var(--a-electric); transform: translateY(-1px); }
    .acc-tab:focus-visible { outline: 3px solid var(--a-electric); outline-offset: 2px; }
    .acc-tab-ico { display: grid; place-items: center; width: 44px; height: 44px; flex: 0 0 44px; color: var(--a-blue); background: var(--a-soft); border-radius: 14px; transition: background .2s, color .2s; }
    .acc-tab-txt { min-width: 0; }
    .acc-tab-txt strong { display: block; font-size: 16px; line-height: 1.3; }
    .acc-tab-txt small { display: block; margin-top: 2px; color: var(--a-muted); font-size: 13px; line-height: 1.4; }
    .acc-tab[aria-selected="true"] { border-color: var(--a-blue); background: linear-gradient(180deg, #fff, var(--a-soft)); box-shadow: 0 10px 26px rgba(36, 87, 214, .18); }
    .acc-tab[aria-selected="true"] .acc-tab-ico { color: #fff; background: linear-gradient(135deg, var(--a-blue), var(--a-electric)); }

    /* ---------- Panel & kartu ---------- */
    .acc-panel:not([hidden]) { animation: accIn .45s cubic-bezier(.2, .8, .2, 1) both; }
    .acc-panel:not([hidden]) .acc-card:nth-child(2) { animation: accIn .45s .08s cubic-bezier(.2, .8, .2, 1) both; }
    @keyframes accIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }

    .acc-grid { display: grid; grid-template-columns: minmax(300px, 400px) minmax(0, 1fr); gap: 22px; align-items: start; }
    .acc-grid.is-security { grid-template-columns: minmax(0, 1fr) minmax(280px, 380px); }

    .acc-card { padding: 28px; background: #fff; border: 1px solid var(--a-border); border-radius: var(--a-radius); box-shadow: 0 10px 30px rgba(8, 27, 51, .05); }
    .acc-card-head { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 22px; }
    .acc-card-head .acc-tab-ico { margin-top: 2px; }
    .acc-card-head h2 { margin: 0; color: var(--a-navy); font-family: var(--font-display, inherit); font-size: 21px; line-height: 1.3; }
    .acc-card-head p { margin: 4px 0 0; color: var(--a-muted); font-size: 15px; line-height: 1.55; }

    /* ---------- Foto ---------- */
    .acc-photo { text-align: center; }
    .acc-avatar-wrap { position: relative; width: 168px; height: 168px; margin: 4px auto 18px; }
    .acc-avatar {
        display: grid; place-items: center; width: 100%; height: 100%; overflow: hidden; color: #fff;
        background: linear-gradient(145deg, var(--a-blue), var(--a-navy)); border-radius: 50%;
        box-shadow: 0 0 0 6px var(--a-soft), 0 16px 34px rgba(8, 27, 51, .18);
        font-family: var(--font-display, inherit); font-size: 64px; font-weight: 700;
    }
    .acc-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .acc-camera {
        position: absolute; right: 2px; bottom: 6px; display: grid; place-items: center; width: 48px; height: 48px; padding: 0; cursor: pointer;
        color: #fff; background: linear-gradient(135deg, var(--a-blue), var(--a-electric)); border: 4px solid #fff; border-radius: 50%;
        box-shadow: 0 8px 18px rgba(36, 87, 214, .35); transition: transform .2s;
    }
    .acc-camera:hover { transform: scale(1.08); }
    .acc-camera:focus-visible { outline: 3px solid var(--a-electric); outline-offset: 3px; }

    .acc-name { margin: 0; color: var(--a-navy); font-family: var(--font-display, inherit); font-size: 22px; font-weight: 700; overflow-wrap: anywhere; }
    .acc-handle { margin: 2px 0 10px; color: var(--a-muted); font-size: 15px; overflow-wrap: anywhere; }
    .acc-role { display: inline-block; padding: 5px 16px; color: var(--a-blue); background: var(--a-soft); border-radius: 999px; font-size: 13px; font-weight: 700; }

    .acc-steps { display: grid; gap: 10px; margin: 22px 0 0; padding: 16px; list-style: none; text-align: left; background: #f6f9fe; border-radius: 16px; counter-reset: s; }
    .acc-steps li { display: flex; align-items: center; gap: 12px; color: #334155; font-size: 14.5px; line-height: 1.45; counter-increment: s; }
    .acc-steps li::before { content: counter(s); display: grid; place-items: center; width: 26px; height: 26px; flex: 0 0 26px; color: #fff; background: var(--a-blue); border-radius: 50%; font-size: 13px; font-weight: 700; }

    .acc-photo-actions { display: grid; gap: 10px; margin-top: 18px; }
    .acc-note { display: block; margin: 16px 0 0; padding: 12px 14px; text-align: left; color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; font-size: 14px; font-weight: 600; line-height: 1.5; }
    .acc-note.is-error { color: #991b1b; background: #fef2f2; border-color: #fecaca; }
    .acc-note.is-info { color: #1e40af; background: #eff6ff; border-color: #bfdbfe; }
    .acc-hint { margin: 14px 0 0; color: var(--a-muted); font-size: 13px; line-height: 1.6; }

    /* ---------- Tombol ---------- */
    .acc-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 48px; padding: 10px 24px; cursor: pointer;
        font: inherit; font-size: 15.5px; font-weight: 700; text-decoration: none; border: 2px solid transparent; border-radius: 14px;
        transition: transform .15s, box-shadow .2s, background .2s, border-color .2s, color .2s;
    }
    .acc-btn:active { transform: scale(.98); }
    .acc-btn:focus-visible { outline: 3px solid var(--a-electric); outline-offset: 2px; }
    .acc-btn[disabled] { opacity: .65; cursor: progress; }
    .acc-btn.primary { color: #fff; background: linear-gradient(135deg, var(--a-blue), var(--a-electric)); box-shadow: 0 10px 22px rgba(36, 87, 214, .3); }
    .acc-btn.primary:hover { box-shadow: 0 14px 28px rgba(36, 87, 214, .4); transform: translateY(-1px); }
    .acc-btn.soft { color: var(--a-blue); background: var(--a-soft); }
    .acc-btn.soft:hover { background: #dbe8ff; }
    .acc-btn.outline { color: #334155; background: #fff; border-color: var(--a-border); }
    .acc-btn.outline:hover { border-color: var(--a-blue); color: var(--a-blue); }
    .acc-btn.danger { color: var(--a-err); background: #fff; border-color: #fecaca; }
    .acc-btn.danger:hover { background: #fef2f2; }

    /* ---------- Form ---------- */
    .acc-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 18px; }
    .acc-field { margin-bottom: 20px; }
    .acc-label { display: block; margin-bottom: 8px; color: var(--a-navy); font-size: 15px; font-weight: 700; }
    .acc-input {
        display: block; width: 100%; min-height: 52px; padding: 12px 16px; color: #0f172a; background: #fff;
        border: 2px solid var(--a-border); border-radius: 14px; font: inherit; font-size: 16px; transition: border-color .2s, box-shadow .2s;
    }
    .acc-input:hover { border-color: #b8c8e2; }
    .acc-input:focus { outline: 0; border-color: var(--a-blue); box-shadow: 0 0 0 4px rgba(36, 87, 214, .15); }
    .acc-input.is-invalid { border-color: #ef4444; background: #fffafa; }
    .acc-input[readonly] { color: var(--a-muted); background: #f3f7fc; cursor: not-allowed; }
    .acc-help { display: block; margin-top: 7px; color: var(--a-muted); font-size: 13.5px; line-height: 1.5; }
    .acc-err { display: block; margin-top: 7px; color: var(--a-err); font-size: 14px; font-weight: 600; line-height: 1.5; }
    .acc-err:empty { display: none; }
    .acc-msg { display: block; margin-top: 7px; font-size: 14px; font-weight: 600; }
    .acc-msg:empty { display: none; }
    .acc-msg.is-err { color: var(--a-err); }
    .acc-msg.is-ok { color: var(--a-ok); }
    .acc-lock { position: relative; }
    .acc-lock .acc-input { padding-left: 46px; }
    .acc-lock svg.i { position: absolute; top: 16px; left: 15px; color: var(--a-muted); }
    .acc-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; padding-top: 22px; border-top: 1px solid var(--a-border); }

    /* ---------- Password ---------- */
    .acc-pw { position: relative; }
    .acc-pw .acc-input { padding-right: 110px; }
    .acc-pw-toggle {
        position: absolute; top: 50%; right: 6px; display: inline-flex; align-items: center; gap: 6px; min-height: 40px; padding: 0 12px; cursor: pointer;
        color: var(--a-blue); background: var(--a-soft); border: 0; border-radius: 10px; font: inherit; font-size: 13.5px; font-weight: 700; transform: translateY(-50%);
    }
    .acc-pw-toggle:hover { background: #dbe8ff; }
    .acc-pw-toggle:focus-visible { outline: 3px solid var(--a-electric); outline-offset: 1px; }
    .acc-pw-toggle svg.i { width: 18px; height: 18px; flex-basis: 18px; }
    .acc-pw-toggle .eye-off { display: none; }
    .acc-pw-toggle[aria-pressed="true"] .eye-on { display: none; }
    .acc-pw-toggle[aria-pressed="true"] .eye-off { display: block; }

    .acc-meter { height: 8px; margin-top: 12px; overflow: hidden; background: #e8eef6; border-radius: 99px; }
    .acc-meter span { display: block; width: 0; height: 100%; background: #dc2626; border-radius: 99px; transition: width .3s ease, background .3s ease; }
    .acc-meter-label { display: block; margin-top: 7px; color: var(--a-muted); font-size: 14px; font-weight: 700; }

    .acc-rules { display: grid; gap: 8px; margin: 14px 0 0; padding: 0; list-style: none; color: var(--a-muted); font-size: 14.5px; }
    .acc-rules li { display: flex; align-items: center; gap: 10px; transition: color .2s; }
    .acc-rules li::before {
        content: ""; width: 20px; height: 20px; flex: 0 0 20px; background: #fff center / 12px no-repeat; border: 2px solid #cbd5e1; border-radius: 50%;
        transition: background-color .2s, border-color .2s;
    }
    .acc-rules li.ok { color: var(--a-ok); font-weight: 700; }
    .acc-rules li.ok::before {
        background-color: #22c55e; border-color: #22c55e;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 13l4 4L19 7'/%3E%3C/svg%3E");
    }

    /* ---------- Kartu tips ---------- */
    .acc-tips { background: linear-gradient(180deg, #f6f9fe, #fff); }
    .acc-tips h3 { margin: 0 0 14px; color: var(--a-navy); font-family: var(--font-display, inherit); font-size: 18px; }
    .acc-tips ul { display: grid; gap: 14px; margin: 0; padding: 0; list-style: none; }
    .acc-tips li { display: flex; gap: 12px; color: #334155; font-size: 14.5px; line-height: 1.55; }
    .acc-tips li svg.i { margin-top: 2px; color: var(--a-blue); }
    .acc-tips-box { margin-top: 18px; padding: 14px 16px; color: #92400e; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; font-size: 14px; line-height: 1.55; }

    /* ---------- Responsif ---------- */
    @media (max-width: 1024px) {
        .acc-grid, .acc-grid.is-security { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .acc-hero { padding: 22px 20px; border-radius: 20px; }
        .acc-hero p.lead { font-size: 15px; }
        .acc-card { padding: 20px 18px; border-radius: 20px; }
        .acc-card-head h2 { font-size: 19px; }
        .acc-tabs { gap: 8px; }
        .acc-tab { flex-direction: column; justify-content: center; gap: 8px; min-height: 92px; padding: 12px 8px; text-align: center; }
        .acc-tab-txt small { display: none; }
        .acc-tab-txt strong { font-size: 14.5px; }
        .acc-avatar-wrap { width: 144px; height: 144px; }
        .acc-avatar { font-size: 54px; }
        .acc-form-grid { grid-template-columns: 1fr; }
        .acc-actions { flex-direction: column-reverse; }
        .acc-actions .acc-btn { width: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .acc *, .acc *::before, .acc *::after { animation: none !important; transition: none !important; }
    }
</style>
@endpush

@section('content')
@php
    $bagProfile  = $errors->getBag('profile');
    $bagPhoto    = $errors->getBag('photo');
    $bagPassword = $errors->getBag('password');

    $activeTab = request('tab', session('tab', $bagPassword->any() ? 'security' : 'info'));
    $activeTab = in_array($activeTab, ['info', 'security'], true) ? $activeTab : 'info';

    $displayName = $user->name ?: ($user->username ?: 'Pengguna');
    $firstName   = explode(' ', trim((string) $displayName))[0];
    $initial     = strtoupper(mb_substr(trim((string) $displayName), 0, 1));
    $roleText    = ucwords(str_replace(['_', '-'], ' ', (string) $user->role)) ?: 'Pengguna';
@endphp

<div class="acc">

    {{-- ======================= BANNER ======================= --}}
    <header class="acc-hero">
        <p class="acc-eyebrow">Pengaturan Akun</p>
        <h1>Halo, {{ $firstName }}!</h1>
        <p class="lead">Di halaman ini Anda bisa mengganti foto, memperbarui data diri, dan mengubah password dengan mudah.</p>
        <div class="acc-chips">
            <div class="acc-chip">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3Z"/></svg>
                <span>{{ $roleText }}</span>
            </div>
            @if ($user->email)
                <div class="acc-chip">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                    <span>{{ $user->email }}</span>
                </div>
            @endif
        </div>
    </header>

    {{-- ======================= PILIHAN TAB ======================= --}}
    <div class="acc-tabs" role="tablist" aria-label="Pengaturan akun">
        <button type="button" class="acc-tab" role="tab" id="tab-info" data-tab="info" aria-controls="panel-info" aria-selected="{{ $activeTab === 'info' ? 'true' : 'false' }}">
            <span class="acc-tab-ico">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </span>
            <span class="acc-tab-txt">
                <strong>Informasi Profil</strong>
                <small>Foto, nama, username, dan email</small>
            </span>
        </button>
        <button type="button" class="acc-tab" role="tab" id="tab-security" data-tab="security" aria-controls="panel-security" aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}">
            <span class="acc-tab-ico">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
            </span>
            <span class="acc-tab-txt">
                <strong>Keamanan Akun</strong>
                <small>Ganti password Anda</small>
            </span>
        </button>
    </div>

    {{-- ======================= TAB: INFORMASI PROFIL ======================= --}}
    <section class="acc-panel" id="panel-info" role="tabpanel" aria-labelledby="tab-info" @if ($activeTab !== 'info') hidden @endif>
        <div class="acc-grid">

            {{-- ---------- Foto profil ---------- --}}
            <div class="acc-card acc-photo">
                <div class="acc-card-head" style="justify-content:center;text-align:left">
                    <div>
                        <h2>Foto Profil</h2>
                    </div>
                </div>

                <form id="photoForm" action="{{ route('account.photo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="acc-avatar-wrap">
                        <div class="acc-avatar">
                            {{-- onerror: kalau file foto gagal dimuat, otomatis balik ke huruf inisial --}}
                            <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Foto profil {{ $displayName }}"
                                 onerror="if(!this.dataset.local){this.hidden=true;document.getElementById('avatarInitial').hidden=false;}"
                                 @if (! $user->avatar_url) hidden @endif>
                            <span id="avatarInitial" aria-hidden="true" @if ($user->avatar_url) hidden @endif>{{ $initial }}</span>
                        </div>
                        <button type="button" class="acc-camera js-pick" aria-label="Pilih foto baru" title="Pilih foto baru">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h3l2-3h6l2 3h3v11H4V8Z"/><circle cx="12" cy="13" r="3.5"/></svg>
                        </button>
                    </div>

                    <p class="acc-name">{{ $displayName }}</p>
                    <p class="acc-handle">&#64;{{ $user->username }}</p>
                    <span class="acc-role">{{ $roleText }}</span>

                    <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none">

                    <div class="acc-photo-actions">
                        <button type="button" class="acc-btn soft js-pick" id="photoPick">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h3l2-3h6l2 3h3v11H4V8Z"/><circle cx="12" cy="13" r="3.5"/></svg>
                            Pilih Foto Baru
                        </button>
                        <button type="submit" class="acc-btn primary" id="photoSave" hidden>
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Foto
                        </button>
                        <button type="button" class="acc-btn outline" id="photoCancel" hidden>Batal</button>
                        @if ($user->avatar)
                            <button type="submit" class="acc-btn danger" id="photoRemove" form="photoRemoveForm">Hapus Foto</button>
                        @endif
                    </div>

                    {{-- Pesan hasil upload / hapus foto --}}
                    @if (session('photo_success'))
                        <span class="acc-note" role="status">{{ session('photo_success') }}</span>
                    @endif
                    @if (session('photo_error'))
                        <span class="acc-note is-error" role="alert">{{ session('photo_error') }}</span>
                    @endif
                    <span class="acc-note is-info" id="photoInfo" role="status" hidden></span>

                    <span class="acc-err" id="photoError" role="alert" style="text-align:left;margin-top:14px">{{ $bagPhoto->first('photo') }}</span>

                    <ol class="acc-steps">
                        <li>Tekan tombol <b>&nbsp;Pilih Foto Baru&nbsp;</b></li>
                        <li>Lihat hasilnya di lingkaran foto</li>
                        <li>Tekan <b>&nbsp;Simpan Foto&nbsp;</b> agar tersimpan</li>
                    </ol>
                    <p class="acc-hint">Format JPG, PNG, atau WEBP. Foto yang terlalu besar akan dikecilkan otomatis.</p>
                </form>

                @if ($user->avatar)
                    <form id="photoRemoveForm" action="{{ route('account.photo.destroy') }}" method="POST"
                          onsubmit="return confirm('Hapus foto profil Anda?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>

            {{-- ---------- Form informasi ---------- --}}
            <div class="acc-card">
                <div class="acc-card-head">
                    <span class="acc-tab-ico">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4L19 9a2.8 2.8 0 0 0-4-4L4 16v4Z"/></svg>
                    </span>
                    <div>
                        <h2>Data Diri</h2>
                        <p>Ubah data yang salah, lalu tekan tombol <b>Simpan Perubahan</b> di bagian bawah.</p>
                    </div>
                </div>

                <form action="{{ route('account.update') }}" method="POST" id="profileForm" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="acc-field">
                        <label class="acc-label" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required autocomplete="name"
                               class="acc-input {{ $bagProfile->has('name') ? 'is-invalid' : '' }}" @if ($bagProfile->has('name')) aria-invalid="true" @endif>
                        @if ($bagProfile->has('name'))
                            <span class="acc-err">{{ $bagProfile->first('name') }}</span>
                        @else
                            <span class="acc-help">Tulis nama Anda seperti biasa, minimal 3 huruf.</span>
                        @endif
                    </div>

                    <div class="acc-form-grid">
                        <div class="acc-field">
                            <label class="acc-label" for="username">Username <small style="font-weight:600;color:var(--a-muted)">(nama untuk masuk)</small></label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" maxlength="30" required autocomplete="username"
                                   autocapitalize="none" spellcheck="false"
                                   class="acc-input {{ $bagProfile->has('username') ? 'is-invalid' : '' }}" @if ($bagProfile->has('username')) aria-invalid="true" @endif>
                            @if ($bagProfile->has('username'))
                                <span class="acc-err">{{ $bagProfile->first('username') }}</span>
                            @else
                                <span class="acc-help">Boleh huruf, angka, titik, garis bawah, dan strip. Tanpa spasi.</span>
                            @endif
                        </div>

                        <div class="acc-field">
                            <label class="acc-label" for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required autocomplete="email"
                                   inputmode="email" autocapitalize="none" spellcheck="false"
                                   class="acc-input {{ $bagProfile->has('email') ? 'is-invalid' : '' }}" @if ($bagProfile->has('email')) aria-invalid="true" @endif>
                            @if ($bagProfile->has('email'))
                                <span class="acc-err">{{ $bagProfile->first('email') }}</span>
                            @else
                                <span class="acc-help">Contoh: nama@email.com</span>
                            @endif
                        </div>
                    </div>

                    <div class="acc-field">
                        <label class="acc-label" for="role_display">Peran Akun</label>
                        <div class="acc-lock">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                            <input type="text" id="role_display" class="acc-input" value="{{ $roleText }}" readonly tabindex="-1" aria-describedby="roleHelp">
                        </div>
                        <span class="acc-help" id="roleHelp">Peran tidak bisa diubah sendiri. Hubungi Super Admin bila perlu diganti.</span>
                    </div>

                    <div class="acc-actions">
                        <a href="{{ route('account.edit', ['tab' => 'info']) }}" class="acc-btn outline">Batalkan</a>
                        <button type="submit" class="acc-btn primary" data-loading="Menyimpan...">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    {{-- ======================= TAB: KEAMANAN AKUN ======================= --}}
    <section class="acc-panel" id="panel-security" role="tabpanel" aria-labelledby="tab-security" @if ($activeTab !== 'security') hidden @endif>
        <div class="acc-grid is-security">

            <div class="acc-card">
                <div class="acc-card-head">
                    <span class="acc-tab-ico">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                    </span>
                    <div>
                        <h2>Ganti Password</h2>
                        <p>Isi tiga kolom di bawah ini, lalu tekan <b>Ubah Password</b>.</p>
                    </div>
                </div>

                <form action="{{ route('account.password.update') }}" method="POST" id="passwordForm" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="acc-field">
                        <label class="acc-label" for="current_password">Password Lama</label>
                        <div class="acc-pw">
                            <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                                   class="acc-input {{ $bagPassword->has('current_password') ? 'is-invalid' : '' }}" @if ($bagPassword->has('current_password')) aria-invalid="true" @endif>
                            <button type="button" class="acc-pw-toggle" data-toggle="current_password" aria-pressed="false" aria-label="Lihat password lama">
                                <svg class="i eye-on" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="i eye-off" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.6 5.1A10 10 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                                <span class="t">Lihat</span>
                            </button>
                        </div>
                        @if ($bagPassword->has('current_password'))
                            <span class="acc-err">{{ $bagPassword->first('current_password') }}</span>
                        @else
                            <span class="acc-help">Password yang Anda pakai sekarang untuk masuk.</span>
                        @endif
                    </div>

                    <div class="acc-field">
                        <label class="acc-label" for="password">Password Baru</label>
                        <div class="acc-pw">
                            <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                                   class="acc-input {{ $bagPassword->has('password') ? 'is-invalid' : '' }}" @if ($bagPassword->has('password')) aria-invalid="true" @endif>
                            <button type="button" class="acc-pw-toggle" data-toggle="password" aria-pressed="false" aria-label="Lihat password baru">
                                <svg class="i eye-on" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="i eye-off" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.6 5.1A10 10 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                                <span class="t">Lihat</span>
                            </button>
                        </div>
                        @if ($bagPassword->has('password'))<span class="acc-err">{{ $bagPassword->first('password') }}</span>@endif

                        <div class="acc-meter" aria-hidden="true"><span id="pwBar"></span></div>
                        <span class="acc-meter-label" id="pwLabel" aria-live="polite">Kekuatan password</span>

                        <ul class="acc-rules" id="pwRules">
                            <li data-rule="len">Minimal 8 karakter</li>
                            <li data-rule="letter">Ada huruf (a-z)</li>
                            <li data-rule="number">Ada angka (0-9)</li>
                        </ul>
                    </div>

                    <div class="acc-field">
                        <label class="acc-label" for="password_confirmation">Ketik Ulang Password Baru</label>
                        <div class="acc-pw">
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="acc-input">
                            <button type="button" class="acc-pw-toggle" data-toggle="password_confirmation" aria-pressed="false" aria-label="Lihat konfirmasi password">
                                <svg class="i eye-on" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="i eye-off" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.6 5.1A10 10 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                                <span class="t">Lihat</span>
                            </button>
                        </div>
                        <span class="acc-msg" id="confirmMsg" role="status" aria-live="polite"></span>
                    </div>

                    <div class="acc-actions">
                        <button type="submit" class="acc-btn primary" data-loading="Menyimpan...">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- ---------- Tips ---------- --}}
            <aside class="acc-card acc-tips">
                <h3>Tips password aman</h3>
                <ul>
                    <li>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Pakai gabungan huruf dan angka, makin panjang makin aman.</span>
                    </li>
                    <li>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Jangan pakai tanggal lahir, nama sendiri, atau "12345678".</span>
                    </li>
                    <li>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Gunakan password yang berbeda dengan akun lain (email, media sosial).</span>
                    </li>
                    <li>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                        <span>Jangan berikan password kepada siapa pun, termasuk yang mengaku admin.</span>
                    </li>
                </ul>
                <div class="acc-tips-box">
                    Setelah password diganti, perangkat lain yang sedang masuk dengan akun Anda akan otomatis keluar.
                </div>
            </aside>

        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    (function () {
        /* ---------- Tab ---------- */
        var tabs = document.querySelectorAll('.acc-tab');
        var panels = { info: document.getElementById('panel-info'), security: document.getElementById('panel-security') };

        function showTab(name) {
            tabs.forEach(function (t) { t.setAttribute('aria-selected', t.dataset.tab === name ? 'true' : 'false'); });
            Object.keys(panels).forEach(function (k) { panels[k].hidden = (k !== name); });
            try { history.replaceState(null, '', location.pathname + '?tab=' + name); } catch (e) {}
        }
        tabs.forEach(function (t) { t.addEventListener('click', function () { showTab(t.dataset.tab); }); });

        /* ---------- Foto: pilih, kecilkan otomatis, preview, batal ---------- */
        var form = document.getElementById('photoForm');
        var input = document.getElementById('photoInput');
        var save = document.getElementById('photoSave');
        var cancel = document.getElementById('photoCancel');
        var remove = document.getElementById('photoRemove');
        var img = document.getElementById('avatarPreview');
        var initial = document.getElementById('avatarInitial');
        var err = document.getElementById('photoError');
        var info = document.getElementById('photoInfo');
        var originalSrc = img.getAttribute('src') || '';
        var hadPhoto = !!originalSrc;
        var objectUrl = null;
        var token = 0;

        var MAX_UPLOAD = 2 * 1024 * 1024;     // batas server: 2 MB
        var MAX_SOURCE = 20 * 1024 * 1024;    // foto asli maksimal 20 MB (akan dikecilkan)
        var MAX_SIDE = 1024;                  // sisi terpanjang hasil kecilan (px)
        var OK = ['image/jpeg', 'image/png', 'image/webp'];
        var READY = 'Foto sudah dipilih tetapi belum disimpan. Tekan "Simpan Foto" agar foto berganti.';

        function setInfo(text) {
            info.textContent = text || '';
            info.hidden = !text;
        }

        function resetPhoto() {
            token++;
            if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
            input.value = '';
            save.hidden = true; cancel.hidden = true;
            if (remove) remove.hidden = false;
            delete img.dataset.local;
            setInfo('');
            if (hadPhoto) { img.src = originalSrc; img.hidden = false; initial.hidden = true; }
            else { img.removeAttribute('src'); img.hidden = true; initial.hidden = false; }
        }

        /* Kecilkan foto lewat canvas -> JPEG. Foto HP biasanya 3-8 MB, setelah ini jadi ratusan KB. */
        function shrink(file) {
            return new Promise(function (resolve, reject) {
                var url = URL.createObjectURL(file);
                var im = new Image();
                im.onload = function () {
                    URL.revokeObjectURL(url);
                    var w = im.naturalWidth, h = im.naturalHeight;
                    var scale = Math.min(1, MAX_SIDE / Math.max(w, h));
                    var cw = Math.max(1, Math.round(w * scale));
                    var ch = Math.max(1, Math.round(h * scale));
                    var c = document.createElement('canvas');
                    c.width = cw; c.height = ch;
                    var ctx = c.getContext('2d');
                    ctx.fillStyle = '#ffffff';          // PNG transparan -> latar putih
                    ctx.fillRect(0, 0, cw, ch);
                    ctx.drawImage(im, 0, 0, cw, ch);
                    c.toBlob(function (blob) {
                        blob ? resolve(blob) : reject(new Error('toBlob'));
                    }, 'image/jpeg', 0.86);
                };
                im.onerror = function () { URL.revokeObjectURL(url); reject(new Error('load')); };
                im.src = url;
            });
        }

        function attachFile(file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        }

        function showPreview(file) {
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);
            img.dataset.local = '1';
            img.src = objectUrl; img.hidden = false; initial.hidden = true;
            save.hidden = false; cancel.hidden = false;
            if (remove) remove.hidden = true;
            setInfo(READY);
        }

        document.querySelectorAll('.js-pick').forEach(function (b) {
            b.addEventListener('click', function () { input.click(); });
        });

        input.addEventListener('change', function () {
            err.textContent = '';
            setInfo('');
            var file = input.files && input.files[0];
            if (!file) { resetPhoto(); return; }

            if (OK.indexOf(file.type) === -1) {
                err.textContent = 'Format foto harus JPG, JPEG, PNG, atau WEBP.';
                resetPhoto(); return;
            }
            if (file.size > MAX_SOURCE) {
                err.textContent = 'Foto terlalu besar (maksimal 20 MB).';
                resetPhoto(); return;
            }

            var my = ++token;
            setInfo('Memproses foto...');

            shrink(file).then(function (blob) {
                if (my !== token) return;
                var out = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                attachFile(out);
                showPreview(out);
            }).catch(function () {
                if (my !== token) return;
                // Browser tidak bisa mengecilkan: pakai file asli bila masih di bawah 2 MB.
                if (file.size <= MAX_UPLOAD) {
                    showPreview(file);
                } else {
                    err.textContent = 'Ukuran foto maksimal 2 MB. Coba pilih foto yang lebih kecil.';
                    resetPhoto();
                }
            });
        });

        cancel.addEventListener('click', function () { err.textContent = ''; resetPhoto(); });

        form.addEventListener('submit', function (e) {
            if (!input.files || !input.files[0]) {
                e.preventDefault();
                err.textContent = 'Pilih foto terlebih dahulu.';
                return;
            }
            save.disabled = true;
            save.textContent = 'Menyimpan...';
        });

        /* ---------- Password: lihat/sembunyikan ---------- */
        document.querySelectorAll('.acc-pw-toggle').forEach(function (btn) {
            var base = btn.getAttribute('aria-label').replace(/^(Lihat|Sembunyikan) /, '');
            btn.addEventListener('click', function () {
                var f = document.getElementById(btn.dataset.toggle);
                var show = f.type === 'password';
                f.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', String(show));
                btn.setAttribute('aria-label', (show ? 'Sembunyikan ' : 'Lihat ') + base);
                btn.querySelector('.t').textContent = show ? 'Sembunyi' : 'Lihat';
            });
        });

        /* ---------- Password: kekuatan & konfirmasi ---------- */
        var pw = document.getElementById('password');
        var conf = document.getElementById('password_confirmation');
        var bar = document.getElementById('pwBar');
        var label = document.getElementById('pwLabel');
        var rules = document.getElementById('pwRules');
        var confMsg = document.getElementById('confirmMsg');

        function checkPw() {
            var v = pw.value;
            var r = { len: v.length >= 8, letter: /[A-Za-z]/.test(v), number: /\d/.test(v) };
            Object.keys(r).forEach(function (k) {
                rules.querySelector('[data-rule="' + k + '"]').classList.toggle('ok', r[k]);
            });

            var score = (r.len ? 1 : 0) + (r.letter ? 1 : 0) + (r.number ? 1 : 0)
                + (v.length >= 12 ? 1 : 0) + (/[^A-Za-z0-9]/.test(v) ? 1 : 0);
            if (!v) score = 0;

            var map = [
                ['0%', '#dc2626', 'Kekuatan password'],
                ['25%', '#dc2626', 'Kekuatan: Lemah'],
                ['45%', '#f97316', 'Kekuatan: Kurang kuat'],
                ['65%', '#eab308', 'Kekuatan: Cukup'],
                ['85%', '#22c55e', 'Kekuatan: Kuat'],
                ['100%', '#16a34a', 'Kekuatan: Sangat kuat']
            ][score];
            bar.style.width = map[0]; bar.style.background = map[1]; label.textContent = map[2];
        }

        function checkConfirm() {
            if (!conf.value) { confMsg.textContent = ''; confMsg.className = 'acc-msg'; return; }
            if (conf.value === pw.value) {
                confMsg.textContent = 'Password sama. Sudah benar.';
                confMsg.className = 'acc-msg is-ok';
            } else {
                confMsg.textContent = 'Password belum sama dengan yang di atas.';
                confMsg.className = 'acc-msg is-err';
            }
        }

        pw.addEventListener('input', function () { checkPw(); checkConfirm(); });
        conf.addEventListener('input', checkConfirm);

        document.getElementById('passwordForm').addEventListener('submit', function (e) {
            if (conf.value !== pw.value) {
                e.preventDefault();
                confMsg.textContent = 'Password belum sama dengan yang di atas.';
                confMsg.className = 'acc-msg is-err';
                conf.focus();
            }
        });

        /* ---------- Tombol "Menyimpan..." agar tidak terkirim dua kali ---------- */
        document.querySelectorAll('form').forEach(function (f) {
            f.addEventListener('submit', function (e) {
                if (e.defaultPrevented) return;
                var b = f.querySelector('[data-loading]');
                if (!b) return;
                setTimeout(function () {
                    b.disabled = true;
                    b.dataset.label = b.innerHTML;
                    b.textContent = b.dataset.loading;
                }, 0);
            });
        });

        // Bila halaman dibuka lagi lewat tombol Kembali, aktifkan tombol seperti semula.
        window.addEventListener('pageshow', function () {
            document.querySelectorAll('[data-loading][disabled]').forEach(function (b) {
                b.disabled = false;
                if (b.dataset.label) b.innerHTML = b.dataset.label;
            });
            if (save && !input.files.length) { save.disabled = false; }
        });
    })();
</script>
@endpush