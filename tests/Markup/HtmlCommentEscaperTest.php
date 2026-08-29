<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Markup\HtmlCommentEscaper;

it('escapes comment attribute values without exposing markup syntax', function (): void {
    expect((new HtmlCommentEscaper())->attribute('Tom & "Jerry"'))->toBe('"Tom &amp; &quot;Jerry&quot;"');
});

it('rejects comment breakouts, controls, and invalid UTF-8', function (string $value): void {
    expect(fn(): string => (new HtmlCommentEscaper())->attribute($value))
        ->toThrow(InvalidMarkup::class);
})->with(["a--b", "a\x00b", "\xC3\x28"]);
