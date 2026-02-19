<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WorkUaAutomator extends AbstractPlatformAutomator
{
    private const LOGIN_URL = 'https://www.work.ua/login/';

    private const POST_JOB_URL = 'https://www.work.ua/employer/vacancies/add/';

    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function login(RemoteWebDriver $driver): bool
    {
        $driver->get(self::LOGIN_URL);

        $this->waitForElement($driver, '#email');
        $this->humanType($driver, '#email', $this->email);
        $this->humanType($driver, '#password', $this->password);
        $driver->findElement(WebDriverBy::cssSelector('[type=submit]'))->click();

        try {
            $this->waitForElement($driver, '.userpic', timeoutSeconds: 15);
            Log::info('Work.ua login successful.', ['email' => $this->email]);

            return true;
        } catch (AutomationException) {
            Log::warning('Work.ua login failed.', ['email' => $this->email]);

            return false;
        }
    }

    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string
    {
        $driver->get(self::POST_JOB_URL);

        $this->waitForElement($driver, '#vacancy-name');
        $this->humanType($driver, '#vacancy-name', $jobPosting->title);
        $this->humanType($driver, '#add-info', $jobPosting->description);

        $driver->findElement(WebDriverBy::cssSelector('#vacancy-submit'))->click();

        $this->waitForElement($driver, '.vacancy-preview', timeoutSeconds: 20);

        $externalUrl = $driver->getCurrentURL();

        $this->takeScreenshot(
            $driver,
            'workua_'.Str::slug($jobPosting->title).'_'.now()->timestamp.'.png'
        );

        return $externalUrl;
    }

    public function verifyPost(RemoteWebDriver $driver, string $url): bool
    {
        $driver->get($url);

        try {
            $this->waitForElement($driver, '.card-vacancy-title', timeoutSeconds: 10);

            return true;
        } catch (AutomationException) {
            return false;
        }
    }
}
