<?php

namespace CasperBoone\LaravelExpiringEmail\Tests;

use CasperBoone\LaravelExpiringEmail\Commands\CleanExpiredEmails;
use CasperBoone\LaravelExpiringEmail\ExpiringEmailModel;

class CleanExpiredEmailsTest extends TestCase
{
    /** @test */
    public function it_cleans_all_expired_emails()
    {
        $expiresA = ExpiringEmailModel::factory()->expiresInDays(-5)->create();
        $expiresB = ExpiringEmailModel::factory()->expiresInDays(-2)->create();
        $remainsA = ExpiringEmailModel::factory()->expiresInDays(1)->create();
        $remainsB = ExpiringEmailModel::factory()->expiresInDays(10)->create();

        $this->artisan(CleanExpiredEmails::class);

        $this->assertNull($expiresA->fresh());
        $this->assertNull($expiresB->fresh());
        $this->assertNotNull($remainsA->fresh());
        $this->assertNotNull($remainsB->fresh());
    }
}
