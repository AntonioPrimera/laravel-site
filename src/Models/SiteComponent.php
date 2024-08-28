<?php
namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Models\Traits\HasUnstructuredData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Properties
 * @property int $id
 * @property string $uid
 * @property string|null $name
 *
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
abstract class SiteComponent extends Model
{
    use HasUnstructuredData, HasTranslations;

    //unguard all attributes by default
    protected $guarded = [];

    //cached list of all translatable attributes
    protected array|null $allTranslatableAttributes = null;

    /**
     * Override the default translatable attributes getter from spatie,
     * to include translatable attributes provided by various traits
     */
    public function getTranslatableAttributes(): array
    {
        return $this->allTranslatableAttributes ??= $this->mergeTranslatableAttributes();
    }

    /**
     * Get all instance properties, starting with 'translatable' and merge them into a single array
     */
    protected function mergeTranslatableAttributes(): array
    {
        $translatableLists = array_filter(
            get_object_vars($this),
            fn($key) => str_starts_with($key, 'translatable') && is_array($this->{$key}),
            ARRAY_FILTER_USE_KEY
        );

        return array_merge(...array_values($translatableLists));
    }

    /**
     * Get the fully qualified UID of the site component
     * e.g. for a bit it would have the structure: 'site-uid/page-uid:section-uid.bit-uid'
     */
    public abstract function fullyQualifiedUid(): string;
}
