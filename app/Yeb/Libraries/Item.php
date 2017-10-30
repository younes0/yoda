<?php

namespace Yeb\Libraries;

use Illuminate\Support\Collection;
use Cache;
use DB;

/**
 * Items are values don't change often and are stored in Repository.
 * Storing in DB instead of PHP Arrays allows easier editing & sharing  
 */
class Item
{
    /**
     * Retrieve Items from "various" table
     *
     * @param  String $item Item type
     * @param  string $con  Database connection
     * @return Collection
     */
    static public function all($item, $con = 'items')
    {
        $cacheMins = env('APP_DEBUG') ? 0 : 60;

        return Cache::tags('items')->remember($item, $cacheMins, function() use ($item, $con) {
            $db = DB::connection($con);

            $array = (\Schema::connection($con)->hasTable($item))
                ? $db->table($item)->get()
                : $db->table('various')->where('item', $item)->get();

            return collect($array);
        });
    }

    /**
     * Retrieve Single Item
     *
     * @return String
     */
    static public function get($id, $item, $field, $con = 'items')
    {
        $item = static::all($item, $con)->where('id', $id)->first();
        
        return $item ? $item->$field : null;
    }

    static public function asOptions(Collection $values, $lang = 'en', $textCallback = null)
    {
        $output = [];

        $textCallback = $textCallback ?: function($text) {
            return ucfirst($text);
        };

        foreach ($values->all() as $value) {
            $output[$value->id] = $textCallback($value->{'text_'.$lang});
        }

        return $output;
    }
}
