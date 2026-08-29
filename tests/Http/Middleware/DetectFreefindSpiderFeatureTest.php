<?php

declare(strict_types=1);

use Freefind\Freefind\Freefind;
use Freefind\Freefind\Spider\SpiderContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

it('keeps spider detection and response policy isolated across sequential requests', function (): void {
    Route::middleware('freefind.spider')->get('/spider-probe', function (Request $request): Response {
        $response = response($request->attributes->get(SpiderContext::REQUEST_ATTRIBUTE)->isSpider() ? 'spider' : 'browser');
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->remove('Cache-Control');

        return $response;
    });

    $spider = $this->withHeaders(['User-Agent' => 'FreeFind/2.1 crawler'])->get('/spider-probe');
    $browser = $this->withHeaders(['User-Agent' => 'Mozilla/5.0'])->get('/spider-probe');

    expect($spider->getContent())->toBe('spider')
        ->and($spider->headers->get('Cache-Control'))->toBe('max-age=3600, public')
        ->and($browser->getContent())->toBe('browser')
        ->and($browser->headers->get('Cache-Control'))->toBeNull()
        ->and(app(SpiderContext::class)->isSpider())->toBeFalse();
});

it('makes the request context available to a route handler through the opt-in alias', function (): void {
    Route::middleware('freefind.spider')->get('/spider-context', function (): Response {
        return response(resolve(Freefind::class)->isSpiderRequest() ? 'spider' : 'browser');
    });

    expect($this->withHeaders(['User-Agent' => 'freefind/2.1'])->get('/spider-context')->getContent())
        ->toBe('spider');
});
