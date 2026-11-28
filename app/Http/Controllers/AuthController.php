<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Halaman login umum.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Halaman login berdasarkan role (route lama tetap didukung).
     * Pilihan role sudah tidak dipakai, semua menampilkan form yang sama.
     */
    public function showRoleLogin(?string $role = null)
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna.
     *
     * Role TIDAK dipilih pengguna. Sistem membaca role langsung dari
     * data akun yang diatur Super Admin, lalu mengarahkan ke dashboard
     * yang sesuai. Parameter $role hanya ada agar kompatibel dengan
     * route lama (/login/{role}) dan sengaja diabaikan.
     */
    public function login(Request $request, ?string $role = null)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'username.string' => 'Username atau email harus berupa teks.',
            'username.max' => 'Username atau email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password tidak valid.',
        ]);

        $identifier = trim($validated['username']);

        /*
         * Cari pengguna berdasarkan email.
         * Jika kolom username tersedia, cari berdasarkan username juga.
         */
        $user = User::query()
            ->where(function ($query) use ($identifier) {
                $query->where('email', $identifier);

                if (Schema::hasColumn('users', 'username')) {
                    $query->orWhere('username', $identifier);
                }
            })
            ->first();

        /*
         * Jangan membuat sesi jika pengguna tidak ditemukan
         * atau password tidak cocok.
         */
        if (
            !$user ||
            !is_string($user->password) ||
            !Hash::check($validated['password'], $user->password)
        ) {
            throw ValidationException::withMessages([
                'username' => 'Username/email atau password tidak sesuai.',
            ]);
        }

        /*
         * Pastikan akun aktif.
         */
        if (!(bool) $user->is_active) {
            throw ValidationException::withMessages([
                'username' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.',
            ]);
        }

        /*
         * Baca role dari database dan normalisasi:
         * super-admin menjadi super_admin.
         */
        $actualRole = str_replace(
            '-',
            '_',
            strtolower(trim((string) $user->role))
        );

        /*
         * Tentukan dashboard tujuan berdasarkan role akun.
         * Role yang tidak dikenal ditolak dan tidak dibuatkan sesi.
         */
        $destination = match ($actualRole) {
            'super_admin' => 'super-admin.dashboard',
            'admin', 'pimpinan' => 'dashboard',
            default => null,
        };

        if ($destination === null) {
            throw ValidationException::withMessages([
                'username' => 'Role akun Anda tidak dikenali. Silakan hubungi Super Admin.',
            ]);
        }

        /*
         * Login pengguna dan regenerasi session untuk keamanan.
         */
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route($destination)
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}