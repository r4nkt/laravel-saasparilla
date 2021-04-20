<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\NotifierFactory;

trait HasNotifier
{
    protected function notifier()
    {
        return NotifierFactory::make($this->requiredParam('notifier'));
    }
}
