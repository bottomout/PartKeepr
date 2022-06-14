<?php

namespace App\Exception;

class InternalPartNumberNotUniqueException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'The internal part number is already used';
    }
}
