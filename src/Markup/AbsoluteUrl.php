<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Illuminate\Support\Str;

/**
 * An absolute, credential-free HTTP or HTTPS URL accepted by FreeFind markup.
 */
final readonly class AbsoluteUrl
{
    /**
     * Validates an absolute URL for use in crawler annotations.
     *
     * @throws InvalidMarkupException When the URL is not an absolute HTTP(S) URL.
     */
    public function __construct(public string $value)
    {
        if (
            preg_match('//u', $this->value) !== 1
            || preg_match('/[\x00-\x20\x7F]/', $this->value) === 1
        ) {
            throw new InvalidMarkupException('FreeFind URLs must be valid absolute URLs without whitespace or control characters.');
        }

        $parts = parse_url($this->value);

        if (
            $parts === false
            || ! in_array(Str::lower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)
            || ! is_string($parts['host'] ?? null)
            || ($parts['user'] ?? null) !== null
            || ($parts['pass'] ?? null) !== null
        ) {
            throw new InvalidMarkupException('FreeFind URLs must use an absolute http or https URL without credentials.');
        }
    }

    /**
     * Normalizes an existing value object or validates a URL string.
     *
     * @throws InvalidMarkupException When the URL is not an absolute HTTP(S) URL.
     */
    public static function from(string|self $url): self
    {
        return $url instanceof self ? $url : new self($url);
    }

    /**
     * Returns the validated URL string.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
