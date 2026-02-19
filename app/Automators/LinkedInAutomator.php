<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LinkedInAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://www.linkedin.com/login';

    private const POST_JOB_URL = 'https://www.linkedin.com/talent/job-postings/create';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, '#username');
        $this->humanType($driver, '#username', $this->email);
        $this->humanType($driver, '#password', $this->password);

        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.global-nav__me-photo', timeoutSeconds: 15);
            Log::info('LinkedIn login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('LinkedIn login failed — could not detect nav after submit.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, 'input[name="title"]');
        $this->humanType($driver, 'input[name="title"]', $jobPosting->title);
        $this->humanType($driver, 'input[name="location"]', $jobPosting->location);

        // Fill description via contenteditable
        $descriptionEl = $this->waitForElement($driver, '.job-description-editor [contenteditable]');
        $descriptionEl->click();
        $descriptionEl->sendKeys($jobPosting->description);

        // Submit the form
        $driver->findElement(WebDriverBy::cssSelector('[data-test="submit-job-post"]'))->click();

        // Wait for confirmation page
        $this->waitForElement($driver, '[data-test="job-post-success"]', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $screenshotPath = $this->takeScreenshot(
            $driver,
            'linkedin_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        Log::info('LinkedIn job posted.', ['url' => $externalUrl, 'screenshot' => $screenshotPath]);

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.job-view-layout', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
