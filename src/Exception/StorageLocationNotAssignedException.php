<?php

namespace App\Exception;

/**
 * This exception is thrown when a part hasn't got a storage location assigned.
 */
class StorageLocationNotAssignedException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'The part has no storage location assigned';
    }
}
