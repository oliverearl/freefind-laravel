<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class HostedSearchUrl
{
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
            throw new InvalidMarkup('Hosted FreeFind URLs must use HTTPS without credentials.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
