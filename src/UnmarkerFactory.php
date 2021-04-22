<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Actions\Contracts\UnmarksResource;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class UnmarkerFactory
{
    use UsesSaasparillaConfig;

    public static function make(string $name): UnmarksResource
    {
        if (! $config = self::unmarker($name)) {
            InvalidConfiguration::missingUnmarker($name);
        }

        $class = $config['class'];

        return (new $class)
            ->setParams($config['params'] ?? []);
    }
}
