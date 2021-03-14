<?php

namespace CasperBoone\LaravelExpiringEmail;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;

class ExpiringMailMessage extends MailMessage
{
    private ?Carbon $expirationDate = null;

    public function expirationDate(): ?Carbon
    {
        return $this->expirationDate;
    }

    public function expiresInDays(int $days): ExpiringMailMessage
    {
        $this->expirationDate = now()->addDays($days);

        return $this;
    }
}
