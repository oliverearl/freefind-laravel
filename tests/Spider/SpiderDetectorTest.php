<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Spider\SpiderDetector;

it('matches configured signatures without treating similar agents as spiders', function (): void {
    $detector = SpiderDetector::fromSettings(new SpiderSettings(userAgents: ['freefind/2.1', 'example-crawler']));

    expect($detector->detect('FreeFind/2.1'))->toBe('freefind/2.1')
        ->and($detector->detect('Example-Crawler/1.0'))->toBe('example-crawler')
        ->and($detector->detect('freefinder/2.1'))->toBeNull()
        ->and($detector->detect(null))->toBeNull();
});
