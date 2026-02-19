<?php

namespace Database\Seeders;

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use App\Models\GoLoginProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GoLoginProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'platform' => Platform::LinkedIn,
                'name' => 'LinkedIn – Profile 1',
                'proxy' => '185.220.101.12:8080',
                'risk_score' => 12,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::LinkedIn,
                'name' => 'LinkedIn – Profile 2',
                'proxy' => '185.220.101.45:8080',
                'risk_score' => 8,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::Indeed,
                'name' => 'Indeed – Profile 1',
                'proxy' => '103.149.162.55:8080',
                'risk_score' => 21,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::Glassdoor,
                'name' => 'Glassdoor – Profile 1',
                'proxy' => '45.140.143.77:8080',
                'risk_score' => 35,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::WorkUa,
                'name' => 'Work.ua – Profile 1',
                'proxy' => null,
                'risk_score' => 5,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::Djinni,
                'name' => 'Djinni – Profile 1',
                'proxy' => null,
                'risk_score' => 9,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::Dou,
                'name' => 'DOU – Profile 1',
                'proxy' => null,
                'risk_score' => 14,
                'status' => GoLoginProfileStatus::Active,
            ],
            [
                'platform' => Platform::LinkedIn,
                'name' => 'LinkedIn – Profile 3 (flagged)',
                'proxy' => '91.108.4.201:9050',
                'risk_score' => 78,
                'status' => GoLoginProfileStatus::Flagged,
            ],
        ];

        foreach ($profiles as $profile) {
            GoLoginProfile::firstOrCreate(
                ['name' => $profile['name']],
                [
                    'gologin_profile_id' => Str::uuid()->toString(),
                    'platform' => $profile['platform']->value,
                    'proxy_address' => $profile['proxy'],
                    'risk_score' => $profile['risk_score'],
                    'status' => $profile['status']->value,
                    'last_score_checked_at' => now()->subHours(rand(1, 48)),
                ]
            );
        }
    }
}
