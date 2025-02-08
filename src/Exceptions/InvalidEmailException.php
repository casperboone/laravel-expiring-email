<?php

namespace CasperBoone\LaravelExpiringEmail\Exceptions;

class InvalidEmailException extends ExpiringEmailException
{
    public static function forEmail(string $email): InvalidEmailException
    {
        return new self("The given email address [{$email}] is not valid.");
    }
}
