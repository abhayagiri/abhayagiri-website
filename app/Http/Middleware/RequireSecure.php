<?php

namespace App\Http\Middleware;

use Closure;

class RequireSecure {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && config('abhayagiri.require_ssl')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
