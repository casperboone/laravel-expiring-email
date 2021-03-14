<?php

namespace CasperBoone\LaravelExpiringEmail\Database\Factories;

use CasperBoone\LaravelExpiringEmail\ExpiringEmailModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpiringEmailModelFactory extends Factory
{
    protected $model = ExpiringEmailModel::class;

    public function definition()
    {
        return [
            'recipient' => $this->faker->email,
            'subject' => $this->faker->sentence(4),
            'body' => $this->faker->randomHtml(),
            'expires_at' => $this->faker->dateTimeBetween('now', '2 months')
        ];
    }
}
