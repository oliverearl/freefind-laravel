<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Freefind\Freefind\Spider\SpiderContext;
use Freefind\Freefind\Spider\SpiderDetector;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FreefindServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('freefind-laravel')
            ->hasConfigFile()
            ->hasViews();
    }

    #[Override]
    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->bind(FreefindConfig::class, function (Application $app): FreefindConfig {
            $config = $app->make('config')->get('freefind-laravel', []);

            if (! is_array($config)) {
                throw new \RuntimeException('The freefind-laravel configuration must be an array.');
            }

            return FreefindConfig::fromConfig($config);
        });

        $this->app->singleton(SpiderDetector::class, fn(Application $app): SpiderDetector => SpiderDetector::fromSettings(
            $app->make(FreefindConfig::class)->spider,
        ));

        $this->app->bind(SpiderSettings::class, fn(Application $app): SpiderSettings => $app->make(
            FreefindConfig::class,
        )->spider);

        $this->app->scoped(SpiderContext::class, function (Application $app): SpiderContext {
            if (! $app->bound('request')) {
                return SpiderContext::notSpider();
            }

            $context = $app->make('request')->attributes->get(
                SpiderContext::REQUEST_ATTRIBUTE,
            );

            return $context instanceof SpiderContext ? $context : SpiderContext::notSpider();
        });

        $config = $this->app->make('config')->get('freefind-laravel', []);
        $spiderConfig = is_array($config) && is_array($config['spider'] ?? null)
            ? $config['spider']
            : [];

        if (($spiderConfig['middleware'] ?? false) === true) {
            $this->app->make(Kernel::class)->pushMiddleware(DetectFreefindSpider::class);
        }
    }

    #[Override]
    public function bootingPackage(): void
    {
        parent::bootingPackage();

        $this->app->make(Router::class)->aliasMiddleware('freefind.spider', DetectFreefindSpider::class);
    }
}
