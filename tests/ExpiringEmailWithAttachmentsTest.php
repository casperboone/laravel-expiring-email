<?php

namespace CasperBoone\LaravelExpiringEmail\Tests;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use CasperBoone\LaravelExpiringEmail\Tests\Fakes\FakeEmailWithAttachmentsNotification;
use CasperBoone\LaravelExpiringEmail\Tests\Fakes\FakeUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ExpiringEmailWithAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_converts_and_persists_file_attachments()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $this->assertCount(1, $expiringEmail->attachments);
        $this->assertEquals(md5_file($temporaryFile), md5($expiringEmail->attachments[0]->contents));
        $this->assertEquals('hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg', $expiringEmail->attachments[0]->filename);
        $this->assertEquals('image/jpeg', $expiringEmail->attachments[0]->mime_type);
    }

    /** @test */
    public function it_converts_and_persists_file_attachments_with_custom_options()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile, [
                'as' => 'custom.jpg',
                'mime' => 'image/png',
            ]));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $this->assertCount(1, $expiringEmail->attachments);
        $this->assertEquals(md5_file($temporaryFile), md5($expiringEmail->attachments[0]->contents));
        $this->assertEquals('custom.jpg', $expiringEmail->attachments[0]->filename);
        $this->assertEquals('image/png', $expiringEmail->attachments[0]->mime_type);
    }

    /** @test */
    public function it_converts_and_persists_raw_data_attachments()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(function (MailMessage $message) use ($temporaryFile) {
                return $message->attachData(file_get_contents($temporaryFile), 'test.jpg', ['mime' => 'application/pdf']);
            });
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $this->assertCount(1, $expiringEmail->attachments);
        $this->assertEquals(md5_file($temporaryFile), md5($expiringEmail->attachments[0]->contents));
        $this->assertEquals('test.jpg', $expiringEmail->attachments[0]->filename);
        $this->assertEquals('application/pdf', $expiringEmail->attachments[0]->mime_type);
    }

    /** @test */
    public function it_converts_and_persists_multiple_attachments()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attachData(file_get_contents($temporaryFile), 'image_a.png'))
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile, ['as' => 'image_b.png']));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $this->assertCount(2, $expiringEmail->attachments);
        $this->assertEquals('image_b.png', $expiringEmail->attachments[0]->filename);
        $this->assertEquals('image_a.png', $expiringEmail->attachments[1]->filename);
    }

    /** @test */
    public function it_makes_the_attachment_available_for_download()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $attachment = ExpiringEmail::first()->attachments->first();

        $response = $this->get($attachment->url());
        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename=hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg');
        $this->assertEquals(md5_file($temporaryFile), md5($response->streamedContent()));
    }

    /** @test */
    public function it_makes_the_attachment_available_for_download_on_the_expiring_email_page()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $attachment = $expiringEmail->attachments->first();

        $expiringEmailPage = $this->get($expiringEmail->url());
        $expiringEmailPage->assertSee($attachment->filename);
        $expiringEmailPage->assertSee(route('expiring_mail.attachments.show', $attachment->random_identifier));
    }

    /** @test */
    public function it_does_not_provide_attachments_for_expired_emails()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $expiringEmail = ExpiringEmail::first();
        $attachment = $expiringEmail->attachments->first();
        $url = $attachment->url(); // Expiration date in URL needs to be in the future
        $expiringEmail->update(['expires_at' => now()->subDays(6)]);

        $this->get($url)->assertNotFound();
    }

    /** @test */
    public function it_does_not_provide_attachments_if_the_url_is_tampered_with()
    {
        Mail::fake();

        $temporaryFile = __DIR__ . '/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';
        $notification = (new FakeEmailWithAttachmentsNotification())
            ->setAttribute(fn (MailMessage $message) => $message->attach($temporaryFile));
        Notification::send(new FakeUser('henk@example.com'), $notification);

        $attachment = ExpiringEmail::first()->attachments->first();
        $url = substr($attachment->url(), 0, -4) . 'AbCd'; // Tamper with url

        $this->get($url)->assertNotFound();
    }
}
