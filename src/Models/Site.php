<?php
namespace AntonioPrimera\Site\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Relations
 * @property Page[]|EloquentCollection $pages
 * @property Section[]|EloquentCollection $sections
 * @property Bit[]|EloquentCollection $bits
 */
class Site extends SiteComponent
{
    //--- Relations ---------------------------------------------------------------------------------------------------

    /**
     * By default, a site has multiple pages
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'site_id');
    }

    /**
     * A site can also have sections, which are not directly related to a single page, but are used in multiple pages
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'site_id');
    }

    /**
     * A site can also have bits, which are not directly related to a single section, but are used in multiple sections
     */
    public function bits(): HasMany
    {
        return $this->hasMany(Bit::class, 'site_id');
    }

    //--- Abstract methods implementation -----------------------------------------------------------------------------

    public function fullyQualifiedUid(): string
    {
        return $this->uid;
    }
}
