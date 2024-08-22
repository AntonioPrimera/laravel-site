<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\HandleSiteComponentSingleImage;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

/**
 * @property Section $siteComponent
 */
class SectionBuilder extends SiteComponentBuilder
{
    use HandleSiteComponentSingleImage;

    final public function __construct(Section $section)
    {
        parent::__construct($section);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    public static function create(
        string $uid,
        string $name,
        string|null $title = null,
        string|null $contents = null,
        array|null $config = null
    ): static
    {
        $section = Section::create([
            'uid' => $uid,
            'name' => $name,
            'title' => $title,
            'contents' => $contents,
            'config' => $config,
        ]);

        return new static($section);
    }

    /**
     * Create a new SectionBuilder instance from an existing
     * section instance or an existing section uid
     */
    public static function from(Section|string $section): static
    {
        $sectionInstance = is_string($section) ? Section::where('uid', $section)->firstOrFail() : $section;
        return new static($sectionInstance);
    }

    //--- Static API --------------------------------------------------------------------------------------------------

    public static function deleteSection(Section|string $section): void
    {
        static::from($section)->delete();
    }

    //--- Set Section Data --------------------------------------------------------------------------------------------

    public function withName(string $name): static
    {
        $this->siteComponent->name = $name;
        return $this;
    }

    public function withTitle(string $title): static
    {
        $this->siteComponent->title = $title;
        return $this;
    }

    public function withContents(string $contents): static
    {
        $this->siteComponent->contents = $contents;
        return $this;
    }

    //--- Section Bits ------------------------------------------------------------------------------------------------

    public function createBit(
        string|callable|null $uid = null,
        string|null $type = null,
        string|null $name = null,
        string|null $icon = null,
        string|null $title = null,
        string|null $contents = null,
        int $position = 0,
        array|null $config = null,
        string|null $mediaCatalogImage = null,
        string $imageAlt = '',
        callable|null $build = null
    ): static
    {

        //if the first argument is a callable, it's the $build callback
        if (is_callable($uid)) {
            $build = $uid;
            $uid = null;
        }

        $builder = BitBuilder::create(
            section: $this->siteComponent,
            uid: $uid,
            type: $type,
            name: $name,
            icon: $icon,
            title: $title,
            contents: $contents,
            position: $position,
            config: $config
        );

        if ($mediaCatalogImage)
            $builder->withImageFromMediaCatalog($mediaCatalogImage, $imageAlt);

        //if a $build callback is provided, call it with the new BitBuilder instance
        if ($build)
            $build($builder);

        return $this;
    }

    public function updateBit(Bit|string $bit, callable $update): static
    {
        $bitInstance = is_string($bit) ? $this->siteComponent->bits()->where('uid', $bit)->first() : $bit;
        if ($bitInstance)
            $update(BitBuilder::from($bitInstance));

        return $this;
    }

    public function deleteBit(Bit|string $bit): static
    {
        $bitInstance = is_string($bit) ? $this->siteComponent->bits()->where('uid', $bit)->first() : $bit;
        if ($bitInstance)
            $bitInstance->delete();

        return $this;
    }

    public function deleteBits(string|null $type = null): static
    {
        $bits = $type ? $this->siteComponent->bits()->where('type', $type)->get() : $this->siteComponent->bits;
        foreach ($bits as $bit)
            $bit->delete();

        return $this;
    }

    public function delete(): void
    {
        //delete all bits
        $this->deleteBits();

        //delete the section
        $this->siteComponent->delete();
    }
}
