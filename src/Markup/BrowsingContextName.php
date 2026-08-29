<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

/**
 * Validates target names used by generated links and forms.
 */
final class BrowsingContextName
{
    /**
     * Pattern for reserved and named HTML browsing contexts.
     */
    private const string PATTERN = '/^(?:_(?:blank|self|parent|top)|[A-Za-z][A-Za-z0-9:_-]{0,63})$/';

    /**
     * Determines whether a value is a valid named browsing context or reserved target.
     */
    public static function isValid(string $value): bool
    {
        return preg_match(self::PATTERN, $value) === 1;
    }
}
