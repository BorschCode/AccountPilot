<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GlassdoorAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://www.glassdoor.com/employers/sign-in/';

    private const POST_JOB_URL = 'https://www.glassdoor.com/employers/jobs/create/';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, '#userEmail');
        $this->humanType($driver, '#userEmail', $this->email);
        $this->humanType($driver, '#userPassword', $this->password);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.employer-dashboard-header', timeoutSeconds: 15);
            Log::info('Glassdoor login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('Glassdoor login failed.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, '#jobTitle');
        $this->humanType($driver, '#jobTitle', $jobPosting->title);
        $this->humanType($driver, '#jobLocation', $jobPosting->location);
        $this->humanType($driver, '#jobDescription', $jobPosting->description);

        $driver->findElement(WebDriverBy::cssSelector('[data-test="job-post-submit"]'))->click();

        $this->waitForElement($driver, '[data-test="job-post-success"]', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $this->takeScreenshot(
            $driver,
            'glassdoor_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.JobDetails', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
