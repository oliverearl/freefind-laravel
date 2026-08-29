<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Http\Middleware\AddFreefindAnnotations;
use Freefind\Freefind\Markup\AnnotationCollector;
use Freefind\Freefind\Markup\Renderer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

it('collects only supported route annotations before the application renders the head', function (): void {
    Route::middleware('freefind.annotate:no-index,no-map,not-new')->get('/annotated', function (): Response {
        app(AnnotationCollector::class)->add('<meta name="application" content="example">');

        return response(Blade::render('<head>@freefindHead</head>'));
    });

    $response = $this->get('/annotated');

    expect($response->getContent())->toBe(<<<'HTML'
        <head><!-- FreeFind No Index Page -->
        <!-- FreeFind No Map -->
        <!-- FreeFind Not New -->
        <meta name="application" content="example"></head>
        HTML);
});

it('normalizes comma-separated parameters and rejects unsupported annotations', function (): void {
    $middleware = new AddFreefindAnnotations(
        resolve(AnnotationCollector::class),
        resolve(Renderer::class),
    );

    $request = Request::create('/annotated');
    $middleware->handle($request, fn(Request $request): Response => response('ok'), 'no-map, no-map');

    expect(resolve(AnnotationCollector::class)->render())->toBe('<!-- FreeFind No Map -->')
        ->and(fn(): mixed => $middleware->handle($request, fn(): Response => response('ok'), 'keywords'))
        ->toThrow(InvalidMarkup::class);
});
