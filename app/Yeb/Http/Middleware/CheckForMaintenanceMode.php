<?php

namespace Yeb\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\App::isDownForMaintenance() && !env('APP_DEBUG')) {
            return response()->make(view('errors.down'), 503);
        }

        return $next($request);
    }
}
