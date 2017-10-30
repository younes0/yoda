<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use McCool\LaravelAutoPresenter\HasPresenter;

class User extends ExtendedModel implements 
    AuthenticatableContract, 
    CanResetPasswordContract 
{
    use Authenticatable, CanResetPassword;
    use ValidatingTrait;
    use SoftDeletes;

    public static $unguarded = false;

    public $table = 'users';
    
    protected $dates = ['deleted_at'];
    
    protected $guarded = ['id', 'remember_token'];
    
    protected $hidden = [];
    
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function getFullname()
    {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }
}
