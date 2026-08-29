<?php

declare(strict_types=1);

namespace Freefind\Freefind\Spider;

use Freefind\Freefind\Configuration\SpiderSettings;

final readonly class SpiderDetector
{
    /**
     * @param  list<string>  $signatures
     */
    public function __construct(private array $signatures = ['freefind/2.1']) {}

    public static function fromSettings(SpiderSettings $settings): self
    {
        return new self($settings->userAgents);
    }

    public function detect(?string $userAgent): ?string
    {
        $normalizedUserAgent = strtolower($userAgent ?? '');

        foreach ($this->signatures as $signature) {
            if (str_contains($normalizedUserAgent, strtolower($signature))) {
                return $signature;
            }
        }

        return null;
    }
}
