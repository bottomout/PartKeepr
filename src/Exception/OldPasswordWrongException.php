<?php

namespace App\Exception;

/**
 * Is thrown when the user specified a wrong old password.
 */
class OldPasswordWrongException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'Old password is wrong';
    }
}
