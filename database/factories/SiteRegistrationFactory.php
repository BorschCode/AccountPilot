<?php

namespace Database\Factories;

use App\Enums\SiteRegistrationStatus;
use App\Models\Email;
use App\Models\SiteTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteRegistration>
 */
class SiteRegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'site_template_id' => SiteTemplate::factory(),
            'email_id' => Email::factory(),
            'go_login_profile_id' => null,
            'status' => SiteRegistrationStatus::Draft->value,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'password' => fake()->password(12),
            'phone_number' => fake()->optional()->phoneNumber(),
            'result_data' => null,
            'confirmation_link' => null,
            'error_message' => null,
            'queued_at' => null,
            'completed_at' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => SiteRegistrationStatus::Draft->value]);
    }

    public function queued(): static
    {
        return $this->state([
            'status' => SiteRegistrationStatus::Queued->value,
            'queued_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => SiteRegistrationStatus::Completed->value,
            'queued_at' => now()->subHour(),
            'completed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state([
            'status' => SiteRegistrationStatus::Failed->value,
            'error_message' => fake()->sentence(),
        ]);
    }

    public function waitingConfirmation(): static
    {
        return $this->state([
            'status' => SiteRegistrationStatus::WaitingConfirmation->value,
            'queued_at' => now()->subMinutes(5),
        ]);
    }
}
