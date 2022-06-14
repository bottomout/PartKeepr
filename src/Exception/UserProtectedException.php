<?php

namespace App\Exception;

class UserProtectedException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'User is protected against changes';
    }
}
