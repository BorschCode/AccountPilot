<?php

namespace Database\Seeders;

use App\Enums\SiteRegistrationStatus;
use App\Models\Email;
use App\Models\SiteRegistration;
use App\Models\SiteTemplate;
use Illuminate\Database\Seeder;

class SiteRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $github = SiteTemplate::where('name', 'GitHub')->first();
        $linkedin = SiteTemplate::where('name', 'LinkedIn')->first();
        $djinni = SiteTemplate::where('name', 'Djinni')->first();
        $workua = SiteTemplate::where('name', 'Work.ua')->first();

        $gmail1 = Email::where('email', 'john.recruiter@gmail.com')->first();
        $gmail2 = Email::where('email', 'hiring.pipeline@gmail.com')->first();
        $outlook = Email::where('email', 'hr.outreach@outlook.com')->first();
        $ukrnet = Email::where('email', 'recruiter.ua@ukr.net')->first();

        $registrations = [
            // Draft — not yet run
            [
                'site_template_id' => $github?->id,
                'email_id' => $gmail1?->id,
                'status' => SiteRegistrationStatus::Draft->value,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'username' => 'john_smith_dev',
                'password' => 'Gh#2024$ecure',
                'phone_number' => null,
            ],
            // Completed — registration done
            [
                'site_template_id' => $linkedin?->id,
                'email_id' => $gmail2?->id,
                'status' => SiteRegistrationStatus::Completed->value,
                'first_name' => 'Anna',
                'last_name' => 'Kovalenko',
                'username' => null,
                'password' => 'L!nked!n2024',
                'phone_number' => null,
                'result_data' => ['profile_url' => 'https://www.linkedin.com/in/anna-kovalenko-dev/'],
                'queued_at' => now()->subDay(),
                'completed_at' => now()->subDay()->addMinutes(8),
            ],
            // Waiting for email confirmation
            [
                'site_template_id' => $djinni?->id,
                'email_id' => $outlook?->id,
                'status' => SiteRegistrationStatus::WaitingConfirmation->value,
                'first_name' => 'Dmytro',
                'last_name' => 'Bondarenko',
                'username' => 'dmytro_bond',
                'password' => 'Djinn!2024',
                'phone_number' => null,
                'queued_at' => now()->subMinutes(15),
            ],
            // Failed — automation error
            [
                'site_template_id' => $workua?->id,
                'email_id' => $ukrnet?->id,
                'status' => SiteRegistrationStatus::Failed->value,
                'first_name' => 'Olena',
                'last_name' => 'Marchenko',
                'username' => 'olena_m',
                'password' => 'W0rk!2024',
                'phone_number' => '+380501234567',
                'error_message' => 'Captcha detected on registration form — could not proceed.',
                'queued_at' => now()->subHours(2),
            ],
        ];

        foreach ($registrations as $data) {
            if (! $data['site_template_id'] || ! $data['email_id']) {
                continue;
            }

            SiteRegistration::firstOrCreate(
                [
                    'site_template_id' => $data['site_template_id'],
                    'email_id' => $data['email_id'],
                ],
                $data
            );
        }
    }
}
