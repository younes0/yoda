<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use League\Url\Url as LeagueUrl;

class Host extends ExtendedModel 
{
    public $connection = 'items';

    public $table = 'hosts';

    protected $guarded = [];

    protected $casts = [
        'can_have_paywall' => 'boolean',
        'is_responsive'    => 'boolean',
        'is_ignored'       => 'boolean',
        'is_trusted'       => 'boolean',
        'is_banned'        => 'boolean',
    ];

    static public function firstOrCreateFromUrl($url)
    {
        $url  = LeagueUrl::createFromUrl($url);
        $host = $url->getHost();

        $prefix = 'www.';
        if (substr($host, 0, strlen($prefix)) == $prefix) {
            $host = substr($host, strlen($prefix));
        }

        return static::firstOrCreate(['id' => $host]);
    }
}
