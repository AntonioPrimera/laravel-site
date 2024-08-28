<?php

use AntonioPrimera\Site\Facades\Site as SiteFacade;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site as SiteModel;
use Illuminate\Support\Collection;

//--- Site facade locale helpers --------------------------------------------------------------------------------------

function currentLocale(): string
{
    return SiteFacade::currentLocale();
}

function defaultLocale(): string
{
    return SiteFacade::defaultLocale();
}

function fallbackLocale(): string
{
    return SiteFacade::fallbackLocale();
}

/**
 * Return a list of all locales that should be used for translations
 * e.g. ['en', 'de', 'es']
 * Make sure to include the default and fallback locale in your site.translations.locales config
 */
function allLocales(): array
{
    return SiteFacade::allLocales();
}

function locale(string|null $locale): string
{
    if (!$locale)
        return currentLocale();

    if (!in_array($locale, allLocales()))
        throw new \Exception("Locale '$locale' is not supported by the site");

    return $locale;
}

//--- SiteManager component getters -----------------------------------------------------------------------------------

function site(SiteModel|string $site = 'default'): SiteModel
{
    return SiteFacade::site($site);
}

function page(Page|string $page): Page
{
    return SiteFacade::page($page);
}

function section(Section|string $section): Section
{
    return SiteFacade::section($section);
}

function bit(Bit|string $bit): Bit
{
    return SiteFacade::bit($bit);
}

function pages(SiteModel|string $site = 'default'): Collection
{
    return SiteFacade::sitePages($site);
}

function sections(Page|string $page): Collection
{
    return SiteFacade::pageSections($page);
}

function bits(Section|string $section): Collection
{
    return SiteFacade::sectionBits($section);
}

//--- Site settings ---------------------------------------------------------------------------------------------------

/**
 * Shortcut helper to get a site setting value
 */
function siteSettings(string $key, mixed $default = null): mixed
{
    return SiteFacade::settings($key, $default);
}

/**
 * Syntactic sugar for siteSettings()
 */
function settings(string $key, mixed $default = null): mixed
{
    return siteSettings($key, $default);
}
