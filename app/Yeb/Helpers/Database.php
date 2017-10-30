<?php

namespace Yeb\Helpers;

use Illuminate\Database\Eloquent\Collection;

class Database
{
    static public function outputArrayAgg($in, $string = false)
    {
        $in = str_replace('NULL', null, $in);
        $in = str_replace(str_split('{}'), '', $in);
        
        if ($string) {
            $in = str_replace('"', null, $in);
        }
        
        return array_filter(array_unique(explode(',', $in)));
    }

    static public function outputRowToJson($string, Array $keys = [])
    {
        return array_filter(json_decode($string, true));
    }

    static public function collectionAsItem(Collection $collection, Array $columns, $cache = null)
    {
        $output = [];

        foreach ($collection->toArray() as $row) {
            $output[] = [
                'value' => isset($columns['value']) ? $row[$columns['value']] : $row['id'],
                'text'  => $row[$columns['text']],
            ];
        }

        return $output;
    }
}
