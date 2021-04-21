<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\NotifiesUser;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class DeletingUnverifiedUserSoonNotifier implements NotifiesUser
{
    use HasParams;

    public function notify($user): void
    {
        $emailAttribute = $this->param('email_attribute', 'email');

        if (! $mailable = $this->param('mailable')) {
            InvalidConfiguration::missingRequiredParam('mailable');
        }

        Mail::to($user->{$emailAttribute})->queue(new $mailable($user));
    }
}
