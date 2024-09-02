<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Collection;

/**
 * View properties (only available in the view):
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
abstract class PageViewComponent extends BaseSiteViewComponent
{
    public Page $page;

	//the sections of the page (additional data containers)
	public Collection|null $sections;

    public function __construct(mixed $page = null, array $config = [])
    {
		parent::__construct($page, $config);
    }

    /**
     * Get a section of this page by its uid
     */
	public function section(string $uid): Section|null
	{
        return Site::pageSection($uid, $this->page);
	}

    protected abstract function getPageInstance(mixed $pageOrUid): Page;

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    final protected function setup(mixed $componentOrUid): void
    {
        //determine the section model instance and load its sections
        $this->page = $this->getPageInstance($componentOrUid);
        $this->sections = $this->page->sections;

        //expose the bit attributes to the view
        $this->exposeModelAttributes($this->page, ['title', 'short', 'contents']);
        $this->exposeModelUnstructuredData($this->page);
    }
}
