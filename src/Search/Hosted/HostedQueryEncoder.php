<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Encodes repeated hosted-search fields using FreeFind's query-string format.
 */
final class HostedQueryEncoder
{
    /**
     * Encodes ordered key/value pairs without collapsing repeated keys.
     *
     * @param  list<array{0: string, 1: string}>  $pairs
     *
     * @throws InvalidMarkupException When a key or value contains invalid UTF-8 or control characters.
     */
    public function encode(array $pairs): string
    {
        return implode('&', array_map(function (array $pair): string {
            [$key, $value] = $pair;
            $this->assertSafe($key);
            $this->assertSafe($value);

            return urlencode($key) . '=' . urlencode($value);
        }, $pairs));
    }

    /**
     * Validates one hosted-search field.
     *
     * @throws InvalidMarkupException When the value contains invalid UTF-8 or control characters.
     */
    private function assertSafe(string $value): void
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidMarkupException('Hosted FreeFind query fields cannot contain control characters or invalid UTF-8.');
        }
    }
}
