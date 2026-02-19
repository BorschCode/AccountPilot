<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DjinniAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://djinni.co/login/';

    private const POST_JOB_URL = 'https://djinni.co/jobs/post/';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, '#id_email');
        $this->humanType($driver, '#id_email', $this->email);
        $this->humanType($driver, '#id_password', $this->password);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.user-menu', timeoutSeconds: 15);
            Log::info('Djinni login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('Djinni login failed.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, '#id_title');
        $this->humanType($driver, '#id_title', $jobPosting->title);
        $this->humanType($driver, '#id_location', $jobPosting->location);
        $this->humanType($driver, '#id_description', $jobPosting->description);

        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        $this->waitForElement($driver, '.job-post-success', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $this->takeScreenshot(
            $driver,
            'djinni_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.job-card', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
