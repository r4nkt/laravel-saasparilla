<?php

namespace R4nkt\Saasparilla\Exceptions;

use Exception;

class FeatureNotEnabled extends Exception
{
    public static function featureNotEnabled(string $feature)
    {
        throw new static("The feature, {$feature}, has not been enabled.");
    }

    public static function marksUnverifiedUsersForDeletion()
    {
        self::featureNotEnabled('MarksUnverifiedUsersForDeletion');
    }

    public static function deletesUsersMarkedForDeletion()
    {
        self::featureNotEnabled('DeletesUsersMarkedForDeletion');
    }
}
