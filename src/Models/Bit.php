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

    /**
     * By default, a bit belongs to a section
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Generic bits, used in multiple sections, can belong directly to a site, instead of a section
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    //--- Abstract methods implementation -----------------------------------------------------------------------------

    /**
     * Get the fully qualified UID of the site component
     * e.g. for a bit it would have the structure: 'site-uid/page-uid:section-uid.bit-uid'
     */
    public function fullyQualifiedUid(): string
    {
        return $this->section->fullyQualifiedUid() . '.' . $this->uid;
    }
}
