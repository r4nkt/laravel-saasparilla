# R4nkt's Laravel Saasparilla

[![Latest Version on Packagist](https://img.shields.io/packagist/v/r4nkt/laravel-saasparilla.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-saasparilla)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/r4nkt/laravel-saasparilla/run-tests?label=tests)](https://github.com/r4nkt/laravel-saasparilla/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/r4nkt/laravel-saasparilla/Check%20&%20fix%20styling?label=code%20style)](https://github.com/r4nkt/laravel-saasparilla/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/r4nkt/laravel-saasparilla.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-saasparilla)

An opinionated collection of functionality to make Laravel SaaS creators' lives a little bit easier.

This package was developed to scratch an itch, but it was also inspired by [Freek](https://twitter.com/freekmurze) and his [blog post](https://freek.dev/1940-why-and-how-you-should-remove-inactive-users-and-teams) about removing inactive users and teams.

It should also be noted that it was build on another [r4nkt](https://twitter.com/r4nkt) [package](https://github.com/r4nkt/laravel-resource-tidier), which you may also want to use for any of your projects.

Finally, it's important to point out that this package benefitted from [Spatie's](https://spatie.be) [Laravel package skeleton package](https://github.com/spatie/package-skeleton-laravel).

## Installation

You can install the package via composer:

```bash
composer require r4nkt/laravel-saasparilla
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="R4nkt\Saasparilla\SaasparillaServiceProvider" --tag="laravel-saasparilla-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="R4nkt\Saasparilla\SaasparillaServiceProvider" --tag="laravel-saasparilla-config"
```

This is the contents of the published config file:

```php
<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use R4nkt\Saasparilla\Actions\Default\CulledUnverifiedUserNotifier;
use R4nkt\Saasparilla\Actions\Default\DeleteUser;
use R4nkt\Saasparilla\Actions\Default\FindUnverifiedUsers;
use R4nkt\Saasparilla\Actions\Default\FindUsersReadyForDeletion;
use R4nkt\Saasparilla\Actions\Default\MarkUserForDeletion;
use R4nkt\Saasparilla\Actions\Default\UnmarkUserMarkedForDeletion;
use R4nkt\Saasparilla\Features;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;

return [

    /**
     * Features (not yet complete):
     *  - cleans up unverified users (default/jetstream)
     *     - marking/notifying/unmarking
     *     - deleting
     *  - inactive users, aka unsubscribed/no-longer-on-trial users (default???/jetstream???)
     *     - marking/notifying/unmarking
     *     - deleting
     *  - inactive teams, aka unsubscribed/no-longer-on-trial teams (default???/jetstream???)
     *     - marking/notifying/unmarking
     *     - deleting
     *  - welcoming verified users
     */
    'features' => [

        /**
         * Cleans up unverified users:
         *  - finds users, marks them for deletion, and notifies them
         *  - unmarks users who verify email before grace period expires
         *  - deletes if still not verified after grace period expires
         */
        Features::cleansUpUnverifiedUsers([
            'tidier' => 'unverified-users',
            'unmark_on' => Verified::class,
        ]),

    ],

    'tidiers' => [
        /**
         * Culls unverified users and purges them after grace period expires.
         */
        'unverified-users' => [
            'culler' => 'unverified-users',
            'unmarker' => 'user-for-deletion',
            'handler' => 'purge-culled-users',
        ],
    ],

    'cullers' => [
        /**
         * Finds unverified users, marks them, and notifies them. Unmarks them
         * if/when they verify their email.
         */
        'unverified-users' => [
            'params' => [
                'finder' => 'unverified-users',
                'marker' => 'user-for-deletion',
                'notifier' => 'culled-unverified-user',
            ],
        ],
    ],

    'handlers' => [
        /**
         * Finds culled users and deletes them.
         */
        'purge-culled-users' => [
            'params' => [
                'finder' => 'users-ready-for-deletion',
                'task' => 'delete-user',
            ],
        ],
    ],

    'finders' => [
        /**
         * Finds users that have not verified their email.
         */
        'unverified-users' => [
            'class' => FindUnverifiedUsers::class,
            'params' => [
                'model' => User::class,
                'threshold' => 14,
            ],
        ],
        /**
         * Finds users that are marked for deletion.
         */
        'users-ready-for-deletion' => [
            'class' => FindUsersReadyForDeletion::class,
            'params' => [
                'model' => User::class,
            ],
        ],
    ],

    'markers' => [
        /**
         * Marks users for deletion, which will take place once the grace
         * period has expired.
         */
        'user-for-deletion' => [
            'class' => MarkUserForDeletion::class,
            'params' => [
                'grace' => 30,
            ],
        ],
    ],

    'notifiers' => [
        /**
         * Notifies unverified users that their accounts will be deleted once
         * the grace period has expired.
         */
        'culled-unverified-user' => [
            'class' => CulledUnverifiedUserNotifier::class,
            'params' => [
                'mailable' => CulledUnverifiedUserMail::class,
                'email_attribute' => 'email',
            ],
        ],
    ],

    'tasks' => [
        /**
         * Deletes the given user.
         */
        'delete-user' => [
            'class' => DeleteUser::class,
        ],
    ],

    'unmarkers' => [
        /**
         * Unmarks a user for deletion, effectively reversing the related
         * marker. Unless it's marked for deletion in some other context, it
         * will no longer be deleted.
         */
        'user-for-deletion' => [
            'class' => UnmarkUserMarkedForDeletion::class,
        ],
    ],

];
```

## Usage

To cull unverified users, execute the following Artisan command:

```bash
php artisan saasparilla:cull-unverified-users
```

To delete users that are ready to be deleted, execute the following Artisan command:

```bash
php artisan saasparilla:delete-users-ready-for-deletion
```

## Verified Users

By default, Saasparilla is configured to listen to Laravel's `Illuminate\Auth\Events\Verified` event. When fired, the user who has been verified will be unmarked. (By default, this will not impact users that were not previously marked for deletion.)

## Deleting Jetstream Users with Teams

If your project uses [Laravel Jetstream](https://jetstream.laravel.com) and is configured to use teams, then you will want to change your configuration so that the `delete-user` task uses the `R4nkt\Saasparilla\Actions\Jetstream\DeleteUser` class instead.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Travis Elkins](https://github.com/telkins)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
