<?php

namespace App\Exceptions;

class LinkException extends AppException
{
    /**
     * @return LinkException
     */
    public static function limitExceeded(): LinkException
    {
        return new self('Links generation limit has been reached');
    }
}
