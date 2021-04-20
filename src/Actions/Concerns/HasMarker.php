<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\MarkerFactory;

trait HasMarker
{
    protected function marker()
    {
        return MarkerFactory::make($this->requiredParam('marker'));
    }
}
