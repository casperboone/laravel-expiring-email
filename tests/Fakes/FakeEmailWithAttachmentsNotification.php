<?php

namespace CasperBoone\LaravelExpiringEmail\Tests\Fakes;

use CasperBoone\LaravelExpiringEmail\ExpiringEmailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FakeEmailWithAttachmentsNotification extends Notification
{
    private array $attributeSetters;

    public function via($notifiable): array
    {
        return [ExpiringEmailChannel::class];
    }

    public function setAttribute($handler): self
    {
        $this->attributeSetters[] = $handler;

        return $this;
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Secret document')
            ->markdown('secret_document_email');

        collect($this->attributeSetters)->each(fn($handler) => $handler($message));

        return $message;
    }
}
