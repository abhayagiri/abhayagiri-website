<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class LongRunningRequest
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
        $originalLimit = ini_get('max_execution_time');
        $newLimit = Config::get('abhayagiri.long_running_request_time_limit');
        if ((int) $originalLimit == 0 || $newLimit <= (int) $originalLimit) {
            $newLimit = null;
        }
        try {
            if (!is_null($newLimit)) {
                set_time_limit($newLimit);
            }
            $result = $next($request);
        } finally {
            if (!is_null($newLimit)) {
                set_time_limit($originalLimit);
            }
        }
        return $result;
    }
}
