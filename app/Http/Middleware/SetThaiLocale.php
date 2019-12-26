<?php

namespace App\Http\Middleware;

use Closure;

class SetThaiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $app = app();
        $originalLocale = $app->getLocale();
        app()->setLocale('th');
        try {
            $result = $next($request);
        } finally {
            $app->setLocale($originalLocale);
        }
        return $result;
    }
}
