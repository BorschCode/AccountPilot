<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AntCaptchaService
{
    private string $apiKey;

    private string $baseUrl;

    private int $pollIntervalSeconds;

    private int $maxPollAttempts;

    public function __construct()
    {
        $this->apiKey = config('antcaptcha.api_key');
        $this->baseUrl = config('antcaptcha.base_url');
        $this->pollIntervalSeconds = config('antcaptcha.poll_interval_seconds');
        $this->maxPollAttempts = config('antcaptcha.max_poll_attempts');
    }

    /**
     * Check the risk score of a proxy/profile against a target website.
     * Returns a score from 0 (clean) to 100 (heavily flagged).
     *
     * @throws RuntimeException
     */
    public function checkProfileScore(string $websiteUrl, string $proxyAddress): int
    {
        $taskId = $this->createScoreDetectorTask($websiteUrl, $proxyAddress);
        $result = $this->pollForResult($taskId);

        $riskScore = (int) ($result['riskScore'] ?? 100);

        Log::info('AntCaptcha risk score checked.', [
            'website_url' => $websiteUrl,
            'proxy' => $proxyAddress,
            'risk_score' => $riskScore,
        ]);

        return $riskScore;
    }

    /**
     * Solve a reCAPTCHA v2 or v3 challenge.
     *
     * @throws RuntimeException
     */
    public function solveCaptcha(string $siteKey, string $url): string
    {
        $taskId = $this->createRecaptchaTask($siteKey, $url);
        $result = $this->pollForResult($taskId);

        $token = $result['gRecaptchaResponse'] ?? null;

        if (! $token) {
            throw new RuntimeException('AntCaptcha did not return a captcha token.');
        }

        return $token;
    }

    /**
     * @throws RuntimeException
     */
    private function createScoreDetectorTask(string $websiteUrl, string $proxyAddress): int
    {
        [$proxyHost, $proxyPort] = explode(':', $proxyAddress, 2) + [null, null];

        $response = Http::post("{$this->baseUrl}/createTask", [
            'clientKey' => $this->apiKey,
            'task' => [
                'type' => 'AntiGateTask',
                'websiteURL' => $websiteUrl,
                'proxyAddress' => $proxyHost,
                'proxyPort' => (int) $proxyPort,
            ],
        ]);

        return $this->extractTaskId($response->json());
    }

    /**
     * @throws RuntimeException
     */
    private function createRecaptchaTask(string $siteKey, string $url): int
    {
        $response = Http::post("{$this->baseUrl}/createTask", [
            'clientKey' => $this->apiKey,
            'task' => [
                'type' => 'RecaptchaV2TaskProxyless',
                'websiteURL' => $url,
                'websiteKey' => $siteKey,
            ],
        ]);

        return $this->extractTaskId($response->json());
    }

    /**
     * @throws RuntimeException
     */
    private function extractTaskId(array $responseData): int
    {
        if (($responseData['errorId'] ?? 0) !== 0) {
            throw new RuntimeException(
                'AntCaptcha task creation failed: '.($responseData['errorDescription'] ?? 'Unknown error')
            );
        }

        $taskId = $responseData['taskId'] ?? null;

        if (! $taskId) {
            throw new RuntimeException('AntCaptcha API response missing taskId.');
        }

        return (int) $taskId;
    }

    /**
     * @throws RuntimeException
     */
    private function pollForResult(int $taskId): array
    {
        for ($attempt = 0; $attempt < $this->maxPollAttempts; $attempt++) {
            sleep($this->pollIntervalSeconds);

            $response = Http::post("{$this->baseUrl}/getTaskResult", [
                'clientKey' => $this->apiKey,
                'taskId' => $taskId,
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'ready') {
                return $data['solution'] ?? [];
            }

            if (($data['errorId'] ?? 0) !== 0) {
                throw new RuntimeException(
                    'AntCaptcha task failed: '.($data['errorDescription'] ?? 'Unknown error')
                );
            }
        }

        throw new RuntimeException("AntCaptcha task {$taskId} did not complete after {$this->maxPollAttempts} attempts.");
    }
}
