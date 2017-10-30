<?php

namespace Yeb\Helpers;

class Bootstrap
{
    /**
     * Returns a nav list
     * 
     * @param  string $navClass    UL class ('nav', 'nav-tabs' etc.)
     * @param  Array  $items        Set of links and their text
     * @param  Callable $urlFilter  Link filter Closure function
     * @param  Callable $urlActive  Closure function that determines if the link is active or not 
     * 
     * @return string                  Nav list
     */
    public static function nav($navClass = 'nav', Array $items, Callable $urlFilter = null, Callable $urlActive = null)
    {
        $dom = null;

        foreach ($items as $item) {

            if (is_callable($item)) {
                $dom.= $item();
            
            } else if (!is_array($item)) {
                  $dom.= '<li class="nav-header">'.$item.'</li>';  
            
            } else {
                $text  =& $item[0];
                $url   =& $item[1];

                $extra = isset($item[2]) ? $item[2] : null;


                if ($urlFilter) {
                    $url = $urlFilter($url);
                } 
    
                if (!$urlActive) {
                    $urlActive = function ($url) {
                        // ignore all query strings
                        return ($url === preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']));
                    };
                }

                $active = ($urlActive($url)) ? 'active' : '';
                $dom.= '<li class="'.$active.'"><a href="'.$url.'" '.$extra.'>'.$text.'</a></li>';  
            }

        }
        return ($navClass) ? '<ul class="'.$navClass.'">'.$dom.'</ul>' : $dom;
    }

    static public function formGroup($id, $label, $content, $labelClass = null, $contentClass = null)
    {
        $errors = \Session::get('errors', new \Illuminate\Support\MessageBag);
                    
        $out = sprintf('<div class="form-group %s">', $errors->has('email') ? 'has-error' : '');

        if ($label) {
            $out.= sprintf(
                '<label for="%s" class="control-label %s" control-label">%s</label>',
                $id, $labelClass, $label
            );
        }

        $out.= sprintf(
            '<div class="%s">%s</div>%s</div>', 
            $contentClass,
            $content,
            $errors->first($id, '<p class="help-block">:message</p>')
        );

        return $out;
    }
}
