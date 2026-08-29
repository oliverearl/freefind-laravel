<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Exceptions\InvalidConfigurationException;

it('uses bounded conservative HTTP defaults', function (): void {
    $settings = new HttpSettings();

    expect($settings->connectTimeout)->toBe(2)
        ->and($settings->timeout)->toBe(5)
        ->and($settings->maxResponseBytes)->toBe(2_000_000);
});

it('reads integer HTTP settings from configuration', function (): void {
    $settings = HttpSettings::fromConfig([
        'connect_timeout' => 1,
        'timeout' => 4,
        'max_response_bytes' => 1000,
    ]);

    expect($settings->connectTimeout)->toBe(1)
        ->and($settings->timeout)->toBe(4)
        ->and($settings->maxResponseBytes)->toBe(1000);
});

it('rejects invalid HTTP settings', function (int $connectTimeout, int $timeout, int $maxResponseBytes): void {
    expect(fn(): HttpSettings => new HttpSettings($connectTimeout, $timeout, $maxResponseBytes))
        ->toThrow(InvalidConfigurationException::class);
})->with([
    [0, 5, 1000],
    [5, 4, 1000],
    [1, 1, 0],
]);

it('rejects non-integer configured HTTP settings', function (): void {
    expect(fn(): HttpSettings => HttpSettings::fromConfig(['timeout' => '5']))
        ->toThrow(InvalidConfigurationException::class);
});
