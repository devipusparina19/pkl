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
        return view('auth.login', [
            'loginRole' => 'admin',
            'loginRoleLabel' => 'Admin',
        ]);
    }

    /**
     * Halaman login berdasarkan role.
     */
    public function showRoleLogin(string $role)
    {
        $this->validateRolePath($role);

        return view('auth.login', [
            'loginRole' => $role,
            'loginRoleLabel' => $this->roleLabel($role),
        ]);
    }

    /**
     * Proses autentikasi pengguna.
     */
    public function login(Request $request, string $role)
    {
        $this->validateRolePath($role);

        // Validasi sesuai name input pada halaman login.
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
        $userQuery = User::query()->where(function ($query) use ($identifier) {
            $query->where('email', $identifier);

            if (Schema::hasColumn('users', 'username')) {
                $query->orWhere('username', $identifier);
            }
        });

        $user = $userQuery->first();

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
         * Normalisasi role:
         * super-admin menjadi super_admin.
         */
        $normalizeRole = static function (?string $value): string {
            return str_replace(
                '-',
                '_',
                strtolower(trim((string) $value))
            );
        };

        $expectedRole = $normalizeRole($role);
        $actualRole = $normalizeRole($user->role);

        /*
         * Pastikan role pilihan pada halaman login cocok
         * dengan role yang tersimpan di database.
         */
        if ($actualRole !== $expectedRole) {
            throw ValidationException::withMessages([
                'username' => 'Jenis akun tidak sesuai dengan role akun yang terdaftar.',
            ]);
        }

        /*
         * Login pengguna dan regenerasi session untuk keamanan.
         */
        Auth::login($user);
        $request->session()->regenerate();

        /*
         * Tentukan dashboard tujuan berdasarkan role.
         */
        $destination = match ($actualRole) {
            'super_admin' => 'super-admin.dashboard',
            'admin', 'pimpinan' => 'dashboard',
            default => 'public.dashboard',
        };

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
            ->route('public.dashboard')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }

    /**
     * Batasi role yang diperbolehkan pada URL login.
     */
    private function validateRolePath(string $role): void
    {
        abort_unless(
            in_array($role, [
                'super-admin',
                'admin',
                'pimpinan',
            ], true),
            404
        );
    }

    /**
     * Label role untuk halaman login.
     */
    private function roleLabel(string $role): string
    {
        return match ($role) {
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
            default => 'Internal',
        };
    }
}