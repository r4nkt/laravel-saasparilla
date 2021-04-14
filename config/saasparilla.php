<?php

use Illuminate\Foundation\Auth\User;
use R4nkt\Saasparilla\Actions\Users\FindInactiveUsers;
use R4nkt\Saasparilla\Actions\Users\WarnInactiveUsers;

return [

    /**
     * Inactivity Context Stack
     *
     * This is a list of "context stacks" that you can define for your app for
     * various Eloquent-based resources that might be inactive and that should
     * be deleted or otherwise dealt with if they are in such a state.
     *
     * When Saasparilla's ??? command is executed, it will iterate through this
     * list, finding, warning, marking, and deleting, as needed.
     */

    'inactive_contexts' => [
        'users' => [
            'model' => User::class,

            'actions' => [
                'find' => [
                    'class' => FindInactiveUsers::class,
                    'params' => [
                        'threshold' => 10,
                    ],
                ],
                'warn' => [
                    'class' => WarnInactiveUsers::class,
                    'params' => [
                        'email_attribute' => 'email',
                        'mailable_class' => UserInactivityMail::class,
                    ],
                ],
                'mark' => [
                    'class' => MarkInactiveUser::class,
                ],
                'delete' => [
                    'class' => DeleteInactiveUser::class,
                    'params' => [
                        'threshold' => 17,
                    ],
                ],
            ],
        ],
    ],
];
