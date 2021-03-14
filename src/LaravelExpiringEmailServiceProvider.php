<?php

namespace CasperBoone\LaravelExpiringEmail;

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
            ->hasMigration('create_expiring_emails_table')
            ->hasRoute('web');
    }
}
