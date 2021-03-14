<?php

namespace CasperBoone\LaravelExpiringEmail\Tests\Fakes;

use Illuminate\Notifications\RoutesNotifications;

class FakeUser
{
    use RoutesNotifications;

    public string $email;

    public function __construct(string $email = 'john@example.com')
    {
        $this->email = $email;
    }
}
