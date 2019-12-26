<?php

namespace App\Http\Middleware;

use App\Util;

use Closure;

class ChunkHashHeader
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('app-chunk-hash', Util::chunkHash());
        return $response;
    }
}
