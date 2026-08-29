<?php

declare(strict_types=1);

namespace Freefind\Freefind\Spider;

use Freefind\Freefind\Configuration\SpiderSettings;
use Illuminate\Support\Str;

/**
 * Performs case-insensitive matching of configured FreeFind spider signatures.
 */
final readonly class SpiderDetector
{
    /**
     * Creates a detector from ordered user-agent fragments.
     *
     * @param  list<string>  $signatures
     */
    public function __construct(private array $signatures = ['freefind/2.1']) {}

    /**
     * Creates a detector from package spider settings.
     */
    public static function fromSettings(SpiderSettings $settings): self
    {
        return new self($settings->userAgents);
    }

    /**
     * Returns the first configured signature found in the user agent, or null.
     */
    public function detect(?string $userAgent): ?string
    {
        $normalizedUserAgent = Str::lower($userAgent ?? '');

        foreach ($this->signatures as $signature) {
            if (Str::contains($normalizedUserAgent, Str::lower($signature))) {
                return $signature;
            }
        }

        return null;
    }
}
