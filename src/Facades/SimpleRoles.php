<?php

namespace Alley\SimpleRoles\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleRoles extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'roles';
    }
}
