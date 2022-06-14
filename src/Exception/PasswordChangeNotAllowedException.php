<?php

namespace App\Exception;
/**
 * Is thrown when the user may not change their password.
 */
class PasswordChangeNotAllowedException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'Password change not allowed by the administrator';
    }
}
