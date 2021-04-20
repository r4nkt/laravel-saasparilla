<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Actions\Contracts\MarksResource;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class MarkerFactory
{
    use UsesSaasparillaConfig;

    public static function make(string $name): MarksResource
    {
        if (! $config = self::marker($name)) {
            InvalidConfiguration::missingMarker($name);
        }

        return (new $config['class'])
            ->setParams($config['params'] ?? []);
    }
}
