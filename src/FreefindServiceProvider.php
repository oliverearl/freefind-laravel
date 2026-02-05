<?php

namespace Freefind\Freefind;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Freefind\Freefind\Commands\FreefindCommand;

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
