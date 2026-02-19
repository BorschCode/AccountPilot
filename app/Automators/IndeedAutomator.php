<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IndeedAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://employers.indeed.com/p/login';

    private const POST_JOB_URL = 'https://employers.indeed.com/p/post-job';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, 'input[type=email]');
        $this->humanType($driver, 'input[type=email]', $this->email);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        $this->waitForElement($driver, 'input[type=password]');
        $this->humanType($driver, 'input[type=password]', $this->password);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.employer-dashboard', timeoutSeconds: 15);
            Log::info('Indeed login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('Indeed login failed.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, '#jobTitle');
        $this->humanType($driver, '#jobTitle', $jobPosting->title);
        $this->humanType($driver, '#jobLocation', $jobPosting->location);

        $descriptionEl = $this->waitForElement($driver, '#jobDescription [contenteditable]');
        $descriptionEl->click();
        $descriptionEl->sendKeys($jobPosting->description);

        $driver->findElement(WebDriverBy::cssSelector('[data-testid="submit-button"]'))->click();

        $this->waitForElement($driver, '[data-testid="job-post-confirmation"]', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $this->takeScreenshot(
            $driver,
            'indeed_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.jobsearch-ViewJobLayout', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
