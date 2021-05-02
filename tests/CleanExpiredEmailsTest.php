<?php

namespace CasperBoone\LaravelExpiringEmail\Tests;

use CasperBoone\LaravelExpiringEmail\Commands\CleanExpiredEmails;
use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;

class CleanExpiredEmailsTest extends TestCase
{
    /** @test */
    public function it_cleans_all_expired_emails()
    {
        $expiresA = ExpiringEmail::factory()->expiresInDays(-5)->create();
        $expiresB = ExpiringEmail::factory()->expiresInDays(-2)->create();
        $remainsA = ExpiringEmail::factory()->expiresInDays(1)->create();
        $remainsB = ExpiringEmail::factory()->expiresInDays(10)->create();

        $this->artisan(CleanExpiredEmails::class);

        $this->assertNull($expiresA->fresh());
        $this->assertNull($expiresB->fresh());
        $this->assertNotNull($remainsA->fresh());
        $this->assertNotNull($remainsB->fresh());
    }
}
