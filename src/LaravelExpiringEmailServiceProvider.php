<?php

namespace CasperBoone\LaravelExpiringEmail;

use CasperBoone\LaravelExpiringEmail\Commands\CleanExpiredEmails;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelExpiringEmailServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-expiring-email')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(CleanExpiredEmails::class)
            ->hasMigration('create_expiring_emails_table')
            ->hasMigration('create_expiring_email_attachments_table')
            ->hasRoute('web');
    }
}
