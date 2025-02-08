<?php

namespace CasperBoone\LaravelExpiringEmail\Database\Factories;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmailAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpiringEmailAttachmentFactory extends Factory
{
    protected $model = ExpiringEmailAttachment::class;

    public function definition(): array
    {
        $temporaryFile = __DIR__ . '/../../tests/resources/hugo-ruiz-e2pVrE1PYzs-unsplash.jpeg';

        return [
            'expiring_email_id' => ExpiringEmail::factory(),
            'contents' => file_get_contents($temporaryFile),
            'filename' => basename($temporaryFile),
            'mime_type' => 'image/png',
        ];
    }
}
