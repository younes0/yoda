<?php

namespace Yeb\Helpers;

use Yeb\Libraries\System;
use ForceUTF8\Encoding;
use TextLanguageDetect\TextLanguageDetect;

class StringHelper
{
    /**
     * Remove all links in string
     * 
     * @param  string $str 
     * @return string
     */
    static public function removeLinks($str)
    {
        $regex = '@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS';
        return preg_replace($regex, '', $str);        
    }

    /**
     * Fix UTF8
     * 
     * @param  string $str 
     * @return string
     */
    static public function toUTF8($str)
    {
        return mb_convert_encoding(Encoding::toUTF8($str), 'UTF-8');
    }

    /**
     * Remove all accents in string
     * 
     * @param  string $str 
     * @return string
     */
    static public function removeAccents($str)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    }

    /**
     * Extract all words
     * 
     * @param  string $str 
     * @return array
     */
    static public function extractWords($str)
    {
        return preg_split('/[\pZ\pC(?\,)]+/u', $str, null, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Extract sentences
     * 
     * @param  string $str 
     * @return array
     */
    static public function extractSentences($str)
    {
        $array = preg_split('/([^.:!?]+[.:!?]+)/', $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return empty($array) ? [0 => ''] : $array;
    }
    
    /**
     * Split string between $start and $end strings
     * 
     * @param  string $str
     * @param  string $start
     * @param  string $end
     * @return string
     */
    static public function getBetween($str, $start, $end)
    {    
        $regex = '/' . preg_quote($start, '/') . '(.*?)' . preg_quote($end, '/') . '/';
        preg_match_all($regex, $str, $matches);

        return $matches[1];
    }

    /**
     * Removes excessive line-breaks and white-spaces
     * 
     * @param  string $str
     * @return string
     */
    static public function squeeze($str)
    {
        $str = preg_replace('/\s*$^\s*/m', PHP_EOL, $str);
        return preg_replace('/[ \t]+/', ' ', $str);
    }

    /**
    * Check if string has NOT whitespace
    * 
    * @param $str string
    * @return boolean
    */
    static public function checkTrim($str) 
    {
        return !preg_match('/\s/', $str);
    }
   
   /**
    * Convert all links to html links
    * 
    * @param  string $str 
    * @return string
    */
    static public function makeHtmlLinks($str)
    {
        $str = preg_replace('/(((f|ht){1}tp(s)?:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '<a href="\\1" >\\1</a>', $str);
        $str = preg_replace('/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '\\1<a href="http://\\2" >\\2</a>', $str);
        $str = preg_replace('/([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/i', '<a href="mailto:\\1" >\\1</a>', $str);
    
        return $str;
    }

    static public function detectLang($string)
    {
        System::setUndefinedIndexesExceptions();
        $detector = new TextLanguageDetect;

        try {
            $langs = $detector->detect($string, 1);
            $first = key($langs);

            // smaller sample if unsure
            if ($langs[$first] < 0.22) {
                $truncated = substr($string, 0, 200);
                $lang = key($detector->detect($truncated, 1));
            
            } else {
                $lang = $first;
            }

            return substr($lang, 0, 2);

        } catch (\OutOfRangeException $e) {
            return null;

        } finally {
            restore_error_handler();
        }
    }
}
