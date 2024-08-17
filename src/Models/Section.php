<?php

namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasSingleImage;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

/**
 * Properties
 *
 * @property int $id
 * @property string $name
 * @property string $uid
 * @property string|null $title
 * @property string|null $contents
 *
 * Relations
 * @property Bit[]|EloquentCollection $bits
 */
class Section extends SiteComponent implements HasMedia
{
    use HasSingleImage;

    protected $guarded = [];

    public function bits(): HasMany
    {
        return $this->hasMany(Bit::class);
    }
}
