<?php
namespace AntonioPrimera\Site\Facades;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site as SiteModel;
use AntonioPrimera\Site\SiteManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SiteModel getSiteByUid(string $uid = 'default')
 * @method static Page getPageByUid(string $uid)
 * @method static Section getSectionByUid(string $uid)
 * @method static Bit getBitByUid(string $uid)
 *
 * @method static SiteModel site(SiteModel|string $site = 'default')
 * @method static Page page(Page|string $page)
 * @method static Section section(Section|string $section)
 * @method static Bit bit(Bit|string $bit)
 *
 * @method static Collection sitePages(SiteModel|string $site = 'default')
 * @method static Collection pageSections(Page|string $page)
 * @method static Collection sectionBits(Section|string $section)
 * @method static Page sitePage(SiteModel|string $site, string $pageUid)
 * @method static Section pageSection(Page|string $page, string $sectionUid)
 * @method static Bit sectionBit(Section|string $section, string $bitUid)
 *
 * @method static string currentLocale()
 * @method static string defaultLocale()
 * @method static string fallbackLocale()
 * @method static array allLocales()
 * @method static void setLocale(string $locale)
 * @method static bool isValidLocale(string $locale)
 */
class Site extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SiteManager::class;
    }
}
