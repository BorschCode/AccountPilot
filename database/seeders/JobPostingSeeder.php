<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Enums\Platform;
use App\Enums\PostingStatus;
use App\Models\GoLoginProfile;
use App\Models\JobPlatformPost;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $hr = User::where('email', 'hr@example.com')->first();

        // 1. Draft posting — not yet dispatched
        JobPosting::firstOrCreate(
            ['title' => 'Senior PHP Developer'],
            [
                'created_by' => $hr->id,
                'description' => "We're looking for a Senior PHP Developer to join our growing team.\n\nRequirements:\n- 4+ years PHP experience\n- Laravel expertise\n- MySQL/PostgreSQL knowledge\n- Git proficiency\n\nWe offer:\n- Competitive salary\n- Remote-first culture\n- Flexible hours",
                'location' => 'Kyiv, Ukraine (Remote)',
                'employment_type' => EmploymentType::FullTime->value,
                'salary_min' => 3500,
                'salary_max' => 5500,
                'salary_currency' => 'USD',
                'platforms' => [Platform::LinkedIn->value, Platform::Djinni->value, Platform::Dou->value],
                'status' => JobPostingStatus::Draft->value,
            ]
        );

        // 2. Posted successfully on two platforms, failed on one
        $partialPosting = JobPosting::firstOrCreate(
            ['title' => 'React Frontend Developer'],
            [
                'created_by' => $hr->id,
                'description' => "Join our frontend team as a React Developer.\n\nRequirements:\n- 3+ years React experience\n- TypeScript proficiency\n- REST/GraphQL API integration\n- Testing experience (Jest, RTL)\n\nWe offer:\n- Fully remote\n- Modern tech stack\n- International team",
                'location' => 'Lviv, Ukraine (Remote)',
                'employment_type' => EmploymentType::FullTime->value,
                'salary_min' => 2800,
                'salary_max' => 4500,
                'salary_currency' => 'USD',
                'platforms' => [Platform::LinkedIn->value, Platform::Indeed->value, Platform::WorkUa->value],
                'status' => JobPostingStatus::PartiallyPosted->value,
                'queued_at' => now()->subHours(3),
                'posted_at' => now()->subHours(2),
            ]
        );

        $linkedinProfile = GoLoginProfile::where('platform', Platform::LinkedIn->value)
            ->where('status', 'active')
            ->first();

        $indeedProfile = GoLoginProfile::where('platform', Platform::Indeed->value)
            ->first();

        if ($partialPosting->wasRecentlyCreated) {
            JobPlatformPost::create([
                'job_posting_id' => $partialPosting->id,
                'go_login_profile_id' => $linkedinProfile?->id,
                'platform' => Platform::LinkedIn->value,
                'status' => PostingStatus::Posted->value,
                'external_url' => 'https://www.linkedin.com/jobs/view/3901234567',
                'screenshot_path' => null,
                'risk_score_at_posting' => 12,
                'posted_at' => now()->subHours(2),
            ]);

            JobPlatformPost::create([
                'job_posting_id' => $partialPosting->id,
                'go_login_profile_id' => $indeedProfile?->id,
                'platform' => Platform::Indeed->value,
                'status' => PostingStatus::Failed->value,
                'error_message' => 'Login failed — captcha detected on Indeed login page.',
                'risk_score_at_posting' => 45,
            ]);

            JobPlatformPost::create([
                'job_posting_id' => $partialPosting->id,
                'go_login_profile_id' => null,
                'platform' => Platform::WorkUa->value,
                'status' => PostingStatus::Posted->value,
                'external_url' => 'https://www.work.ua/jobs/8123456/',
                'risk_score_at_posting' => 5,
                'posted_at' => now()->subHours(2),
            ]);
        }

        // 3. Fully posted on all platforms
        $successPosting = JobPosting::firstOrCreate(
            ['title' => 'DevOps Engineer'],
            [
                'created_by' => $hr->id,
                'description' => "We are hiring a DevOps Engineer to strengthen our infrastructure.\n\nRequirements:\n- Docker & Kubernetes experience\n- CI/CD pipeline management\n- AWS or GCP cloud experience\n- Linux administration\n\nWe offer:\n- Competitive salary\n- Stock options\n- Remote work",
                'location' => 'Remote (Ukraine)',
                'employment_type' => EmploymentType::Contract->value,
                'salary_min' => 4000,
                'salary_max' => 7000,
                'salary_currency' => 'USD',
                'platforms' => [Platform::LinkedIn->value, Platform::Djinni->value],
                'status' => JobPostingStatus::Posted->value,
                'queued_at' => now()->subDays(2),
                'posted_at' => now()->subDays(2)->addHour(),
            ]
        );

        if ($successPosting->wasRecentlyCreated) {
            JobPlatformPost::create([
                'job_posting_id' => $successPosting->id,
                'go_login_profile_id' => $linkedinProfile?->id,
                'platform' => Platform::LinkedIn->value,
                'status' => PostingStatus::Posted->value,
                'external_url' => 'https://www.linkedin.com/jobs/view/3901234890',
                'risk_score_at_posting' => 8,
                'posted_at' => now()->subDays(2)->addHour(),
            ]);

            JobPlatformPost::create([
                'job_posting_id' => $successPosting->id,
                'go_login_profile_id' => null,
                'platform' => Platform::Djinni->value,
                'status' => PostingStatus::Posted->value,
                'external_url' => 'https://djinni.co/jobs/738291-devops-engineer/',
                'risk_score_at_posting' => 9,
                'posted_at' => now()->subDays(2)->addHour(),
            ]);
        }

        // 4. Queued posting — waiting in queue
        JobPosting::firstOrCreate(
            ['title' => 'QA Automation Engineer'],
            [
                'created_by' => $hr->id,
                'description' => "We're looking for a QA Automation Engineer.\n\nRequirements:\n- Selenium or Playwright experience\n- Python or JavaScript\n- API testing (Postman, REST Assured)\n- CI/CD integration\n\nWe offer:\n- Flexible schedule\n- Remote work\n- English classes",
                'location' => 'Dnipro, Ukraine (Hybrid)',
                'employment_type' => EmploymentType::FullTime->value,
                'salary_min' => 2000,
                'salary_max' => 3500,
                'salary_currency' => 'USD',
                'platforms' => [Platform::LinkedIn->value, Platform::Indeed->value, Platform::Glassdoor->value],
                'status' => JobPostingStatus::Queued->value,
                'queued_at' => now()->subMinutes(10),
            ]
        );
    }
}
