<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Commands\SaasparillaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SaasparillaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-saasparilla')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_saasparilla_table')
            ->hasCommand(SaasparillaCommand::class);
    }
}
