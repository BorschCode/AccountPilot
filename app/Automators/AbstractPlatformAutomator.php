<?php

namespace App\Automators;

use App\Exceptions\AutomationException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Support\Facades\Log;

abstract class AbstractPlatformAutomator implements PlatformAutomatorInterface
{
    /**
     * Wait for an element to be visible and return it.
     *
     * @throws AutomationException
     */
    protected function waitForElement(RemoteWebDriver $driver, string $cssSelector, int $timeoutSeconds = 10): \Facebook\WebDriver\WebDriverElement
    {
        try {
            $driver->wait($timeoutSeconds)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(
                    WebDriverBy::cssSelector($cssSelector)
                )
            );

            return $driver->findElement(WebDriverBy::cssSelector($cssSelector));
        } catch (\Exception $e) {
            throw new AutomationException("Element '{$cssSelector}' not visible after {$timeoutSeconds}s: {$e->getMessage()}");
        }
    }

    /**
     * Take a screenshot and return the storage path.
     */
    protected function takeScreenshot(RemoteWebDriver $driver, string $filename): string
    {
        $path = storage_path("app/screenshots/{$filename}");

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $driver->takeScreenshot($path);

        Log::info('Screenshot taken.', ['path' => $path]);

        return "screenshots/{$filename}";
    }

    /**
     * Type text slowly to simulate human input.
     */
    protected function humanType(RemoteWebDriver $driver, string $cssSelector, string $text): void
    {
        $element = $driver->findElement(WebDriverBy::cssSelector($cssSelector));
        $element->clear();

        foreach (str_split($text) as $char) {
            $element->sendKeys($char);
            usleep(random_int(30000, 120000)); // 30-120ms per character
        }
    }
}
