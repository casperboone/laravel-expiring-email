<?php

namespace CasperBoone\LaravelExpiringEmail\Tests\Fakes;

use App\Models\Membership;
use CasperBoone\LaravelExpiringEmail\ExpiringEmailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FakeEmailOverExpiringChannelNotification extends Notification
{
    public function via($notifiable): array
    {
        return [ExpiringEmailChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Secret document')
            ->markdown('secret_document_email');
    }
}
