<?php
namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Exceptions\SiteComponentNotFoundException;
use AntonioPrimera\Site\Exceptions\SiteException;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;
use Illuminate\Support\Collection;

/**
 * The uid format for site components is as follows:
 * 'site-uid/page-uid:section-uid.bit-uid'
 * The uid for the site is optional (if not provided, the first site is used)
 */
class SiteManager {

    protected array $translatable = ['title', 'short', 'contents'];

    //--- Site component getters by UID -------------------------------------------------------------------------------

    /**
     * For most projects, there should only be one site, so the uid is optional.
     */
    public function getSiteByUid(string $uid = 'default'): Site
    {
        return Site::where('uid', $uid)->firstOrFail();
    }

    public function getPageByUid(string $uid): Page|null
    {
        $uidParts = explode('/', $uid, 2);
        $siteUid = count($uidParts) > 1 ? $uidParts[0] : null;
        $pageUid = count($uidParts) > 1 ? $uidParts[1] : $uidParts[0];

        return $siteUid
            ? $this->getSiteByUid($siteUid)?->pages()->where('uid', $pageUid)->firstOrFail()
            : Page::where('uid', $uid)->firstOrFail();
    }

    public function getSectionByUid(string $uid): Section|null
    {
        $uid = explode(':', $uid, 2);
        $pageUid = count($uid) > 1 ? $uid[0] : null;
        $sectionUid = count($uid) > 1 ? $uid[1] : $uid[0];

        return $pageUid
            ? $this->getPageByUid($pageUid)?->sections()->where('uid', $sectionUid)->firstOrFail()
            : Section::where('uid', $uid)->firstOrFail();
    }

    public function getBitByUid(string $uid): Bit|null
    {
        $uid = explode('.', $uid, 2);
        $sectionUid = count($uid) > 1 ? $uid[0] : null;
        $bitUid = count($uid) > 1 ? $uid[1] : $uid[0];

        return $sectionUid
            ? $this->getSectionByUid($sectionUid)?->bits()->where('uid', $bitUid)->first()
            : Bit::where('uid', $uid)->first();
    }

    //--- Simplified site component getters ---------------------------------------------------------------------------

    public function site(Site|string $site = 'default'): Site
    {
        return $site instanceof Site ? $site : $this->getSiteByUid($site);
    }

    public function page(Page|string $page): Page
    {
        return is_string($page) ? $this->getPageByUid($page) : $page;
    }

    public function section(Section|string $section): Section
    {
        return is_string($section) ? $this->getSectionByUid($section) : $section;
    }

    public function bit(Bit|string $bit): Bit
    {
        return is_string($bit) ? $this->getBitByUid($bit) : $bit;
    }

    //--- Site component children -------------------------------------------------------------------------------------

    public function sitePages(Site|string $site = 'default'): Collection
    {
        return $site ? $this->site($site)->pages : Page::all();
    }

    public function pageSections(Page|string $page): Collection
    {
        return $this->page($page)->sections;
    }

    public function sectionBits(Section|string $section): Collection
    {
        return $this->section($section)->bits;
    }

    public function sitePage(Site|string $site, string $pageUid): Page
    {
        $page = $this->site($site)->pages->first(fn (Page $page) => $page->uid === $pageUid);

        if (!$page)
            throw new SiteComponentNotFoundException(
                "Page with uid '$pageUid' not found in site with uid '$site->uid'"
            );

        return $page;
    }

    public function pageSection(Page|string $page, string $sectionUid): Section
    {
        $section = $this->page($page)->sections->first(fn (Section $section) => $section->uid === $sectionUid);

        if (!$section)
            throw new SiteComponentNotFoundException(
                "Section with uid '$sectionUid' not found in page with uid '$page->uid'"
            );

        return $section;
    }

    public function sectionBit(Section|string $section, string $bitUid): Bit
    {
        $bit = $this->section($section)->bits->first(fn (Bit $bit) => $bit->uid === $bitUid);

        if (!$bit)
            throw new SiteComponentNotFoundException(
                "Bit with uid '$bitUid' not found in section with uid '$section->uid'"
            );

        return $bit;
    }

    //--- Locale management -------------------------------------------------------------------------------------------

    public function setLocale(string $locale): void
    {
        if (!$this->isValidLocale($locale))
            throw new SiteException("Locale '$locale' is not supported by the site");

        app()->setLocale($locale);
    }

    public function currentLocale(): string
    {
        return app()->getLocale();
    }

    public function defaultLocale(): string
    {
        return config('app.locale');
    }

    public function fallbackLocale(): string
    {
        return config('app.fallback_locale');
    }

    public function allLocales(): array
    {
        return config('site.translations.locales', fn() => array_unique([$this->defaultLocale(), $this->fallbackLocale(), $this->currentLocale()]));
    }

    public function isValidLocale(string $locale): bool
    {
        return in_array($locale, $this->allLocales());
    }
}
