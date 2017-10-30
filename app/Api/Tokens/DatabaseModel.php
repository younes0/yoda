<?php

namespace Yoda\Api\Tokens;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DatabaseModel extends Eloquent 
{
    protected $table = 'oauth_tokens';

    protected $fillable = ['id', 'value'];
}
