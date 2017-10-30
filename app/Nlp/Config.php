<?php

namespace Yoda\Nlp;

class Config 
{
    protected $config;

    public function __construct($name)
    {
        $options  = config('yoda.nlp.models.'.$name); 

        $classes = $options['classes'] ?? Tools::getClasses(
            $options['domains'], 
            isset($options['classesFromTags']) ? $options['classesFromTags'] : false
        );

        $defaults = [
            'name'    => $name,
            'method'  => 'Nlptools::MultinomialNB',
            'classes' => $classes,
            'cache'   => false,
            'store'   => false,
        ];

        $this->config = array_merge($defaults, $options);
    }

    public function __get($name) 
    {
        return $this->config[$name];
    }

    public function toArray()
    {
        return $this->config;
    }
}
