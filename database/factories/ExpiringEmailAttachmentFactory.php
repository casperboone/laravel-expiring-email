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
        $file = $this->faker->image();

        return [
            'expiring_email_id' => ExpiringEmail::factory(),
            'contents' => file_get_contents($file),
            'filename' => basename($file),
            'mime_type' => 'image/png',
        ];
    }
}
