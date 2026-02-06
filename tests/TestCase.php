<?php

declare(strict_types=1);

namespace Freefind\Freefind\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;
use Freefind\Freefind\FreefindServiceProvider;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    /** @inheritDoc */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('freefind-laravel.site_id', $this->faker->randomNumber(7));
    }

    /** @inheritDoc */
    protected function getPackageProviders($app): array
    {
        return [
            FreefindServiceProvider::class,
        ];
    }
}
