<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\GetterFactory;

trait HasGetter
{
    protected function getter()
    {
        return GetterFactory::make($this->requiredParam('getter'));
    }
}
