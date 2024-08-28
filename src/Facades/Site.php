<?php
namespace AntonioPrimera\Site\Facades;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site as SiteModel;
use AntonioPrimera\Site\SiteManager;
use Closure;
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
 * @method static Collection sitePages(SiteModel|string $site = 'default', Closure|null $filter = null)
 * @method static Collection siteSections(SiteModel|string $site = 'default', Closure|null $filter = null)
 * @method static Collection genericSections(SiteModel|string $site = 'default', Closure|null $filter = null)
 * @method static Collection siteBits(SiteModel|string $site = 'default', Closure|null $filter = null)
 * @method static Collection genericBits(SiteModel|string $site = 'default', Closure|null $filter = null)
 * @method static Collection pageSections(Page|string $page, Closure|null $filter = null)
 * @method static Collection sectionBits(Section|string $section, Closure|null $filter = null)
 *
 * @method static Page sitePage(string $pageUid, SiteModel|string $site = 'default')
 * @method static Section siteSection(string $sectionUid, SiteModel|string $site = 'default')
 * @method static Section genericSection(string $sectionUid, SiteModel|string $site = 'default')
 * @method static Bit siteBit(string $bitUid, SiteModel|string $site = 'default')
 * @method static Bit genericBit(string $bitUid, SiteModel|string $site = 'default')
 * @method static Section pageSection(string $sectionUid, Page|string $page)
 * @method static Bit sectionBit(string $bitUid, Section|string $section)
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
