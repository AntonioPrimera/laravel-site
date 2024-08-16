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
        string|null $uid,
        string|null $type,
        string|null $name,
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
        $sectionInstance = is_string($section) ? Section::where('uid', $section)->first() : $section;
        if (!$section)
            return;

        //delete all bits one by one, so that the bit model events are triggered and the media is deleted
        foreach ($sectionInstance->bits as $bit)
            $bit->delete();

        //delete the section
        $section->delete();
    }
}
