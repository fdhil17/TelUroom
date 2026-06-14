<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userRole = $request->user()->role;

        // Jika user adalah admin, ambil role dari session
        if ($userRole === 'admin') {
            $userRole = $request->session()->get('admin_role', 'admin');
        }

        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
