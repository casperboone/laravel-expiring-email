<?php

namespace CasperBoone\LaravelExpiringEmail;

use CasperBoone\LaravelExpiringEmail\Exceptions\InvalidEmailException;
use CasperBoone\LaravelExpiringEmail\Mail\ExpiringEmailAvailableMail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpiringEmailChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     * @throws InvalidEmailException
     *
     * @psalm-suppress UndefinedMethod The toMail method can only be assumed dynamically
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var MailMessage $originalMail */
        $originalMail = $notification->toMail($notifiable);
        $recipient = $notifiable->routeNotificationFor('mail', $notification);

        if (!$recipient) {
            throw InvalidEmailException::forEmail($recipient);
        }

        // Persist original mail in database
        $expiringEmail = ExpiringEmailModel::create([
            'recipient' => $recipient,
            'subject' => $originalMail->subject,
            'body' => $originalMail->render(),
            'expires_at' => $this->getExpirationDate($originalMail),
        ]);

        // Send new email with link to original email
        Mail::to($recipient)->send(new ExpiringEmailAvailableMail($expiringEmail));
    }

    private function getExpirationDate(MailMessage $originalMail): ?Carbon {
        return $originalMail instanceof ExpiringMailMessage && $originalMail->expirationDate()
            ? $originalMail->expirationDate()
            : now()->addDays(config('expiring-email.default_expiration_days'));
    }
}