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
