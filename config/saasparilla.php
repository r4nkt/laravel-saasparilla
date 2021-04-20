<?php

use Illuminate\Foundation\Auth\User;
use R4nkt\Saasparilla\Actions\Default\DeleteUser;
use R4nkt\Saasparilla\Actions\Default\DeletingUnverifiedUserSoonNotifier;
use R4nkt\Saasparilla\Actions\Default\GetDeletableUsers;
use R4nkt\Saasparilla\Actions\Default\GetUnverifiedUsers;
use R4nkt\Saasparilla\Actions\Default\MarkUserForDeletion;
use R4nkt\Saasparilla\Actions\DeleteDeletableUsers;
use R4nkt\Saasparilla\Actions\MarkUsersForDeletion;
use R4nkt\Saasparilla\Features;
use R4nkt\Saasparilla\Mail\UnverifiedUserMarkedForDeletionMail;

return [

    /**
     * Features (not yet complete):
     *  - unverified users (default/jetstream)
     *     - marking/notifying
     *     - deleting
     *  - inactive users, aka unsubscribed/no-longer-on-trial users (default???/jetstream???)
     *     - marking/notifying
     *     - deleting
     *  - inactive teams, aka unsubscribed/no-longer-on-trial teams (default???/jetstream???)
     *     - marking/notifying
     *     - deleting
     *  - welcoming verified users
     */
    'features' => [

        /**
         * Finds unverified users and marks them for deletion.
         *
         * Required:
         *  - class
         *  - params:
         *     - getter
         *     - marker
         *     - notifier
         */
        Features::marksUnverifiedUsersForDeletion([
            'class' => MarkUsersForDeletion::class,
            'params' => [
                'getter' => 'unverified-users',
                'marker' => 'unverified-user-for-deletion',
                'notifier' => 'deleting-unverified-user-soon',
            ],
        ]),

        /**
         * Finds deletes unverified users that have been marked for deletion.
         *
         * Required:
         *  - class
         *  - params:
         *     - getter
         *     - deleter
         */
        Features::deletesUnverifiedUsers([
            'class' => DeleteDeletableUsers::class,
            'params' => [
                'getter' => 'deletable-users',
                'deleter' => 'users',
            ],
        ]),
    ],

    'deleters' => [
        'users' => [
            'class' => DeleteUser::class,
        ],
    ],

    'getters' => [
        'unverified-users' => [
            'class' => GetUnverifiedUsers::class,
            'params' => [
                'model' => User::class,
                'threshold' => 14,
            ],
        ],
        'deletable-users' => [
            'class' => GetDeletableUsers::class,
            'params' => [
                'model' => User::class,
            ],
        ],
    ],

    'markers' => [
        'unverified-user-for-deletion' => [
            'class' => MarkUserForDeletion::class,
            'params' => [
                'grace' => 30,
            ],
        ],
    ],

    'notifiers' => [
        'deleting-unverified-user-soon' => [
            'class' => DeletingUnverifiedUserSoonNotifier::class,
            'params' => [
                'mailable' => UnverifiedUserMarkedForDeletionMail::class,
                'email_attribute' => 'email',
            ],
        ],
    ],

];
