<?php
namespace AntonioPrimera\Site\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Relations
 * @property Page[]|EloquentCollection $pages
 */
class Site extends SiteComponent
{
    //--- Relations ---------------------------------------------------------------------------------------------------

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
