<?php

/** @noinspection HttpUrlsUsage */
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Freefind\Freefind\Freefind;
use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

beforeEach(function (): void {
    $this->middleware = new DetectFreefindSpider($this->app);
    $this->request = Request::create('/test');
});

it('detects freefind spider with standard user agent', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    $response = $this->middleware->handle($this->request, function (): Response {
        return response('OK');
    });

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue()
        ->and($this->app->make(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue()
        ->and(Config::get('session.driver'))->toBe('array')
        ->and($response->getContent())->toBe('OK');
});

it('detects freefind spider with case variations in user agent', function (): void {
    $this->request->headers->set('User-Agent', 'FreeFind/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue();
});

it('detects freefind spider when user agent contains additional information', function (): void {
    $this->request->headers->set('User-Agent', 'Mozilla/5.0 (compatible; freefind/2.1; +http://www.freefind.com/spider.html)');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue()
        ->and(Config::get('session.driver'))->toBe('array');
});

it('does not detect regular browser user agents', function (): void {
    $this->request->headers->set('User-Agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeFalse();
});

it('does not detect when user agent is missing', function (): void {
    // Don't set any user agent.

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeFalse();
});

it('does not detect similar but different user agents', function (): void {
    $this->request->headers->set('User-Agent', 'freefinder/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeFalse();
});

it('sets session driver to array when freefind spider is detected', function (): void {
    Config::set('session.driver', 'file'); // Set a different initial value
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect(Config::get('session.driver'))->toBe('array');
});

it('does not change session driver for regular requests', function (): void {
    Config::set('session.driver', 'file');
    $this->request->headers->set('User-Agent', 'Mozilla/5.0');

    passRequestToMiddleware($this->middleware, $this->request);

    expect(Config::get('session.driver'))->toBe('file');
});

it('passes the request through to the next middleware', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');
    $nextCalled = false;

    $response = $this->middleware->handle($this->request, function () use (&$nextCalled): Response {
        $nextCalled = true;
        return response('Next Middleware');
    });

    expect($nextCalled)->toBeTrue()
        ->and($response->getContent())->toBe('Next Middleware');
});

it('passes the correct request object to the next middleware', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');
    $receivedRequest = null;

    $this->middleware->handle($this->request, function (?Request $req) use (&$receivedRequest): Response {
        $receivedRequest = $req;
        return response('OK');
    });

    expect($receivedRequest)->toBe($this->request);
});

it('sets cache control header when freefind spider is detected', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    ob_start();
    passRequestToMiddleware($this->middleware, $this->request);
    ob_end_clean();

    /** @noinspection ForgottenDebugOutputInspection */
    $headers = xdebug_get_headers();
    $hasCacheControl = false;

    foreach ($headers as $header) {
        if (str_contains($header, 'Cache-Control: public, max-age=3600')) {
            $hasCacheControl = true;
            break;
        }
    }

    expect($hasCacheControl)->toBeTrue();
})->skip(function (): bool {
    return ! function_exists('xdebug_get_headers');
}, 'Xdebug is not available');

it('handles empty user agent string gracefully', function (): void {
    $this->request->headers->set('User-Agent', '');

    $response = $this->middleware->handle($this->request, function (): Response {
        return response('OK');
    });

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeFalse()
        ->and($response->getContent())->toBe('OK');
});

it('can detect multiple known freefind user agents', function (): void {
    $userAgents = DetectFreefindSpider::KNOWN_FREEFIND_USER_AGENTS;

    expect($userAgents)->toBeArray()
        ->and(count($userAgents))->toBeGreaterThan(0);

    foreach ($userAgents as $userAgent) {
        // Reset the app binding for each iteration:
        if ($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY)) {
            $this->app->offsetUnset(Freefind::FREEFIND_REQUEST_INDICATOR_KEY);
        }

        $request = Request::create('/test');
        $request->headers->set('User-Agent', $userAgent);

        $this->middleware->handle($request, function (): Response {
            return response('OK');
        });

        expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))
            ->toBeTrue("Failed to detect user agent: {$userAgent}");
    }
});

it('stores boolean true as the indicator value', function (): void {
    $this->request->headers->set('User-Agent', 'freefind/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->make(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))
        ->toBeTrue()
        ->and($this->app->make(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue();
});

it('works correctly with uppercase user agent header', function (): void {
    $this->request->headers->set('User-Agent', 'FREEFIND/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue();
});

it('works correctly with mixed case user agent header', function (): void {
    $this->request->headers->set('User-Agent', 'FrEeFiNd/2.1');

    passRequestToMiddleware($this->middleware, $this->request);

    expect($this->app->bound(Freefind::FREEFIND_REQUEST_INDICATOR_KEY))->toBeTrue();
});

/**
 * Helper function to pass a request through the middleware and capture the response.
 */
function passRequestToMiddleware(DetectFreefindSpider $middleware, Request $request): void
{
    $middleware->handle($request, function (): Response {
        return response('OK');
    });
}
