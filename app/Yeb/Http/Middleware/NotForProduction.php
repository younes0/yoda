<?php 

namespace Yeb\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Routing\ResponseFactory;

class NotForProduction implements Middleware 
{
    protected $response;

    /**
     * Create a new middleware instance.
     *
     * @param  ResponseFactory $response
     * @return void
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function handle($request, Closure $next)
	{
        if (\App::environment() !== 'production') {
            return $next($request);
        }

        return $this->response->redirectTo('/');
	}
}
