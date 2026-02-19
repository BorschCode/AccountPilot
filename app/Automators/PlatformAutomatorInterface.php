<?php

namespace App\Automators;

use App\Models\JobPosting;
use Facebook\WebDriver\Remote\RemoteWebDriver;

interface PlatformAutomatorInterface
{
    /**
     * Log in to the platform using stored credentials.
     */
    public function login(RemoteWebDriver $driver): bool;

    /**
     * Post a job to the platform.
     * Returns the external URL of the posted job.
     */
    public function postJob(RemoteWebDriver $driver, JobPosting $jobPosting): string;

    /**
     * Verify the job post is publicly accessible at the given URL.
     */
    public function verifyPost(RemoteWebDriver $driver, string $url): bool;
}
