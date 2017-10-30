<?php

namespace Yoda\Api\Tokens;

class Database implements TokensInterface
{
    public function get($name)
    {
        if ($eloquent = DatabaseModel::find($name)) {
            return unserialize($eloquent->value);
        }

        return null;
    }

    public function set($name, $value)
    {
        return DatabaseModel::firstOrCreate(['id' => $name])
            ->update(['value' => serialize($value)]);
    }
}
