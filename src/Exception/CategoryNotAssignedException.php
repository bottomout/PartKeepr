<?php

namespace App\Exception;

/**
 * This exception is thrown when a part hasn't got a category assigned.
 */
class CategoryNotAssignedException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'The part has no category assigned';
    }
}
