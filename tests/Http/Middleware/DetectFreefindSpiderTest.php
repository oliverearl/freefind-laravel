<?php

declare(strict_types=1);

use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Freefind\Freefind\Spider\SpiderContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

beforeEach(function (): void {
    $this->middleware = resolve(DetectFreefindSpider::class);
    $this->request = Request::create('/test');
});

it('detects a FreeFind spider case-insensitively and stores request-local context', function (): void {
    $this->request->headers->set('User-Agent', 'Mozilla/5.0 FreeFind/2.1 crawler');

    $response = $this->middleware->handle($this->request, function (Request $request): Response {
        expect($request->attributes->get(SpiderContext::REQUEST_ATTRIBUTE)->isSpider())->toBeTrue();

        $response = response('<h1>OK</h1>');
        $response->headers->remove('Cache-Control');

        return $response;
    });

    expect($this->request->attributes->get(SpiderContext::REQUEST_ATTRIBUTE)->matchedUserAgent())
        ->toBe('freefind/2.1')
        ->and($response->headers->get('Cache-Control'))->toBe('max-age=3600, public');
});

it('does not detect regular or similar user agents', function (string $userAgent): void {
    $this->request->headers->set('User-Agent', $userAgent);

    $response = $this->middleware->handle($this->request, fn(): Response => response('<h1>OK</h1>'));
    $context = $this->request->attributes->get(SpiderContext::REQUEST_ATTRIBUTE);

    expect($context->isSpider())->toBeFalse()
        ->and($response->headers->get('Cache-Control'))->not->toBe('public, max-age=3600');
})->with(['Mozilla/5.0', 'freefinder/2.1', '']);

it('preserves existing cache headers', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    $response = $this->middleware->handle($this->request, function (): Response {
        $response = response('<h1>OK</h1>');
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    });

    expect($response->headers->get('Cache-Control'))->toBe('no-store, private');
});

it('leaves non-html, compressed, redirect, streamed, and binary responses unchanged', function (callable $responseFactory): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    $response = $this->middleware->handle($this->request, $responseFactory);

    expect($response->headers->get('Cache-Control'))->not->toBe('public, max-age=3600');
})->with([
    fn(): \Symfony\Component\HttpFoundation\Response => response()->json(['ok' => true]),
    function (): Response {
        $response = response('<h1>OK</h1>');
        $response->headers->set('Content-Encoding', 'gzip');

        return $response;
    },
    fn(): RedirectResponse => new RedirectResponse('/next'),
    fn(): StreamedResponse => new StreamedResponse(fn(): string => 'stream'),
    fn(): BinaryFileResponse => new BinaryFileResponse(__FILE__),
]);

it('does not change application configuration or authorize the request', function (): void {
    config(['session.driver' => 'file']);
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    $this->middleware->handle($this->request, fn(): Response => response('<h1>OK</h1>'));

    expect(config('session.driver'))->toBe('file')
        ->and($this->request->attributes->get(SpiderContext::REQUEST_ATTRIBUTE)->isSpider())->toBeTrue();
});
