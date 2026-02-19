<?php

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Enums\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPosting>
 */
class JobPostingFactory extends Factory
{
    public function definition(): array
    {
        $platforms = fake()->randomElements(
            array_column(Platform::cases(), 'value'),
            fake()->numberBetween(1, 3)
        );

        return [
            'created_by' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city().', '.fake()->country(),
            'employment_type' => fake()->randomElement(EmploymentType::cases())->value,
            'salary_min' => fake()->numberBetween(30000, 80000),
            'salary_max' => fake()->numberBetween(80001, 150000),
            'salary_currency' => fake()->randomElement(['USD', 'EUR', 'UAH']),
            'platforms' => $platforms,
            'status' => JobPostingStatus::Draft->value,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => JobPostingStatus::Draft->value]);
    }

    public function queued(): static
    {
        return $this->state([
            'status' => JobPostingStatus::Queued->value,
            'queued_at' => now(),
        ]);
    }

    public function posted(): static
    {
        return $this->state([
            'status' => JobPostingStatus::Posted->value,
            'queued_at' => now()->subHour(),
            'posted_at' => now(),
        ]);
    }
}
