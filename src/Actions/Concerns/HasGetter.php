<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\Actions\Contracts\GetsResources;
use R4nkt\Saasparilla\GetterFactory;

trait HasGetter
{
    protected function getter(): GetsResources
    {
        return GetterFactory::make($this->requiredParam('getter'));
    }
}
