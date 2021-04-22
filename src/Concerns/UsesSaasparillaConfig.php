<?php

namespace R4nkt\Saasparilla\Concerns;

trait UsesSaasparillaConfig
{
    public static function deleter(string $name): array
    {
        return config("saasparilla.deleters.{$name}");
    }

    public static function getter(string $name): array
    {
        return config("saasparilla.getters.{$name}");
    }

    public static function marker(string $name): array
    {
        return config("saasparilla.markers.{$name}");
    }

    public static function notifier(string $name): array
    {
        return config("saasparilla.notifiers.{$name}");
    }

    public static function unmarker(string $name): array
    {
        return config("saasparilla.unmarkers.{$name}");
    }
}
