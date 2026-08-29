<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Markup\AbsoluteUrl;

it('accepts absolute HTTP and HTTPS URLs', function (): void {
    $url = AbsoluteUrl::from('https://example.test/path?q=one&two=2#part');

    expect($url->value)->toBe('https://example.test/path?q=one&two=2#part')
        ->and((string) $url)->toBe($url->value);
});

it('rejects unsafe or non-absolute URLs', function (string $url): void {
    expect(fn(): AbsoluteUrl => new AbsoluteUrl($url))
        ->toThrow(InvalidMarkup::class);
})->with([
    'javascript:alert(1)',
    'data:text/html,hello',
    '//example.test/path',
    'https://user:pass@example.test/path',
    'https://example.test/a path',
    '/relative/path',
]);
