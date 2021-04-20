<?php

namespace R4nkt\Saasparilla\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \R4nkt\Saasparilla\Saasparilla
 */
class Saasparilla extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'saasparilla';
    }
}
