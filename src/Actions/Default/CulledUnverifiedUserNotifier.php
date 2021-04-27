<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Facades\Mail;
use R4nkt\ResourceTidier\Actions\Contracts\NotifiesResourceOwner;
use R4nkt\ResourceTidier\Concerns\HasParams;

class CulledUnverifiedUserNotifier implements NotifiesResourceOwner
{
    use HasParams;

    public function notify(mixed $resource): void
    {
        $emailAttribute = $this->requiredParam('email_attribute');

        $mailable = $this->requiredParam('mailable');

        Mail::to($resource->{$emailAttribute})->queue(new $mailable($resource));
    }
}
