<?php

declare(strict_types=1);

namespace Freefind\Freefind\Spider;

/**
 * Request-scoped result of optional FreeFind spider user-agent detection.
 *
 * Detection is informational and must not be used for authorization.
 */
final readonly class SpiderContext
{
    /**
     * Request attribute used to store the current spider context.
     */
    public const string REQUEST_ATTRIBUTE = 'freefind.spider_context';

    /**
     * Creates a consistent detected or non-spider context.
     *
     * @throws \InvalidArgumentException When the detection flag and matched user agent disagree.
     */
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

    /**
     * Returns a context representing an ordinary browser request.
     */
    public static function notSpider(): self
    {
        return new self(false);
    }

    /**
     * Returns a context representing a request matching a configured spider signature.
     */
    public static function detected(string $matchedUserAgent): self
    {
        return new self(true, $matchedUserAgent);
    }

    /**
     * Determines whether a configured spider signature matched this request.
     */
    public function isSpider(): bool
    {
        return $this->spider;
    }

    /**
     * Returns the matched signature, or null for an ordinary request.
     */
    public function matchedUserAgent(): ?string
    {
        return $this->matchedUserAgent;
    }
}
