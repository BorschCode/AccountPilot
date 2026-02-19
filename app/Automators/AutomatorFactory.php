<?php

namespace App\Automators;

use App\Enums\Platform;
use InvalidArgumentException;

class AutomatorFactory
{
    /**
     * @param  array{email: string, password: string}  $credentials
     */
    public static function make(Platform $platform, array $credentials): PlatformAutomatorInterface
    {
        return match ($platform) {
            Platform::LinkedIn => new LinkedInAutomator($credentials['email'], $credentials['password']),
            Platform::Indeed => new IndeedAutomator($credentials['email'], $credentials['password']),
            Platform::Glassdoor => new GlassdoorAutomator($credentials['email'], $credentials['password']),
            Platform::WorkUa => new WorkUaAutomator($credentials['email'], $credentials['password']),
            Platform::Djinni => new DjinniAutomator($credentials['email'], $credentials['password']),
            Platform::Dou => new DouAutomator($credentials['email'], $credentials['password']),
            default => throw new InvalidArgumentException("No automator registered for platform: {$platform->value}"),
        };
    }
}
