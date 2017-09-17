<?php

namespace App\Http\Middleware;

use Closure;

class RequireSuperAdmin {

    public function handle($request, Closure $next)
    {
        if (!\Auth::check() || !\Auth::user()->is_super_admin) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
