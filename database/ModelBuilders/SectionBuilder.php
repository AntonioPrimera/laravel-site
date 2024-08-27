<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsPosition;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsSingleImage;
use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsTranslatableTextContents;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;

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
        //create the model with the minimum required data
        $section = page($page)->sections()->create(['uid' => $uid]);

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
            $this->updateBit($builder, $build);

        return $this;
    }

    public function updateBit(BitBuilder|Bit|string $bit, callable $build): static
    {
        $build($this->bitBuilder($bit));
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
}
