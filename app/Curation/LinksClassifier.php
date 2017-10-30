<?php

namespace Yoda\Curation;

use Yoda\Models\Link;
use Yoda\Nlp\Config;
use Illuminate\Database\Eloquent\Collection;

// later: domain specific
class LinksClassifier
{
    protected $links;

    protected $config;

    public function __construct(Collection $links)
    {
        $this->links = $links;
    }

    public function boot()
    {
        foreach ($this->links as $link) {

            $link->nlpClassify('is_law-fr');

            if ($link->nlpClassed->class === 'true') {
                $link->nlpClassify('law-fr');
                $link->tagFromNlp();
            }

        }
    }
}
