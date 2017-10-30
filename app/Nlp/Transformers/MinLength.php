<?php

namespace Yoda\Nlp\Transformers;

use NlpTools\Utils\TransformationInterface;

class MinLength implements TransformationInterface
{
    public function __construct($length)
    {
        $this->length = $length;
    }

    public function transform($word)
    {
        return (strlen($word) >= $this->length) ? $word : null;
    }
}
