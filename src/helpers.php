<?php

use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

//--- Site facade locale helpers --------------------------------------------------------------------------------------

function currentLocale(): string
{
    return Site::currentLocale();
}

function defaultLocale(): string
{
    return Site::defaultLocale();
}

function fallbackLocale(): string
{
    return Site::fallbackLocale();
}

/**
 * Return a list of all locales that should be used for translations
 * e.g. ['en', 'de', 'es']
 * Make sure to include the default and fallback locale in your site.translations.locales config
 */
function allLocales(): array
{
    return Site::allLocales();
}

//--- Site facade sections & bits helpers -----------------------------------------------------------------------------

/**
 * Get a section by its uid (section uids are unique)
 */
function section(string $uid): Section|null
{
    return Site::getSection($uid);
}

/**
 * Try to get a bit by its 'section-uid.bit-uid' string
 * (bit uids are nullable and not necessarily unique)
 */
function bit(string $uid): Bit|null
{
    return Site::getBit($uid);
}
