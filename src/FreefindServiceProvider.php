<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FreefindServiceProvider extends PackageServiceProvider
{
    /**
     * This class is a Package Service Provider
     *
     * @see https://github.com/spatie/laravel-package-tools
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('freefind-laravel')
            ->hasConfigFile()
            ->hasViews();
    }

    /**
     * Override this method to perform any actions right after the package is registered.
     */
    #[Override]
    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $kernel = resolve(Kernel::class);
        $kernel->pushMiddleware(DetectFreefindSpider::class);

        $this->app->when(Freefind::class)->needs('application')->give(fn(): Application => $this->app);
    }
}
