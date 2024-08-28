<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsPosition;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsSingleImage;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsTranslatableTextContents;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;

/**
 * @property Section $model
 */
class SectionBuilder extends SiteComponentBuilder
{
    use BuildsSingleImage, BuildsPosition, BuildsTranslatableTextContents;

    final public function __construct(Section $section)
    {
        parent::__construct($section);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    /**
     * Create a new Section for a Page and return its SectionBuilder
     */
    public static function create(
        Page|string $page,
        string $uid,
        string|null $name = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        int $position = 0,
        array|null $data = null,
        string|null $imageFromMediaCatalog = null,
        string $imageAlt = '',
    ): static
    {
        return static::buildSection(
            page($page)->sections()->create(['uid' => $uid]),
            $name,
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
     * Create a new Section for the site and return its SectionBuilder
     */
    public static function createGenericSection(
        string $uid,
        string|null $name = null,
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
        return static::buildSection(
            site($site)->sections()->create(['uid' => $uid]),
            $name,
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
     * Create a new SectionBuilder for an existing section
     */
    public static function from(Section|string $section): static
    {
        return new static(section($section));
    }

    //--- Static API --------------------------------------------------------------------------------------------------

    public static function deleteSection(Section|string $section): void
    {
        static::from($section)->delete();
    }

    //--- Section Bits ------------------------------------------------------------------------------------------------

    public function createBit(
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
        callable|null $build = null
    ): static
    {

        $builder = BitBuilder::create(
            section: $this->model,
            uid: $uid,
            name: $name,
            type: $type,
            title: $title,
            short: $short,
            contents: $contents,
            position: $position,
            data: $data,
            imageFromMediaCatalog: $imageFromMediaCatalog,
            imageAlt: $imageAlt
        );

        //if a $build callback is provided, call it with the new BitBuilder instance
        if ($build)
            call_user_func($build, $builder);

        return $this;
    }

    public function deleteBit(Bit|string $bit): static
    {
        $this->bitBuilder($bit)->delete();
        return $this;
    }

    public function deleteBits(string|null $type = null): static
    {
        $bits = $type ? $this->model->bits()->where('type', $type)->get() : $this->model->bits;
        foreach ($bits as $bit)
            $this->deleteBit($bit);

        return $this;
    }

    public function delete(): void
    {
        //delete all bits
        $this->deleteBits();

        //delete the section
        $this->model->delete();
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function bitBuilder(BitBuilder|Bit|string $bitOrBuilder): BitBuilder
    {
        return $bitOrBuilder instanceof BitBuilder ? $bitOrBuilder : BitBuilder::from($bitOrBuilder);
    }

    protected static function buildSection(
        Section $section,
        string|null $name = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        int $position = 0,
        array|null $data = null,
        string|null $imageFromMediaCatalog = null,
        string $imageAlt = '',
    ): static
    {
        //add the rest of the data to the model, using the fluent interface
        $builder = (new static($section))
            ->massAssignFluently(
                compact('name', 'title', 'short', 'contents', 'position', 'data')
            )
            ->save();

        if ($imageFromMediaCatalog)
            $builder->withImageFromMediaCatalog($imageFromMediaCatalog, $imageAlt);

        return $builder;
    }
}
