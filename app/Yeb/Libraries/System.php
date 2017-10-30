<?php

namespace Yeb\Libraries;

class System
{
    public static function echoElapsedTime()
    {
        echo microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"].PHP_EOL;
    }

    public static function setUndefinedIndexesExceptions() 
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {

            if (str_contains($errstr, 'Undefined index') OR str_contains($errstr, 'Undefined offset')) {
                throw new \OutOfRangeException($errstr, 0);
            }

            restore_error_handler();
            return true; // error bublling to PHP's default handler
        });
    }
}
