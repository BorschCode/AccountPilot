<?php

namespace Database\Factories;

use App\Enums\Platform;
use App\Enums\PostingStatus;
use App\Models\GoLoginProfile;
use App\Models\JobPosting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPlatformPost>
 */
class JobPlatformPostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'job_posting_id' => JobPosting::factory(),
            'go_login_profile_id' => GoLoginProfile::factory(),
            'platform' => fake()->randomElement(Platform::cases())->value,
            'status' => PostingStatus::Pending->value,
            'external_url' => null,
            'screenshot_path' => null,
            'error_message' => null,
            'risk_score_at_posting' => null,
        ];
    }

    public function posted(): static
    {
        $platform = fake()->randomElement(Platform::cases());

        return $this->state([
            'platform' => $platform->value,
            'status' => PostingStatus::Posted->value,
            'external_url' => $platform->baseUrl().'/jobs/'.fake()->numberBetween(100000, 999999),
            'screenshot_path' => 'screenshots/'.fake()->uuid().'.png',
            'risk_score_at_posting' => fake()->numberBetween(0, 25),
            'posted_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state([
            'status' => PostingStatus::Failed->value,
            'error_message' => fake()->sentence(),
        ]);
    }

    public function skipped(): static
    {
        return $this->state([
            'status' => PostingStatus::Skipped->value,
            'risk_score_at_posting' => fake()->numberBetween(60, 100),
            'error_message' => 'Profile risk score too high.',
        ]);
    }
}
