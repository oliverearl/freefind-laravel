<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Exceptions\InvalidConfiguration;

it('composes immutable account, HTTP, and spider settings', function (): void {
    $config = FreefindConfig::fromConfig(['site_id' => 'site-42']);

    expect($config->account->siteId)->toBe('site-42')
        ->and($config->http->timeout)->toBe(5)
        ->and($config->spider->middleware)->toBeFalse();
});

it('rejects non-array nested settings', function (string $key): void {
    expect(fn(): FreefindConfig => FreefindConfig::fromConfig([
        'site_id' => 'site-42',
        $key => 'invalid',
    ]))->toThrow(InvalidConfiguration::class);
})->with(['http', 'spider']);
