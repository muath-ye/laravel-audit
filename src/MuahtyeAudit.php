<?php

namespace Muathye\Audit;

use Muathye\Audit\Storage\StorageInterface;

class MuahtyeAudit
{
    /**
     * Sets the storage backend to use to store the collected data
     *
     * @param StorageInterface $storage .
     * 
     * @return $this
     */
    public function setStorage(StorageInterface $storage = null)
    {
        $this->storage = $storage;
        return $this;
    }
}