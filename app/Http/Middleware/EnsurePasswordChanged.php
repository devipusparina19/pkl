<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pengguna yang masuk dengan password sementara (hasil reset Super Admin)
 * hanya boleh membuka halaman Akun dan logout sampai password diganti.
 */
class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->must_change_password
            && ! $request->routeIs('account.*', 'logout')
        ) {
            return redirect()
                ->route('account.edit', ['tab' => 'security'])
                ->with(
                    'error',
                    'Anda masuk dengan password sementara. Silakan buat password baru terlebih dahulu.'
                );
        }

        return $next($request);
    }
}