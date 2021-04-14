<?php

namespace R4nkt\Saasparilla\Actions\Users;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Actions\Contracts\Warn;
use R4nkt\Saasparilla\Mail\UserInactivityMail;

class WarnInactiveUsers implements Warn
{
    protected string $emailAttribute;
    protected string $mailable;

    public function __construct(array $params)
    {
        $this->emailAttribute = $params['email_attribute'] ?? 'email';
        $this->mailable = $params['mailable_class'] ?? UserInactivityMail::class;
    }

    public function warn(Collection $users): void
    {
        $users->each(function ($user) {
            dump($user->{$this->emailAttribute});
            Mail::to($user->{$this->emailAttribute})->queue(new $this->mailable($user));
        });
    }
}
