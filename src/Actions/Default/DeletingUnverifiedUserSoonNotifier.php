<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\NotifiesUser;

class DeletingUnverifiedUserSoonNotifier implements NotifiesUser
{
    use HasParams;

    public function notify(mixed $user): void
    {
        $emailAttribute = $this->requiredParam('email_attribute');

        $mailable = $this->requiredParam('mailable');

        Mail::to($user->{$emailAttribute})->queue(new $mailable($user));
    }
}
