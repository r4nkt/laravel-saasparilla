<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\Actions\Contracts\MarksResource;
use R4nkt\Saasparilla\MarkerFactory;

trait HasMarker
{
    protected function marker(): MarksResource
    {
        return MarkerFactory::make($this->requiredParam('marker'));
    }
}
