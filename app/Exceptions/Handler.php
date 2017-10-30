<?php

namespace Yoda\Exceptions;

use Yeb\Exceptions\Handler as YebHandler;

class Handler extends YebHandler
{
    public function render($request, \Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $message = 'La ressource demandée n\'existe pas.';
            $this->redirect = true;
        }
            
        return parent::render($request, $e);
    }
}
