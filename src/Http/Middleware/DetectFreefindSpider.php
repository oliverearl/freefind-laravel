<?php

declare(strict_types=1);

namespace Freefind\Freefind\Http\Middleware;

use Closure;
use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Spider\SpiderContext;
use Freefind\Freefind\Spider\SpiderDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Detects FreeFind spiders and applies an opt-in cache policy to eligible HTML responses.
 *
 * The detected context is informational and never grants access to protected content.
 */
final class DetectFreefindSpider
{
    /**
     * Creates middleware with the configured detector and response policy.
     */
    public function __construct(
        private readonly SpiderDetector $detector,
        private readonly SpiderSettings $settings,
    ) {}

    /**
     * Stores request-scoped detection state and applies cache policy after the response is built.
     *
     * @param Closure(Request): mixed $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $matchedUserAgent = $this->detector->detect($request->userAgent());
        $context = $matchedUserAgent === null
            ? SpiderContext::notSpider()
            : SpiderContext::detected($matchedUserAgent);

        $request->attributes->set(SpiderContext::REQUEST_ATTRIBUTE, $context);

        $response = $next($request);

        if ($context->isSpider() && $this->canApplyCachePolicy($response)) {
            $response->headers->set('Cache-Control', $response->headers->get('Cache-Control') ?? $this->settings->cacheControl);
        }

        return $response;
    }

    /**
     * Determines whether the response is an unencoded, non-streamed HTML response.
     */
    private function canApplyCachePolicy(mixed $response): bool
    {
        if (
            ! $response instanceof Response
            || $response instanceof BinaryFileResponse
            || $response instanceof RedirectResponse
            || $response instanceof StreamedResponse
            || $response->headers->has('Content-Encoding')
        ) {
            return false;
        }

        $contentType = Str::lower($response->headers->get('Content-Type', ''));

        return $contentType === '' || Str::startsWith($contentType, 'text/html');
    }
}
