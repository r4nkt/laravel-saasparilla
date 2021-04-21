<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\Actions\Contracts\NotifiesUser;
use R4nkt\Saasparilla\NotifierFactory;

trait HasNotifier
{
    protected function notifier(): NotifiesUser
    {
        return NotifierFactory::make($this->requiredParam('notifier'));
    }
}
