<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PasswordRecoveryController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengajukan permintaan reset. Respons SELALU sama, apa pun hasilnya,
     * agar halaman ini tidak bisa dipakai untuk menebak akun yang terdaftar.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'string', 'max:0'], // honeypot
        ]);

        $identifier = trim($data['username']);
        $generic = redirect()
            ->route('password.forgot')
            ->with('recovery_sent', true);

        // Honeypot terisi: kemungkinan bot. Tampilkan respons sama, tanpa menyimpan.
        if (! empty($request->input('website'))) {
            return $generic;
        }

        // Batas per identitas: 3 permintaan per jam.
        $key = 'pwreset:' . sha1(mb_strtolower($identifier));

        if (RateLimiter::tooManyAttempts($key, 3)) {
            Log::notice('Permintaan reset password dibatasi (rate limit).', [
                'ip' => $request->ip(),
            ]);

            return $generic;
        }

        RateLimiter::hit($key, 3600);

        $user = User::query()
            ->where(function ($query) use ($identifier) {
                $query->where('email', $identifier)
                    ->orWhere('username', $identifier);
            })
            ->whereIn('role', ['admin', 'pimpinan'])
            ->where('is_active', true)
            ->first();

        if (! $user) {
            Log::notice('Permintaan reset password untuk akun yang tidak memenuhi syarat.', [
                'ip' => $request->ip(),
            ]);

            return $generic;
        }

        $alreadyPending = PasswordResetRequest::query()
            ->where('user_id', $user->id)
            ->where('status', PasswordResetRequest::PENDING)
            ->exists();

        if (! $alreadyPending) {
            $nameMatches = mb_strtolower(trim($data['name']))
                === mb_strtolower(trim((string) $user->name));

            PasswordResetRequest::create([
                'user_id' => $user->id,
                'username_snapshot' => (string) ($user->username ?: $user->email),
                'full_name_input' => trim($data['name']),
                'name_matches' => $nameMatches,
                'note' => $data['note'] ?? null,
                'status' => PasswordResetRequest::PENDING,
                'requested_ip' => $request->ip(),
                'requested_user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            ]);

            Log::info('Permintaan reset password dibuat.', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
        }

        return $generic;
    }
}