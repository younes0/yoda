<?php

namespace Yoda\Nlp\Models;

use Yoda\Nlp\Libraries\AbstractLibrary;

interface ModelInterface
{
    public function getNlpProps();

    public function nlpClassify($model);

    public function nlpClassed();

    public function nlpDoc();
    
    public function createNlpDoc(Array $array);

    public function tokenize($method);
}
