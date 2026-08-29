<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Exceptions\InvalidConfiguration;

it('keeps a site id as the configured string', function (): void {
    $account = new Account('0012345');

    expect($account->siteId)->toBe('0012345')
        ->and($account->htmlEndpoint)->toBe(Account::DEFAULT_HTML_ENDPOINT);
});

it('builds an account from package configuration', function (): void {
    $account = Account::fromConfig([
        'site_id' => 'site-42',
        'endpoints' => [
            'html' => 'https://example.test/find.html',
            'xml' => 'https://example.test/find.xml',
            'index' => 'https://example.test/index.html',
        ],
    ]);

    expect($account->siteId)->toBe('site-42')
        ->and($account->xmlEndpoint)->toBe('https://example.test/find.xml');
});

it('rejects missing, non-string, blank, and unsafe site ids', function (mixed $siteId): void {
    expect(fn(): Account => Account::fromConfig(['site_id' => $siteId]))
        ->toThrow(InvalidConfiguration::class);
})->with([null, 123, '', ' site-id', "site\n-id"]);

it('rejects non-https or credential-bearing endpoints', function (string $endpoint): void {
    expect(fn(): Account => new Account('site-42', htmlEndpoint: $endpoint))
        ->toThrow(InvalidConfiguration::class);
})->with([
    'http://example.test/find.html',
    'https://user:pass@example.test/find.html',
    'https://example.test/find.html?site=42',
    'https://example.test/find.html#fragment',
    'not a url',
]);

it('rejects endpoint values that are not strings', function (): void {
    expect(fn(): Account => Account::fromConfig([
        'site_id' => 'site-42',
        'endpoints' => ['html' => 123],
    ]))->toThrow(InvalidConfiguration::class);
});
