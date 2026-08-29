<?php

declare(strict_types=1);

namespace Freefind\Freefind\Http\Middleware;

use Closure;
use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Spider\SpiderContext;
use Freefind\Freefind\Spider\SpiderDetector;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DetectFreefindSpider
{
    public function __construct(
        private readonly SpiderDetector $detector,
        private readonly SpiderSettings $settings,
    ) {}

    /**
     * @param  Closure(Request): mixed  $next
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

        $contentType = strtolower($response->headers->get('Content-Type', ''));

        return $contentType === '' || str_starts_with($contentType, 'text/html');
    }
}
