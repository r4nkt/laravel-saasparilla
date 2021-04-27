<?php

namespace R4nkt\Saasparilla;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use R4nkt\ResourceTidier\Support\Factories\TidierFactory;
use R4nkt\Saasparilla\Commands\DeleteUsersMarkedForDeletion;
use R4nkt\Saasparilla\Commands\MarkUnverifiedUsersForDeletion;
use R4nkt\Saasparilla\Features;
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
            ->hasCommands([
                DeleteUsersMarkedForDeletion::class,
                MarkUnverifiedUsersForDeletion::class,
            ]);
    }

    public function registeringPackage()
    {
        $this->app->bind('saasparilla', function () {
            return new Saasparilla();
        });
    }

    public function bootingPackage()
    {
        $this->setUpUnmarkingVerifiedUser();
        $this->setUpResourceTidierConfiguration();
    }

    protected function setUpUnmarkingVerifiedUser()
    {
        if (Features::hasCleansUpUnverifiedUsersFeature()) {
            Event::listen(function (Verified $event) {
                $options = Features::options(Features::cleansUpUnverifiedUsers());

                $tidier = TidierFactory::make($options['tidier']);

                $tidier->culler()->unmarker()->unmark($event->user);
            });
        }
    }

    /** @todo There must be a better way than this. */
    protected function setUpResourceTidierConfiguration()
    {
        $this->copyConfig('saasparilla.tidiers.unverified-users', 'resource-tidier.tidiers.unverified-users');
        $this->copyConfig('saasparilla.cullers.unverified-users', 'resource-tidier.cullers.unverified-users');
        $this->copyConfig('saasparilla.handlers.purge-culled-users', 'resource-tidier.handlers.purge-culled-users');
        $this->copyConfig('saasparilla.finders.unverified-users', 'resource-tidier.finders.unverified-users');
        $this->copyConfig('saasparilla.finders.users-ready-for-deletion', 'resource-tidier.finders.users-ready-for-deletion');
        $this->copyConfig('saasparilla.markers.user-for-deletion', 'resource-tidier.markers.user-for-deletion');
        $this->copyConfig('saasparilla.notifiers.culled-unverified-user', 'resource-tidier.notifiers.culled-unverified-user');
        $this->copyConfig('saasparilla.tasks.delete-user', 'resource-tidier.tasks.delete-user');
        $this->copyConfig('saasparilla.unmarkers.user-for-deletion', 'resource-tidier.unmarkers.user-for-deletion');
    }

    protected function copyConfig(string $source, string $destination)
    {
        config([$destination => config($source)]);
    }
}
