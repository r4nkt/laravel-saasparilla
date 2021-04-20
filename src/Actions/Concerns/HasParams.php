<?php

namespace R4nkt\Saasparilla\Actions\Concerns;

use Illuminate\Support\Arr;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

trait HasParams
{
    protected array $params;

    public function setParams(array $params = [])
    {
        $this->params = $params;

        return $this;
    }

    protected function param(string $key, $default = null)
    {
        return Arr::get($this->params, $key, $default);
    }

    protected function requiredParam(string $key, $default = null)
    {
        if (! Arr::has($this->params, $key)) {
            InvalidConfiguration::missingRequiredParam($key);
        }

        return Arr::get($this->params, $key, $default);
    }
}
