<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final class HtmlCommentEscaper
{
    public static function assertSafe(string $value): void
    {
        if (preg_match('//u', $value) !== 1) {
            throw new InvalidMarkup('FreeFind annotation values must be valid UTF-8.');
        }

        if (preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidMarkup('FreeFind annotation values cannot contain control characters.');
        }

        if (str_contains($value, '--')) {
            throw new InvalidMarkup('FreeFind annotation values cannot contain consecutive hyphens.');
        }
    }

    public function attribute(string $value): string
    {
        self::assertSafe($value);

        return '"' . htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '"';
    }
}
