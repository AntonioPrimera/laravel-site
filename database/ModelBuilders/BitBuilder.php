<?php

namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\HandleSiteComponentSingleImage;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

/**
 * @property Bit $siteComponent
 */
class BitBuilder extends SiteComponentBuilder
{
    use HandleSiteComponentSingleImage;

    final public function __construct(Bit $bit)
    {
        parent::__construct($bit);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    public static function create(
        Section|string $section,
        ?string $uid,
        ?string $type,
        ?string $name,
        ?string $icon = null,
        ?string $title = null,
        ?string $contents = null,
        int $position = 0,
        ?array $config = null
    ): static {
        $sectionInstance = is_string($section) ? Section::where('uid', $section)->firstOrFail() : $section;

        $bit = $sectionInstance->bits()->create([
            'uid' => $uid,
            'type' => $type,
            'name' => $name,
            'icon' => $icon,
            'title' => $title,
            'contents' => $contents,
            'position' => $position,
            'config' => $config,
        ]);

        return new static($bit);
    }

    /**
     * Create a new BitBuilder instance from an existing bit instance
     */
    public static function from(Bit $bit): static
    {
        return new static($bit);
    }

    //--- Set Bit Data --------------------------------------------------------------------------------------------

    public function withUid(string $uid): static
    {
        $this->siteComponent->uid = $uid;

        return $this;
    }

    public function withType(string $type): static
    {
        $this->siteComponent->type = $type;

        return $this;
    }

    public function withName(string $name): static
    {
        $this->siteComponent->name = $name;

        return $this;
    }

    public function withIcon(string $icon): static
    {
        $this->siteComponent->icon = $icon;

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

    public function withPosition(int $position): static
    {
        $this->siteComponent->position = $position;
        return $this;
    }
}
