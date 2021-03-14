<?php

namespace CasperBoone\LaravelExpiringEmail\Tests\Fakes;

use App\Models\Membership;
use CasperBoone\LaravelExpiringEmail\ExpiringEmailChannel;
use CasperBoone\LaravelExpiringEmail\ExpiringMailMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FakeExpiringEmailOverExpiringChannelNotification extends Notification
{
    private int $expiresInDays;

    public function __construct(int $expiresInDays = 5)
    {
        $this->expiresInDays = $expiresInDays;
    }

    public function via($notifiable): array
    {
        return [ExpiringEmailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new ExpiringMailMessage())
            ->subject('Secret document')
            ->expiresInDays($this->expiresInDays)
            ->markdown('secret_document_email');
    }
}
