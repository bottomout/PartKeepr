<?php

namespace App\Exception;

class PartLimitExceededException extends TranslatableException
{
    public function getMessageKey()
    {
        return 'The maximum number of parts is reached';
    }
}
