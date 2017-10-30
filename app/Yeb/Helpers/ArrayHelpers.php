<?php

namespace Yeb\Helpers;

class ArrayHelpers 
{
    public static function countValuesOf($value, $array) 
    {
        $counts = array_count_values($array);

        return isset($counts[$value]) ? $counts[$value] : 0;
    }

    /**
     * Convert recursively object to array
     * 
     * @param  Object object
     * @return Array array
     */
    public static function objectToArray($obj)
    {
        if( !is_object($obj) && !is_array($obj) ) {
            return $obj;
        }

        if( is_object($obj) ) {
            $obj = (array) $obj;
        }

        return array_map('\Yeb\Helpers\ArrayHelpers::objectToArray', $obj);
    }

    /**
     * Retourne la clé contenant la valeur la plus élevée (numérique)
     * 
     * @param  array  $array
     * @return mixed
     */
    public static function maxKey($array) 
    {
        foreach ($array as $key => $val) {
            if ($val == max($array)) return $key;
        }
    }

    /**
     * Find max() of specific multidimensional array value
     * 
     * @param  Array  $array Arrays to search 
     * @param  String $key   Key of array to search by
     * @return Array         Array
     */
    public static function maxKeyValue($array, $key) 
    {
        $max = max(array_pluck($array, $key));

        return static::recursiveArraySearch($array, $key, $max)[0];
    }
        
    /**
     * Compare arrays without caring for the order
     * 
     * @param  Array  $a 
     * @param  Array  $b 
     * @return boolean
     */
    public static function compareWoOrder(Array $a, Array $b) 
    {
        return (count(array_diff(array_merge($a, $b), array_intersect($a, $b))) === 0);
    }

    /**
     * Modifie la structure d'un tableau en regroupant les valeurs par une clé
     */
    public static function groupByKey($array, $groupingKey)
    {
        $out = [];
        foreach($array as $key => $item) {
           $out[$item[$groupingKey]][$key] = $item;
        }
        ksort($out);
        return $out;
    }
    
    /**
     * Détermine si une valeur est trouvée dans un tableau multi-dimensionnel
     * 
     * @param  array $array tableau où chercher
     * @param  mixed $key   la clé où chercher
     * @param  mixed $value valeur à chercher
     * @return boolean
     */
    public static function inKeyArray($array, $key, $value)
    {
        //loop through the array
        foreach ($array as $val) {
            
            //if $val is an array call inKeyArray again with $val as array input
            if (is_array($val)) {
                if (self::inKeyArray($val, $key, $value)) {
                    return true;
                }
            }
            //else check if the given key has $value as value
            else{
                if ($array[$key]==$value) {
                    return true;
                }
            }
        }
        return false;
    }
   
    /**
     * Extract Children Arrays from a specific key/value
     * 
     * @param  array  $array [description]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function children(array $array, $key, $value)
    {
        $results = [];

        foreach ($array as $el){

            if ($el[$key] == $value) {
                $results[] = $el;
            }
            if (count($el['children']) > 0 AND ($children = self::children($el['children'], $key, $value)) !== FALSE) {
                $results = array_merge($results, $children);
            }
        }
        return count($results) > 0 ? $results : FALSE;
    }

    /**
     * Destroy Key and Value
     *
     * Destroys a Key => Value pair by unsetting it from the array. 
     * This is obviously useful when you want to quickly delete something from an array but it could be present at any level.
     */
    public static function destroyKeyValue($haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if ($key == $needle) {
                unset($key);

            } elseif (is_array($value)) {
                $output[$key] = self::destroyKeyValue($value, $needle);

            } else {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Sort multidimensional Array by Value 
     *
     * @param array $array array to sort
     * @param string $key sorting
     */
    public static function aaSort($array, $key, $reverse = false) {
        $sorter = [];
        $ret    = [];
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }

        if ($reverse) {
            $ret = array_reverse($ret);
        }

        return $ret;
    }

    public static function recursiveArraySearch($array, $key, $value)
    {
        $results = [];

        if (is_array($array)) {
            if (isset($array[$key]) && ($array[$key] == $value)) {
                $results[] = $array;
            }

            foreach ($array as $subArray) {
                $results = array_merge(
                    $results, 
                    static::recursiveArraySearch($subArray, $key, $value)
                );
            }
        }

        return $results;
    }
}
