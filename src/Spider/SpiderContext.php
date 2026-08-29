<?php

declare(strict_types=1);

namespace Freefind\Freefind\Spider;

final readonly class SpiderContext
{
    public const string REQUEST_ATTRIBUTE = 'freefind.spider_context';

    public function __construct(
        private bool $spider,
        private ?string $matchedUserAgent = null,
    ) {
        if (! $this->spider && $this->matchedUserAgent !== null) {
            throw new \InvalidArgumentException('A non-spider context cannot contain a matched user agent.');
        }

        if ($this->spider && $this->matchedUserAgent === null) {
            throw new \InvalidArgumentException('A spider context must contain a matched user agent.');
        }
    }

    public static function notSpider(): self
    {
        return new self(false);
    }

    public static function detected(string $matchedUserAgent): self
    {
        return new self(true, $matchedUserAgent);
    }

    public function isSpider(): bool
    {
        return $this->spider;
    }

    public function matchedUserAgent(): ?string
    {
        return $this->matchedUserAgent;
    }
}
