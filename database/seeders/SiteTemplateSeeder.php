<?php

namespace Database\Seeders;

use App\Models\SiteTemplate;
use Illuminate\Database\Seeder;

class SiteTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'GitHub',
                'site_url' => 'https://github.com/signup',
                'description' => 'Create a GitHub developer account.',
                'expects_email_confirmation' => true,
                'confirmation_timeout' => 300,
                'notes' => 'GitHub sends a confirmation code to the email. Check inbox for a 6-digit code.',
            ],
            [
                'name' => 'LinkedIn',
                'site_url' => 'https://www.linkedin.com/signup',
                'description' => 'Register a LinkedIn professional profile.',
                'expects_email_confirmation' => true,
                'confirmation_timeout' => 300,
                'notes' => 'LinkedIn may require phone verification in some regions.',
            ],
            [
                'name' => 'Indeed',
                'site_url' => 'https://secure.indeed.com/account/register',
                'description' => 'Create an Indeed job seeker account.',
                'expects_email_confirmation' => true,
                'confirmation_timeout' => 180,
                'notes' => null,
            ],
            [
                'name' => 'Djinni',
                'site_url' => 'https://djinni.co/login?from=header_signup',
                'description' => 'Register a Djinni candidate account for Ukrainian tech market.',
                'expects_email_confirmation' => true,
                'confirmation_timeout' => 300,
                'notes' => 'Confirmation link is sent via email. No phone required.',
            ],
            [
                'name' => 'Work.ua',
                'site_url' => 'https://www.work.ua/register/',
                'description' => 'Create a Work.ua job seeker profile.',
                'expects_email_confirmation' => false,
                'confirmation_timeout' => 300,
                'notes' => 'No email confirmation required after registration.',
            ],
            [
                'name' => 'DOU',
                'site_url' => 'https://dou.ua/reg/',
                'description' => 'Register on DOU — the Ukrainian developers community.',
                'expects_email_confirmation' => true,
                'confirmation_timeout' => 600,
                'notes' => 'DOU sends a confirmation email with an activation link.',
            ],
        ];

        foreach ($templates as $data) {
            SiteTemplate::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
