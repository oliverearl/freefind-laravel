<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * A validated HTTPS URL for the FreeFind XML search endpoint.
 */
final readonly class XmlSearchUrl
{
    /**
     * Validates an XML-search URL before transport uses it.
     *
     * @throws InvalidSearchRequestException When the URL is not a credential-free HTTPS URL.
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
            throw new InvalidSearchRequestException('FreeFind XML URLs must use HTTPS without credentials.');
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
