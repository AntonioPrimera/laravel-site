<?php
namespace AntonioPrimera\Site\Database\Traits;

use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;

trait SiteStructureMigrationHelpers
{

    public function createSection(
        string $uid,
        string $name,
        string|null $title = null,
        string|null $contents = null,
        array|null $config = null,
    ): SectionBuilder
    {
        return SectionBuilder::create(
            uid: $uid,
            name: $name,
            title: $title,
            contents: $contents,
            config: $config
        );
    }

    public function createBit(
        Section $section,
        string|null $uid = null,
        string|null $type = null,
        string|null $name = null,
        string|null $title = null,
        string|null $contents = null,
        string|null $icon = null,
        int|null $position = null,
        array|null $config = null,
    ): BitBuilder
    {
        return BitBuilder::create(
            section: $section,
            uid: $uid,
            type: $type,
            name: $name,
            icon: $icon,
            title: $title,
            contents: $contents,
            position: $position ?? $section->bits()->max('position') + 1,
            config: $config
        );
    }

    //--- Object deletion ---------------------------------------------------------------------------------------------

    public function deleteSection(string|Section $section): void
    {
        SectionBuilder::deleteSection($section);
    }
}
