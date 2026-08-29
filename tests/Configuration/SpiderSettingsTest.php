<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Exceptions\InvalidConfigurationException;

it('defaults spider middleware to opt-in', function (): void {
    $settings = new SpiderSettings();

    expect($settings->middleware)->toBeFalse()
        ->and($settings->userAgents)->toBe(['freefind/2.1'])
        ->and($settings->cacheControl)->toBe('public, max-age=3600');
});

it('reads spider settings from configuration', function (): void {
    $settings = SpiderSettings::fromConfig([
        'middleware' => true,
        'user_agents' => ['crawler/1.0', 'freefind/2.1'],
        'cache_control' => 'public, max-age=60',
    ]);

    expect($settings->middleware)->toBeTrue()
        ->and($settings->userAgents)->toBe(['crawler/1.0', 'freefind/2.1'])
        ->and($settings->cacheControl)->toBe('public, max-age=60');
});

it('rejects malformed spider settings', function (array $config): void {
    expect(fn(): SpiderSettings => SpiderSettings::fromConfig($config))
        ->toThrow(InvalidConfigurationException::class);
})->with([
    [['middleware' => 'yes']],
    [['user_agents' => []]],
    [['user_agents' => ['']]],
    [['user_agents' => 'freefind/2.1']],
    [['cache_control' => '']],
]);
