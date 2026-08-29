<?php

declare(strict_types=1);

use Freefind\Freefind\View\BladeRegistrar;
use Illuminate\Support\Facades\Blade;

it('registers the package components and directives', function (): void {
    $registrar = app(BladeRegistrar::class);

    $registrar->register();

    expect($registrar)->toBeInstanceOf(BladeRegistrar::class)
        ->and(Blade::render('@freefindNoMap'))->toBe('<!-- FreeFind No Map -->');
});
