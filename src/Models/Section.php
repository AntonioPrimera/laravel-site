<?php
namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasPosition;
use AntonioPrimera\Site\Models\Traits\HasSingleImage;
use AntonioPrimera\Site\Models\Traits\HasTextContents;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

/**
 * Properties
 * @property int $page_id
 *
 * Relations
 * @property Bit[]|EloquentCollection $bits
 * @property Page $page
 */
class Section extends SiteComponent implements HasMedia
{
    use HasTextContents, HasSingleImage, HasPosition;

    //--- Relations ---------------------------------------------------------------------------------------------------

    public function bits(): HasMany
    {
        return $this->hasMany(Bit::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
