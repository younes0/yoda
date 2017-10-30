<?php 

namespace Yoda\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Auth;

class Admin implements Middleware 
{
    /**
     * Handle an incoming request.
     * Check if admin is logged
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_admin) {
            return $next($request);
        }

        return response()->redirectTo('/');
    }
}
