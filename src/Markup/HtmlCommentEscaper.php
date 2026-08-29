<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Illuminate\Support\Str;

/**
 * Guards annotation values against invalid UTF-8 and HTML-comment breakouts.
 */
final class HtmlCommentEscaper
{
    /**
     * Ensures a value can be embedded in a FreeFind HTML comment or attribute.
     *
     * @throws InvalidMarkupException When the value contains invalid UTF-8, controls, or consecutive hyphens.
     */
    public static function assertSafe(string $value): void
    {
        if (preg_match('//u', $value) !== 1) {
            throw new InvalidMarkupException('FreeFind annotation values must be valid UTF-8.');
        }

        if (preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidMarkupException('FreeFind annotation values cannot contain control characters.');
        }

        if (Str::contains($value, '--')) {
            throw new InvalidMarkupException('FreeFind annotation values cannot contain consecutive hyphens.');
        }
    }

    /**
     * Escapes a safe value as a quoted HTML attribute value.
     *
     * @throws InvalidMarkupException When the value is unsafe for an HTML comment or attribute.
     */
    public function attribute(string $value): string
    {
        self::assertSafe($value);

        return '"' . htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '"';
    }
}
