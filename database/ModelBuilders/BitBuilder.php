<?php

namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsPosition;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsSingleImage;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsTranslatableTextContents;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Str;

/**
 * @property Bit $model
 */
class BitBuilder extends SiteComponentBuilder
{
    use BuildsSingleImage, BuildsPosition, BuildsTranslatableTextContents;

    final public function __construct(Bit $bit)
    {
        parent::__construct($bit);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    public static function create(
        Section|string $section,
        string $uid,
        string|null $name = null,
        string|null $type = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        int $position = 0,
        array|null $data = null,
        string|null $imageFromMediaCatalog = null,
        string $imageAlt = '',
    ): static {
        //create the model with the minimum required data
        $bit = section($section)->bits()->create(['uid' => $uid]);

        //add the rest of the data to the model, using the fluent interface
        $builder = (new static($bit))
            ->massAssignFluently(
                compact('name', 'type', 'title', 'short', 'contents', 'position', 'data')
            )
            ->save();

        if ($imageFromMediaCatalog)
            $builder->withImageFromMediaCatalog($imageFromMediaCatalog, $imageAlt);

        return $builder;
    }

    /**
     * Create a new BitBuilder instance from an existing bit instance
     */
    public static function from(Bit|string $bit): static
    {
        return new static(bit($bit));
    }

    //--- Set Bit Data --------------------------------------------------------------------------------------------

    public function withType(string $type): static
    {
        $this->model->type = $type;
        return $this;
    }
}
