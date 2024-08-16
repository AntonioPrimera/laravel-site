<?php

namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasSingleImage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

/**
 * Properties
 *
 * @property int $id
 * @property int $section_id
 * @property string|null $uid
 * @property string|null $type
 * @property string|null $name
 * @property string|null $icon
 * @property string|null $title
 * @property string|null $contents
 * @property int $position
 *
 * Relations
 * @property Section $section
 */
class Bit extends SiteComponent implements HasMedia
{
    use HasSingleImage;

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
