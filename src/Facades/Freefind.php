<?php

namespace Freefind\Freefind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Freefind\Freefind\Freefind
 */
class Freefind extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Freefind\Freefind\Freefind::class;
    }
}
