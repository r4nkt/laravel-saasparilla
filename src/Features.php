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
     * Determine if the feature that allows marking unverified users for deletion is enabled.
     */
    public static function hasMarksUnverifiedUsersForDeletionFeature(): bool
    {
        return static::enabled(static::marksUnverifiedUsersForDeletion());
    }

    /**
     * Determine if the feature that allows deleting users marked for deletion is enabled.
     */
    public static function hasDeletesUsersMarkedForDeletionFeature(): bool
    {
        return static::enabled(static::deletesUsersMarkedForDeletion());
    }

    /**
     * Enable the feature that allows marking unverified users for deletion.
     */
    public static function marksUnverifiedUsersForDeletion(array $options = []): string
    {
        if (! empty($options)) {
            config(['saasparilla-options.marks-unverified-users-for-deletion' => $options]);
        }

        return 'marks-unverified-users-for-deletion';
    }

    /**
     * Enable the feature that allows deleting users marked for deletion.
     */
    public static function deletesUsersMarkedForDeletion(array $options = []): string
    {
        if (! empty($options)) {
            config(['saasparilla-options.deletes-users-marked-for-deletion' => $options]);
        }

        return 'deletes-users-marked-for-deletion';
    }
}
