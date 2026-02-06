<?php

declare(strict_types=1);

namespace Freefind\Freefind\Http\Middleware;

use Closure;
use Freefind\Freefind\Freefind;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class DetectFreefindSpider
{
    /**
     * A list of known Freefind user agents.
     *
     * @var list<string>
     */
    public const array KNOWN_FREEFIND_USER_AGENTS = [
        'freefind/2.1',
    ];

    /**
     * Create a new instance of the middleware.
     */
    public function __construct(private readonly Application $application) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $userAgent = Str::lower($request->userAgent() ?? '');

        if (Str::contains($userAgent, self::KNOWN_FREEFIND_USER_AGENTS)) {
            $this->application->instance(Freefind::FREEFIND_REQUEST_INDICATOR_KEY, true);

            Config::set('session.driver', 'array');
            header('Cache-Control: public, max-age=3600');
        }

        return $next($request);
    }
}
