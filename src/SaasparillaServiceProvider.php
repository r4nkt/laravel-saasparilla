<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Commands\DeleteUsersMarkedForDeletion;
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
            ->hasMigrations(['add_deletion_marker_columns_to_users_table'])
            ->hasCommand(DeleteUsersMarkedForDeletion::class);
    }

    public function registeringPackage()
    {
        $this->app->bind('saasparilla', function ($app) {
            return new Saasparilla();
        });
    }
}
