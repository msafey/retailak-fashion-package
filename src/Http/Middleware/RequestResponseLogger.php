<?php

namespace App\Http\Middleware;

use Closure;

use Carbon\Carbon;
use Log;

class RequestResponseLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::info('DAILY_LOGS', [
            'Time' => Carbon::now(),
            'IP' => $request->ip(),
            'Method' => $request->method(),
            'URL' => $request->fullUrl(),
            'Headers' => $request->headers,
            'Request' => $request->getContent,
            'response' => $response->getContent(),
        ]);
    }
}
