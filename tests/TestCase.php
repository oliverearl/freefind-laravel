<?php

declare(strict_types=1);

namespace Freefind\Freefind\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Freefind\Freefind\FreefindServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @inheritDoc */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    /** @inheritDoc */
    protected function getPackageProviders($app): array
    {
        return [
            FreefindServiceProvider::class,
        ];
    }
}
