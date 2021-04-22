<?php

namespace R4nkt\Saasparilla;

use R4nkt\Saasparilla\Actions\Contracts\NotifiesUser;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\Exceptions\InvalidConfiguration;

class NotifierFactory
{
    use UsesSaasparillaConfig;

    public static function make(string $name): NotifiesUser
    {
        if (! $config = self::notifier($name)) {
            InvalidConfiguration::missingNotifier($name);
        }

        $class = $config['class'];

        return (new $class)
            ->setParams($config['params'] ?? []);
    }
}
