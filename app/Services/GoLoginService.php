<?php

namespace App\Services;

use App\Exceptions\GoLoginException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoLoginService
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('gologin.api_key');
        $this->baseUrl = config('gologin.base_url');
    }

    /**
     * Create a new GoLogin cloud browser profile.
     *
     * @param  array{name: string, os?: string, proxy?: array}  $config
     * @return string The new GoLogin profile ID
     *
     * @throws GoLoginException
     */
    public function createProfile(array $config): string
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/browser/v2", array_merge([
                'os' => 'lin',
            ], $config));

        if (! $response->successful()) {
            throw new GoLoginException(
                "Failed to create GoLogin profile: {$response->status()} {$response->body()}"
            );
        }

        $profileId = $response->json('id');

        if (! $profileId) {
            throw new GoLoginException('GoLogin API response missing profile ID.');
        }

        Log::info('GoLogin profile created.', ['profile_id' => $profileId, 'name' => $config['name'] ?? '']);

        return $profileId;
    }

    /**
     * Start a cloud browser session for the given profile.
     *
     * @return array{wsUrl: string, sessionId: string}
     *
     * @throws GoLoginException
     */
    public function startBrowser(string $profileId): array
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/browser/start-remote", [
                'profileId' => $profileId,
            ]);

        if (! $response->successful()) {
            throw new GoLoginException(
                "Failed to start GoLogin browser for profile {$profileId}: {$response->status()} {$response->body()}"
            );
        }

        $data = $response->json();

        if (empty($data['wsUrl']) || empty($data['sessionId'])) {
            throw new GoLoginException('GoLogin start-remote response missing wsUrl or sessionId.');
        }

        Log::info('GoLogin browser started.', ['profile_id' => $profileId, 'session_id' => $data['sessionId']]);

        return [
            'wsUrl' => $data['wsUrl'],
            'sessionId' => $data['sessionId'],
        ];
    }

    /**
     * Stop and clean up a running browser session.
     *
     * @throws GoLoginException
     */
    public function stopBrowser(string $sessionId): void
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->delete("{$this->baseUrl}/browser/{$sessionId}");

            if (! $response->successful()) {
                Log::warning('GoLogin stop browser returned non-success.', [
                    'session_id' => $sessionId,
                    'status' => $response->status(),
                ]);
            }

            Log::info('GoLogin browser stopped.', ['session_id' => $sessionId]);
        } catch (ConnectionException $e) {
            // Log but do not rethrow — we don't want cleanup failures to mask the real error
            Log::error('GoLogin browser stop failed (network error).', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a GoLogin profile permanently.
     *
     * @throws GoLoginException
     */
    public function deleteProfile(string $profileId): void
    {
        $response = Http::withToken($this->apiKey)
            ->delete("{$this->baseUrl}/browser/v2/{$profileId}");

        if (! $response->successful()) {
            throw new GoLoginException(
                "Failed to delete GoLogin profile {$profileId}: {$response->status()} {$response->body()}"
            );
        }

        Log::info('GoLogin profile deleted.', ['profile_id' => $profileId]);
    }
}
