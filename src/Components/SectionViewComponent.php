<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\SiteComponent;
use Illuminate\Support\Collection;

/**
 * @property Section $model     //available in the component class
 *
 * View properties (only available in the view):
 * @property Section $section
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
abstract class SectionViewComponent extends BaseSiteViewComponent
{
    protected array $exposedModelAttributes = ['title', 'short', 'contents'];

	//the bits of the section (additional data containers)
	public Collection|null $bits;

    public function __construct(mixed $section = null, array $config = [])
    {
		parent::__construct($section, $config);
        $this->bits = $this->model->bits;
    }

    /**
     * Get a bit of this section by its uid
     */
	public function bit(string $uid): Bit|null
	{
        return Site::sectionBit($uid, $this->model);
	}

    public function bitsOfType(string $type): Collection
    {
        return $this->bits->filter(fn (Bit $bit) => $bit->type === $type);
    }

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    protected function determineModelInstance(mixed $componentOrUid): SiteComponent
    {
        //if a section() method exists in the child class, use it to get the model instance
        //otherwise, use the global section() helper function
        return method_exists($this, 'section')
            ? $this->section($componentOrUid)
            : section($componentOrUid);
    }
}
