<?php

namespace R4nkt\Saasparilla;

use Illuminate\Support\Facades\Event;
use R4nkt\ResourceTidier\Support\Facades\ResourceTidier;
use R4nkt\Saasparilla\Commands\CullUnverifiedUsers;
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
            ->hasCommands([
                DeleteUsersMarkedForDeletion::class,
                CullUnverifiedUsers::class,
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
        $this->setUpResourceTidierConfiguration();
        $this->setUpUnmarkingVerifiedUser();
    }

    protected function setUpUnmarkingVerifiedUser()
    {
        if (Features::hasCleansUpUnverifiedUsersFeature()) {
            $options = Features::options(Features::cleansUpUnverifiedUsers());

            $event = $options['unmark_on'];
            $tidier = ResourceTidier::tidier($options['tidier']);

            Event::listen($event, function ($event) use ($tidier) {
                $tidier->unmark($event->user);
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
