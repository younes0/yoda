<?php

namespace Yoda\Nlp\Libraries;

use Camspiers\StatisticalClassifier\Tokenizer\TokenizerInterface;
use Yoda\Nlp\Tokenizer;

class CamspiersTokenizer implements TokenizerInterface
{
    public function tokenize($string)
    {
        return Tokenizer::tokenize($string, 'classic');
    }
}
