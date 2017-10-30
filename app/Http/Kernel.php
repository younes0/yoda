<?php 

namespace Yoda\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel 
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Yeb\Http\Middleware\CheckForMaintenanceMode::class,
        \Yoda\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Xinax\LaravelGettext\Middleware\GettextMiddleware::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Yoda\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin'      => \Yoda\Http\Middleware\Admin::class,
        'auth'       => \Yoda\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \Yoda\Http\Middleware\RedirectIfAuthenticated::class,
        'noProd'     => \Yeb\Http\Middleware\NotForProduction::class,
    ];
}
