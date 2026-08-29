<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class AbsoluteUrl
{
    public function __construct(public string $value)
    {
        if (
            preg_match('//u', $this->value) !== 1
            || preg_match('/[\x00-\x20\x7F]/', $this->value) === 1
        ) {
            throw new InvalidMarkup('FreeFind URLs must be valid absolute URLs without whitespace or control characters.');
        }

        $parts = parse_url($this->value);

        if (
            $parts === false
            || ! in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)
            || ! is_string($parts['host'] ?? null)
            || ($parts['user'] ?? null) !== null
            || ($parts['pass'] ?? null) !== null
        ) {
            throw new InvalidMarkup('FreeFind URLs must use an absolute http or https URL without credentials.');
        }
    }

    public static function from(string|self $url): self
    {
        return $url instanceof self ? $url : new self($url);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
