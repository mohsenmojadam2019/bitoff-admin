<?php


namespace App\Support\Hash;

/**
 * @property $hash
 */
trait MakesHash
{
    public function getHashAttribute()
    {
        return HashId::encode($this->{$this->primaryKey});
    }
}
