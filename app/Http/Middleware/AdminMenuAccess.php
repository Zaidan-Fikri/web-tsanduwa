<?php

namespace App\Http\Middleware;

use App\Support\AdminMenu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tolak akses ke menu admin yang tidak diizinkan untuk pengguna ini
 * (berlaku juga bila alamat menu diketik langsung di browser).
 */
class AdminMenuAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $key = AdminMenu::keyForRequest($request);

        if ($user && $key !== null && ! $user->canAccess($key)) {
            abort(403, 'Anda tidak memiliki akses ke menu ini. Hubungi Super Admin bila memerlukan akses.');
        }

        return $next($request);
    }
}
