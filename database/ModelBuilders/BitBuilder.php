<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsPosition;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsSingleImage;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsTranslatableTextContents;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;

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
        return static::buildBit(
            section($section)->bits()->create(['uid' => $uid]),
            $name,
            $type,
            $title,
            $short,
            $contents,
            $position,
            $data,
            $imageFromMediaCatalog,
            $imageAlt
        );
    }

    public static function createGenericBit(
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
        Site|string $site = 'default'
    ): static
    {
        return static::buildBit(
            site($site)->bits()->create(['uid' => $uid]),
            $name,
            $type,
            $title,
            $short,
            $contents,
            $position,
            $data,
            $imageFromMediaCatalog,
            $imageAlt
        );
    }

    /**
     * Create a new BitBuilder instance from an existing bit instance
     */
    public static function from(Bit|string $bit): static
    {
        return new static(bit($bit));
    }

    public static function deleteBit(Bit|string $bit): void
    {
        static::from($bit)->delete();
    }

    //--- Fluent building interface -----------------------------------------------------------------------------------

    public function withType(string $type): static
    {
        $this->model->type = $type;
        return $this;
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected static function buildBit(
        Bit $bit,
        string|null $name = null,
        string|null $type = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        int $position = 0,
        array|null $data = null,
        string|null $imageFromMediaCatalog = null,
        string $imageAlt = '',
    ): static
    {
        $builder = (new static($bit))
            ->massAssignFluently(
                compact('name', 'type', 'title', 'short', 'contents', 'position', 'data')
            )
            ->save();

        if ($imageFromMediaCatalog)
            $builder->withImageFromMediaCatalog($imageFromMediaCatalog, $imageAlt);

        return $builder;
    }
}
