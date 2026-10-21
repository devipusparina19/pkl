<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', [
            'loginRole' => null,
            'loginRoleLabel' => 'Internal',
        ]);
    }

    public function showRoleLogin(string $role)
    {
        $this->validateRolePath($role);

        return view('auth.login', [
            'loginRole' => $role,
            'loginRoleLabel' => $this->roleLabel($role),
        ]);
    }

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

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Username/email atau password tidak sesuai.',
            ]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.',
            ]);
        }

        $normalizeRole = static function (string $value): string {
            return str_replace('-', '_', strtolower(trim($value)));
        };

        $expectedRole = $normalizeRole($role);
        $actualRole = $normalizeRole((string) $user->role);

        if ($actualRole !== $expectedRole) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Jenis akun tidak sesuai dengan role akun yang terdaftar.',
            ]);
        }

        $request->session()->regenerate();

        $destination = match ($actualRole) {
            'super_admin' => 'super-admin.dashboard',
            'admin', 'pimpinan' => 'dashboard',
            default => 'public.dashboard',
        };

        return redirect()
            ->route($destination)
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('public.dashboard')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }

    private function validateRolePath(string $role): void
    {
        abort_unless(
            in_array($role, ['super-admin', 'admin', 'pimpinan'], true),
            404
        );
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
        };
    }
}