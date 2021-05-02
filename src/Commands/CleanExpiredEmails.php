<?php

namespace CasperBoone\LaravelExpiringEmail\Commands;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use Illuminate\Console\Command;

class CleanExpiredEmails extends Command
{
    public $signature = 'expiring-email:clean';

    public $description = 'Remove all expired emails from the database';

    public function handle(): void
    {
        $count = ExpiringEmail::expired()->count();
        $this->comment("Removing {$count} expired emails...");

        ExpiringEmail::expired()->delete();

        $this->comment("All expired emails have been removed.");
    }
}
