{{-- Ganti 'layouts.app' bila nama file layout Anda berbeda --}}
@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@push('styles')
<style>
    .acc-tabs {
        display: flex; gap: 6px; width: max-content; max-width: 100%; margin-bottom: 24px; padding: 6px;
        overflow-x: auto; background: #fff; border: 1px solid var(--border); border-radius: 999px;
    }
    .acc-tab {
        display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 8px 20px;
        color: var(--muted); background: transparent; border: 0; border-radius: 999px;
        font-size: 14.5px; font-weight: 700; white-space: nowrap;
        transition: background var(--transition), color var(--transition);
    }
    .acc-tab:hover { color: var(--blue); background: var(--blue-soft); }
    .acc-tab[aria-selected="true"] { color: #fff; background: linear-gradient(135deg, var(--blue), var(--electric)); box-shadow: 0 8px 20px rgba(36,87,214,.28); }

    .acc-panel[hidden] { display: none; }

    .acc-grid { display: grid; grid-template-columns: minmax(280px, 360px) minmax(0, 1fr); gap: 22px; align-items: start; }

    .acc-card-title { display: flex; align-items: center; gap: 10px; margin: 0 0 4px; }
    .acc-card-sub { margin: 0 0 22px !important; color: var(--muted); font-size: 14.5px !important; }

    /* Foto */
    .acc-photo { text-align: center; }
    .acc-avatar {
        position: relative; display: grid; place-items: center; width: 150px; height: 150px; margin: 6px auto 18px;
        overflow: hidden; color: #fff; background: linear-gradient(145deg, var(--blue), var(--navy));
        border-radius: 50%; box-shadow: 0 0 0 6px var(--blue-soft), 0 16px 34px rgba(8,27,51,.16);
        font-family: var(--font-display); font-size: 56px; font-weight: 700;
    }
    .acc-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .acc-avatar img[hidden], .acc-avatar span[hidden] { display: none; }

    .acc-name { margin: 0; color: var(--navy); font-family: var(--font-display); font-size: 20px; font-weight: 700; overflow-wrap: anywhere; }
    .acc-handle { margin: 2px 0 10px; color: var(--muted); font-size: 14px; overflow-wrap: anywhere; }
    .acc-role { display: inline-block; padding: 4px 14px; color: var(--blue); background: var(--blue-soft); border-radius: 999px; font-size: 12.5px; font-weight: 700; }

    .acc-photo-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 20px; }
    .acc-photo-actions [hidden] { display: none; }
    .acc-hint { margin-top: 14px; color: var(--muted); font-size: 12.5px; line-height: 1.6; }
    .acc-note { display: inline-block; margin-top: 12px; padding: 8px 12px; color: #1e40af; background: #eff6ff; border-radius: 10px; font-size: 13px; font-weight: 600; }
    .acc-photo-remove { margin-top: 14px; }

    /* Password */
    .acc-pw { position: relative; }
    .acc-pw input { padding-right: 52px; }
    .acc-pw-toggle {
        position: absolute; top: 50%; right: 6px; display: grid; place-items: center; width: 40px; height: 40px; padding: 0;
        color: var(--muted); background: transparent; border: 0; border-radius: 10px; transform: translateY(-50%);
    }
    .acc-pw-toggle:hover { color: var(--blue); background: var(--blue-soft); }

    .acc-meter { height: 6px; margin-top: 10px; overflow: hidden; background: #e8eef6; border-radius: 99px; }
    .acc-meter span { display: block; width: 0; height: 100%; background: #dc2626; border-radius: 99px; transition: width .25s ease, background .25s ease; }
    .acc-meter-label { display: block; margin-top: 6px; color: var(--muted); font-size: 12.5px; font-weight: 600; }

    .acc-rules { display: grid; gap: 6px; margin: 14px 0 0; padding: 0; list-style: none; color: var(--muted); font-size: 13px; }
    .acc-rules li { display: flex; align-items: center; gap: 8px; }
    .acc-rules li::before { content: ""; width: 8px; height: 8px; flex: 0 0 8px; background: #cbd5e1; border-radius: 50%; transition: background .2s; }
    .acc-rules li.ok { color: var(--success); font-weight: 600; }
    .acc-rules li.ok::before { background: #22c55e; }

    .acc-readonly { background: #f3f7fc; color: var(--muted); }

    @media (max-width: 960px) { .acc-grid { grid-template-columns: 1fr; } }
    @media (max-width: 560px) {
        .acc-tabs { width: 100%; }
        .acc-tab { flex: 1 1 0; justify-content: center; padding-inline: 12px; }
        .acc-avatar { width: 128px; height: 128px; font-size: 48px; }
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
    $initial     = strtoupper(mb_substr(trim((string) $displayName), 0, 1));
    $roleText    = ucwords(str_replace(['_', '-'], ' ', (string) $user->role));
@endphp

<div class="acc-tabs" role="tablist" aria-label="Pengaturan akun">
    <button type="button" class="acc-tab" role="tab" id="tab-info" data-tab="info" aria-controls="panel-info" aria-selected="{{ $activeTab === 'info' ? 'true' : 'false' }}">
        <svg class="icon icon-sm" aria-hidden="true"><use href="#icon-user"/></svg>
        Informasi Profil
    </button>
    <button type="button" class="acc-tab" role="tab" id="tab-security" data-tab="security" aria-controls="panel-security" aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}">
        <svg class="icon icon-sm" aria-hidden="true"><use href="#icon-shield"/></svg>
        Keamanan Akun
    </button>
</div>

{{-- ======================= TAB: INFORMASI PROFIL ======================= --}}
<section class="acc-panel" id="panel-info" role="tabpanel" aria-labelledby="tab-info" @if ($activeTab !== 'info') hidden @endif>
    <div class="acc-grid">

        {{-- Foto profil --}}
        <div class="content-card acc-photo">
            <h2 class="acc-card-title" style="justify-content:center">Foto Profil</h2>

            <form id="photoForm" action="{{ route('account.photo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="acc-avatar">
                    <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Foto profil {{ $displayName }}" @if (! $user->avatar_url) hidden @endif>
                    <span id="avatarInitial" aria-hidden="true" @if ($user->avatar_url) hidden @endif>{{ $initial }}</span>
                </div>

                <p class="acc-name">{{ $displayName }}</p>
                <p class="acc-handle">&#64;{{ $user->username }}</p>
                <span class="acc-role">{{ $roleText ?: 'Pengguna' }}</span>

                <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/webp" style="display:none">

                <div class="acc-photo-actions">
                    <button type="button" class="btn btn-light btn-sm" id="photoPick">Ubah Foto</button>
                    <button type="submit" class="btn btn-sm" id="photoSave" hidden>Simpan Foto</button>
                    <button type="button" class="btn btn-outline btn-sm" id="photoCancel" hidden>Batal</button>
                </div>

                <span class="field-error" id="photoError" role="alert">{{ $bagPhoto->first('photo') }}</span>
                <p class="acc-hint">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
            </form>

            @if ($user->avatar)
                <form class="acc-photo-remove" action="{{ route('account.photo.destroy') }}" method="POST"
                      onsubmit="return confirm('Hapus foto profil Anda?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm">Hapus Foto</button>
                </form>
            @endif
        </div>

        {{-- Form informasi --}}
        <div class="form-card">
            <h2>Informasi Akun</h2>
            <p class="acc-card-sub">Perbarui nama lengkap, username, dan email Anda.</p>

            <form action="{{ route('account.update') }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required autocomplete="name"
                           class="{{ $bagProfile->has('name') ? 'input-error' : '' }}">
                    @if ($bagProfile->has('name'))<span class="field-error">{{ $bagProfile->first('name') }}</span>@endif
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" maxlength="30" required autocomplete="username"
                               class="{{ $bagProfile->has('username') ? 'input-error' : '' }}">
                        @if ($bagProfile->has('username'))
                            <span class="field-error">{{ $bagProfile->first('username') }}</span>
                        @else
                            <span class="form-help">Huruf, angka, titik, garis bawah, strip. Tanpa spasi.</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required autocomplete="email"
                               class="{{ $bagProfile->has('email') ? 'input-error' : '' }}">
                        @if ($bagProfile->has('email'))<span class="field-error">{{ $bagProfile->first('email') }}</span>@endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="role_display">Peran Akun</label>
                    <input type="text" id="role_display" class="acc-readonly" value="{{ $roleText ?: 'Pengguna' }}" readonly tabindex="-1" aria-describedby="roleHelp">
                    <span class="form-help" id="roleHelp">Peran hanya dapat diubah oleh Super Admin.</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('account.edit') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>

    </div>
</section>

{{-- ======================= TAB: KEAMANAN AKUN ======================= --}}
<section class="acc-panel" id="panel-security" role="tabpanel" aria-labelledby="tab-security" @if ($activeTab !== 'security') hidden @endif>
    <div class="form-card" style="max-width: 720px;">
        <h2>Ubah Password</h2>
        <p class="acc-card-sub">Gunakan password yang kuat dan tidak dipakai di layanan lain.</p>

        <form action="{{ route('account.password.update') }}" method="POST" id="passwordForm" novalidate>
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Password Lama</label>
                <div class="acc-pw">
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                           class="{{ $bagPassword->has('current_password') ? 'input-error' : '' }}">
                    <button type="button" class="acc-pw-toggle" data-toggle="current_password" aria-label="Tampilkan password lama">
                        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @if ($bagPassword->has('current_password'))<span class="field-error">{{ $bagPassword->first('current_password') }}</span>@endif
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="acc-pw">
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                           class="{{ $bagPassword->has('password') ? 'input-error' : '' }}">
                    <button type="button" class="acc-pw-toggle" data-toggle="password" aria-label="Tampilkan password baru">
                        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @if ($bagPassword->has('password'))<span class="field-error">{{ $bagPassword->first('password') }}</span>@endif

                <div class="acc-meter" aria-hidden="true"><span id="pwBar"></span></div>
                <span class="acc-meter-label" id="pwLabel">Kekuatan password</span>

                <ul class="acc-rules" id="pwRules">
                    <li data-rule="len">Minimal 8 karakter</li>
                    <li data-rule="letter">Mengandung huruf</li>
                    <li data-rule="number">Mengandung angka</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <div class="acc-pw">
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="acc-pw-toggle" data-toggle="password_confirmation" aria-label="Tampilkan konfirmasi password">
                        <svg class="icon icon-sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <span class="field-error" id="confirmError" role="alert"></span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ubah Password</button>
            </div>
        </form>
    </div>
</section>
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

        /* ---------- Foto: pilih, validasi, preview, batal ---------- */
        var input = document.getElementById('photoInput');
        var pick = document.getElementById('photoPick');
        var save = document.getElementById('photoSave');
        var cancel = document.getElementById('photoCancel');
        var img = document.getElementById('avatarPreview');
        var initial = document.getElementById('avatarInitial');
        var err = document.getElementById('photoError');
        var originalSrc = img.getAttribute('src') || '';
        var hadPhoto = !!originalSrc;
        var objectUrl = null;
        var MAX = 2 * 1024 * 1024;
        var OK = ['image/jpeg', 'image/png', 'image/webp'];

        function resetPhoto() {
            if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
            input.value = '';
            save.hidden = true; cancel.hidden = true;
            if (hadPhoto) { img.src = originalSrc; img.hidden = false; initial.hidden = true; }
            else { img.removeAttribute('src'); img.hidden = true; initial.hidden = false; }
        }

        pick.addEventListener('click', function () { input.click(); });

        input.addEventListener('change', function () {
            err.textContent = '';
            var file = input.files && input.files[0];
            if (!file) { resetPhoto(); return; }

            if (OK.indexOf(file.type) === -1) {
                err.textContent = 'Format foto harus JPG, JPEG, PNG, atau WEBP.';
                resetPhoto(); return;
            }
            if (file.size > MAX) {
                err.textContent = 'Ukuran foto maksimal 2 MB.';
                resetPhoto(); return;
            }

            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);
            img.src = objectUrl; img.hidden = false; initial.hidden = true;
            save.hidden = false; cancel.hidden = false;
        });

        cancel.addEventListener('click', function () { err.textContent = ''; resetPhoto(); });

        /* ---------- Password: tampil/sembunyi ---------- */
        document.querySelectorAll('.acc-pw-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var f = document.getElementById(btn.dataset.toggle);
                var show = f.type === 'password';
                f.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', String(show));
            });
        });

        /* ---------- Password: kekuatan & konfirmasi ---------- */
        var pw = document.getElementById('password');
        var conf = document.getElementById('password_confirmation');
        var bar = document.getElementById('pwBar');
        var label = document.getElementById('pwLabel');
        var rules = document.getElementById('pwRules');
        var confErr = document.getElementById('confirmError');

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
                ['25%', '#dc2626', 'Lemah'],
                ['45%', '#f97316', 'Kurang kuat'],
                ['65%', '#eab308', 'Cukup'],
                ['85%', '#22c55e', 'Kuat'],
                ['100%', '#16a34a', 'Sangat kuat']
            ][score];
            bar.style.width = map[0]; bar.style.background = map[1]; label.textContent = map[2];
        }

        function checkConfirm() {
            confErr.textContent = (conf.value && conf.value !== pw.value) ? 'Konfirmasi password tidak sama.' : '';
        }

        pw.addEventListener('input', function () { checkPw(); checkConfirm(); });
        conf.addEventListener('input', checkConfirm);

        document.getElementById('passwordForm').addEventListener('submit', function (e) {
            if (conf.value !== pw.value) {
                e.preventDefault();
                confErr.textContent = 'Konfirmasi password tidak sama.';
                conf.focus();
            }
        });
    })();
</script>
@endpush