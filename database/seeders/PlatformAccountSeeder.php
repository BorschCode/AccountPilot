<?php

namespace Database\Seeders;

use App\Enums\Platform;
use App\Models\Email;
use App\Models\PlatformAccount;
use Illuminate\Database\Seeder;

class PlatformAccountSeeder extends Seeder
{
    public function run(): void
    {
        $gmailUs = Email::where('email', 'john.recruiter@gmail.com')->first();
        $outlookUk = Email::where('email', 'hr.outreach@outlook.com')->first();
        $yahooCA = Email::where('email', 'talent.pipeline@yahoo.com')->first();
        $protonDE = Email::where('email', 'sourcing.bot@protonmail.com')->first();
        $ukrNetUA = Email::where('email', 'recruiter.ua@ukr.net')->first();
        $iuaUA = Email::where('email', 'talent.scout@i.ua')->first();
        $gmailPL = Email::where('email', 'hiring.pipeline@gmail.com')->first();

        $accounts = [
            [
                'platform' => Platform::LinkedIn,
                'login' => 'john.recruiter@gmail.com',
                'username' => 'johnrecruiter',
                'first_name' => 'John',
                'geo_region' => 'United States',
                'email_id' => $gmailUs?->id,
            ],
            [
                'platform' => Platform::LinkedIn,
                'login' => 'hr.outreach@outlook.com',
                'username' => 'hr_outreach',
                'first_name' => 'Sarah',
                'geo_region' => 'United Kingdom',
                'email_id' => $outlookUk?->id,
            ],
            [
                'platform' => Platform::Indeed,
                'login' => 'talent.pipeline@yahoo.com',
                'username' => null,
                'first_name' => 'Mike',
                'geo_region' => 'Canada',
                'email_id' => $yahooCA?->id,
            ],
            [
                'platform' => Platform::Glassdoor,
                'login' => 'sourcing.bot@protonmail.com',
                'username' => 'sourcingbot_de',
                'first_name' => null,
                'geo_region' => 'Germany',
                'email_id' => $protonDE?->id,
            ],
            [
                'platform' => Platform::WorkUa,
                'login' => 'recruiter.ua@ukr.net',
                'username' => 'recruiter_ua',
                'first_name' => 'Olena',
                'geo_region' => 'Ukraine',
                'email_id' => $ukrNetUA?->id,
            ],
            [
                'platform' => Platform::Djinni,
                'login' => 'recruiter.ua@ukr.net',
                'username' => 'olena_recruits',
                'first_name' => 'Olena',
                'geo_region' => 'Ukraine',
                'email_id' => $ukrNetUA?->id,
            ],
            [
                'platform' => Platform::Dou,
                'login' => 'talent.scout@i.ua',
                'username' => null,
                'first_name' => 'Taras',
                'geo_region' => 'Ukraine',
                'email_id' => $iuaUA?->id,
            ],
            [
                'platform' => Platform::LinkedIn,
                'login' => 'hiring.pipeline@gmail.com',
                'username' => 'hiring_pl',
                'first_name' => 'Anna',
                'geo_region' => 'Poland',
                'email_id' => $gmailPL?->id,
            ],
            [
                'platform' => Platform::Indeed,
                'login' => 'hiring.pipeline@gmail.com',
                'username' => null,
                'first_name' => 'Anna',
                'geo_region' => 'Poland',
                'email_id' => $gmailPL?->id,
            ],
            [
                'platform' => Platform::WorkUa,
                'login' => 'talent.scout@i.ua',
                'username' => 'taras_scout',
                'first_name' => 'Taras',
                'geo_region' => 'Ukraine',
                'email_id' => $iuaUA?->id,
            ],
        ];

        foreach ($accounts as $data) {
            PlatformAccount::firstOrCreate(
                [
                    'platform' => $data['platform']->value,
                    'login' => $data['login'],
                ],
                [
                    'username' => $data['username'],
                    'first_name' => $data['first_name'],
                    'geo_region' => $data['geo_region'],
                    'email_id' => $data['email_id'],
                ]
            );
        }
    }
}
