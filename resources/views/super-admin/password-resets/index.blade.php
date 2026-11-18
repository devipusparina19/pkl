@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<style>
    .pr-page {
        --navy: #081B33; --royal: #2457D6; --soft: #EAF2FF; --ice: #F3F7FC;
        --slate: #64748B; --text: #14243A; --border: #E1E9F2;
        --green: #166534; --green-soft: #F0FDF4; --red: #991B1B; --red-soft: #FEF2F2;
        --amber: #92400E; --amber-soft: #FFFBEB;
        width: 100%; color: var(--text);
    }
    .pr-page *, .pr-page *::before, .pr-page *::after { box-sizing: border-box; }
    .pr-head { margin-bottom: 24px; }
    .pr-eyebrow { display: inline-block; margin-bottom: 8px; color: var(--royal); font-size: 14px; font-weight: 700; }
    .pr-head h1 { margin: 0 0 8px; color: var(--navy); font-size: clamp(26px, 3vw, 38px); font-weight: 800; letter-spacing: -.03em; line-height: 1.15; }
    .pr-head p { margin: 0; color: var(--slate); max-width: 70ch; }

    .pr-temp {
        margin-bottom: 24px; padding: 22px; border: 2px solid var(--royal); border-radius: 16px;
        background: var(--soft);
    }
    .pr-temp h2 { margin: 0 0 6px; color: var(--navy); font-size: 18px; }
    .pr-temp p { margin: 0 0 14px; color: #1e3a8a; font-size: 14px; }
    .pr-temp-row { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .pr-temp-code {
        padding: 12px 18px; border: 1px dashed var(--royal); border-radius: 12px; background: #fff;
        font-family: ui-monospace, Consolas, monospace; font-size: 22px; font-weight: 700; letter-spacing: .08em; user-select: all;
    }

    .pr-card { margin-bottom: 24px; padding: 22px; border: 1px solid var(--border); border-radius: 16px; background: #fff; }
    .pr-card h2 { display: flex; align-items: center; gap: 10px; margin: 0 0 16px; color: var(--navy); font-size: 18px; }
    .pr-count { display: inline-grid; place-items: center; min-width: 28px; height: 28px; padding: 0 8px; border-radius: 999px; background: var(--royal); color: #fff; font-size: 13px; }
    .pr-empty { margin: 0; padding: 18px; border-radius: 12px; background: var(--ice); color: var(--slate); text-align: center; }

    .pr-item { display: grid; gap: 14px; padding: 18px 0; border-top: 1px solid var(--border); }
    .pr-item:first-of-type { border-top: 0; padding-top: 0; }
    .pr-who strong { display: block; font-size: 17px; color: var(--navy); }
    .pr-who span, .pr-meta { color: var(--slate); font-size: 14px; }
    .pr-meta { display: flex; flex-wrap: wrap; gap: 6px 18px; }
    .pr-note { padding: 10px 14px; border-radius: 10px; background: var(--ice); font-size: 14px; }

    .pr-badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .pr-badge.ok { background: var(--green-soft); color: var(--green); }
    .pr-badge.warn { background: var(--amber-soft); color: var(--amber); }
    .pr-badge.bad { background: var(--red-soft); color: var(--red); }
    .pr-badge.neutral { background: var(--ice); color: var(--slate); }

    .pr-actions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .pr-form { display: grid; gap: 8px; padding: 14px; border: 1px solid var(--border); border-radius: 12px; }
    .pr-form label { font-size: 13px; font-weight: 700; color: #40536a; }
    .pr-form input[type="text"] {
        width: 100%; height: 44px; padding: 0 12px; border: 1.5px solid var(--border); border-radius: 10px; font: inherit; font-size: 15px;
    }
    .pr-form input[type="text"]:focus { outline: none; border-color: var(--royal); box-shadow: 0 0 0 3px rgba(36,87,214,.12); }
    .pr-btn { min-height: 44px; padding: 8px 18px; border: 0; border-radius: 999px; font: inherit; font-weight: 700; cursor: pointer; color: #fff; background: var(--royal); }
    .pr-btn:hover { background: var(--navy); }
    .pr-btn.danger { background: #fff; color: var(--red); border: 1.5px solid #fecaca; }
    .pr-btn.danger:hover { background: var(--red-soft); }
    .pr-btn.small { min-height: 38px; font-size: 14px; }

    .pr-table-wrap { overflow-x: auto; }
    .pr-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .pr-table th, .pr-table td { padding: 10px 12px; border-bottom: 1px solid var(--border); text-align: left; vertical-align: top; }
    .pr-table th { color: var(--slate); font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }

    @media (max-width: 760px) { .pr-actions { grid-template-columns: 1fr; } .pr-temp-code { font-size: 18px; } }
</style>

@php
    $statusLabel = [
        'approved'  => ['Disetujui', 'ok'],
        'rejected'  => ['Ditolak', 'bad'],
        'cancelled' => ['Dibatalkan', 'neutral'],
    ];
@endphp

<div class="pr-page">

    <header class="pr-head">
        <span class="pr-eyebrow">Manajemen Sistem</span>
        <h1>Permintaan Reset Password</h1>
        <p>
            Verifikasi identitas pemohon di luar aplikasi (telepon, atasan, atau tatap muka) sebelum menyetujui.
            Password sementara hanya ditampilkan satu kali dan pemohon wajib menggantinya saat login.
        </p>
    </header>

    @isset($tempPassword)
        <section class="pr-temp" role="alert">
            <h2>Password sementara untuk {{ $tempPassword['name'] }}</h2>
            <p>
                Username: <strong>{{ $tempPassword['username'] }}</strong>.
                Sampaikan langsung kepada pemilik akun. Halaman ini tidak bisa dibuka lagi untuk melihat password ini.
            </p>
            <div class="pr-temp-row">
                <span class="pr-temp-code" id="tempCode">{{ $tempPassword['password'] }}</span>
                <button type="button" class="pr-btn small" id="copyTemp">Salin</button>
            </div>
        </section>
    @endisset

    <section class="pr-card">
        <h2>Menunggu verifikasi <span class="pr-count">{{ $pending->count() }}</span></h2>

        @forelse ($pending as $r)
            <article class="pr-item">
                <div class="pr-who">
                    <strong>{{ $r->user?->name ?? $r->username_snapshot }}</strong>
                    <span>Username: {{ $r->username_snapshot }} · Role: {{ $r->user?->role ?? '-' }}</span>
                </div>

                <div class="pr-meta">
                    <span>Diajukan: {{ $r->created_at->format('d/m/Y H:i') }}</span>
                    <span>IP: {{ $r->requested_ip ?: '-' }}</span>
                    <span>Nama yang diketik: {{ $r->full_name_input }}</span>
                    @if ($r->name_matches)
                        <span class="pr-badge ok">Nama cocok</span>
                    @else
                        <span class="pr-badge warn">Nama TIDAK cocok</span>
                    @endif
                </div>

                @if ($r->note)
                    <div class="pr-note">Keterangan pemohon: {{ $r->note }}</div>
                @endif

                <div class="pr-actions">
                    <form class="pr-form" method="POST"
                          action="{{ route('super-admin.password-resets.approve', $r) }}"
                          onsubmit="return confirm('Setujui reset password untuk akun ini? Semua sesi aktifnya akan diputus.');">
                        @csrf
                        <label for="vn-{{ $r->id }}">Catatan verifikasi identitas (wajib)</label>
                        <input type="text" id="vn-{{ $r->id }}" name="verification_note"
                               placeholder="Contoh: Dikonfirmasi via telepon ke atasan langsung" required minlength="5" maxlength="255">
                        <button type="submit" class="pr-btn">Setujui &amp; buat password sementara</button>
                    </form>

                    <form class="pr-form" method="POST"
                          action="{{ route('super-admin.password-resets.reject', $r) }}"
                          onsubmit="return confirm('Tolak permintaan ini?');">
                        @csrf
                        <label for="rn-{{ $r->id }}">Alasan penolakan (wajib)</label>
                        <input type="text" id="rn-{{ $r->id }}" name="handler_note"
                               placeholder="Contoh: Identitas tidak dapat diverifikasi" required minlength="5" maxlength="255">
                        <button type="submit" class="pr-btn danger">Tolak permintaan</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="pr-empty">Tidak ada permintaan yang menunggu.</p>
        @endforelse
    </section>

    <section class="pr-card">
        <h2>Riwayat terakhir</h2>

        @if ($history->isEmpty())
            <p class="pr-empty">Belum ada riwayat.</p>
        @else
            <div class="pr-table-wrap">
                <table class="pr-table">
                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th>Status</th>
                            <th>Diproses oleh</th>
                            <th>Waktu</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $h)
                            @php [$label, $tone] = $statusLabel[$h->status] ?? [$h->status, 'neutral']; @endphp
                            <tr>
                                <td>{{ $h->user?->name ?? $h->username_snapshot }}</td>
                                <td><span class="pr-badge {{ $tone }}">{{ $label }}</span></td>
                                <td>{{ $h->handler?->name ?? 'Server (artisan)' }}</td>
                                <td>{{ $h->handled_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $h->handler_note ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>

<script>
    (function () {
        var btn = document.getElementById('copyTemp');
        var code = document.getElementById('tempCode');
        if (!btn || !code) return;

        btn.addEventListener('click', function () {
            var text = code.textContent.trim();
            var done = function () { btn.textContent = 'Tersalin'; setTimeout(function () { btn.textContent = 'Salin'; }, 2000); };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, function () {});
            } else {
                var r = document.createRange(); r.selectNodeContents(code);
                var s = window.getSelection(); s.removeAllRanges(); s.addRange(r);
                try { document.execCommand('copy'); done(); } catch (e) {}
            }
        });
    })();
</script>

@endsection