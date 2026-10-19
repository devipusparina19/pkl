<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login internal.
     */
    public function showLogin()
    {
        return view('auth.login', [
            'loginRole' => null,
            'loginRoleLabel' => 'Internal',
        ]);
    }

    /**
     * Menampilkan halaman login berdasarkan role.
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
     * Proses login menggunakan username/email yang sama.
     *
     * Autentikasi dilakukan melalui kolom email.
     */
    public function login(Request $request, string $role)
    {
        $this->validateRolePath($role);

        $validated = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Username/email wajib diisi.',
            'email.max' => 'Username/email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Username/email atau password tidak sesuai.',
            ]);
        }

        $user = Auth::user();

        /*
         * Tolak login jika akun dinonaktifkan.
         */
        if (! $user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.',
            ]);
        }

        /*
         * Role pada URL menggunakan tanda hubung,
         * sedangkan role di database menggunakan underscore.
         *
         * super-admin -> super_admin
         */
        $expectedRole = str_replace('-', '_', $role);

        /*
         * Pastikan role akun sesuai dengan halaman login.
         */
        if ($user->role !== $expectedRole) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Jenis akun tidak sesuai dengan role akun yang terdaftar.',
            ]);
        }

        /*
         * Regenerasi session setelah login berhasil.
         */
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
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
     * Memvalidasi role yang diperbolehkan pada URL login.
     */
    private function validateRolePath(string $role): void
    {
        abort_unless(
            in_array($role, ['super-admin', 'admin', 'pimpinan'], true),
            404
        );
    }

    /**
     * Label role untuk ditampilkan pada halaman login.
     */
    private function roleLabel(string $role): string
    {
        return match ($role) {
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
        };
    }
}