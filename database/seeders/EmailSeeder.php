<?php

namespace Database\Seeders;

use App\Enums\ProxyType;
use App\Models\Email;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        $emails = [
            [
                'email' => 'john.recruiter@gmail.com',
                'password' => 'p@ssw0rd!Gmail1',
                'geo_region' => 'United States',
                'proxy_type' => ProxyType::Residential,
                'proxy_address' => '185.220.101.12:3128',
            ],
            [
                'email' => 'hr.outreach@outlook.com',
                'password' => null,
                'geo_region' => 'United Kingdom',
                'proxy_type' => ProxyType::Socks5,
                'proxy_address' => '91.108.4.201:9050',
            ],
            [
                'email' => 'talent.pipeline@yahoo.com',
                'password' => 'Y@hoo$ecure99',
                'geo_region' => 'Canada',
                'proxy_type' => ProxyType::Http,
                'proxy_address' => '103.149.162.55:8080',
            ],
            [
                'email' => 'sourcing.bot@protonmail.com',
                'password' => null,
                'geo_region' => 'Germany',
                'proxy_type' => ProxyType::Https,
                'proxy_address' => '45.140.143.77:8443',
            ],
            [
                'email' => 'recruiter.ua@ukr.net',
                'password' => 'Ukr!N3t2024',
                'geo_region' => 'Ukraine',
                'proxy_type' => null,
                'proxy_address' => null,
            ],
            [
                'email' => 'talent.scout@i.ua',
                'password' => null,
                'geo_region' => 'Ukraine',
                'proxy_type' => ProxyType::Mobile,
                'proxy_address' => '176.38.10.22:3128',
            ],
            [
                'email' => 'hiring.pipeline@gmail.com',
                'password' => 'G00gl3#Hire',
                'geo_region' => 'Poland',
                'proxy_type' => ProxyType::Residential,
                'proxy_address' => '89.187.161.55:3128',
            ],
            [
                'email' => 'no.proxy@gmail.com',
                'password' => null,
                'geo_region' => 'United States',
                'proxy_type' => null,
                'proxy_address' => null,
            ],
        ];

        foreach ($emails as $data) {
            Email::firstOrCreate(
                ['email' => $data['email']],
                [
                    'password' => $data['password'],
                    'geo_region' => $data['geo_region'],
                    'proxy_type' => $data['proxy_type']?->value,
                    'proxy_address' => $data['proxy_address'],
                ]
            );
        }
    }
}
