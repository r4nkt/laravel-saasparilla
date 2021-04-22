<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\Actions\Contracts\DeletesResource;
use R4nkt\Saasparilla\DeleterFactory;

trait HasDeleter
{
    protected function deleter(): DeletesResource
    {
        return DeleterFactory::make($this->requiredParam('deleter'));
    }
}
