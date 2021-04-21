<?php

namespace R4nkt\Saasparilla;

class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @param  string  $feature
     * @return bool
     */
    public static function enabled(string $feature)
    {
        return in_array($feature, config('saasparilla.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     *
     * @param  string  $feature
     * @param  string  $option
     * @return bool
     */
    public static function optionEnabled(string $feature, string $option)
    {
        return static::enabled($feature) &&
               config("saasparilla-options.{$feature}.{$option}") === true;
    }

    /**
     * Return the feature options if they exist or the specified feature option if it exists.
     *
     * @param  string  $feature
     * @return bool
     */
    public static function options(string $feature)
    {
        if (! static::enabled($feature)) {
            return null;
        }

        return config("saasparilla-options.{$feature}");
    }

    /**
     * Return the specified feature option if it exists.
     *
     * @param  string  $feature
     * @param  string  $option
     * @return bool
     */
    public static function option(string $feature, string $option)
    {
        if (! static::enabled($feature)) {
            return null;
        }

        return config("saasparilla-options.{$feature}.{$option}");
    }

    /**
     * Determine if the feature that allows marking unverified users for deletion is enabled.
     *
     * @return bool
     */
    public static function hasMarksUnverifiedUsersForDeletionFeature()
    {
        return static::enabled(static::marksUnverifiedUsersForDeletion());
    }

    /**
     * Determine if the feature that allows deleting unverified users is enabled.
     *
     * @return bool
     */
    public static function hasDeletesUnverifiedUsersFeature()
    {
        return static::enabled(static::deletesUnverifiedUsers());
    }

    /**
     * Enable the feature that allows marking unverified users for deletion.
     *
     * @return string
     */
    public static function marksUnverifiedUsersForDeletion(array $options = [])
    {
        if (! empty($options)) {
            config(['saasparilla-options.marks-unverified-users-for-deletion' => $options]);
        }

        return 'marks-unverified-users-for-deletion';
    }

    /**
     * Enable the feature that allows deleting unverified users.
     *
     * @return string
     */
    public static function deletesUnverifiedUsers(array $options = [])
    {
        if (! empty($options)) {
            config(['saasparilla-options.deletes-unverified-users' => $options]);
        }

        return 'deletes-unverified-users';
    }
}
