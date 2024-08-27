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
 * @property int $site_id
 * @property string $route
 * @property array|null $menu_label
 * @property bool $menu_visible
 * @property int $menu_position
 *
 * Relations
 * @property Section[]|EloquentCollection $sections
 * @property Site $site
 */
class Page extends SiteComponent
{
    use HasTextContents;

    protected array $translatable = ['menu_label'];

    protected static function booted(): void
    {
        static::mergeCasts([
            'menu_visible' => 'boolean',
        ]);
    }

    //--- Relations ---------------------------------------------------------------------------------------------------

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
