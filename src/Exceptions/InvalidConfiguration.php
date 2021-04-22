<?php

namespace R4nkt\Saasparilla\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function missingDeleter(string $name)
    {
        throw new self("Deleter with name, {$name}, was not found.");
    }

    public static function missingGetter(string $name)
    {
        throw new self("Getter with name, {$name}, was not found.");
    }

    public static function missingMarker(string $name)
    {
        throw new self("Marker with name, {$name}, was not found.");
    }

    public static function missingNotifier(string $name)
    {
        throw new self("Notifier with name, {$name}, was not found.");
    }

    public static function missingRequiredParam(string $key)
    {
        throw new self("Required parameter with key, {$key}, was not found.");
    }

    public static function missingUnmarker(string $name)
    {
        throw new self("Unmarker with name, {$name}, was not found.");
    }
}
