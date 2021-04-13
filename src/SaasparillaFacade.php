<?php

namespace R4nkt\Saasparilla;

use Illuminate\Support\Facades\Facade;

/**
 * @see \R4nkt\Saasparilla\Saasparilla
 */
class SaasparillaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-saasparilla';
    }
}
