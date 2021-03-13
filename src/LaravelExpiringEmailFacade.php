<?php

namespace CasperBoone\LaravelExpiringEmail;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CasperBoone\LaravelExpiringEmail\LaravelExpiringEmail
 */
class LaravelExpiringEmailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-expiring-email';
    }
}
