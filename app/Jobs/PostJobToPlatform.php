<?php

namespace App\Jobs;

use App\Automators\AutomatorFactory;
use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use App\Enums\PostingStatus;
use App\Exceptions\AutomationException;
use App\Exceptions\GoLoginException;
use App\Exceptions\ProfileRiskTooHighException;
use App\Models\GoLoginProfile;
use App\Models\JobPlatformPost;
use App\Models\JobPosting;
use App\Services\AntCaptchaService;
use App\Services\GoLoginService;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class PostJobToPlatform implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 1800; // 30 minutes between retries

    public function __construct(
        public readonly int $jobPostingId,
        public readonly int $jobPlatformPostId,
    ) {}

    public function handle(GoLoginService $goLoginService, AntCaptchaService $antCaptchaService): void
    {
        $platformPost = JobPlatformPost::with('jobPosting')->findOrFail($this->jobPlatformPostId);
        $jobPosting = $platformPost->jobPosting;
        $platform = $platformPost->platform;

        $platformPost->update(['status' => PostingStatus::Posting]);

        $goLoginProfile = $this->resolveGoLoginProfile($platform);

        try {
            $riskScore = $antCaptchaService->checkProfileScore(
                $platform->baseUrl(),
                $goLoginProfile->proxy_address ?? ''
            );

            $goLoginProfile->update([
                'risk_score' => $riskScore,
                'last_score_checked_at' => now(),
            ]);

            $proceedThreshold = config('gologin.risk_score.proceed');
            $manualThreshold = config('gologin.risk_score.manual');

            if ($riskScore >= $manualThreshold) {
                $goLoginProfile->update(['status' => GoLoginProfileStatus::Flagged]);
                throw new ProfileRiskTooHighException($riskScore);
            }

            if ($riskScore >= $proceedThreshold) {
                Log::warning('Medium risk score — proceeding with caution.', [
                    'profile_id' => $goLoginProfile->id,
                    'risk_score' => $riskScore,
                    'platform' => $platform->value,
                ]);
            }

            $sessionData = $goLoginService->startBrowser($goLoginProfile->gologin_profile_id);

            try {
                $externalUrl = $this->runAutomation($sessionData['wsUrl'], $platform, $jobPosting);

                $platformPost->update([
                    'status' => PostingStatus::Posted,
                    'external_url' => $externalUrl,
                    'risk_score_at_posting' => $riskScore,
                    'posted_at' => now(),
                    'go_login_profile_id' => $goLoginProfile->id,
                ]);

                $goLoginProfile->update(['last_used_at' => now()]);
            } finally {
                $goLoginService->stopBrowser($sessionData['sessionId']);
            }
        } catch (ProfileRiskTooHighException $e) {
            $platformPost->update([
                'status' => PostingStatus::Skipped,
                'risk_score_at_posting' => $e->riskScore,
                'error_message' => $e->getMessage(),
                'go_login_profile_id' => $goLoginProfile->id,
            ]);

            Log::warning('Job posting skipped — profile flagged.', [
                'job_posting_id' => $this->jobPostingId,
                'platform' => $platform->value,
                'risk_score' => $e->riskScore,
            ]);
        }
    }

    public function failed(Throwable $exception): void
    {
        $platformPost = JobPlatformPost::find($this->jobPlatformPostId);
        $platformPost?->update([
            'status' => PostingStatus::Failed,
            'error_message' => $exception->getMessage(),
        ]);

        Log::error('PostJobToPlatform job permanently failed.', [
            'job_posting_id' => $this->jobPostingId,
            'platform_post_id' => $this->jobPlatformPostId,
            'error' => $exception->getMessage(),
        ]);
    }

    private function resolveGoLoginProfile(Platform $platform): GoLoginProfile
    {
        $profile = GoLoginProfile::query()
            ->where('platform', $platform->value)
            ->where('status', GoLoginProfileStatus::Active->value)
            ->where(fn ($q) => $q
                ->whereNull('last_used_at')
                ->orWhere('last_used_at', '<', now()->subHours(2))
            )
            ->orderBy('risk_score')
            ->first();

        if (! $profile) {
            throw new GoLoginException("No active GoLogin profile available for platform: {$platform->value}");
        }

        return $profile;
    }

    private function runAutomation(string $wsUrl, Platform $platform, JobPosting $jobPosting): string
    {
        $credentials = $this->getCredentialsForPlatform($platform);
        $automator = AutomatorFactory::make($platform, $credentials);

        $capabilities = DesiredCapabilities::chrome();
        $driver = RemoteWebDriver::create($wsUrl, $capabilities);

        try {
            $loginSucceeded = $automator->login($driver);

            if (! $loginSucceeded) {
                throw new AutomationException("Login failed for platform: {$platform->value}");
            }

            return $automator->postJob($driver, $jobPosting);
        } finally {
            $driver->quit();
        }
    }

    /**
     * @return array{email: string, password: string}
     */
    private function getCredentialsForPlatform(Platform $platform): array
    {
        return [
            'email' => config("platforms.{$platform->value}.email"),
            'password' => config("platforms.{$platform->value}.password"),
        ];
    }
}
