<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  Comma-separated roles from the route definition
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route($request->is('ux2/*') ? 'ux2.login' : 'login');
        }

        // Compare the user's role (Enum value) with the allowed roles array
        if (! in_array($request->user()->role->value, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin (role) yang sesuai untuk halaman ini.');
        }

        return $next($request);
    }
}
