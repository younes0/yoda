<?php

return [

    'name' => 'Yoda',

    // if disabled, a simple generic error page is shown.
    'debug' => env('APP_DEBUG', false),

    // for artisan URLs generated
    'url' => env('PUBLIC_URL'),

    // Application Timezone : used by the PHP date and date-time functions
    'timezone' => 'UTC',

    // Default Locale Configuration
    'locale' => 'fr',
    'fallback_locale' => 'fr',

    // Encryption Key is used by the Illuminate encrypter service and should be set
    // to a random, 32 character string, otherwise these encrypted strings
    // will not be safe. Please do this before deploying an application!
    'key' => env('APP_KEY', 'SomeRandomString'),
    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => 'daily',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
   
    'providers' => [
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Routing\ControllerServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        // vendor:yeb
        BackupManager\Laravel\Laravel5ServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Chumper\Datatable\DatatableServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Former\FormerServiceProvider::class,
        GrahamCampbell\Exceptions\ExceptionsServiceProvider::class,
        GrahamCampbell\HTMLMin\HTMLMinServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Menu\MenuServiceProvider::class,
        Prologue\Alerts\AlertsServiceProvider::class,
        Radic\BladeExtensions\BladeExtensionsServiceProvider::class,
        Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
        Rosio\PhpToJavaScriptVariables\PhpToJavaScriptVariablesServiceProvider::class,
        Xinax\LaravelGettext\LaravelGettextServiceProvider::class,

        // vendor:app
        Caffeinated\Themes\ThemeServiceProvider::class,
        Spatie\Activitylog\ActivitylogServiceProvider::class,
        Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
        Bootstrapper\BootstrapperL5ServiceProvider::class,

        // app
        Yoda\Providers\AppServiceProvider::class,
        Yoda\Providers\EventServiceProvider::class,
        Yoda\Providers\RouteServiceProvider::class,
        Yoda\Providers\BindingServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        // app
        'YodaView'   => Yoda\Libraries\View::class,
        'ApiClients' => Yoda\Api\Clients\ClientsFacade::class,
        'ApiHelper'  => Yoda\Api\Utils\HelperFacade::class,

        // yeb framework 
        'Item'         => Yeb\Facades\Item::class,
        'YebArray'     => Yeb\Helpers\ArrayHelpers::class,
        'YebBootstrap' => Yeb\Helpers\Bootstrap::class,
        'YebDatabase'  => Yeb\Helpers\Database::class,
        'YebString'    => Yeb\Helpers\StringHelper::class,
        'YebUrl'       => Yeb\Helpers\Url::class,
        'YebUtils'     => Yeb\Helpers\Utils::class,

        // vendor
        'Activity'  => Spatie\Activitylog\ActivitylogFacade::class,
        'Agent'     => Jenssegers\Agent\Facades\Agent::class,
        'Alert'     => Prologue\Alerts\Facades\Alert::class,
        'Datatable' => Chumper\Datatable\Facades\DatatableFacade::class,
        'Debugbar'  => Barryvdh\Debugbar\Facade::class,
        'Former'    => Former\Facades\Former::class,
        'HTMLMin'   => GrahamCampbell\HTMLMin\Facades\HTMLMin::class,
        'Menu'      => Menu\Menu::class,
        // 'Themes' => Laradic\Themes\Facades\Themes::class,
        'Themes'    => Caffeinated\Themes\Facades\Themes::class,

        // bootstraper
        'Button'         => Bootstrapper\Facades\Button::class,
        'DropdownButton' => Bootstrapper\Facades\DropdownButton::class,
        'Label'          => Bootstrapper\Facades\Label::class,

        // illuminate
        'Form' => Collective\Html\FormFacade::class,
        'HTML' => Collective\Html\HtmlFacade::class,
        'Str'  => Illuminate\Support\Str::class,
        
        // laravel
        'App'       => Illuminate\Support\Facades\App::class,
        'Artisan'   => Illuminate\Support\Facades\Artisan::class,
        'Auth'      => Illuminate\Support\Facades\Auth::class,
        'Blade'     => Illuminate\Support\Facades\Blade::class,
        'Bus'       => Illuminate\Support\Facades\Bus::class,
        'Cache'     => Illuminate\Support\Facades\Cache::class,
        'Config'    => Illuminate\Support\Facades\Config::class,
        'Cookie'    => Illuminate\Support\Facades\Cookie::class,
        'Crypt'     => Illuminate\Support\Facades\Crypt::class,
        'DB'        => Illuminate\Support\Facades\DB::class,
        'Event'     => Illuminate\Support\Facades\Event::class,
        'File'      => Illuminate\Support\Facades\File::class,
        'Hash'      => Illuminate\Support\Facades\Hash::class,
        'Input'     => Illuminate\Support\Facades\Input::class,
        'Inspiring' => Illuminate\Foundation\Inspiring::class,
        'Lang'      => Illuminate\Support\Facades\Lang::class,
        'Log'       => Illuminate\Support\Facades\Log::class,
        'Mail'      => Illuminate\Support\Facades\Mail::class,
        'Password'  => Illuminate\Support\Facades\Password::class,
        'Queue'     => Illuminate\Support\Facades\Queue::class,
        'Redirect'  => Illuminate\Support\Facades\Redirect::class,
        'Redis'     => Illuminate\Support\Facades\Redis::class,
        'Request'   => Illuminate\Support\Facades\Request::class,
        'Response'  => Illuminate\Support\Facades\Response::class,
        'Route'     => Illuminate\Support\Facades\Route::class,
        'Schema'    => Illuminate\Support\Facades\Schema::class,
        'Session'   => Illuminate\Support\Facades\Session::class,
        'Storage'   => Illuminate\Support\Facades\Storage::class,
        'URL'       => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View'      => Illuminate\Support\Facades\View::class,
    ],

];
