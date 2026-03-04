<?php

namespace Database\Factories;

use App\Enums\ProxyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => null,
            'geo_region' => fake()->country(),
            'proxy_type' => fake()->randomElement(ProxyType::cases())->value,
            'proxy_address' => fake()->ipv4().':'.fake()->numberBetween(1000, 9999),
        ];
    }

    public function withPassword(): static
    {
        return $this->state([
            'password' => fake()->password(12),
        ]);
    }
}
