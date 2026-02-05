<?php

namespace Freefind\Freefind;

use Freefind\Freefind\Commands\FreefindCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FreefindServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('freefind-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_freefind_laravel_table')
            ->hasCommand(FreefindCommand::class);
    }
}
