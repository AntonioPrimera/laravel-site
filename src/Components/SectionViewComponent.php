<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Collection;

/**
 * View properties (only available in the view):
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
abstract class SectionViewComponent extends BaseSiteViewComponent
{
    public Section $section;

	//the bits of the section (additional data containers)
	public Collection|null $bits;

    public function __construct(mixed $section = null, array $config = [])
    {
		parent::__construct($section, $config);
    }

    /**
     * Get a bit of this section by its uid
     */
	public function bit(string $uid): Bit|null
	{
        return Site::sectionBit($uid, $this->section);
	}

    public function bitsOfType(string $type): Collection
    {
        return $this->bits->filter(fn (Bit $bit) => $bit->type === $type);
    }

    protected function getSectionInstance(mixed $componentOrUid): Section
    {
        return section($componentOrUid);
    }

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    final protected function setup(mixed $componentOrUid): void
    {
        //determine the section model instance and load its bits
        $this->section = $this->getSectionInstance($componentOrUid);
        $this->bits = $this->section->bits;

        //expose the section attributes to the view
        $this->exposeModelAttributes($this->section, ['title', 'short', 'contents']);
        $this->exposeModelUnstructuredData($this->section);
    }
}
