<?php

namespace App\Concerns;

trait HasCompositePrimaryKey
{
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        return $this->getAttribute($keyName);
    }

    public function getIncrementing()
    {
        return false;
    }
}
