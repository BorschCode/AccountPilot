<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteTemplate>
 */
class SiteTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'site_url' => fake()->url(),
            'description' => fake()->optional()->sentence(),
            'expects_email_confirmation' => fake()->boolean(),
            'confirmation_timeout' => fake()->randomElement([60, 120, 300, 600]),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function withConfirmation(): static
    {
        return $this->state([
            'expects_email_confirmation' => true,
            'confirmation_timeout' => 300,
        ]);
    }

    public function withoutConfirmation(): static
    {
        return $this->state(['expects_email_confirmation' => false]);
    }
}
