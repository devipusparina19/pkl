<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Memastikan pengguna memiliki role yang diizinkan.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (!$user) {
            return redirect()->guest(route('login'));
        }

        $normalizeRole = static function (?string $role): string {
            $role = strtolower(trim((string) $role));
            $role = str_replace('-', '_', $role);

            return match ($role) {
                'participant' => 'user',
                default => $role,
            };
        };

        $userRole = $normalizeRole($user->role);

        $allowedRoles = array_map(
            $normalizeRole,
            $roles
        );

        if (
            $userRole === '' ||
            !in_array($userRole, $allowedRoles, true)
        ) {
            abort(
                403,
                'Akses ditolak. Role akun Anda: '
                . ($userRole !== '' ? $userRole : 'belum diatur')
                . '. Role yang diizinkan: '
                . implode(', ', $allowedRoles)
                . '.'
            );
        }

        return $next($request);
    }
}