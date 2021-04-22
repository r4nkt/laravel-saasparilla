<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use R4nkt\Saasparilla\Actions\Contracts\MarksResource;
use R4nkt\Saasparilla\UnmarkerFactory;

trait HasUnmarker
{
    protected function unmarker(): MarksResource
    {
        return UnmarkerFactory::make($this->requiredParam('unmarker'));
    }
}
