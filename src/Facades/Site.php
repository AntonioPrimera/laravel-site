<?php

namespace AntonioPrimera\Site\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AntonioPrimera\Site\Site
 */
class Site extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AntonioPrimera\Site\Site::class;
    }
}
