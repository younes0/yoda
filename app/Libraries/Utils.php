<?php

namespace Yoda\Libraries;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class Utils
{    
    static public function transformData(Array $response, TransformerAbstract $transformer)
    {
        return (new Manager)
            ->createData(new Collection($response, $transformer))
            ->toArray()['data'];
    }

    static public function getJediwpPostLink($id)
    {
        return sprintf(
            '%s/wp/wp-admin/post.php?post=%d&action=edit', 
            env('JEDIWP_URL'), 
            $id
        );
    }
}
