<?php
namespace AntonioPrimera\Site\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $position
 */
trait HasPosition
{
    public static function bootHasPosition(): void
    {
        static::addGlobalScope('sortedByPosition', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }
}
