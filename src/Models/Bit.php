<?php
namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasPosition;
use AntonioPrimera\Site\Models\Traits\HasSingleImage;
use AntonioPrimera\Site\Models\Traits\HasTextContents;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

/**
 * Properties
 * @property int $section_id
 * @property string|null $type
 *
 * Relations
 * @property Section $section
 */
class Bit extends SiteComponent implements HasMedia
{
    use HasTextContents, HasSingleImage, HasPosition;

    //--- Relations ---------------------------------------------------------------------------------------------------

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
