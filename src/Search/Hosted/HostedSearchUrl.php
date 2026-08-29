<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * A validated HTTPS URL generated for FreeFind's hosted Page Search endpoints.
 */
final readonly class HostedSearchUrl
{
    /**
     * Validates a generated hosted-search URL.
     *
     * @throws InvalidMarkupException When the URL is not a credential-free HTTPS URL.
     */
    public function __construct(public string $value)
    {
        $parts = parse_url($this->value);

        if (
            $parts === false
            || preg_match('/[\x00-\x20\x7F]/', $this->value) === 1
            || ($parts['scheme'] ?? null) !== 'https'
            || ! is_string($parts['host'] ?? null)
            || ($parts['user'] ?? null) !== null
            || ($parts['pass'] ?? null) !== null
        ) {
            throw new InvalidMarkupException('Hosted FreeFind URLs must use HTTPS without credentials.');
        }
    }

    /**
     * Returns the URL string.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
