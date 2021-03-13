<?php

namespace CasperBoone\LaravelExpiringEmail\Commands;

use Illuminate\Console\Command;

class LaravelExpiringEmailCommand extends Command
{
    public $signature = 'laravel-expiring-email';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
