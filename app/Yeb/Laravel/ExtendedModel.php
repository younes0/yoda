<?php

namespace Yeb\Laravel;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class ExtendedModel extends Model
{
    use Rememberable;

    protected $nullable = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            $model->beforeSave();
        });
    }

    /**
     * Set empty nullable fields to null
     */
    public function beforeSave()
    {
        foreach ($this->attributes as $key => &$value) {
            if (in_array($key, $this->nullable)) {
                empty($value) && $value = null;
            }
        } 
    }
}
