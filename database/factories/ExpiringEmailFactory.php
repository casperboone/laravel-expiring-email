<?php

namespace CasperBoone\LaravelExpiringEmail\Database\Factories;

use CasperBoone\LaravelExpiringEmail\Models\ExpiringEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpiringEmailFactory extends Factory
{
    protected $model = ExpiringEmail::class;

    public function expiresInDays(int $days): self
    {
        return $this->state(['expires_at' => now()->addDay($days)]);
    }

    public function definition(): array
    {
        return [
            'recipient' => $this->faker->email,
            'subject' => $this->faker->sentence(4),
            'body' => $this->faker->randomHtml(),
            'expires_at' => $this->faker->dateTimeBetween('now', '2 months')
        ];
    }
}
