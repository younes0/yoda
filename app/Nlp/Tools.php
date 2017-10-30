<?php

namespace Yoda\Nlp;

use Carbon\Carbon;
use DB, Str, YebString;
use Yoda\Models\Tag;

class Tools 
{
    static public function getLibrary($name)
    {
        $exploded = explode('::', $name);
        $class    = 'Yoda\Nlp\Libraries\\'.$exploded[0];
        return (new $class($exploded[1]));
    }

    static public function getTester($name)
    {
        $config = new Config($name);
        $class  = 'Yoda\Nlp\Testers\\'.studly_case($name);
        return (new $class($config));
    }

    static public function getClasses(Array $domains, $fromTags = false)
    {
        return $fromTags
            ? Tag::whereIn('domain', $domains)->lists('codename')->all()
            : \DB::connection('nlp')
                ->table('documents')
                ->whereIn('domain', $domains)
                ->orderBy('class')
                ->groupBy('class')
                ->lists('class');
    }
}
