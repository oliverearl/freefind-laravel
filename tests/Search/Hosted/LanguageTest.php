<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Hosted\Language;

it('accepts documented language codes', function (): void {
    expect(Language::fromCode('es')->code)->toBe('es')
        ->and(Language::fromCode('zh2')->code)->toBe('zh2');
});

it('allows a validated custom language code for forward compatibility', function (): void {
    expect(Language::custom('xx-new')->code)->toBe('xx-new');
});

it('rejects undocumented or malformed language codes', function (string $code): void {
    expect(fn(): Language => Language::fromCode($code))
        ->toThrow(InvalidMarkup::class);
})->with(['xx', 'EN', 'not a code', '']);

it('rejects malformed custom language codes', function (string $code): void {
    expect(fn(): Language => Language::custom($code))
        ->toThrow(InvalidMarkup::class);
})->with(['not a code', 'TOO-LONG-CODE', '1x']);
