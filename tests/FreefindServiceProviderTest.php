<?php

declare(strict_types=1);

use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

it('registers the spider middleware as an opt-in alias without pushing it globally', function (): void {
    $router = app(Router::class);
    $kernel = app(Kernel::class);

    expect($router->getMiddleware()['freefind.spider'] ?? null)
        ->toBe(DetectFreefindSpider::class)
        ->and($kernel->hasMiddleware(DetectFreefindSpider::class))
        ->toBeFalse();
});
