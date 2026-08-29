<?php

declare(strict_types=1);

namespace Freefind\Freefind\Testing;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

final readonly class SearchFixture
{
    private function __construct(
        public string $query,
        public string $body,
    ) {}

    public static function for(string $query): self
    {
        if (
            trim($query) === ''
            || preg_match('//u', $query) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $query) === 1
        ) {
            throw new InvalidSearchRequestException('FreeFind fake fixture queries must be non-empty valid text without control characters.');
        }

        return new self($query, '');
    }

    public function fromFile(string $path): self
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidSearchRequestException('The FreeFind fake fixture file could not be read.');
        }

        $body = file_get_contents($path);

        if ($body === false || $body === '') {
            throw new InvalidSearchRequestException('The FreeFind fake fixture file was empty.');
        }

        return new self($this->query, $body);
    }
}
