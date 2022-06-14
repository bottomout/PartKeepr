<?php

namespace App\Exception;

class PackagingUnitOutOfRangeException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'Packaging Unit is out of range. It must be 1 or higher.';
    }
}
