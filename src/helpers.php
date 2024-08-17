<?php

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

function currentLocale(): string
{
    return app()->getLocale();
}

function defaultLocale(): string
{
    return config('app.locale');
}

function fallbackLocale(): string
{
    return config('app.fallback_locale');
}

/**
 * Return a list of all locales that should be used for translations
 * e.g. ['en', 'de', 'es']
 * Make sure to include the default and fallback locale in your site.translations.locales config
 */
function allLocales(): array
{
    return config('site.translations.locales', array_unique([defaultLocale(), fallbackLocale(), currentLocale()]));
}

/**
 * Get a section by its uid (section uids are unique)
 */
function section(string $uid): Section|null
{
    return Section::where('uid', $uid)->first();
}

/**
 * Try to get a bit by its 'section-uid.bit-uid' string
 * (bit uids are nullable and not necessarily unique)
 */
function bit(string $uid): Bit|null
{
    $uid = explode('.', $uid, 2);
    return section($uid[0])?->bits()->where('uid', $uid[1])->first();
}
