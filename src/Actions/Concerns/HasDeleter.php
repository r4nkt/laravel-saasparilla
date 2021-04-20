<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\DeleterFactory;

trait HasDeleter
{
    protected function deleter()
    {
        return DeleterFactory::make($this->requiredParam('deleter'));
    }
}
