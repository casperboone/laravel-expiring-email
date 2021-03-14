<?php

namespace CasperBoone\LaravelExpiringEmail\Tests;

use CasperBoone\LaravelExpiringEmail\LaravelExpiringEmailServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'CasperBoone\\LaravelExpiringEmail\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelExpiringEmailServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('mail.mailers.smtp.host', 'localhost');
        $app['config']->set('mail.mailers.smtp.port', 1025);
        $app['config']->set('mail.mailers.smtp.encryption', null);

        View::addLocation(__DIR__ . '/views');

        include_once __DIR__.'/../database/migrations/create_expiring_emails_table.php.stub';
        (new \CreateExpiringEmailsTable())->up();
    }
}
