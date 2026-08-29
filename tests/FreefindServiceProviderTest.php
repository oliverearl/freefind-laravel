<?php

declare(strict_types=1);

use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\View\Compilers\BladeCompiler;

it('registers the spider middleware as an opt-in alias without pushing it globally', function (): void {
    $router = app(Router::class);
    $kernel = app(Kernel::class);

    expect($router->getMiddleware()['freefind.spider'] ?? null)
        ->toBe(DetectFreefindSpider::class)
        ->and($kernel->hasMiddleware(DetectFreefindSpider::class))
        ->toBeFalse()
        ->and(resolve(HostedSearch::class))->toBeInstanceOf(HostedSearch::class)
        ->and(app(BladeCompiler::class)->getClassComponentAliases())
        ->toHaveKey('freefind::search-form');
});
