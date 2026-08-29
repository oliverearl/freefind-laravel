<?php

declare(strict_types=1);

use Freefind\Freefind\Spider\SpiderContext;

it('represents a non-spider request without a matched user agent', function (): void {
    $context = SpiderContext::notSpider();

    expect($context->isSpider())->toBeFalse()
        ->and($context->matchedUserAgent())->toBeNull();
});

it('represents a detected spider and its configured signature', function (): void {
    $context = SpiderContext::detected('freefind/2.1');

    expect($context->isSpider())->toBeTrue()
        ->and($context->matchedUserAgent())->toBe('freefind/2.1');
});

it('rejects inconsistent context state', function (bool $spider, ?string $userAgent): void {
    expect(fn(): SpiderContext => new SpiderContext($spider, $userAgent))
        ->toThrow(InvalidArgumentException::class);
})->with([[false, 'freefind/2.1'], [true, null]]);
