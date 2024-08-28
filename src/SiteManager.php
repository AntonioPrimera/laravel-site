<?php
namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Exceptions\SiteComponentNotFoundException;
use AntonioPrimera\Site\Exceptions\SiteException;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;
use Closure;
use Illuminate\Support\Collection;

/**
 * The uid format for site components is as follows:
 * 'site-uid/page-uid:section-uid.bit-uid'
 * The uid for the site is optional (if not provided, the first site is used)
 */
class SiteManager {

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
        $siteUid = count($uidParts) > 1 ? $uidParts[0] : 'default';
        $pageUid = count($uidParts) > 1 ? $uidParts[1] : $uidParts[0];

        return $this->getSiteByUid($siteUid)?->pages()->where('uid', $pageUid)->firstOrFail();
    }

    public function getSectionByUid(string $uid): Section|null
    {
        $uid = explode(':', $uid, 2);
        $pageUid = count($uid) > 1 ? $uid[0] : null;
        $sectionUid = count($uid) > 1 ? $uid[1] : $uid[0];

        //get the page section if a page uid is provided, otherwise get the site section
        return $pageUid
            ? $this->getPageByUid($pageUid)?->sections()->where('uid', $sectionUid)->firstOrFail()
            : $this->getSiteByUid()->sections()->where('uid', $sectionUid)->firstOrFail();
    }

    public function getBitByUid(string $uid): Bit|null
    {
        $uid = explode('.', $uid, 2);
        $sectionUid = count($uid) > 1 ? $uid[0] : null;
        $bitUid = count($uid) > 1 ? $uid[1] : $uid[0];

        //get the section bit if a section uid is provided, otherwise get the site bit
        return $sectionUid
            ? $this->getSectionByUid($sectionUid)?->bits()->where('uid', $bitUid)->first()
            : $this->getSiteByUid()->bits()->where('uid', $bitUid)->first();
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

    /**
     * Get the pages belonging directly to a site, optionally filtered by a closure
     */
    public function sitePages(Site|string $site = 'default', Closure|null $filter = null): Collection
    {
        $pages = $this->site($site)->pages;
        return $filter ? $pages->filter($filter) : $pages;
    }

    /**
     * Get the sections belonging directly to a site (usually generic sections, reusable across multiple pages),
     * optionally filtered by a closure
     */
    public function siteSections(Site|string $site = 'default', Closure|null $filter = null): Collection
    {
        $sections = $this->site($site)->sections;
        return $filter ? $sections->filter($filter) : $sections;
    }

    /**
     * Syntactic sugar for siteSections(), because sections belonging directly
     * to the site are generic sections, reusable across multiple pages
     */
    public function genericSections(Site|string $site = 'default', Closure|null $filter = null): Collection
    {
        return $this->siteSections($site, $filter);
    }

    /**
     * Get the bits belonging directly to a site (usually generic bits, reusable across multiple sections),
     * optionally filtered by a closure
     */
    public function siteBits(Site|string $site = 'default', Closure|null $filter = null): Collection
    {
        $bits = $this->site($site)->bits;
        return $filter ? $bits->filter($filter) : $bits;
    }

    /**
     * Syntactic sugar for siteBits(), because bits belonging directly to
     * the site are generic bits, reusable across multiple sections
     */
    public function genericBits(Site|string $site = 'default', Closure|null $filter = null): Collection
    {
        return $this->siteBits($site, $filter);
    }

    /**
     * Get the sections belonging to a page, optionally filtered by a closure
     */
    public function pageSections(Page|string $page, Closure|null $filter = null): Collection
    {
        $sections = $this->page($page)->sections;
        return $filter ? $sections->filter($filter) : $sections;
    }

    /**
     * Get the bits belonging to a section, optionally filtered by a closure
     */
    public function sectionBits(Section|string $section, Closure|null $filter = null): Collection
    {
        $bits = $this->section($section)->bits;
        return $filter ? $bits->filter($filter) : $bits;
    }

    /**
     * Get a single page belonging directly to a site
     */
    public function sitePage(string $pageUid, Site|string $site = 'default'): Page
    {
        $page = $this->site($site)->pages->first(fn (Page $page) => $page->uid === $pageUid);

        if (!$page)
            throw new SiteComponentNotFoundException(
                "Page with uid '$pageUid' not found in site with uid '$site->uid'"
            );

        return $page;
    }

    /**
     * Get a single section belonging directly to a site (usually a generic section, reusable across multiple pages)
     */
    public function siteSection(string $sectionUid, Site|string $site = 'default'): Section
    {
        $section = $this->site($site)->sections->first(fn (Section $section) => $section->uid === $sectionUid);

        if (!$section)
            throw new SiteComponentNotFoundException(
                "Generic section with uid '$sectionUid' not found in site with uid '$site->uid'"
            );

        return $section;
    }

    /**
     * Syntactic sugar for siteSection(), because sections belonging directly
     * to the site are generic sections, reusable across multiple pages
     */
    public function genericSection(string $sectionUid, Site|string $site = 'default'): Section
    {
        return $this->siteSection($sectionUid, $site);
    }

    /**
     * Get a single bit belonging directly to a site (usually a generic bit, reusable across multiple sections)
     */
    public function siteBit(string $bitUid, Site|string $site = 'default'): Bit
    {
        $bit = $this->site($site)->bits->first(fn (Bit $bit) => $bit->uid === $bitUid);

        if (!$bit)
            throw new SiteComponentNotFoundException(
                "Generic bit with uid '$bitUid' not found in site with uid '$site->uid'"
            );

        return $bit;
    }

    /**
     * Syntactic sugar for siteBit(), because bits belonging directly to
     * the site are generic bits, reusable across multiple sections
     */
    public function genericBit(string $bitUid, Site|string $site = 'default'): Bit
    {
        return $this->siteBit($bitUid, $site);
    }

    /**
     * Get a single section belonging to a page
     */
    public function pageSection(string $sectionUid, Page|string $page): Section
    {
        $section = $this->page($page)->sections->first(fn (Section $section) => $section->uid === $sectionUid);

        if (!$section)
            throw new SiteComponentNotFoundException(
                "Section with uid '$sectionUid' not found in page with uid '$page->uid'"
            );

        return $section;
    }

    /**
     * Get a single bit belonging to a section
     */
    public function sectionBit(string $bitUid, Section|string $section): Bit
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
