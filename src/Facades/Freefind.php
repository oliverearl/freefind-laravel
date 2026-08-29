<?php

declare(strict_types=1);

namespace Freefind\Freefind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Static proxy for the package's main Freefind service.
 *
 * @see \Freefind\Freefind\Freefind
 */
class Freefind extends Facade
{
    /**
     * Returns the container binding represented by this facade.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Freefind\Freefind\Freefind::class;
    }
}
