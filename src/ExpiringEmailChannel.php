<?php

namespace CasperBoone\LaravelExpiringEmail;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use CasperBoone\LaravelExpiringEmail\Exceptions\InvalidEmailException;
use CasperBoone\LaravelExpiringEmail\Mail\ExpiringEmailAvailableMail;
use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmailAttachment;
use Illuminate\Mail\Message;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

        if (! $recipient) {
            throw InvalidEmailException::forEmail($recipient);
        }

        // Persist original mail in database
        $expiringEmail = ExpiringEmail::create([
            'recipient' => $recipient,
            'subject' => $originalMail->subject,
            'body' => $originalMail->render(),
            'expires_at' => $this->getExpirationDate($originalMail),
        ]);

        // Convert and persist attachments
        $expiringEmail->attachments()->saveMany($this->getAttachments($originalMail));

        // Send new email with link to original email
        Mail::to($recipient)->send(new ExpiringEmailAvailableMail($expiringEmail));
    }

    private function getExpirationDate(MailMessage $originalMail): ?Carbon
    {
        return $originalMail instanceof ExpiringMailMessage && $originalMail->expirationDate()
            ? $originalMail->expirationDate()
            : now()->addDays(config('expiring-email.default_expiration_days'));
    }

    private function getAttachments(MailMessage $originalMail): Collection
    {
        $temporaryMessage = new Message(new \Swift_Message);

        foreach ($originalMail->attachments as $attachment) {
            $temporaryMessage->attach($attachment['file'], $attachment['options']);
        }

        foreach ($originalMail->rawAttachments as $attachment) {
            $temporaryMessage->attachData($attachment['data'], $attachment['name'], $attachment['options']);
        }

        return collect($temporaryMessage->getSwiftMessage()->getChildren())
            ->filter(fn ($child) => $child instanceof \Swift_Attachment)
            ->map(fn (\Swift_Attachment $attachment) => ExpiringEmailAttachment::make([
                'filename' => $attachment->getFilename(),
                'mime_type' => $attachment->getBodyContentType(),
                'contents' => $attachment->getBody(),
            ]));
    }
}
