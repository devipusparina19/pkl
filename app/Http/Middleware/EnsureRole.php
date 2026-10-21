<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $normalizeRole = static function (string $role): string {
            $role = strtolower(trim($role));
            $role = str_replace('-', '_', $role);

            if ($role === 'participant') {
                return 'user';
            }

            return $role;
        };

        $userRole = $normalizeRole((string) $user->role);

        $allowedRoles = array_map(
            $normalizeRole,
            $roles
        );

        abort_unless(
            in_array($userRole, $allowedRoles, true),
            403,
            'Akses ditolak. Akun Anda tidak memiliki izin untuk halaman ini.'
        );

        return $next($request);
    }
}