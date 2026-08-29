<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Hosted\HostedQueryEncoder;

it('encodes form values with pluses and preserves repeated keys', function (): void {
    $query = (new HostedQueryEncoder())->encode([
        ['si', '0012345'],
        ['query', 'laravel middleware'],
        ['s', 'manuals'],
        ['s', 'release notes'],
        ['css', ''],
    ]);

    expect($query)->toBe('si=0012345&query=laravel+middleware&s=manuals&s=release+notes&css=');
});

it('rejects unsafe form values', function (): void {
    expect(fn(): string => (new HostedQueryEncoder())->encode([['query', "one\x00two"]]))
        ->toThrow(InvalidMarkup::class);
});
