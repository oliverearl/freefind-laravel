<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Hosted\HostedSearchUrl;

it('represents a secure hosted search URL', function (): void {
    $url = new HostedSearchUrl('https://search.freefind.com/find.html?si=0012345');

    expect($url->value)->toBe('https://search.freefind.com/find.html?si=0012345')
        ->and((string) $url)->toBe($url->value);
});

it('rejects insecure or credential-bearing hosted URLs', function (string $url): void {
    expect(fn(): HostedSearchUrl => new HostedSearchUrl($url))
        ->toThrow(InvalidMarkup::class);
})->with([
    'http://search.freefind.com/find.html',
    'https://user:pass@search.freefind.com/find.html',
    'https://search.freefind.com/find.html?query=one two',
    'not-a-url',
]);
