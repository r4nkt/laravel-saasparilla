<?php

namespace R4nkt\Saasparilla\Concerns;

/**
 * @deprecated Not currently used.
 */
trait UsesSaasparillaConfig
{
    public static function cleaner(string $name): ?array
    {
        return config("saasparilla.cleaners.{$name}");
    }

    public static function culler(string $name): ?array
    {
        return config("saasparilla.cullers.{$name}");
    }

    public static function deleter(string $name): ?array
    {
        return config("saasparilla.deleters.{$name}");
    }

    public static function getter(string $name): ?array
    {
        return config("saasparilla.getters.{$name}");
    }

    public static function marker(string $name): ?array
    {
        return config("saasparilla.markers.{$name}");
    }

    public static function notifier(string $name): ?array
    {
        return config("saasparilla.notifiers.{$name}");
    }

    public static function purger(string $name): ?array
    {
        return config("saasparilla.purgers.{$name}");
    }

    public static function unmarker(string $name): ?array
    {
        return config("saasparilla.unmarkers.{$name}");
    }
}
