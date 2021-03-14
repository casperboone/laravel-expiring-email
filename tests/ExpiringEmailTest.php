<?php

namespace CasperBoone\LaravelExpiringEmail\Tests;

use CasperBoone\LaravelExpiringEmail\ExpiringEmailModel;
use CasperBoone\LaravelExpiringEmail\Mail\ExpiringEmailAvailableMail;
use CasperBoone\LaravelExpiringEmail\Tests\Fakes\FakeEmailOverExpiringChannelNotification;
use CasperBoone\LaravelExpiringEmail\Tests\Fakes\FakeExpiringEmailOverExpiringChannelNotification;
use CasperBoone\LaravelExpiringEmail\Tests\Fakes\FakeUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ExpiringEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_a_replacement_email()
    {
        Mail::fake();

        Notification::send(new FakeUser('henk@example.com'), new FakeEmailOverExpiringChannelNotification);

        Mail::assertSent(
            ExpiringEmailAvailableMail::class,
            fn (ExpiringEmailAvailableMail $mail) =>
            $mail->build() &&
            $mail->subject == 'Secret document' &&
            $mail->hasTo('henk@example.com')
        );
    }

    /** @test */
    public function it_has_a_link_to_the_full_email_in_the_replacement_email()
    {
        $expiringEmail = ExpiringEmailModel::factory()->create();

        $mail = new ExpiringEmailAvailableMail($expiringEmail);

        $mail->assertSeeInHtml(route('expiring_mail.show', $expiringEmail->random_identifier));
    }

    /** @test */
    public function it_provides_a_display_page_with_the_secret_content_of_the_original_mail()
    {
        Mail::fake();

        Notification::send(new FakeUser, new FakeEmailOverExpiringChannelNotification);

        $expiringEmail = ExpiringEmailModel::first();
        $expiringEmailPage = $this->get($expiringEmail->url());
        $expiringEmailPage->assertSee("This is a very secret document");
        $expiringEmailPage->assertSee("Nobody can know about this");
    }

    /** @test */
    public function it_does_not_show_expiring_emails_that_are_expired_on_the_display_page()
    {
        Mail::fake();

        Notification::send(new FakeUser, new FakeExpiringEmailOverExpiringChannelNotification(5));

        $expiringEmail = ExpiringEmailModel::first();
        $url = $expiringEmail->url(); // Expiration date in URL needs to be in the future
        $expiringEmail->update(['expires_at' => now()->subDays(6)]);

        $this->get($url)->assertNotFound();
    }

    /** @test */
    public function it_does_not_show_emails_if_the_url_is_tampered_with_on_the_display_page()
    {
        Mail::fake();

        Notification::send(new FakeUser, new FakeExpiringEmailOverExpiringChannelNotification(5));

        $expiringEmail = ExpiringEmailModel::first();
        $url = substr($expiringEmail->url(), 0, -4).'AbCd'; // Tamper with url

        $this->get($url)->assertNotFound();
    }
}
