<?php 

namespace Yeb\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Agent, Config, Cookie;

class Translate implements Middleware 
{

    protected $accepted, $locales;

    public function __construct() 
    {
        $this->accepted = $this->getAccepted();
        $this->locales  = Config::get('laravel-gettext.supported-locales');
    }

    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        $locale = null;

        // define lang
        if ( count($this->locales) === 1) {
            $locale = $this->locales[0];

        } elseif ( !$locale ) {
            // user changes lang
            if ($request->has('$locale')) {
                $locale = $request->get('$locale');
                Cookie::queue('$locale', $locale, (3600*24*7), '/');

            // cookie apply
            } elseif ($request->cookie('$locale')) {
                $locale = $request->cookie('$locale');
                
            // user's browser
            } else {
                $locale = ( !Agent::isRobot() && $this->accepted ) 
                    ? $this->getBestLocale() 
                    : null;
            } 

            if ( !in_array($locale, $this->locales) ) {
                $locale = $this->locales[0]; // default locale
            }
        }
        
        // setup
        \LaravelGettext::setLocale($locale);

        return $next($request);
    }

    protected function getAccepted()
    {
        return array_map(function($value) {
            if ( !\Str::contains($value, '-')) {
                return $value;
            }

            $parts = explode('-', $value);
            return $parts[0].'_'.\Str::upper($parts[1]);

        }, Agent::languages()); 
    }

    protected function getBestLocale()
    {
        $langs = array_map(function($n) {
            return [$n => substr($n, 0, 2)];
        }, $this->locales); 

        foreach ($this->accepted as $value) {
            if (in_array($value, $this->locales)) {
                return $value;
            }

            if (in_array($value, array_values($langs))) {
                return array_search($value, $langs); 
            }
        }

        return null;
    }
    
}
