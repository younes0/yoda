<?php 

namespace Yeb\Helpers;

use Carbon\Carbon;

class Utils
{
    static $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
    ];

    static public function timeToString($timestamp, $format)
    {
        return Carbon::createFromTimeStamp($timestamp)->formatLocalized($format);
    }

    static public function stringToSqlTime($string)
    {
        return Carbon::parse($string)->format('Y-m-d H:i:s');
    }

    static public function getGeoip($ip = null)
    {
        $ip || $ip = \Request::getClientIp();

        if (ENVIRONMENT === 'local') {
            $ip = '5.50.61.242';
        }

        $adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter();
        $geocoder = new \Geocoder\Geocoder();

        return $geocoder
            ->registerProvider(new \Geocoder\Provider\GeoipProvider($adapter))
            ->geocode($ip);
    }

    static public function addHttp($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }

    static public function deltree($folder)
    {
        if (!is_dir($folder)) {
            return false;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        return rmdir($folder);
    }
    
    static public function itemText($value, Array $options = [])
    {    
        foreach ($options as $o) {        
            if ($o['value'] == $value) {
                return $o['text'];
                break; 
            }        
        }
    }
}
