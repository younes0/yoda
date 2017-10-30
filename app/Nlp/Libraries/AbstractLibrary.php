<?php

namespace Yoda\Nlp\Libraries;

use Carbon\Carbon;
use Yoda\Nlp\Config;
use Yoda\Nlp\Models\Document;
use Illuminate\Support\Collection;
use Cache;
use Storage;

abstract class AbstractLibrary
{    
    protected $config;
    protected $classifier;
    protected $documents;
    protected $type;

    static protected $notSerializable = [
        'MaximumEntropy',
    ];
    
    public function __construct($type)
    {
        $this->type = $type;
    }
     
    /**
     * @param  $mixed array|Eloquent
     * @return array [$class, $probability]
     */
    abstract public function classify($mixed);

    abstract protected function build();

    public function setup(Config $config, Collection $documents = null)
    {
        $this->documents = $documents;
        $this->config    = $config;
        extract($config->toArray());
    
        if (in_array($this->type, static::$notSerializable)) {
            $this->classifier = $this->build();

        // serializable
        } else {
            $lib  = (new \ReflectionClass($this))->getShortName();
            $key  = str_slug(implode('-', [$config->name, $lib, $this->type]));
            $path = 'classifiers/'.$key;

            // cached
            if ($cache && ($cached = Cache::get($key))) {
                $this->classifier = $cached;
                
            } else {
                if ($store && Storage::exists($path)) { // stored
                    $classifier = unserialize(Storage::get($path));
                
                } else { // build and store
                    $classifier = $this->build();
                    $store && Storage::put($path, serialize($classifier)); 
                }

                $cache && Cache::forever($key, $classifier);

                $this->classifier = $classifier;
            }
        }

        return $this;
    }

    protected function getDocuments()
    {
        foreach ($this->documents as $doc) {
            yield [ $doc->class, $doc->content];
        }
    }

    protected function setNoClass()
    {
        return [
            'class' => 'none', 
            'score' => null,
        ];
    }
}
