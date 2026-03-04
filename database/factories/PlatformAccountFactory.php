<?php

namespace Database\Factories;

use App\Enums\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlatformAccount>
 */
class PlatformAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email_id' => null,
            'platform' => fake()->randomElement(Platform::cases())->value,
            'login' => fake()->userName(),
            'username' => fake()->optional()->userName(),
            'first_name' => fake()->optional()->firstName(),
            'geo_region' => fake()->country(),
        ];
    }

    public function forPlatform(Platform $platform): static
    {
        return $this->state([
            'platform' => $platform->value,
        ]);
    }
}
