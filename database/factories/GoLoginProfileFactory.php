<?php

namespace Database\Factories;

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoLoginProfile>
 */
class GoLoginProfileFactory extends Factory
{
    public function definition(): array
    {
        $platform = fake()->randomElement(Platform::cases());

        return [
            'gologin_profile_id' => Str::uuid()->toString(),
            'name' => $platform->label().' Bot - '.fake()->unique()->word(),
            'platform' => $platform->value,
            'proxy_address' => fake()->ipv4().':'.fake()->numberBetween(1000, 9999),
            'status' => GoLoginProfileStatus::Active->value,
            'risk_score' => fake()->numberBetween(0, 25),
            'last_score_checked_at' => now()->subHours(fake()->numberBetween(1, 24)),
        ];
    }

    public function active(): static
    {
        return $this->state([
            'status' => GoLoginProfileStatus::Active->value,
            'risk_score' => fake()->numberBetween(0, 25),
        ]);
    }

    public function flagged(): static
    {
        return $this->state([
            'status' => GoLoginProfileStatus::Flagged->value,
            'risk_score' => fake()->numberBetween(60, 100),
        ]);
    }

    public function forPlatform(Platform $platform): static
    {
        return $this->state([
            'platform' => $platform->value,
            'name' => $platform->label().' Bot - '.fake()->unique()->word(),
        ]);
    }
}
