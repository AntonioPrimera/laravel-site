<?php

use AntonioPrimera\Site\Facades\SiteManager;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;
use Illuminate\Support\Collection;

//--- Site facade locale helpers --------------------------------------------------------------------------------------

function currentLocale(): string
{
    return SiteManager::currentLocale();
}

function defaultLocale(): string
{
    return SiteManager::defaultLocale();
}

function fallbackLocale(): string
{
    return SiteManager::fallbackLocale();
}

/**
 * Return a list of all locales that should be used for translations
 * e.g. ['en', 'de', 'es']
 * Make sure to include the default and fallback locale in your site.translations.locales config
 */
function allLocales(): array
{
    return SiteManager::allLocales();
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

function site(Site|string|null $site = null): Site
{
    return SiteManager::site($site);
}

function page(Page|string $page): Page
{
    return SiteManager::page($page);
}

function section(Section|string $section): Section
{
    return SiteManager::section($section);
}

function bit(Bit|string $bit): Bit
{
    return SiteManager::bit($bit);
}

function pages(Site|string|null $site = null): Collection
{
    return SiteManager::sitePages($site);
}

function sections(Page|string $page): Collection
{
    return SiteManager::pageSections($page);
}

function bits(Section|string $section): Collection
{
    return SiteManager::sectionBits($section);
}
