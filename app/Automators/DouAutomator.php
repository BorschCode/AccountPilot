<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DouAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://jobs.dou.ua/login/';

    private const POST_JOB_URL = 'https://jobs.dou.ua/vacancies/add/';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, '#id_username');
        $this->humanType($driver, '#id_username', $this->email);
        $this->humanType($driver, '#id_password', $this->password);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.company-logo', timeoutSeconds: 15);
            Log::info('DOU login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('DOU login failed.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, '#id_title');
        $this->humanType($driver, '#id_title', $jobPosting->title);
        $this->humanType($driver, '#id_city', $jobPosting->location);
        $this->humanType($driver, '#id_description', $jobPosting->description);

        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        $this->waitForElement($driver, '.vacancy-details', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $this->takeScreenshot(
            $driver,
            'dou_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.vacancy-details', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
