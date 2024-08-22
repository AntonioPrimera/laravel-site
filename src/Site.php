<?php
namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

class Site {

    //--- Sections and Bits -------------------------------------------------------------------------------------------

    public function getSection(string $uid): Section|null
    {
        return Section::where('uid', $uid)->first();
    }

    public function getBit(string $uid): Bit|null
    {
        $uid = explode('.', $uid, 2);
        return $this->getSection($uid[0])?->bits()->where('uid', $uid[1])->first();
    }

    //--- Locale management -------------------------------------------------------------------------------------------

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
}
