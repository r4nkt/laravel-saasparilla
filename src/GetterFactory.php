<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Actions\Contracts\GetsResources;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class GetterFactory
{
    use UsesSaasparillaConfig;

    public static function make(string $name): GetsResources
    {
        if (! $config = self::getter($name)) {
            InvalidConfiguration::missingGetter($name);
        }

        return (new $config['class'])
            ->setParams($config['params'] ?? []);
    }
}
