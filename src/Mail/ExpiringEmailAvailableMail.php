<?php

namespace CasperBoone\LaravelExpiringEmail\Mail;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use Illuminate\Mail\Mailable;

class ExpiringEmailAvailableMail extends Mailable
{
    public ExpiringEmail $expiringEmail;

    public function __construct(ExpiringEmail $expiringEmail)
    {
        $this->expiringEmail = $expiringEmail;
    }

    public function build(): ExpiringEmailAvailableMail
    {
        return $this
            ->subject($this->expiringEmail->subject)
            ->markdown('expiring-email::expiring_email_available');
    }
}
