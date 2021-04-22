<?php

namespace R4nkt\Saasparilla\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Jetstream\JetstreamServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use R4nkt\Saasparilla\SaasparillaServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'R4nkt\\Saasparilla\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SaasparillaServiceProvider::class,
            JetstreamServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/Database/Migrations/2014_10_12_000000_create_users_table.php';
        (new \CreateUsersTable())->up();
        include_once __DIR__.'/../database/migrations/add_deletion_marker_columns_to_users_table.php.stub';
        (new \AddDeletionMarkerColumnsToUsersTable())->up();

        \Laravel\Jetstream\Jetstream::deleteUsersUsing(\R4nkt\Saasparilla\Tests\TestClasses\Actions\Jetstream\DeleteUser::class);
        \Laravel\Jetstream\Jetstream::deleteTeamsUsing(\R4nkt\Saasparilla\Tests\TestClasses\Actions\Jetstream\DeleteTeam::class);
    }
}
