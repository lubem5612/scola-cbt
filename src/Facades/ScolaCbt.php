<?php

namespace Transave\ScolaCbt\Facades;

use Illuminate\Support\Facades\Facade;

class ScolaCbt extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'scola-cbt';
    }
}
