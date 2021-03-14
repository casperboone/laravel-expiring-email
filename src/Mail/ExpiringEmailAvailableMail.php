<?php

namespace CasperBoone\LaravelExpiringEmail\Mail;

use CasperBoone\LaravelExpiringEmail\ExpiringEmailModel;
use Illuminate\Mail\Mailable;

class ExpiringEmailAvailableMail extends Mailable
{
    public ExpiringEmailModel $expiringEmail;

    public function __construct(ExpiringEmailModel $expiringEmail)
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
