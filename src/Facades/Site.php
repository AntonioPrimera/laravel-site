<?php

namespace AntonioPrimera\Site\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \AntonioPrimera\Site\Models\Section|null getSection(string $uid)
 * @method static \AntonioPrimera\Site\Models\Bit|null getBit(string $uid)
 *
 * @method static string currentLocale()
 * @method static string defaultLocale()
 * @method static string fallbackLocale()
 * @method static array allLocales()
 */
class Site extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AntonioPrimera\Site\Site::class;
    }
}
