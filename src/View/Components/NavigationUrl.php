<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Illuminate\Support\Str;

final readonly class NavigationUrl
{
    public function __construct(public string $value)
    {
        if (
            preg_match('//u', $this->value) !== 1
            || preg_match('/[\x00-\x20\x7F]/', $this->value) === 1
            || Str::startsWith($this->value, '//')
        ) {
            throw new InvalidMarkupException('FreeFind navigation URLs must be valid URLs without whitespace, controls, or protocol-relative targets.');
        }

        if (Str::startsWith($this->value, '/')) {
            return;
        }

        $parts = parse_url($this->value);

        if (
            $parts === false
            || ! in_array(Str::lower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)
            || ! is_string($parts['host'] ?? null)
            || ($parts['user'] ?? null) !== null
            || ($parts['pass'] ?? null) !== null
        ) {
            throw new InvalidMarkupException('FreeFind navigation URLs must use an absolute http or https URL without credentials, or a root-relative path.');
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
