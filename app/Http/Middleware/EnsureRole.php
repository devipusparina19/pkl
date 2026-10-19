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

        if (! $user) {
            return redirect()->route('login');
        }

        $userRole = strtolower(trim((string) $user->role));
        $allowedRoles = array_map(
            fn (string $role) => strtolower(trim($role)),
            $roles
        );

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(
                403,
                'Akses ditolak. Role akun kamu tidak memiliki izin untuk halaman ini.'
            );
        }

        return $next($request);
    }
}