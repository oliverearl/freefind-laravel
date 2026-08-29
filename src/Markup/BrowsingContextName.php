<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

final class BrowsingContextName
{
    private const string PATTERN = '/^(?:_(?:blank|self|parent|top)|[A-Za-z][A-Za-z0-9:_-]{0,63})$/';

    public static function isValid(string $value): bool
    {
        return preg_match(self::PATTERN, $value) === 1;
    }
}
