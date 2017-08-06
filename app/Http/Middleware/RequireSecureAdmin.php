<?php

namespace App\Http\Middleware;

use Closure;

class RequireSecureAdmin {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && config('abhayagiri.require_mahapanel_ssl')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
