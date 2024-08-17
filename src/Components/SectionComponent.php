<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Collection;

abstract class SectionComponent extends SiteComponent
{
	//override this if a section will always have the same uid,
    //so you don't have to pass it in the constructor
	protected string|null $section = null;

	//the section instance holding the data for this component
	protected Section|null $sectionInstance;

	//the title, contents and icon of the section
	public string|null $title;
	public string|null $contents;

	//the bits of the section (additional data containers)
	public Collection|null $bits;

    public function __construct(string|Section|null $section = null, array $config = [])
    {
		parent::__construct($config);

		$this->sectionInstance = $this->determineSectionInstance($section);
		$this->fillPropertiesWithSectionData($this->sectionInstance);
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

	protected function determineSectionInstance(string|Section|null $section): Section
	{
		if ($section instanceof Section)
			return $section;

		$sectionUid = $section ?: $this->section;
		return Section::whereUid($sectionUid)->with('bits')->firstOrFail();
	}

	protected function fillPropertiesWithSectionData(Section $section): void
	{
		$this->title = $section->title;
		$this->contents = $section->contents;
		$this->bits = $section->bits;
	}
}
