<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Actions\Contracts\DeletesResource;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class DeleterFactory
{
    use UsesSaasparillaConfig;

    public static function make(string $name): DeletesResource
    {
        if (! $config = self::deleter($name)) {
            InvalidConfiguration::missingDeleter($name);
        }

        return (new $config['class'])
            ->setParams($config['params'] ?? []);
    }
}
