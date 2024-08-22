<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Collection;

abstract class SectionComponent extends SiteComponent
{
    //set the default section uid for this component if the section is
    //always the same, so you don't have to pass it in the constructor
    public string|null $defaultSection = null;

	public Section $section;

	//the title and contents of the section
	public string|null $title;
	public string|null $contents;

	//the bits of the section (additional data containers)
	public Collection|null $bits;

    public function __construct(string|Section|null $section = null, array $config = [])
    {
		parent::__construct($config);

        //determine the section instance from the provided section or the default section
        $this->section = $this->determineSectionInstance($section ?: $this->defaultSection);
		$this->fillPropertiesWithSectionData($this->section);
    }

	public function bit(string $uid): Bit|null
	{
		return $this->bits->first(fn (Bit $bit) => $bit->uid === $uid);
	}

    public function bitsOfType(string $type): Collection
    {
        return $this->bits->filter(fn (Bit $bit) => $bit->type === $type);
    }

	//--- Protected helpers -------------------------------------------------------------------------------------------

	protected function determineSectionInstance(string|Section $section): Section
	{
		return $section instanceof Section
            ? $section
            : Section::where('uid', $section)->with('bits')->firstOrFail();
	}

	protected function fillPropertiesWithSectionData(Section $section): void
	{
		$this->title = $section->title;
		$this->contents = $section->contents;
		$this->bits = $section->bits;
	}
}
