<?php

namespace R4nkt\Saasparilla;

class Features
{
    /**
     * Determine if the given feature is enabled.
     */
    public static function enabled(string $feature): bool
    {
        return in_array($feature, config('saasparilla.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     */
    public static function optionEnabled(string $feature, string $option): bool
    {
        return static::enabled($feature) &&
               config("saasparilla-options.{$feature}.{$option}") === true;
    }

    /**
     * Return the feature options if they exist or the specified feature option if it exists.
     */
    public static function options(string $feature): mixed
    {
        if (! static::enabled($feature)) {
            return null;
        }

        return config("saasparilla-options.{$feature}");
    }

    /**
     * Return the specified feature option if it exists.
     */
    public static function option(string $feature, string $option): mixed
    {
        if (! static::enabled($feature)) {
            return null;
        }

        return config("saasparilla-options.{$feature}.{$option}");
    }

    /**
     * Determine if the feature that cleans up unverified users is enabled.
     */
    public static function hasCleansUpUnverifiedUsersFeature(): bool
    {
        return static::enabled(static::cleansUpUnverifiedUsers());
    }

    /**
     * Enable the feature that cleans up unverified users.
     */
    public static function cleansUpUnverifiedUsers(array $options = []): string
    {
        if (! empty($options)) {
            config(['saasparilla-options.cleans-unverified-users' => $options]);
        }

        return 'cleans-unverified-users';
    }
}
