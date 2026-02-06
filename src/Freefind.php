<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;

class Freefind
{
    /**
     * The key that is registered in the container to indicate that the current request is a Freefind request.
     */
    public const string FREEFIND_REQUEST_INDICATOR_KEY = 'isFreefindRequest';

    /**
     * Create a new Freefind instance.
     */
    public function __construct(private readonly Application $application) {}

    /**
     * Determine if the current request is a Freefind request.
     */
    public function isFreeFindRequest(): bool
    {
        return $this->application->bound(self::FREEFIND_REQUEST_INDICATOR_KEY);
    }

    /**
     * Get the Freefind site ID from the configuration.
     */
    public function getSiteId(): int
    {
        return Config::integer('freefind-laravel.site_id');
    }
}
