<?php

namespace App\Enums;

enum Platform: string
{
    case LinkedIn = 'linkedin';
    case Indeed = 'indeed';
    case Glassdoor = 'glassdoor';
    case WorkUa = 'work_ua';
    case Djinni = 'djinni';
    case Dou = 'dou';

    public function label(): string
    {
        return match ($this) {
            Platform::LinkedIn => 'LinkedIn',
            Platform::Indeed => 'Indeed',
            Platform::Glassdoor => 'Glassdoor',
            Platform::WorkUa => 'Work.ua',
            Platform::Djinni => 'Djinni',
            Platform::Dou => 'DOU',
        };
    }

    public function baseUrl(): string
    {
        return match ($this) {
            Platform::LinkedIn => 'https://www.linkedin.com',
            Platform::Indeed => 'https://www.indeed.com',
            Platform::Glassdoor => 'https://www.glassdoor.com',
            Platform::WorkUa => 'https://www.work.ua',
            Platform::Djinni => 'https://djinni.co',
            Platform::Dou => 'https://jobs.dou.ua',
        };
    }
}
